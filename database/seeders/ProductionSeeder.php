<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Insert users
        \App\Models\User::create([
            'name' => 'Team 1',
            'email' => 'team1@inventory.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        
        // Insert items
        \App\Models\Item::create(['name' => 'Laptop', 'category' => 'Electronics', 'quantity' => 10]);
        \App\Models\Item::create(['name' => 'Chair', 'category' => 'Furniture', 'quantity' => 25]);

    }
}

Route::get('/run-seeder', function() {
    Artisan::call('db:seed --class=ProductionSeeder --force');
    return "Seeder executed!";
});