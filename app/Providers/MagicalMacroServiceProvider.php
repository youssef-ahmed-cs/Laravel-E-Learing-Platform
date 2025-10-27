<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class MagicalMacroServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        Blueprint::macro('commonFields', function () {
            $this->id();
            $this->string('status')->default('active');
            $this->timestamps();
            $this->softDeletes();
            $this->index(['created_by', 'status']);
        });

        User::macro('isAdmin', function (): bool {
            return $this->role === 'admin' ?? 'false';
        });
    }
}
