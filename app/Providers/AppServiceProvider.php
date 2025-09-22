<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Task;
use App\Models\User;
use App\Observers\CourseObserver;
use App\Observers\TaskObserver;
use App\Observers\UserObserver;
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

        Password::defaults(static function () {
            return Password::min(8)->letters()->numbers()->mixedCase()->symbols();
        });
    }
}
