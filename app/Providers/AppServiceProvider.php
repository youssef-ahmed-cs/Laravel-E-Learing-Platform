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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
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

        # Add a macro to the Blueprint class for common fields used in multiple tables Don't repeat yourself (DRY principle)
        Blueprint::macro('commonFields', function () {
            $this->id();
            $this->string('status')->default('active');
            $this->timestamps();
            $this->softDeletes();
            $this->index(['created_by', 'status']);
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

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });
    }
}
