<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Log;

class Approval extends Model
{
    protected $fillable = [
        'workflow_id',
        'workflow_name',
        'approvable_type',
        'approvable_id',
        'approvable_title',
        'amount',
        'currency',
        'description',
        'status',
        'current_level',
        'total_levels',
        'level_progress',
        'approver_history',
        'current_approvers',
        'all_assigned_approvers',
        'requested_at',
        'due_date',
        'approved_at',
        'rejected_at',
        'expired_at',
        'requested_by',
        'requested_by_name',
        'branch_id',
        'rejection_reason',
        'rejected_by',
        'rejected_by_name',
        'priority',
        'is_urgent',
        'auto_approved',
        'auto_approval_reason',
        'metadata',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'level_progress' => 'array',
        'approver_history' => 'array',
        'current_approvers' => 'array',
        'all_assigned_approvers' => 'array',
        'requested_at' => 'datetime',
        'due_date' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expired_at' => 'datetime',
        'is_urgent' => 'boolean',
        'auto_approved' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'workflow_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function approvable(): MorphTo
    {
        return $this->morphTo();
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

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeForApprover($query, $userId)
    {
        return $query->whereJsonContains('current_approvers', (string)$userId)
                    ->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', 'pending');
    }

    // Helper methods
    public function approve(User $approver, string $notes = null): bool
    {
        try {
            // Check if user can approve
            if (!$this->canBeApprovedBy($approver)) {
                return false;
            }

            // Record approval in history
            $this->recordApprovalAction($approver, 'approved', $notes);

            // Update level progress
            $this->updateLevelProgress($approver->id, 'approved');

            // Check if level is complete
            if ($this->isLevelComplete()) {
                if ($this->isFinalLevel()) {
                    // Final approval
                    $this->finalizeApproval();
                } else {
                    // Move to next level
                    $this->moveToNextLevel();
                }
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Approval processing failed', [
                'approval_id' => $this->id,
                'approver_id' => $approver->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function reject(User $approver, string $reason = null): bool
    {
        try {
            // Check if user can reject
            if (!$this->canBeRejectedBy($approver)) {
                return false;
            }

            $this->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejection_reason' => $reason,
                'rejected_by' => $approver->id,
                'rejected_by_name' => $approver->name,
            ]);

            // Record rejection in history
            $this->recordApprovalAction($approver, 'rejected', $reason);

            // Handle rejection
            $this->handleRejection();

            return true;

        } catch (\Exception $e) {
            Log::error('Approval rejection failed', [
                'approval_id' => $this->id,
                'approver_id' => $approver->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function canBeApprovedBy(User $user): bool
    {
        return in_array($user->id, $this->current_approvers ?? []);
    }

    public function canBeRejectedBy(User $user): bool
    {
        return in_array($user->id, $this->current_approvers ?? []) ||
               in_array($user->id, $this->all_assigned_approvers ?? []);
    }

    public function isLevelComplete(): bool
    {
        $progress = $this->level_progress ?? [];
        $currentLevelData = collect($progress)->firstWhere('level', $this->current_level);

        if (!$currentLevelData) {
            return false;
        }

        $approved = $currentLevelData['approved'] ?? 0;
        $required = $this->workflow->getMinApprovalsForLevel($this->current_level);

        return $approved >= $required;
    }

    public function isFinalLevel(): bool
    {
        return $this->current_level >= $this->total_levels;
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status === 'pending';
    }

    public function getDaysOverdue(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return now()->diffInDays($this->due_date);
    }

    protected function recordApprovalAction(User $user, string $action, string $notes = null): void
    {
        $history = $this->approver_history ?? [];
        $history[] = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'action' => $action,
            'level' => $this->current_level,
            'timestamp' => now(),
            'notes' => $notes,
        ];

        $this->update(['approver_history' => $history]);
    }

    protected function updateLevelProgress(int $userId, string $action): void
    {
        $progress = $this->level_progress ?? [];
        $levelIndex = collect($progress)->search(function ($level) {
            return $level['level'] === $this->current_level;
        });

        if ($levelIndex === false) {
            // Initialize level progress
            $progress[] = [
                'level' => $this->current_level,
                'required' => $this->workflow->getMinApprovalsForLevel($this->current_level),
                'approved' => 0,
                'rejected' => 0,
                'approved_by' => [],
            ];
            $levelIndex = count($progress) - 1;
        }

        if ($action === 'approved') {
            $progress[$levelIndex]['approved']++;
            $progress[$levelIndex]['approved_by'][] = $userId;
        } elseif ($action === 'rejected') {
            $progress[$levelIndex]['rejected']++;
        }

        $this->update(['level_progress' => $progress]);
    }

    protected function moveToNextLevel(): void
    {
        $nextLevel = $this->current_level + 1;
        $nextApprovers = $this->workflow->getApproversForLevel($nextLevel);

        $this->update([
            'current_level' => $nextLevel,
            'current_approvers' => $nextApprovers,
        ]);
    }

    protected function finalizeApproval(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Update the approvable item
        if ($this->approvable && method_exists($this->approvable, 'approve')) {
            $this->approvable->approve();
        }
    }

    protected function handleRejection(): void
    {
        // Update the approvable item
        if ($this->approvable && method_exists($this->approvable, 'reject')) {
            $this->approvable->reject();
        }
    }

    /**
     * Auto-approve if configured and overdue
     */
    public function checkAutoApproval(): bool
    {
        if ($this->status !== 'pending' || !$this->workflow->getAutoApproveDays()) {
            return false;
        }

        $autoApproveDays = $this->workflow->getAutoApproveDays();
        if ($this->requested_at->addDays($autoApproveDays)->isPast()) {
            $this->update([
                'status' => 'approved',
                'approved_at' => now(),
                'auto_approved' => true,
                'auto_approval_reason' => "Auto-approved after {$autoApproveDays} days",
            ]);

            // Finalize the approval
            $this->finalizeApproval();

            return true;
        }

        return false;
    }

    /**
     * Mark as expired if overdue
     */
    public function checkExpiration(): bool
    {
        if ($this->status !== 'pending' || !$this->due_date) {
            return false;
        }

        if ($this->due_date->isPast()) {
            $this->update([
                'status' => 'expired',
                'expired_at' => now(),
            ]);

            return true;
        }

        return false;
    }
}
