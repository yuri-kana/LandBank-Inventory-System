<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'minimum_stock',
        'is_available',
        'available_stock',
        'reserved_stock',
        // Add new columns
        'beginning_stock_30d',
        'total_requested_30d',
        'total_claimed_30d',
        'total_restocked_30d'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'available_stock' => 'integer',
        'reserved_stock' => 'integer',
        'beginning_stock_30d' => 'integer',
        'total_requested_30d' => 'integer',
        'total_claimed_30d' => 'integer',
        'total_restocked_30d' => 'integer'
    ];

    // Add these accessors for the view
    public function getCurrentStock30dAttribute()
    {
        // Formula: beginning + restocked - claimed
        return $this->beginning_stock_30d + $this->total_restocked_30d - $this->total_claimed_30d;
    }

    public function getStatus30dAttribute()
    {
        $current = $this->current_stock_30d;
        $minimum = $this->minimum_stock;
        
        if ($current <= 0) {
            return 'Out of Stock';
        } elseif ($current <= $minimum) {
            return 'Needs Restock';
        } else {
            return 'In Stock';
        }
    }

    // Original relationships remain
    public function requests()
    {
        return $this->hasMany(TeamRequest::class);
    }

    public function teamRequests()
    {
        return $this->requests();
    }

    public function pendingRequests()
    {
        return $this->teamRequests()
            ->whereIn('status', ['pending', 'approved']);
    }

    // Update the approve method to track 30-day requested
    public function approveRequest($quantity)
    {
        DB::beginTransaction();
        try {
            $this->reserved_stock += $quantity;
            $this->total_requested_30d += $quantity; // Add to 30-day counter
            $this->available_stock = $this->quantity - $this->reserved_stock;
            $this->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Update the claim method to track 30-day claimed
    public function claimStock($quantity)
    {
        DB::beginTransaction();
        try {
            // Check physical stock
            if ($this->quantity < $quantity) {
                throw new \Exception('Insufficient physical stock. Available: ' . $this->quantity);
            }
            
            // Reduce reserved stock
            $this->reserved_stock -= $quantity;
            if ($this->reserved_stock < 0) {
                $this->reserved_stock = 0;
            }
            
            // Deduct from physical quantity
            $this->quantity -= $quantity;
            
            // Add to 30-day claimed counter
            $this->total_claimed_30d += $quantity;
            
            // Recalculate available stock
            $this->available_stock = $this->quantity - $this->reserved_stock;
            
            if (!$this->save()) {
                throw new \Exception('Failed to save item stock changes.');
            }
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Update the restock method to track 30-day restocked
    public function restockItems($quantity)
    {
        DB::beginTransaction();
        try {
            $this->quantity += $quantity;
            $this->total_restocked_30d += $quantity; // Add to 30-day restocked counter
            $this->available_stock = $this->quantity - $this->reserved_stock;
            $this->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // New method to update beginning stock (call this daily via scheduler)
    public function updateBeginningStock30d()
    {
        // This should be called daily to update what "beginning stock" was 30 days ago
        $this->beginning_stock_30d = $this->quantity;
        $this->save();
    }

    // Keep all your existing methods below...
    public function calculateAvailableStock()
    {
        $reserved = $this->teamRequests()
            ->whereIn('status', ['pending', 'approved'])
            ->sum('quantity_requested');
        
        $this->reserved_stock = $reserved;
        $this->available_stock = $this->quantity - $reserved;
        $this->save();
        
        return $this->available_stock;
    }

// Add this method to get reserved stock for display:
public function getReservedStockAttribute()
{
    // This is for display only - don't confuse with the database column
    return $this->pendingRequests()->sum('quantity_requested');
}


    public function canRequest($quantity)
    {
        return $this->getAvailableStockAttribute() >= $quantity;
    }

    public function getStockStatusAttribute()
    {
        if ($this->quantity <= 0) {
            return 'out-of-stock';
        } elseif ($this->quantity <= $this->minimum_stock) {
            return 'low-stock';
        }
        return 'in-stock';
    }

    public function getStockStatusColorAttribute()
    {
        switch ($this->stock_status) {
            case 'out-of-stock':
                return 'text-red-600 bg-red-100';
            case 'low-stock':
                return 'text-yellow-600 bg-yellow-100';
            default:
                return 'text-green-600 bg-green-100';
        }
    }

    public function scopeLowStock($query)
    {
        return $query->where('quantity', '>', 0)
                    ->whereColumn('quantity', '<=', 'minimum_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeInStock($query)
    {
        return $query->whereColumn('quantity', '>', 'minimum_stock')
                    ->where('quantity', '>', 0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where('quantity', '>', 0);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function reserveStock($quantity)
    {
        DB::beginTransaction();
        try {
            $this->reserved_stock += $quantity;
            $this->available_stock = $this->quantity - $this->reserved_stock;
            $this->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function releaseReservedStock($quantity)
    {
        DB::beginTransaction();
        try {
            $this->reserved_stock -= $quantity;
            if ($this->reserved_stock < 0) $this->reserved_stock = 0;
            $this->available_stock = $this->quantity - $this->reserved_stock;
            $this->save();
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Get pending requests quantity (requests that are pending approval)
    public function getPendingRequestsAttribute()
    {
        return $this->requests()->where('status', 'pending')->get();
    }

    public function getPendingQuantityAttribute()
    {
        return $this->requests()
            ->where('status', 'pending')
            ->sum('quantity_requested');
    }

    // Get approved requests quantity (already approved, reserved stock)
    public function getApprovedRequestsAttribute()
    {
        return $this->requests()->where('status', 'approved')->get();
    }

    public function getApprovedQuantityAttribute()
    {
        return $this->requests()
            ->where('status', 'approved')
            ->sum('quantity_requested');
    }

    // Get claimed requests quantity (actually taken from stock)
    public function getClaimedRequestsAttribute()
    {
        return $this->requests()->where('status', 'claimed')->get();
    }

    public function getClaimedQuantityAttribute()
    {
        return $this->requests()
            ->where('status', 'claimed')
            ->sum('quantity_requested');
    }

    // Get declined requests quantity
    public function getDeclinedQuantityAttribute()
    {
        return $this->requests()
            ->where('status', 'rejected') // Note: your system uses 'rejected' status
            ->sum('quantity_requested');
    }

    // Update your getAvailableStockAttribute to only subtract APPROVED requests
    public function getAvailableStockAttribute()
    {
        // Only subtract approved requests (not pending)
        $approvedReserved = $this->getApprovedQuantityAttribute();
        return max(0, $this->quantity - $approvedReserved);
    }

    // Add this method for backward compatibility (if you still need total requested)
    public function getTotalRequestedAttribute()
    {
        return $this->getPendingQuantityAttribute() + $this->getApprovedQuantityAttribute();
    }

    /**
     * Calculate priority score for ranking
     * Higher score = higher priority for attention
     */
    public function getPriorityScoreAttribute()
    {
        $score = 0;
        
        // 1. Stock Status (50% weight)
        $stockScore = 0;
        if ($this->quantity <= 0) {
            $stockScore = 100; // Out of stock - highest priority
        } elseif ($this->quantity <= $this->minimum_stock) {
            $stockScore = 70; // Low stock
        } elseif ($this->available_stock <= $this->minimum_stock) {
            $stockScore = 60; // Available stock is low due to reservations
        } else {
            $stockScore = 10; // In stock
        }
        $score += $stockScore * 0.5;
        
        // 2. Consumption Rate (25% weight) - from current month
        $currentMonth = now()->startOfMonth();
        $claimedThisMonth = TeamRequest::where('item_id', $this->id)
            ->where('status', 'claimed')
            ->where('created_at', '>=', $currentMonth)
            ->sum('quantity_requested');
        
        $consumptionRate = $this->quantity > 0 ? 
            ($claimedThisMonth / $this->quantity) * 100 : 100;
        $consumptionScore = min($consumptionRate, 100);
        $score += $consumptionScore * 0.25;
        
        // 3. Reserved Stock Impact (15% weight)
        $reservedPercentage = $this->quantity > 0 ? 
            ($this->reserved_stock / $this->quantity) * 100 : 100;
        $reservedScore = min($reservedPercentage, 100);
        $score += $reservedScore * 0.15;
        
        // 4. Pending Requests (10% weight)
        $pendingRequests = $this->getPendingQuantityAttribute();
        $pendingScore = $pendingRequests > 0 ? 50 : 0;
        $score += $pendingScore * 0.1;
        
        return min($score, 100);
    }

    /**
     * Get priority level based on score
     */
    public function getPriorityLevelAttribute()
    {
        $score = $this->priority_score;
        
        if ($score >= 80) return 'Critical';
        if ($score >= 60) return 'High';
        if ($score >= 40) return 'Medium';
        if ($score >= 20) return 'Low';
        return 'Normal';
    }

    /**
     * Get priority color for display
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority_level) {
            'Critical' => 'bg-red-100 text-red-800 border-red-200',
            'High' => 'bg-orange-100 text-orange-800 border-orange-200',
            'Medium' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'Low' => 'bg-blue-100 text-blue-800 border-blue-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200'
        };
    }

    /**
     * Get priority icon
     */
    public function getPriorityIconAttribute()
    {
        return match($this->priority_level) {
            'Critical' => 'fas fa-exclamation-triangle',
            'High' => 'fas fa-exclamation-circle',
            'Medium' => 'fas fa-arrow-up',
            'Low' => 'fas fa-arrow-right',
            default => 'fas fa-check'
        };
    }
}