<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        //        parent::boot();
        Route::model('user', User::class, static function ($id) {
            return User::where('id', $id)
                ->where('role', 'student')->firstOrFail();
        });
    }
}
