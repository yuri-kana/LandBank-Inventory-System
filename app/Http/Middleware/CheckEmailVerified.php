<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Routes that allow access even if the user is not active/verified
            $allowedRoutes = [
                'logout',
                'verification.notice',
                'verification.resend',
                'verification.verify', // Verification Link
                'verification.success',
                'verification.check',
            ];
            
            $currentRoute = $request->route()?->getName();
            
            // Check if the user is inactive AND not trying to access an allowed route
            if (!$user->is_active && !in_array($currentRoute, $allowedRoutes)) {
                // Flash an error and redirect to the notice page
                return redirect()->route('verification.notice')
                    ->with('error', 'Your account is not active. Please verify your email first.');
            }
        }

        return $next($request);
    }
}