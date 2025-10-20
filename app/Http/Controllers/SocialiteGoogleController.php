<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteGoogleController
{
    public function login()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback(): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate([
            'provider_id' => $googleUser->getId(),
        ], [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'username' => $googleUser->getNickname() ?? explode('@', $googleUser->getEmail())[0],
            'password' => bcrypt(str()->random(16)),
            'avatar' => $googleUser->getAvatar(),
            'email_verified_at' => now(),
            'bio' => $googleUser->getNickname() ?? '',
        ]);

        Auth::login($user, true);

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
        ]);
    }
}
