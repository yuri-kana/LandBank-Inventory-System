<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $request->expectsJson() 
                ? response()->json(['error' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            return $request->expectsJson()
                ? response()->json(['error' => 'This action is unauthorized.'], 403)
                : redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Allow both team_member and admin to access staff routes
        if (auth()->user()->isTeamMember() || auth()->user()->isAdmin()) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized access. Staff only.');
    }
}