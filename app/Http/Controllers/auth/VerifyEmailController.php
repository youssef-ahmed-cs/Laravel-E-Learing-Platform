<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Handle the email verification link.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Mark the authenticated user's email address as verified.
        $request->fulfill();

        // Redirect to the dashboard (same route used elsewhere in the app).
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
