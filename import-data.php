<?php
// import-data.php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Starting data import...<br>";

// Clear existing data
DB::table('team_requests')->delete();
DB::table('inventory_logs')->delete();
DB::table('items')->delete();
DB::table('users')->delete();
DB::table('teams')->delete();
echo "Cleared existing data<br>";

// Insert Teams
$teams = [
    ['id' => 1, 'name' => 'Team 1', 'created_at' => '2026-01-14 06:35:39', 'updated_at' => '2026-01-14 06:35:39'],
    ['id' => 2, 'name' => 'Team 2', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
    ['id' => 3, 'name' => 'Team 3', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
    ['id' => 4, 'name' => 'Team 4', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
    ['id' => 5, 'name' => 'Team 5', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
    ['id' => 6, 'name' => 'Team 6', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
    ['id' => 7, 'name' => 'Team 7', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
    ['id' => 8, 'name' => 'Team 8', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
    ['id' => 9, 'name' => 'Team 9', 'created_at' => '2026-01-14 06:35:40', 'updated_at' => '2026-01-14 06:35:40'],
];

DB::table('teams')->insert($teams);
echo "Inserted teams<br>";

// Insert Users (simplified - just admin)
$users = [
    [
        'id' => 10, 
        'name' => 'Landy', 
        'email' => 'landbankinventory@gmail.com',
        'password' => '$2y$12$O1G8J9Qb7UHg8GUW9MLfZOOQJaB05GA1/nVxgxX77gZcfkz.gxT3O',
        'role' => 'admin',
        'email_verified_at' => '2026-01-14 06:36:27',
        'created_at' => '2026-01-14 06:36:27',
        'updated_at' => '2026-01-14 06:36:27'
    ]
];

DB::table('users')->insert($users);
echo "Inserted admin user<br>";

// Insert Items (first 5 as example)
$items = [
    ['id' => 1, 'name' => 'Alcohol', 'quantity' => 25, 'unit' => 'piece', 'minimum_stock' => 5, 'created_at' => '2026-01-14 06:35:43', 'updated_at' => '2026-01-15 02:22:36'],
    ['id' => 2, 'name' => 'Ball Pen', 'quantity' => 150, 'unit' => 'piece', 'minimum_stock' => 30, 'created_at' => '2026-01-14 06:35:43', 'updated_at' => '2026-01-14 06:35:43'],
    ['id' => 3, 'name' => 'Ballpen, Black', 'quantity' => 1, 'unit' => 'piece', 'minimum_stock' => 20, 'created_at' => '2026-01-14 06:35:43', 'updated_at' => '2026-01-15 03:17:52'],
    ['id' => 4, 'name' => 'Ballpen, Blue', 'quantity' => 100, 'unit' => 'piece', 'minimum_stock' => 20, 'created_at' => '2026-01-14 06:35:43', 'updated_at' => '2026-01-14 06:35:43'],
    ['id' => 5, 'name' => 'Binder Clip', 'quantity' => 15, 'unit' => 'box', 'minimum_stock' => 3, 'created_at' => '2026-01-14 06:35:43', 'updated_at' => '2026-01-14 06:35:43'],
];

DB::table('items')->insert($items);
echo "Inserted items<br>";

echo "<h2>✅ Data Import Complete!</h2>";
echo "Login with: landbankinventory@gmail.com";
?>