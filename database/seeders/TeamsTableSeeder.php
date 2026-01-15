<?php
// database/seeders/TeamsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeamsTableSeeder extends Seeder
{
    public function run()
    {
        $teams = [
            ['id' => 1, 'name' => 'Team 1', 'description' => 'Head Office - Main Branch'],
            ['id' => 2, 'name' => 'Team 2', 'description' => 'Metro Manila Division'],
            ['id' => 3, 'name' => 'Team 3', 'description' => 'Northern Luzon Division'],
            ['id' => 4, 'name' => 'Team 4', 'description' => 'Southern Luzon Division'],
            ['id' => 5, 'name' => 'Team 5', 'description' => 'Visayas Division'],
            ['id' => 6, 'name' => 'Team 6', 'description' => 'Mindanao Division'],
            ['id' => 7, 'name' => 'Team 7', 'description' => 'IT Department'],
            ['id' => 8, 'name' => 'Team 8', 'description' => 'Finance Department'],
            ['id' => 9, 'name' => 'Team 9', 'description' => 'HR Department'],
            ['id' => 10, 'name' => 'Team 10', 'description' => 'Audit Department'],
            ['id' => 11, 'name' => 'Admin Team', 'description' => 'System Administrators'],
        ];

        foreach ($teams as $team) {
            DB::table('teams')->updateOrInsert(
                ['id' => $team['id']],
                [
                    'name' => $team['name'],
                    'description' => $team['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('✅ Teams seeded successfully!');
        $this->command->table(['ID', 'Name', 'Description'], $teams);
    }
}