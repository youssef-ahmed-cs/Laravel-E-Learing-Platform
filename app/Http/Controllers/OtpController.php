<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Random\RandomException;

class OtpController extends Controller
{
    /**
     * @throws RandomException
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $email = $validated['email'];

        $otp = random_int(10000, 99999);
        Redis::setex("otp:{$email}", 300, $otp);
        Mail::to($email)->send(new OtpMail($otp));

        return response()->json(['message' => 'OTP sent successfully']);
    }
}
