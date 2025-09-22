<?php

namespace App\Listeners;

use App\Events\UserRegisteredEvent;
use App\Mail\WelcomeEmailMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailListener
{
    public function __construct()
    {
    }

    public function handle(UserRegisteredEvent $event): void
    {
        $user = $event->user;
        Log::info('Sending welcome email to ' . $user->email);
        Mail::to($user->email)->send(new WelcomeEmailMail($user));
    }
}
