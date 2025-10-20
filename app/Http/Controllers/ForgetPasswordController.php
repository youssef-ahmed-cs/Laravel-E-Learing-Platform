<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequests\resetPasswordRequest;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Random\RandomException;

class ForgetPasswordController extends Controller
{
    /**
     * @throws RandomException
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json(['message' => 'User not found check your database.'], 404);
        }
        $otp = random_int(1000, 9999);
        $user->update([
            'two_factor_code' => $otp,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($user->email)->send(new OtpMail($user));

        return response()->json([
            'message' => 'OTP sent to your email address',
        ]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'two_factor_code' => 'required|digits:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        if ($user->two_factor_code !== $request->two_factor_code) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        return response()->json(['message' => 'OTP verified successfully'], 200);
    }

    public function resetPassword(resetPasswordRequest $request): JsonResponse
    {
        $request->validated();

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json(['error' => 'Email not found'], 404);
        }

        if ($user->two_factor_code !== $request->two_factor_code) {
            return response()->json(['error' => 'Invalid OTP'], 400);
        }

        if ($user->two_factor_expires_at < now()) {
            return response()->json(['error' => 'OTP has expired'], 400);
        }

        $user->password = bcrypt($request->password);
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();
        Log::notice('Password reset successful for user ID: '.$user->id);

        return response()->json(['message' => 'Password reset successful'], 200);
    }
}
