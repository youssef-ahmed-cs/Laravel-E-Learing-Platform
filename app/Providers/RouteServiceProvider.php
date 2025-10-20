<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
//        parent::boot();
        Route::model('user', User::class, static function ($id) {
            return User::where('id', $id)
                ->where('role', 'student')->firstOrFail();
        });
    }
}
