<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'action',
        'quantity_change',
        'beginning_quantity',
        'ending_quantity',
        'user_id',
        'notes'
    ];

    // Add this to automatically update monthly reports when items are restocked
    protected static function booted()
    {
        parent::booted();

        // When a restock log is created
        static::created(function ($inventoryLog) {
            if ($inventoryLog->action === 'restock') {
                $year = $inventoryLog->created_at->year;
                $month = $inventoryLog->created_at->month;
                
                // Update total_restocked in monthly report
                MonthlyReport::updateOrCreate(
                    ['year' => $year, 'month' => $month],
                    ['total_restocked' => DB::raw('COALESCE(total_restocked, 0) + ' . $inventoryLog->quantity_change)]
                );
            }
        });

        // When a restock log is updated
        static::updated(function ($inventoryLog) {
            if ($inventoryLog->isDirty('quantity_change') && $inventoryLog->action === 'restock') {
                $year = $inventoryLog->created_at->year;
                $month = $inventoryLog->created_at->month;
                $oldQuantity = $inventoryLog->getOriginal('quantity_change');
                $newQuantity = $inventoryLog->quantity_change;
                $difference = $newQuantity - $oldQuantity;
                
                if ($difference != 0) {
                    MonthlyReport::where('year', $year)
                        ->where('month', $month)
                        ->increment('total_restocked', $difference);
                }
            }
        });

        // When a restock log is deleted
        static::deleting(function ($inventoryLog) {
            if ($inventoryLog->action === 'restock') {
                $year = $inventoryLog->created_at->year;
                $month = $inventoryLog->created_at->month;
                
                MonthlyReport::where('year', $year)
                    ->where('month', $month)
                    ->where('total_restocked', '>=', $inventoryLog->quantity_change)
                    ->decrement('total_restocked', $inventoryLog->quantity_change);
            }
        });
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}