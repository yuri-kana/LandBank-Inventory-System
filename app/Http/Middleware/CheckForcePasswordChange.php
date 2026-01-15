<?php
// app/Http/Middleware/CheckForcePasswordChange.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckForcePasswordChange
{
    public function handle(Request $request, Closure $next)
    {
        // If user is not authenticated, proceed
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        return $next($request);
    }
}