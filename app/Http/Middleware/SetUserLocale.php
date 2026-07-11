<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has a preferred locale
        if (auth()->check() && auth()->user()->locale) {
            App::setLocale(auth()->user()->locale->value);
        }

        return $next($request);
    }
}
