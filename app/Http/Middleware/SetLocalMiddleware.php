<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocalMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('lang') === 'ar') {
            app()->setLocale('ar');
        } else {
            app()->setLocale('en');
        }

        return $next($request);
    }
}
