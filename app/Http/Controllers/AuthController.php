<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Log session info for debugging
        Log::debug('Login page accessed', [
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token(),
            'is_authenticated' => Auth::check(),
        ]);
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Log login attempt
        Log::info('Login attempt initiated', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'has_remember' => $request->has('remember'),
            'csrf_token_present' => $request->has('_token'),
        ]);

        // Validate credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        Log::debug('Credentials validated', ['email' => $request->email]);

        // Check if user exists but password is wrong
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            // Check if email is not verified
            if (!$user->hasVerifiedEmail()) {
                Log::warning('Login attempt with unverified email', ['email' => $user->email]);
                return back()->withErrors([
                    'email' => 'Please verify your email address first. 
                    <a href="' . route('verification.resend') . '?email=' . $user->email . '" class="underline">Resend verification email</a>'
                ])->withInput($request->only('email', 'remember'));
            }

            // Check if account is not active
            if (!$user->isActive()) {
                Log::warning('Login attempt with inactive account', ['email' => $user->email]);
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact Inventory Head.'
                ])->withInput($request->only('email', 'remember'));
            }
        }

        // ============ CRITICAL MISSING CODE ============
        // ATTEMPT TO LOGIN THE USER
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            Log::info('=== LOGIN SUCCESSFUL ===', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'is_active' => $user->is_active,
                'email_verified_at' => $user->email_verified_at,
            ]);

            $request->session()->regenerate();
            
            if (!$user->isActive()) {
                Auth::logout();
                Log::warning('Inactive account login attempt', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact Inventory Head.'
                ])->withInput($request->only('email', 'remember'));
            }
        }
        // ============ END OF MISSING CODE ============

        Log::warning('Failed login attempt', [
            'email' => $request->email, 
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Log::info('User logging out', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        Log::info('Logout complete, session invalidated');
        return redirect('/login');
    }
}