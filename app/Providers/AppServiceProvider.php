<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Task;
use App\Models\User;
use App\Observers\CourseObserver;
use App\Observers\LessonObserver;
use App\Observers\TaskObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Course::observe(CourseObserver::class);
        User::observe(UserObserver::class);
        Task::observe(TaskObserver::class);
        Lesson::observe(LessonObserver::class);

        Password::defaults(static function () {
            return Password::min(8)->letters()->numbers()->mixedCase()->symbols();
        });

        VerifyEmail::createUrlUsing(static function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });
    }
}
