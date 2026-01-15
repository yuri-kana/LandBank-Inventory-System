<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "=== Testing Verification Fix ===\n\n";

// Create a test user
$user = User::create([
    'name' => 'Test Verification User',
    'email' => 'test.verify@example.com',
    'password' => bcrypt('password123'),
    'role' => 'team_member',
    'is_active' => false,
    'email_verified_at' => null,
    'verification_token' => hash('sha256', 'test_token'),
    'verification_required' => true,
    'verification_sent_at' => now(),
]);

echo "1. Created test user:\n";
echo "   Email: " . $user->email . "\n";
echo "   Initial is_active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
echo "   Initial email_verified_at: " . ($user->email_verified_at ?: 'NULL') . "\n";
echo "   Initial verification_token: " . ($user->verification_token ? 'SET' : 'NULL') . "\n";
echo "   Initial verification_required: " . ($user->verification_required ? 'YES' : 'NO') . "\n";

echo "\n2. Calling markEmailAsVerified()...\n";
$result = $user->markEmailAsVerified();
echo "   Result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

// Refresh from database
$user->refresh();

echo "\n3. After markEmailAsVerified():\n";
echo "   is_active: " . ($user->is_active ? 'YES' : 'NO') . "\n";
echo "   email_verified_at: " . ($user->email_verified_at ?: 'NULL') . "\n";
echo "   verification_token: " . ($user->verification_token ? 'SET' : 'NULL') . "\n";
echo "   verification_required: " . ($user->verification_required ? 'YES' : 'NO') . "\n";

echo "\n4. Testing methods:\n";
echo "   hasVerifiedEmail(): " . ($user->hasVerifiedEmail() ? 'YES' : 'NO') . "\n";
echo "   isActive(): " . ($user->isActive() ? 'YES' : 'NO') . "\n";
echo "   needsVerification(): " . ($user->needsVerification() ? 'YES' : 'NO') . "\n";

// Clean up
$user->delete();
echo "\n✅ Test completed. User cleaned up.\n";