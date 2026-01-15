<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class VerificationController extends Controller
{
    /**
     * Verify email with token - FIXED METHOD
     */
    public function verify(Request $request, $id, $hash)
    {
        \Log::info('=== VERIFICATION ATTEMPT ===', [
            'id' => $id,
            'hash' => $hash,
            'token' => $request->query('token'),
            'full_url' => $request->fullUrl(),
            'signature_valid' => $request->hasValidSignature() ? 'YES' : 'NO'
        ]);

        $user = User::find($id);

        if (!$user) {
            \Log::error('User not found for verification', ['id' => $id]);
            return redirect()->route('login')
                ->with('error', 'Invalid verification link. User not found.');
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            \Log::info('Email already verified', ['user_id' => $user->id, 'email' => $user->email]);
            return redirect()->route('login')
                ->with('info', 'Your email is already verified. You can login to your account.');
        }

        // Check if signature is valid (Laravel signed route)
        if (!$request->hasValidSignature()) {
            \Log::error('Invalid signature', [
                'user_id' => $user->id,
                'email' => $user->email,
                'expired' => $request->has('expires') ? 'YES' : 'NO'
            ]);
            return redirect()->route('login')
                ->with('error', 'Verification link has expired or is invalid.');
        }

        // Check if hash matches email
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            \Log::error('Hash mismatch', [
                'user_id' => $user->id,
                'email' => $user->email,
                'expected_hash' => sha1($user->getEmailForVerification()),
                'received_hash' => $hash
            ]);
            return redirect()->route('login')
                ->with('error', 'Invalid verification link.');
        }

        // Check token if provided
        if ($request->has('token')) {
            // Verify the token matches
            $tokenMatches = $user->verification_token === hash('sha256', $request->query('token'));
            
            if (!$tokenMatches) {
                \Log::error('Token mismatch', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'stored_hash' => $user->verification_token,
                    'received_token' => $request->query('token'),
                    'received_hash' => hash('sha256', $request->query('token'))
                ]);
                return redirect()->route('login')
                    ->with('error', 'Invalid verification token.');
            }
        }

        // ✅ FIXED: Use the User model's markEmailAsVerified() method
        if (!$user->markEmailAsVerified()) {
            \Log::error('Failed to mark email as verified', ['user_id' => $user->id]);
            return redirect()->route('login')
                ->with('error', 'Failed to verify email. Please contact support.');
        }

        event(new Verified($user));

        \Log::info('Email verified successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()
        ]);

        // ✅ UPDATED: Clear verification data and redirect to login
        $user->verification_token = null;
        $user->verification_required = 0;
        $user->verification_sent_at = null;
        $user->is_active = 1;
        $user->save();

        // ✅ UPDATED: Redirect to login with success message
        return redirect()->route('login')
            ->with('success', 'Email verified successfully! You can now login to your account.');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email is already verified.');
        }

        // Check if user can resend (prevent spam)
        $lastSent = $user->verification_sent_at ?? $user->created_at;
        $nextAllowed = Carbon::parse($lastSent)->addMinutes(1);
        
        if (Carbon::now()->lt($nextAllowed)) {
            $waitTime = Carbon::now()->diffInSeconds($nextAllowed);
            return back()->with('error', "Please wait $waitTime seconds before requesting another verification email.");
        }

        // Update verification sent time
        $user->verification_sent_at = now();
        $user->save();

        // Resend verification email
        $user->sendEmailVerificationNotification();

        \Log::info('Verification email resent', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return back()->with('success', 'Verification email has been resent. Please check your inbox.');
    }

    public function showVerificationNotice()
    {
        if (auth()->check() && !auth()->user()->hasVerifiedEmail()) {
            // Auto-verify if verification not required
            if (!auth()->user()->needsVerification()) {
                // ✅ FIXED: Use the model method
                auth()->user()->markEmailAsVerified();
                return redirect()->route('dashboard');
            }
            
            return view('auth.verify-email');
        }

        return redirect()->route('dashboard');
    }
    
    // Verification success page (Optional - keep if you want manual access)
    public function showVerificationSuccess()
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', 'Please login to access your account.');
        }
        
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        
        return view('auth.verify-success', [
            'user' => auth()->user()
        ]);
    }
    
    // Check verification status API endpoint
    public function checkStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        
        return response()->json([
            'verified' => $user->hasVerifiedEmail(),
            'is_active' => $user->is_active,
            'registered_at' => $user->created_at->format('Y-m-d H:i:s'),
        ]);
    }
}