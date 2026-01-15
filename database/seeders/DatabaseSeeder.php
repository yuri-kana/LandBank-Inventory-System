<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Team;
use App\Models\Item;
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

        // Create Team Members (one for each team)
        foreach ($teams as $teamId => $team) {
            User::create([
                'name' => 'Team ' . $teamId,
                'email' => 'team' . $teamId . '@inventory.com',
                'password' => Hash::make('password123'),
                'role' => 'team_member',
                'team_id' => $team->id,
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
        }

        // Create Items with units (from your images, sorted A-Z)
        $items = [
            // Office Supplies (First Image)
            ['name' => 'Alcohol', 'quantity' => 20, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Ballpen, Black', 'quantity' => 100, 'unit' => 'piece', 'minimum_stock' => 20],
            ['name' => 'Ballpen, Blue', 'quantity' => 100, 'unit' => 'piece', 'minimum_stock' => 20],
            ['name' => 'Ball Pen', 'quantity' => 150, 'unit' => 'piece', 'minimum_stock' => 30],
            ['name' => 'Binder Clip', 'quantity' => 15, 'unit' => 'box', 'minimum_stock' => 3],
            ['name' => 'Bond Paper (A4)', 'quantity' => 5000, 'unit' => 'ream', 'minimum_stock' => 1000],
            ['name' => 'Bond Paper (Legal)', 'quantity' => 3000, 'unit' => 'ream', 'minimum_stock' => 500],
            ['name' => 'Box, Landbank', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Brown Envelope', 'quantity' => 200, 'unit' => 'piece', 'minimum_stock' => 50],
            ['name' => 'Correction Tape', 'quantity' => 25, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Cutter', 'quantity' => 15, 'unit' => 'piece', 'minimum_stock' => 3],
            ['name' => 'Disk, Blank', 'quantity' => 50, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Diskette', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Divider, Loan', 'quantity' => 20, 'unit' => 'set', 'minimum_stock' => 5],
            ['name' => 'DTR', 'quantity' => 50, 'unit' => 'pack', 'minimum_stock' => 10],
            ['name' => 'Envelope, Brown', 'quantity' => 200, 'unit' => 'piece', 'minimum_stock' => 50],
            ['name' => 'Envelope, Small (landbank)', 'quantity' => 100, 'unit' => 'piece', 'minimum_stock' => 20],
            ['name' => 'Eraser', 'quantity' => 50, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Expandable Folder', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Fastener', 'quantity' => 20, 'unit' => 'box', 'minimum_stock' => 5],
            ['name' => 'Folder, Brown', 'quantity' => 40, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Folder, Expandable', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Folder, Loan, Green', 'quantity' => 25, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Folder, Loan, Red', 'quantity' => 25, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Green Proposal', 'quantity' => 40, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Highlighter', 'quantity' => 60, 'unit' => 'piece', 'minimum_stock' => 15],
            ['name' => 'Ink, Black, 003', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Ink, Black, 664', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Ink, Cyan, 003', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Ink, Cyan, 664', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Ink, Magenta, 003', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Ink, Magenta, 664', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Ink, Stamp pad', 'quantity' => 15, 'unit' => 'piece', 'minimum_stock' => 3],
            ['name' => 'Ink, Yellow, 003', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Ink, Yellow, 664', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Letterhead', 'quantity' => 1000, 'unit' => 'ream', 'minimum_stock' => 200],
            ['name' => 'Logbook', 'quantity' => 20, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Mailing Envelope', 'quantity' => 150, 'unit' => 'box', 'minimum_stock' => 30],
            ['name' => 'Marker, Black', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Marker, Blue', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Memo Pad/ Note Pad', 'quantity' => 50, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Notebook', 'quantity' => 40, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'OB Forms', 'quantity' => 30, 'unit' => 'pad', 'minimum_stock' => 5],
            ['name' => 'Oncol Forms', 'quantity' => 25, 'unit' => 'bundle', 'minimum_stock' => 5],
            ['name' => 'Packaging Tape', 'quantity' => 50, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Paper Clip', 'quantity' => 80, 'unit' => 'box', 'minimum_stock' => 20],
            ['name' => 'Paper clip, jumbo', 'quantity' => 20, 'unit' => 'box', 'minimum_stock' => 5],
            ['name' => 'Paper, A4', 'quantity' => 5000, 'unit' => 'ream', 'minimum_stock' => 1000],
            ['name' => 'Paper, Legal', 'quantity' => 3000, 'unit' => 'ream', 'minimum_stock' => 500],
            ['name' => 'Pencil', 'quantity' => 100, 'unit' => 'piece', 'minimum_stock' => 20],
            ['name' => 'Pins, Big', 'quantity' => 10, 'unit' => 'box', 'minimum_stock' => 2],
            ['name' => 'Pins, Push pins', 'quantity' => 10, 'unit' => 'box', 'minimum_stock' => 2],
            ['name' => 'Pins, Small', 'quantity' => 10, 'unit' => 'box', 'minimum_stock' => 2],
            ['name' => 'Puncher', 'quantity' => 5, 'unit' => 'piece', 'minimum_stock' => 1],
            ['name' => 'Red Commercial', 'quantity' => 100, 'unit' => 'piece', 'minimum_stock' => 25],
            ['name' => 'Rubber Band', 'quantity' => 30, 'unit' => 'box', 'minimum_stock' => 5],
            ['name' => 'Ruler', 'quantity' => 25, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Scissor', 'quantity' => 15, 'unit' => 'piece', 'minimum_stock' => 3],
            ['name' => 'Scotch Tape', 'quantity' => 40, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Sign pen, Black', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Sign pen, Blue', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Sign pen, Red', 'quantity' => 30, 'unit' => 'piece', 'minimum_stock' => 5],
            ['name' => 'Signature Card', 'quantity' => 200, 'unit' => 'piece', 'minimum_stock' => 50],
            ['name' => 'Stamp pad', 'quantity' => 10, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Stamp, Dater', 'quantity' => 5, 'unit' => 'piece', 'minimum_stock' => 1],
            ['name' => 'Staple Remover', 'quantity' => 8, 'unit' => 'piece', 'minimum_stock' => 2],
            ['name' => 'Staple Wire', 'quantity' => 25, 'unit' => 'box', 'minimum_stock' => 5],
            ['name' => 'Stapler', 'quantity' => 15, 'unit' => 'piece', 'minimum_stock' => 3],
            ['name' => 'Sticky Note (127x76mm)', 'quantity' => 20, 'unit' => 'pad', 'minimum_stock' => 5],
            ['name' => 'Sticky Note (3x4)', 'quantity' => 20, 'unit' => 'pad', 'minimum_stock' => 5],
            ['name' => 'Tape, Packaging', 'quantity' => 50, 'unit' => 'piece', 'minimum_stock' => 10],
            ['name' => 'Window Envelope', 'quantity' => 100, 'unit' => 'box', 'minimum_stock' => 20],
            ['name' => 'Looselcaf folder', 'quantity' => 20, 'unit' => 'piece', 'minimum_stock' => 5],
        ];

        // Sort items alphabetically by name
        usort($items, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        // Create all items
        foreach ($items as $item) {
            Item::create($item);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Team members: team1@inventory.com to team9@inventory.com / password123');
        $this->command->info('Total items created: ' . count($items));
    }
}
?>