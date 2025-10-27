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
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        Course::observe(CourseObserver::class);
        User::observe(UserObserver::class);
        Task::observe(TaskObserver::class);
        Lesson::observe(LessonObserver::class);

        Password::defaults(static function () {
            return Password::min(8)->letters()->numbers()->mixedCase()->symbols()->max(16);
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

//        User::macro('isAdmin', function (): bool {
//            return $this->role === 'admin';
//        });

        // Add a macro to the Blueprint class for common fields used in multiple tables Don't repeat yourself (DRY principle)
        Blueprint::macro('commonFields', function () {
            $this->id();
            $this->string('status')->default('active');
            $this->timestamps();
            $this->softDeletes();
            $this->index(['created_by', 'status']);
        });


        Blueprint::macro('fileFields', function () {
            $this->string('file_path');
            $this->string('file_type');
        });

        Builder::macro('admins', static function () {
            return $this->where('role', 'admin');
        });

        Http::macro('request', static function () {
            return Http::withHeaders([
                'Authorization' => 'Bearer ' . 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3YxL3JlZ2lzdGVyIiwiaWF0IjoxNzU5Mzc0Nzk0LCJleHAiOjE3NTkzNzgzOTQsIm5iZiI6MTc1OTM3NDc5NCwianRpIjoiUFRXTTFFMlRyQXBNSUNrbSIsInN1YiI6IjM4OCIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.EZT2b9DEj8ghXLEmCdMPx4sD3deawbE2BKfFf9_ojao',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->baseUrl('localhost:8000/api/')->timeout(10);
        });

        Builder::macro('all_users', function () {
            return $this->whereIn('role', ['student', 'instructor', 'admin']);
        });

        RateLimiter::for('api', static function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        // explain => Premium users can make 5 requests per minute, while non-premium users are limited to 3 requests per minute.
        RateLimiter::for('premium', static function (Request $request) {
            return $request->user()?->is_premium
                ? Limit::perMinute(5)
                : Limit::perMinute(3);
        });

        Response::macro('caps', static function ($value) {
            return Response::make(strtoupper($value));
        });
    }
}
