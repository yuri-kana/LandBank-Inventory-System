<?php
// app/Console/Commands/ResendVerificationEmail.php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResendVerificationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:resend-verification 
                            {email : Email address of the admin}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend verification email to admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ User with email {$email} not found!");
            return Command::FAILURE;
        }

        if ($user->email_verified_at) {
            $this->warn("⚠️ User {$email} is already verified!");
            return Command::SUCCESS;
        }

        try {
            // Update verification timestamp
            $user->update(['verification_sent_at' => now()]);
            
            // Send verification email
            $user->sendEmailVerificationNotification();
            $this->info("✅ Verification email resent to: {$email}");
            
        } catch (\Exception $e) {
            $this->error("❌ Failed to resend verification email: " . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}