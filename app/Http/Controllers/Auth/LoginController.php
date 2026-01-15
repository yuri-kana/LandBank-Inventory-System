<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Check if account is active
            if (!$user->is_active) {
                return back()->withErrors([
                    'email' => 'Your account is not active. Please verify your email first.',
                ])->onlyInput('email');
            }
        }

        // Attempt login
        if (Auth::attempt($credentials, $request->remember)) {
            $user = Auth::user();
            
            // Record login attempt
            $user->recordLogin();
            
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}