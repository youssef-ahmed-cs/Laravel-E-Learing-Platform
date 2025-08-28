<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Observers\TestObserve;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Review;
use App\Policies\ReviewPolicy;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //User::observe(new TestObserve);
    }

    protected array $policies = [
        User::class => UserPolicy::class,
        Course::class => CoursePolicy::class,
        Review::class => ReviewPolicy::class,
        Enrollment::class => EnrollmentPolicy::class
    ];
}
