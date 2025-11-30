<?php

namespace App\Services;

use App\Models\ApprovalWorkflow;
use App\Models\Approval;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ApprovalService
{
    /**
     * Check if an item requires approval and create approval request if needed
     */
    public function checkAndCreateApproval(Model $item, User $requester): ?Approval
    {
        // Determine module type
        $moduleType = $this->getModuleType($item);

        // Get approval amount
        $amount = $this->getApprovalAmount($item);

        // Get category ID if applicable
        $categoryId = $this->getCategoryId($item);

        // Get branch ID
        $branchId = $this->getBranchId($item, $requester);

        // Find applicable workflow
        $workflow = $this->findApplicableWorkflow($moduleType, $amount, $categoryId, $requester->id, $branchId);

        if (!$workflow) {
            // No approval required
            return null;
        }

        // Create approval request
        return $this->createApprovalRequest($workflow, $item, $requester, $amount);
    }

    /**
     * Find applicable workflow for the given conditions
     */
    public function findApplicableWorkflow(string $moduleType, float $amount, ?int $categoryId, int $userId, ?int $branchId): ?ApprovalWorkflow
    {
        $workflows = ApprovalWorkflow::active()
            ->forModule($moduleType)
            ->forBranch($branchId)
            ->get();

        foreach ($workflows as $workflow) {
            if ($workflow->matchesConditions($moduleType, $amount, $categoryId, $userId, $branchId)) {
                return $workflow;
            }
        }

        return null;
    }

    /**
     * Create an approval request
     */
    public function createApprovalRequest(ApprovalWorkflow $workflow, Model $item, User $requester, float $amount): Approval
    {
        // Get current approvers for level 1
        $currentApprovers = $workflow->getApproversForLevel(1);
        $allAssignedApprovers = $this->getAllAssignedApprovers($workflow);

        // Calculate due date
        $dueDate = $this->calculateDueDate($workflow);

        // Create approval request
        $approval = Approval::create([
            'workflow_id' => $workflow->id,
            'workflow_name' => $workflow->name,
            'approvable_type' => get_class($item),
            'approvable_id' => $item->getKey(),
            'approvable_title' => $this->getItemTitle($item),
            'amount' => $amount,
            'currency' => 'IDR',
            'description' => $this->getItemDescription($item),
            'status' => 'pending',
            'current_level' => 1,
            'total_levels' => $workflow->getTotalLevels(),
            'level_progress' => $this->initializeLevelProgress($workflow),
            'approver_history' => [],
            'current_approvers' => $currentApprovers,
            'all_assigned_approvers' => $allAssignedApprovers,
            'requested_at' => now(),
            'due_date' => $dueDate,
            'requested_by' => $requester->id,
            'requested_by_name' => $requester->name,
            'branch_id' => $this->getBranchId($item, $requester),
            'priority' => $this->calculatePriority($amount),
            'is_urgent' => $this->isUrgent($amount),
            'item_details' => $this->getItemDetails($item),
        ]);

        // Log the approval creation
        Log::info('Approval request created', [
            'approval_id' => $approval->id,
            'workflow_id' => $workflow->id,
            'item_type' => get_class($item),
            'item_id' => $item->getKey(),
            'requester_id' => $requester->id,
            'amount' => $amount,
        ]);

        return $approval;
    }

    /**
     * Process an approval action (approve/reject)
     */
    public function processApproval(int $approvalId, int $userId, string $action, ?string $notes = null): bool
    {
        $approval = Approval::findOrFail($approvalId);
        $user = User::findOrFail($userId);

        if ($action === 'approve') {
            return $approval->approve($user, $notes);
        } elseif ($action === 'reject') {
            return $approval->reject($user, $notes);
        }

        return false;
    }

    /**
     * Check and process auto-approvals
     */
    public function processAutoApprovals(): int
    {
        $approvals = Approval::pending()
            ->whereNotNull('due_date')
            ->get();

        $processed = 0;

        foreach ($approvals as $approval) {
            if ($approval->checkAutoApproval()) {
                $processed++;
            }
        }

        return $processed;
    }

    /**
     * Check and expire overdue approvals
     */
    public function processExpiredApprovals(): int
    {
        $approvals = Approval::pending()
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->get();

        $expired = 0;

        foreach ($approvals as $approval) {
            if ($approval->checkExpiration()) {
                $expired++;
            }
        }

        return $expired;
    }

    /**
     * Get pending approvals for a user
     */
    public function getPendingApprovalsForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return Approval::forApprover($userId)
            ->with(['workflow', 'requester', 'approvable'])
            ->orderBy('requested_at', 'desc')
            ->get();
    }

    /**
     * Get overdue approvals
     */
    public function getOverdueApprovals(): \Illuminate\Database\Eloquent\Collection
    {
        return Approval::overdue()
            ->with(['workflow', 'requester', 'approvable'])
            ->orderBy('due_date', 'asc')
            ->get();
    }

    // Helper methods

    private function getModuleType(Model $item): string
    {
        return match(get_class($item)) {
            \App\Models\Transaction::class => 'transaction',
            \App\Models\Transfer::class => 'transfer',
            \App\Models\JournalEntry::class => 'journal_entry',
            default => 'unknown',
        };
    }

    private function getApprovalAmount(Model $item): float
    {
        return match(get_class($item)) {
            \App\Models\Transaction::class => abs($item->amount),
            \App\Models\Transfer::class => $item->amount,
            \App\Models\JournalEntry::class => $item->total_amount ?? 0,
            default => 0,
        };
    }

    private function getCategoryId(Model $item): ?int
    {
        return $item->category_id ?? null;
    }

    private function getBranchId(Model $item, User $requester): ?int
    {
        return $item->branch_id ?? $requester->branch_id ?? session('active_branch');
    }

    private function getItemTitle(Model $item): string
    {
        return match(get_class($item)) {
            \App\Models\Transaction::class => "Transaksi: {$item->description}",
            \App\Models\Transfer::class => "Transfer: {$item->description}",
            \App\Models\JournalEntry::class => "Jurnal: {$item->description}",
            default => "Item {$item->getKey()}",
        };
    }

    private function getItemDescription(Model $item): ?string
    {
        return $item->description ?? $item->notes ?? null;
    }

    private function getAllAssignedApprovers(ApprovalWorkflow $workflow): array
    {
        $allApprovers = [];
        for ($level = 1; $level <= $workflow->getTotalLevels(); $level++) {
            $approvers = $workflow->getApproversForLevel($level);
            $allApprovers = array_merge($allApprovers, $approvers);
        }
        return array_unique($allApprovers);
    }

    private function calculateDueDate(ApprovalWorkflow $workflow): ?\Carbon\Carbon
    {
        $autoApproveDays = $workflow->getAutoApproveDays();
        if ($autoApproveDays) {
            return now()->addDays($autoApproveDays);
        }
        return null;
    }

    private function initializeLevelProgress(ApprovalWorkflow $workflow): array
    {
        $progress = [];
        for ($level = 1; $level <= $workflow->getTotalLevels(); $level++) {
            $progress[] = [
                'level' => $level,
                'required' => $workflow->getMinApprovalsForLevel($level),
                'approved' => 0,
                'rejected' => 0,
                'approved_by' => [],
            ];
        }
        return $progress;
    }

    private function calculatePriority(float $amount): string
    {
        if ($amount >= 10000000) return 'urgent'; // 10M+
        if ($amount >= 5000000) return 'high';    // 5M+
        if ($amount >= 1000000) return 'medium';  // 1M+
        return 'low';
    }

    private function isUrgent(float $amount): bool
    {
        return $amount >= 10000000; // 10M+
    }

    private function getItemDetails(Model $item): array
    {
        return match(get_class($item)) {
            \App\Models\Transaction::class => [
                'type' => $item->type,
                'date' => $item->date,
                'account_name' => $item->account?->name,
                'category_name' => $item->category?->name,
            ],
            \App\Models\Transfer::class => [
                'date' => $item->date,
                'from_account' => $item->fromAccount?->name,
                'to_account' => $item->toAccount?->name,
            ],
            default => $item->toArray(),
        };
    }
}