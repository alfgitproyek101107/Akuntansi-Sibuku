<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Scopes\BranchScope;

class ReportSchedule extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'report_type',
        'scheduled_time',
        'whatsapp_number',
        'is_active',
        'settings',
        'last_sent_at',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'settings' => 'array',
        'last_sent_at' => 'datetime',
    ];

    // Note: ReportSchedule doesn't use BranchScope because schedules can be for all branches
    // Branch filtering is handled in the controller logic instead

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Scope for active schedules
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific report type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope for schedules due at specific time
     */
    public function scopeDueAt($query, $time)
    {
        return $query->where('scheduled_time', $time);
    }

    /**
     * Check if schedule is due for sending
     */
    public function isDue(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        $scheduledTime = $this->scheduled_time;

        // Check if current time matches scheduled time
        if ($now->format('H:i') !== $scheduledTime->format('H:i')) {
            return false;
        }

        // Check frequency-based conditions
        switch ($this->report_type) {
            case 'daily':
                return true; // Send every day at scheduled time

            case 'weekly':
                return $now->dayOfWeek === 0; // Send on Sundays

            case 'monthly':
                return $now->day === 1; // Send on first day of month

            default:
                return false;
        }
    }

    /**
     * Mark schedule as sent
     */
    public function markAsSent(): void
    {
        $this->update(['last_sent_at' => now()]);
    }
}