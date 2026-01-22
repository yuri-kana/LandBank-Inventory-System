<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create Teams (Team 1 to Team 9)
        $teams = [];
        for ($i = 1; $i <= 9; $i++) {
            $teams[$i] = Team::create(['name' => 'Team ' . $i]);
        }

        // Create ONLY 3 Team Members (inactive by default)
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'name' => 'Team Member ' . $i,
                'email' => 'teammember' . $i . '@inventory.com',
                'password' => Hash::make('password123'),
                'role' => 'team_member',
                'team_id' => $teams[$i]->id, // Assign to first 3 teams
                'email_verified_at' => now(),
                'is_active' => false, // Set to inactive (0) for security
            ]);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Team Members:');
        $this->command->info('- teammember1@inventory.com / password123 (Inactive)');
        $this->command->info('- teammember2@inventory.com / password123 (Inactive)');
        $this->command->info('- teammember3@inventory.com / password123 (Inactive)');
        $this->command->info('=== Important ===');
        $this->command->info('Team members are INACTIVE by default for security.');
        $this->command->info('Admin must activate team members manually in the admin panel.');
        $this->command->info('No items were created as requested.');
    }
}