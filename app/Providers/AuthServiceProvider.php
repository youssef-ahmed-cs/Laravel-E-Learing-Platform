<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Profile;
use App\Models\Review;
use App\Models\User;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\ReviewPolicy;
use App\Policies\UserPolicy;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('is_Admin', static function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('is_instructor', static function (User $user) {
            return $user->role === 'instructor';
        });

        Gate::define('is_student', static function (User $user) {
            return $user->role === 'student';
        });

        Gate::define('markAsRead', static function (User $user, DatabaseNotification $notification) {
            return $user->is($notification->notifiable);
        });
    }

    protected array $policies = [
        User::class => UserPolicy::class,
        Course::class => CoursePolicy::class,
        Review::class => ReviewPolicy::class,
        Enrollment::class => EnrollmentPolicy::class,
        Profile::class => ProfilePolicy::class,
    ];
}
