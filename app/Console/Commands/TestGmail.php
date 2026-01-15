<?php
// app/Console/Commands/TestGmail.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestGmail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Test Gmail SMTP configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info("Testing Gmail SMTP configuration...");
        $this->line("Sending to: " . $email);
        
        try {
            Mail::raw('Test email from Laravel Inventory System', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email - Laravel Inventory');
            });
            
            $this->info("✓ Email sent successfully!");
            $this->line("Check your inbox and spam folder.");
            
        } catch (\Exception $e) {
            $this->error("✗ Failed to send email: " . $e->getMessage());
            $this->line("\nTroubleshooting:");
            $this->line("1. Verify .env MAIL settings are correct");
            $this->line("2. Make sure you're using App Password (not Gmail password)");
            $this->line("3. Check if 2-Step Verification is enabled");
            $this->line("4. Try allowing less secure apps (temporarily)");
        }
        
        return Command::SUCCESS;
    }
}