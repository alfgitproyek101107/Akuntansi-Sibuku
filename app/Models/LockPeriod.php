<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LockPeriod extends Model
{
    protected $fillable = [
        'branch_id',
        'period_type',
        'start_date',
        'end_date',
        'is_locked',
        'locked_by',
        'locked_at',
        'lock_reason',
        'allowed_modules',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_locked' => 'boolean',
        'locked_at' => 'datetime',
        'allowed_modules' => 'array',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function lockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    // Scopes
    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForPeriodType($query, $type)
    {
        return $query->where('period_type', $type);
    }

    public function scopeContainsDate($query, $date)
    {
        return $query->where('start_date', '<=', $date)
                    ->where('end_date', '>=', $date);
    }

    // Helper methods
    public function lock(User $user, $reason = null)
    {
        $this->update([
            'is_locked' => true,
            'locked_by' => $user->id,
            'locked_at' => now(),
            'lock_reason' => $reason,
        ]);
    }

    public function unlock()
    {
        $this->update([
            'is_locked' => false,
            'locked_by' => null,
            'locked_at' => null,
            'lock_reason' => null,
        ]);
    }

    public function containsDate(Carbon $date)
    {
        return $this->start_date <= $date && $this->end_date >= $date;
    }

    public function isModuleAllowed($module)
    {
        if (!$this->is_locked) {
            return true;
        }

        $allowedModules = $this->allowed_modules ?? [];
        return in_array($module, $allowedModules) || empty($allowedModules);
    }

    public function getPeriodName()
    {
        switch ($this->period_type) {
            case 'monthly':
                return $this->start_date->format('F Y');
            case 'quarterly':
                $quarter = ceil($this->start_date->month / 3);
                return "Q{$quarter} " . $this->start_date->year;
            case 'yearly':
                return $this->start_date->year;
            default:
                return $this->start_date->format('M j, Y') . ' - ' . $this->end_date->format('M j, Y');
        }
    }

    public static function isDateLocked($branchId, Carbon $date, $module = null)
    {
        $lockPeriod = static::locked()
            ->forBranch($branchId)
            ->containsDate($date)
            ->first();

        if (!$lockPeriod) {
            return false;
        }

        return $module ? !$lockPeriod->isModuleAllowed($module) : true;
    }

    public static function canModifyTransaction($branchId, Carbon $date, $module = 'transactions')
    {
        return !static::isDateLocked($branchId, $date, $module);
    }
}
