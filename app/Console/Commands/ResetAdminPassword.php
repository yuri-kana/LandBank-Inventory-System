<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {email?} {--password=}';
    protected $description = 'Reset admin user password';

    public function handle()
    {
        $email = $this->argument('email') ?? 'ken0.kaneki3@gmail.com';
        $newPassword = $this->option('password') ?? 'Admin@123';

        $this->info("Looking for admin with email: {$email}");

        $admin = User::where('email', $email)->first();

        if (!$admin) {
            $this->error("❌ Admin user not found with email: {$email}");
            $this->info("Available admin emails:");
            User::where('role', 'admin')->get(['id', 'name', 'email'])->each(function ($user) {
                $this->line("  - {$user->email} ({$user->name})");
            });
            return 1;
        }

        $this->info("✅ Admin found: {$admin->name} <{$admin->email}>");
        
        // Reset password
        $admin->password = Hash::make($newPassword);
        $admin->save();

        $this->info("✅ Password reset successfully!");
        $this->line("New password: {$newPassword}");
        $this->line("Password hash: " . substr($admin->password, 0, 30) . "...");
        
        // Verification info
        $this->info("\n📋 Verification:");
        $this->table(
            ['Property', 'Value'],
            [
                ['Email', $admin->email],
                ['Name', $admin->name],
                ['Role', $admin->role],
                ['Email Verified', $admin->hasVerifiedEmail() ? '✅ YES' : '❌ NO'],
                ['Is Active', $admin->is_active ? '✅ YES' : '❌ NO'],
                ['Uses Bcrypt', strpos($admin->password, '$2y$') === 0 ? '✅ YES' : '❌ NO'],
                ['Created At', $admin->created_at->format('Y-m-d H:i:s')],
                ['Updated At', $admin->updated_at->format('Y-m-d H:i:s')],
            ]
        );

        return 0;
    }
}