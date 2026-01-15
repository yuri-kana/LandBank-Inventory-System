<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

// CORRECT EMAIL WITH SPACE
$email = 'practice example12@gmail.com';  // ← IMPORTANT: with space
$user = User::where('email', $email)->first();

if (!$user) {
    echo "User not found with email: $email\n";
    
    // Try to find with wildcard
    $user = User::where('email', 'like', '%practice%example12%')->first();
    if ($user) {
        echo "Found similar: " . $user->email . "\n";
        $email = $user->email;
    } else {
        exit;
    }
}

echo "=== User Found ===\n";
echo "Email: " . $user->email . "\n";
echo "ID: " . $user->id . "\n";
echo "Email Verified At: " . ($user->email_verified_at ? $user->email_verified_at : 'NULL') . "\n";
echo "Is Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
echo "Verification Token: " . ($user->verification_token ? 'SET' : 'NULL') . "\n";

// Ask for confirmation
echo "\nDo you want to fix this user? (yes/no): ";
$handle = fopen("php://stdin", "r");
$response = trim(fgets($handle));

if (strtolower($response) === 'yes') {
    // Fix the user
    $user->email_verified_at = now();
    $user->is_active = true;
    $user->verification_token = null;
    $user->verification_required = false;
    $user->save();
    
    echo "\n✅ User fixed!\n";
    echo "New status:\n";
    echo "- Email Verified At: " . $user->email_verified_at . "\n";
    echo "- Is Active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
    echo "- Verification Token: " . ($user->verification_token ? 'SET' : 'NULL') . "\n";
    
    echo "\n⚠️  IMPORTANT: Use this exact email to login:\n";
    echo "Email: \"" . $user->email . "\"\n";
    echo "(Notice the SPACE between 'practice' and 'example12')\n";
} else {
    echo "User not modified.\n";
}