<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ForcePasswordChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    /**
     * Show the force password change form
     */
    public function showForm()
    {
        $user = Auth::user();
        
        // Only show form if this is first login AND password hasn't been changed yet
        if (!$user->first_login || $user->password_changed_at) {
            return redirect()->route('dashboard')
                ->with('info', 'Your password has already been changed.');
        }
        
        return view('auth.force-password-change');
    }

    /**
     * Handle the password change request
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        Log::info('First login password change attempt', [
            'user_id' => $user->id,
            'first_login' => $user->first_login,
            'password_changed_at' => $user->password_changed_at
        ]);
        
        // 1. CHECK: Only allow if this is first login
        if (!$user->first_login || $user->password_changed_at) {
            return redirect()->route('dashboard')
                ->with('warning', 'Password change is not required.');
        }
        
        // 2. SIMPLE VALIDATION (remove symbols requirement)
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);
        
        // 3. Check if new password is same as current
        if (Hash::check($request->password, $user->password)) {
            return back()
                ->withInput()
                ->with('error', 'New password cannot be the same as your current password.');
        }
        
        // 4. UPDATE USER - Mark first login as completed
        try {
            // Update password
            $user->password = Hash::make($request->password);
            
            // MARK AS "NOT FIRST LOGIN" ANYMORE
            $user->first_login = false;
            $user->password_changed_at = now();
            
            // Remove any force change flags
            $user->force_password_change = false;
            $user->password_change_required = false;
            
            $user->save();
            
            Log::info('First login password change completed', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to change password', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please try again.');
        }

        // 5. REDIRECT TO DASHBOARD - USER STAYS LOGGED IN
        return redirect()->route('dashboard')
            ->with('success', 'Password changed successfully! Welcome to the system.');
    }
}