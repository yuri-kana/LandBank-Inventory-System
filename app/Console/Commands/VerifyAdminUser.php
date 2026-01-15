<?php
// app/Console/Commands/VerifyAdminUser.php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyAdminUser extends Command
{
    protected $signature = 'admin:verify 
                            {email : Email address of the admin to verify}';
    
    protected $description = 'Manually verify an admin user';

    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email {$email} not found!");
            return Command::FAILURE;
        }

        if ($user->email_verified_at) {
            $this->warn("User {$email} is already verified!");
            return Command::SUCCESS;
        }

        try {
            $user->update([
                'email_verified_at' => now(),
                'verification_token' => null,
                'is_active' => true,
            ]);
            
            $this->info("✅ User {$email} has been verified!");
            $this->line("They can now login at: " . url('/login'));
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to verify user: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}