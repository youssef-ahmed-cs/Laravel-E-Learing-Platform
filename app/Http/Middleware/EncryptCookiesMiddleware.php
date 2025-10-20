<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EncryptCookiesMiddleware
{
    protected array $except = [
        'test',
    ];

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
