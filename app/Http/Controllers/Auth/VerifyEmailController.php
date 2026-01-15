<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified and auto-activate them.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // Get user from URL parameters
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        // Check if hash matches
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            \Log::warning('Hash mismatch during verification', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'expected_hash' => sha1($user->getEmailForVerification()),
                'actual_hash' => $request->route('hash'),
            ]);
            
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        // If already verified, just redirect
        if ($user->hasVerifiedEmail()) {
            return redirect('/login')->with('info', 'Email already verified. You can login now.');
        }

        try {
            // Mark as verified
            $user->markEmailAsVerified();
            
            // Log the verification
            \Log::info('Email verified successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'verified_at' => now(),
            ]);
            
            // If this is a team member who needs to set up password
            if ($user->isTeamMember() && ($user->password_change_required || $user->force_password_change)) {
                // Create a password reset token for the user to set their password
                $token = Password::createToken($user);
                
                \Log::info('Redirecting team member to password setup', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'has_token' => !empty($token),
                ]);
                
                // Redirect to password reset with setup flag
                return redirect()->route('password.reset', [
                    'token' => $token,
                    'email' => $user->email,
                ])->with('status', 'Email verified successfully! Please set your password.');
            }
            
            // For admin users or users who already have password
            return redirect('/login')->with('success', 'Email verified successfully! You can now login.');
            
        } catch (\Exception $e) {
            \Log::error('Error during email verification: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e,
            ]);
            
            return redirect('/login')->with('error', 'Verification failed. Please contact administrator.');
        }
    }
}