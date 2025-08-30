<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\User;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    protected array $policies = [
        User::class => UserPolicy::class,
        Course::class => CoursePolicy::class,
        Review::class => ReviewPolicy::class,
        Enrollment::class => EnrollmentPolicy::class
    ];
}
