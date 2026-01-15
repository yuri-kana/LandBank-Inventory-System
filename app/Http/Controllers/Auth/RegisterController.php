<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:user,team_member,admin',
        ]);

        // Create user with verification
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
            'verification_required' => true,
            'verification_sent_at' => Carbon::now(),
            'is_active' => false,
        ]);

        // Send verification email - This will use OUR custom notification
        $user->sendEmailVerificationNotification();

        \Log::info('User registered successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'verification_sent' => true
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }
}