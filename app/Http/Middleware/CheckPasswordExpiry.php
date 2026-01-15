<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPasswordExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $user = Auth::user();
            
            // Define routes that are allowed without password check
            $allowedRoutes = [
                'password.force-change.show',
                'password.force-change.submit',
                'logout',
                'login',
                'password.request',
                'password.reset',
                'password.update',
                'verification.notice',
                'verification.verify',
                'verification.resend',
                'verification.success',
                'password.expired',
            ];
            
            // Check if password change is required
            if ($user->requiresPasswordChange() && !$request->routeIs($allowedRoutes)) {
                // Log the redirect for security
                \Log::channel('security')->info('Redirected to force password change', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'route' => $request->route()->getName(),
                    'ip' => $request->ip(),
                ]);
                
                return redirect()->route('password.force-change')
                    ->with('warning', 'Your password needs to be changed before proceeding.');
            }
        }

        return $next($request);
    }
}