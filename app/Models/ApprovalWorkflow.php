<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalWorkflow extends Model
{
    protected $fillable = [
        'name',
        'description',
        'module_type',
        'trigger_condition',
        'approval_levels',
        'min_amount',
        'max_amount',
        'category_ids',
        'user_ids',
        'branch_ids',
        'is_active',
        'require_all_levels',
        'auto_approve_after_days',
        'allow_self_approval',
        'metadata',
        'created_by',
        'branch_id',
    ];

    protected $casts = [
        'approval_levels' => 'array',
        'category_ids' => 'array',
        'user_ids' => 'array',
        'branch_ids' => 'array',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'require_all_levels' => 'boolean',
        'allow_self_approval' => 'boolean',
        'metadata' => 'array',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'workflow_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForModule($query, $moduleType)
    {
        return $query->where('module_type', $moduleType);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where(function ($q) use ($branchId) {
            $q->whereNull('branch_ids')
              ->orWhereJsonContains('branch_ids', (string)$branchId);
        });
    }

    // Helper methods
    public function matchesConditions($moduleType, $amount, $categoryId = null, $userId = null, $branchId = null): bool
    {
        // Check module type
        if ($this->module_type !== $moduleType) {
            return false;
        }

        // Check amount range
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }
        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }

        // Check category
        if ($this->category_ids && !in_array($categoryId, $this->category_ids)) {
            return false;
        }

        // Check user
        if ($this->user_ids && !in_array($userId, $this->user_ids)) {
            return false;
        }

        // Check branch
        if ($this->branch_ids && !in_array($branchId, $this->branch_ids)) {
            return false;
        }

        return true;
    }

    public function requiresApproval($amount, $categoryId = null, $userId = null, $branchId = null): bool
    {
        return $this->matchesConditions($this->module_type, $amount, $categoryId, $userId, $branchId);
    }

    public function getApproversForLevel(int $level): array
    {
        $levels = $this->approval_levels ?? [];
        // Handle case where approval_levels might be stored as string
        if (is_string($levels)) {
            $levels = json_decode($levels, true) ?? [];
        }
        // Ensure it's an array
        if (!is_array($levels)) {
            $levels = [];
        }
        foreach ($levels as $levelData) {
            if (($levelData['level'] ?? 0) === $level) {
                return $levelData['approvers'] ?? [];
            }
        }
        return [];
    }

    public function getMinApprovalsForLevel(int $level): int
    {
        $levels = $this->approval_levels ?? [];
        // Handle case where approval_levels might be stored as string
        if (is_string($levels)) {
            $levels = json_decode($levels, true) ?? [];
        }
        // Ensure it's an array
        if (!is_array($levels)) {
            $levels = [];
        }
        foreach ($levels as $levelData) {
            if (($levelData['level'] ?? 0) === $level) {
                return $levelData['min_approvals'] ?? 1;
            }
        }
        return 1;
    }

    public function getTotalLevels(): int
    {
        $levels = $this->approval_levels ?? [];
        // Handle case where approval_levels might be stored as string
        if (is_string($levels)) {
            $levels = json_decode($levels, true) ?? [];
        }
        // Ensure it's an array
        if (!is_array($levels)) {
            $levels = [];
        }
        return count($levels);
    }

    public function isSequentialApproval(): bool
    {
        return $this->require_all_levels;
    }

    public function allowsSelfApproval(): bool
    {
        return $this->allow_self_approval;
    }

    public function getAutoApproveDays(): ?int
    {
        return $this->auto_approve_after_days;
    }

    /**
     * Create a default workflow for transactions
     */
    public static function createDefaultTransactionWorkflow($branchId = null): self
    {
        return static::create([
            'name' => 'Approval Transaksi > 1 Juta',
            'description' => 'Workflow approval untuk transaksi di atas 1 juta rupiah',
            'module_type' => 'transaction',
            'trigger_condition' => 'amount > 1000000',
            'approval_levels' => [
                [
                    'level' => 1,
                    'approvers' => [], // To be filled with manager IDs
                    'min_approvals' => 1,
                    'name' => 'Manager Approval'
                ]
            ],
            'min_amount' => 1000000,
            'is_active' => true,
            'require_all_levels' => true,
            'allow_self_approval' => false,
            'created_by' => auth()->id() ?? 1,
            'branch_id' => $branchId,
        ]);
    }
}
