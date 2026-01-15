<?php
// database/seeders/UsersTableSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $currentYear = date('Y');
        
        $users = [
            // ADMIN USERS (Team 11 - Admin Team)
            [
                'name' => 'System Administrator',
                'email' => 'admin@landbank.test',
                'team_id' => 11,
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'IT Manager',
                'email' => 'it.manager@landbank.test',
                'team_id' => 7, // IT Department
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
            
            // TEAM 1 - Head Office
            [
                'name' => 'Juan Dela Cruz',
                'email' => 'team1.member1@landbank.test',
                'team_id' => 1,
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maria Santos',
                'email' => 'team1.member2@landbank.test',
                'team_id' => 1,
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            
            // TEAM 9 - HR Department (This is your problematic case - ID 10)
            [
                'name' => 'HR Manager',
                'email' => 'hr.manager@landbank.test',
                'team_id' => 10, // This is ID 10 but should be Team 9
                'role' => 'manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'HR Staff',
                'email' => 'hr.staff@landbank.test',
                'team_id' => 10,
                'role' => 'user',
                'email_verified_at' => now(),
            ],
            
            // TEAM 10 - Audit Department (ID 11)
            [
                'name' => 'Audit Officer',
                'email' => 'audit.officer@landbank.test',
                'team_id' => 11, // This is ID 11 but should be Team 10
                'role' => 'auditor',
                'email_verified_at' => now(),
            ],
            
            // UNVERIFIED USER FOR TESTING
            [
                'name' => 'Test User - Unverified',
                'email' => 'unverified.user@landbank.test',
                'team_id' => 3,
                'role' => 'user',
                'email_verified_at' => null,
            ],
        ];

        foreach ($users as $userData) {
            // Extract team number for password
            $team = \App\Models\Team::find($userData['team_id']);
            $teamNumber = 1; // Default
            
            if ($team) {
                // Try to extract number from team name
                preg_match('/\d+/', $team->name, $matches);
                $teamNumber = $matches[0] ?? $userData['team_id'];
            }
            
            $defaultPassword = "Inventory-Team{$teamNumber}@{$currentYear}";
            
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => bcrypt($defaultPassword),
                    'team_id' => $userData['team_id'],
                    'role' => $userData['role'],
                    'email_verified_at' => $userData['email_verified_at'],
                    'remember_token' => Str::random(10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            
            $this->command->info("✅ Created user: {$userData['name']} ({$userData['email']})");
            $this->command->info("   Password: {$defaultPassword} | Team: {$team->name} (ID: {$userData['team_id']})");
        }

        $this->command->info("\n🎯 IMPORTANT TEST CASES CREATED:");
        $this->command->info("1. HR Manager (ID 10 = Team 9) - Password: Inventory-Team9@{$currentYear}");
        $this->command->info("2. Audit Officer (ID 11 = Team 10) - Password: Inventory-Team10@{$currentYear}");
        $this->command->info("3. Unverified user for email testing");
        
        $this->command->info("\n📋 Login Credentials Summary:");
        $this->command->table(
            ['Email', 'Password', 'Team', 'Verified'],
            [
                ['admin@landbank.test', "Inventory-Team11@{$currentYear}", 'Admin Team', 'YES'],
                ['hr.manager@landbank.test', "Inventory-Team9@{$currentYear}", 'HR Department (Team 9)', 'YES'],
                ['audit.officer@landbank.test', "Inventory-Team10@{$currentYear}", 'Audit Department (Team 10)', 'YES'],
                ['unverified.user@landbank.test', "Inventory-Team3@{$currentYear}", 'Team 3', 'NO - Test verification'],
            ]
        );
    }
}