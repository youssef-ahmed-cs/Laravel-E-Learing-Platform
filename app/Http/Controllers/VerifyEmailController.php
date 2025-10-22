<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailVerification\ResendEmailRequest;
use App\Http\Requests\EmailVerification\VerifyEmailRequest;
use App\Mail\EmailVerifiedSuccessfully;
use App\Mail\verify_email_otpMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Random\RandomException;

class VerifyEmailController extends Controller
{
    public function verifyEmailOtp(VerifyEmailRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || $user->two_factor_code !== $request->two_factor_code) {
            return $this->errorResponse('Invalid email or verification code', 400);
        }

        $user->update([
            'email_verified_at' => now(),
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);
        Log::info('Email verified successfully for user ID: '.$user->id);
        Mail::to($user->email)->send(new EmailVerifiedSuccessfully($user));

        return $this->successResponse('Email verified successfully');
    }

    /**
     * @throws RandomException
     */
    public function resendEmail(ResendEmailRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $this->errorResponse('Email not found', 404);
        }

        $otp = random_int(1000, 9999);
        $user->update([
            'two_factor_code' => $otp,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new verify_email_otpMail($user));
        Log::notice('Verification code resent to email: '.$user->email);

        return $this->successResponse('Verification code resent successfully');
    }

    public function isVerified(): JsonResponse
    {
        $user = auth()->user();
        if (! $user) {
            return $this->errorResponse('Unauthenticated', 401);
        }
        if ($user->email_verified_at) {
            return $this->successResponse('Email is verified with user: '.$user->name);
        }

        return $this->errorResponse('Email is not verified', 400);
    }

    public function resetEmailVerification(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'two_factor_code' => 'required|digits:4',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || $user->two_factor_code !== $request->two_factor_code) {
            return $this->errorResponse('Invalid email or OTP', 400);
        }

        if ($user->two_factor_expires_at < now()) {
            return $this->errorResponse('OTP has expired', 400);
        }

        $user->update([
            'email_verified_at' => now(),
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        Log::notice('Email reset successful for user ID: '.$user->id);

        return $this->successResponse('Password reset successful');
    }

    private function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json(['error' => $message], $status);
    }

    private function successResponse(string $message): JsonResponse
    {
        return response()->json(['message' => $message], 200);
    }
}
