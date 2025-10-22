<?php

namespace App\Otp;

use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\Hash;
use SadiqSalau\LaravelOtp\Contracts\OtpInterface as Otp;

class UserRegistrationOtp implements Otp
{
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $password
    ) {}

    /**
     * Creates or verifies the user depending on OTP stage
     *
     * @throws \Exception
     */
    public function process(): array
    {
        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $user = User::unguarded(function () {
                return User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'email_verified_at' => null,
                ]);
            });
        }

        $otpCode = app('otp')->generate($this->email);
        $expiresAt = now()->addMinutes(config('otp.lifetime', 10));

        $user->notify(new OtpNotification($otpCode, $expiresAt));

        return [
            'message' => 'OTP has been sent to your email',
            'expires_at' => $expiresAt,
            'user' => $user,
        ];
    }
}
