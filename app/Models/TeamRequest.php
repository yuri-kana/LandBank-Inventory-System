<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TeamRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'item_id',
        'quantity_requested',
        'status',
        'admin_notes',
        'approved_by',
        'approved_at',
        'claimed_at',
        'claimed_by',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'claimed_at' => 'datetime',
        'quantity_requested' => 'integer'
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function claimer()
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeClaimed($query)
    {
        return $query->where('status', 'claimed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isClaimed()
    {
        return $this->status === 'claimed';
    }

    public function canBeClaimed()
    {
        // Check if approved and not already claimed
        if (!$this->isApproved() || $this->isClaimed()) {
            return false;
        }
        
        // Check if item exists
        if (!$this->item) {
            return false;
        }
        
        // Check if there's enough physical stock
        return $this->item->quantity >= $this->quantity_requested;
    }

    public function approve($approvedById, $notes = null)
    {
        // Reserve the stock (don't deduct yet)
        if ($this->item) {
            $this->item->reserveStock($this->quantity_requested);
        }
        
        return $this->update([
            'status' => 'approved',
            'approved_by' => $approvedById,
            'approved_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    public function reject($approvedById, $notes = null)
    {
        // Release reserved stock if it was previously approved
        if ($this->isApproved() && $this->item) {
            $this->item->releaseReservedStock($this->quantity_requested);
        }
        
        return $this->update([
            'status' => 'rejected',
            'approved_by' => $approvedById,
            'approved_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    // FIXED METHOD: Correct name and logic
    public function markAsClaimed($claimerId)
    {
        if (!$this->canBeClaimed()) {
            $reason = 'Cannot claim this request. ';
            if (!$this->isApproved()) $reason .= 'Request is not approved. ';
            if ($this->isClaimed()) $reason .= 'Already claimed. ';
            if ($this->item && $this->item->quantity < $this->quantity_requested) {
                $reason .= 'Insufficient physical stock. ';
            }
            throw new \Exception($reason);
        }

        // Use transaction for safety
        DB::beginTransaction();

        try {
            // First, check if reserved stock needs adjustment
            // If this request is in reserved stock, reduce it
            $item = $this->item;
            
            // Check if we're already reserving this stock
            $alreadyReservedForThisRequest = $item->reserved_stock >= $this->quantity_requested;
            
            if ($alreadyReservedForThisRequest) {
                // Reduce reserved stock
                $item->reserved_stock -= $this->quantity_requested;
                if ($item->reserved_stock < 0) $item->reserved_stock = 0;
            }
            
            // Always deduct from physical quantity
            if ($item->quantity < $this->quantity_requested) {
                throw new \Exception('Insufficient physical stock. Available: ' . $item->quantity);
            }
            
            $item->quantity -= $this->quantity_requested;
            
            // Recalculate available stock
            $item->available_stock = $item->quantity - $item->reserved_stock;
            
            if (!$item->save()) {
                throw new \Exception('Failed to save item stock changes.');
            }

            // Update request status
            $this->status = 'claimed';
            $this->claimed_by = $claimerId;
            $this->claimed_at = now();
            
            if (!$this->save()) {
                throw new \Exception('Failed to update request status.');
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // ADDED: Status description for display
    public function getStatusDescription()
    {
        return match($this->status) {
            'pending' => 'Awaiting admin approval',
            'approved' => 'Ready for admin to claim',
            'rejected' => 'Request was denied',
            'claimed' => 'Items have been claimed',
            default => 'Unknown status'
        };
    }

    // ADDED: Time formatting helpers
    public function getCreatedAtFormatted()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getUpdatedAtFormatted()
    {
        return $this->updated_at->format('M d, Y h:i A');
    }

    public function getClaimedAtFormatted()
    {
        return $this->claimed_at ? $this->claimed_at->format('M d, Y h:i A') : null;
    }

    // For dashboard display
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'claimed' => 'bg-blue-100 text-blue-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800'
        };
    }

    public function getStatusIcon()
    {
        return match($this->status) {
            'claimed' => 'fas fa-check-double',
            'approved' => 'fas fa-check-circle',
            'rejected' => 'fas fa-times-circle',
            default => 'fas fa-clock'
        };
    }

    // Event handlers - UPDATED WITH AUTO-REPORT SYNC
    protected static function booted()
    {
        // When request status changes, update monthly report
        static::updated(function ($teamRequest) {
            if ($teamRequest->isDirty('status')) {
                $year = $teamRequest->created_at->year;
                $month = $teamRequest->created_at->month;
                
                if ($teamRequest->status === 'approved') {
                    // Increment total_requests in monthly report
                    MonthlyReport::updateOrCreate(
                        ['year' => $year, 'month' => $month],
                        ['total_requests' => DB::raw('COALESCE(total_requests, 0) + 1')]
                    );
                } 
                // If status changed FROM approved to something else, decrement
                elseif ($teamRequest->getOriginal('status') === 'approved') {
                    MonthlyReport::where('year', $year)
                        ->where('month', $month)
                        ->where('total_requests', '>', 0)
                        ->decrement('total_requests');
                }
                
                if ($teamRequest->status === 'claimed') {
                    // Increment total_claimed in monthly report
                    MonthlyReport::updateOrCreate(
                        ['year' => $year, 'month' => $month],
                        ['total_claimed' => DB::raw('COALESCE(total_claimed, 0) + ' . $teamRequest->quantity_requested)]
                    );
                }
                // If status changed FROM claimed to something else, decrement
                elseif ($teamRequest->getOriginal('status') === 'claimed') {
                    MonthlyReport::where('year', $year)
                        ->where('month', $month)
                        ->where('total_claimed', '>=', $teamRequest->quantity_requested)
                        ->decrement('total_claimed', $teamRequest->quantity_requested);
                }
            }
        });

        // When a new request is created and immediately approved (rare but possible)
        static::created(function ($teamRequest) {
            if ($teamRequest->status === 'approved') {
                $year = $teamRequest->created_at->year;
                $month = $teamRequest->created_at->month;
                
                MonthlyReport::updateOrCreate(
                    ['year' => $year, 'month' => $month],
                    ['total_requests' => DB::raw('COALESCE(total_requests, 0) + 1')]
                );
            }
        });

        // When a request is deleted
        static::deleting(function ($teamRequest) {
            $year = $teamRequest->created_at->year;
            $month = $teamRequest->created_at->month;
            
            if ($teamRequest->status === 'approved') {
                MonthlyReport::where('year', $year)
                    ->where('month', $month)
                    ->where('total_requests', '>', 0)
                    ->decrement('total_requests');
            }
            
            if ($teamRequest->status === 'claimed') {
                MonthlyReport::where('year', $year)
                    ->where('month', $month)
                    ->where('total_claimed', '>=', $teamRequest->quantity_requested)
                    ->decrement('total_claimed', $teamRequest->quantity_requested);
            }
            
            // When a request is deleted (cancelled), release any reserved stock
            if ($teamRequest->isApproved() && $teamRequest->item) {
                $teamRequest->item->releaseReservedStock($teamRequest->quantity_requested);
            }
        });
    }
}