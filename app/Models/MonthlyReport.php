<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'beginning_stock_value',
        'total_requests',
        'total_restocked',
        'total_claimed',
        'ending_stock_value',
        'most_requested_items',
        'fast_depleting_items',
        'report_generated_at',
        'is_finalized',
    ];

    protected $casts = [
        'most_requested_items' => 'array',
        'fast_depleting_items' => 'array',
        'year' => 'integer',
        'month' => 'integer',
        'is_finalized' => 'boolean',
        'report_generated_at' => 'datetime',
    ];

    /**
     * Get the month name
     */
    public function getMonthNameAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    /**
     * Get the full period name (e.g., "January 2025")
     */
    public function getPeriodAttribute()
    {
        return $this->month_name . ' ' . $this->year;
    }

    /**
     * Calculate activity score for this report
     */
    public function getActivityScoreAttribute()
    {
        return ($this->total_requests * 0.4) + 
               ($this->total_claimed * 0.3) + 
               ($this->total_restocked * 0.3);
    }

    /**
     * Get status based on activity score
     */
    public function getStatusAttribute()
    {
        $score = $this->activity_score;
        
        if ($score >= 100) return 'High Activity';
        if ($score >= 50) return 'Medium Activity';
        if ($score > 0) return 'Low Activity';
        return 'No Activity';
    }

    /**
     * Scope for finalized reports
     */
    public function scopeFinalized(Builder $query): Builder
    {
        return $query->where('is_finalized', true);
    }

    /**
     * Scope for reports by year
     */
    public function scopeForYear(Builder $query, $year): Builder
    {
        return $query->where('year', $year);
    }

    /**
     * Scope for reports by month
     */
    public function scopeForMonth(Builder $query, $month): Builder
    {
        return $query->where('month', $month);
    }

    /**
     * Scope for active reports (not finalized)
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_finalized', false);
    }

    /**
     * Scope for sorting
     */
    public function scopeSortByColumn(Builder $query, $column, $direction = 'desc'): Builder
    {
        $validColumns = ['year', 'month', 'total_requests', 'total_restocked', 'total_claimed', 'report_generated_at'];
        $validDirections = ['asc', 'desc'];
        
        if (in_array($column, $validColumns) && in_array($direction, $validDirections)) {
            return $query->orderBy($column, $direction);
        }
        
        return $query->orderBy('year', 'desc')->orderBy('month', 'desc');
    }

    /**
     * Scope for searching
     */
    public function scopeSearch(Builder $query, $search): Builder
    {
        if (!$search) {
            return $query;
        }
        
        return $query->where(function($q) use ($search) {
            $q->where('year', 'like', "%{$search}%")
              ->orWhere('month', 'like', "%{$search}%");
        });
    }

    /**
     * Get previous month's report
     */
    public function previousMonth()
    {
        $prevMonth = $this->month - 1;
        $prevYear = $this->year;
        
        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }
        
        return static::where('year', $prevYear)
            ->where('month', $prevMonth)
            ->first();
    }

    /**
     * Get year-to-date totals
     */
    public static function getYearToDateTotals($year)
    {
        return self::where('year', $year)
            ->selectRaw('
                SUM(total_requests) as total_requests,
                SUM(total_restocked) as total_restocked,
                SUM(total_claimed) as total_claimed,
                COUNT(*) as report_count
            ')
            ->first();
    }

    /**
     * Get available years for filtering
     */
    public static function getAvailableYears()
    {
        return self::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }
}