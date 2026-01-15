<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isTeamMember()) {
            abort(403, 'Unauthorized access. Staff only.');
        }
        
        return $next($request);
    }
}