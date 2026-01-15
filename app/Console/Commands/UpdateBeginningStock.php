<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;

class UpdateBeginningStock extends Command
{
    protected $signature = 'inventory:update-beginning-stock';
    protected $description = 'Update beginning stock for 30-day tracking';

    public function handle()
    {
        $items = Item::all();
        $updated = 0;
        
        foreach ($items as $item) {
            $item->updateBeginningStock30d();
            $updated++;
        }
        
        $this->info("Updated beginning stock for {$updated} items.");
        
        // Also log this action
        \App\Models\InventoryLog::create([
            'action' => 'system',
            'notes' => 'Beginning stock updated for 30-day tracking'
        ]);
    }
}