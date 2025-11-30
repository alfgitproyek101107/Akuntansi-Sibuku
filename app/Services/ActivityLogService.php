<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log a general activity
     */
    public static function log(string $actionType, string $description = null, array $metadata = []): ActivityLog
    {
        return ActivityLog::log($actionType, $description, $metadata);
    }

    /**
     * Log user authentication events
     */
    public static function logLogin(User $user, string $status = 'success'): ActivityLog
    {
        return ActivityLog::logLogin($user, $status);
    }

    public static function logLogout(User $user): ActivityLog
    {
        return ActivityLog::logLogout($user);
    }

    /**
     * Log data export activities
     */
    public static function logExport(User $user, string $exportType, int $recordCount, array $filters = []): ActivityLog
    {
        return ActivityLog::logExport($user, $exportType, $recordCount, $filters);
    }

    /**
     * Log financial activities
     */
    public static function logTransactionApproval(User $user, $transaction, string $action = 'approved'): ActivityLog
    {
        $description = "Transaction {$action}: {$transaction->description} (Rp " . number_format($transaction->amount, 0, ',', '.') . ")";

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'branch_id' => $transaction->branch_id,
            'branch_name' => $transaction->branch?->name,
            'action_type' => "transaction_{$action}",
            'model_type' => get_class($transaction),
            'model_id' => $transaction->id,
            'model_name' => $transaction->description,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'metadata' => [
                'transaction_type' => $transaction->type,
                'amount' => $transaction->amount,
                'account_name' => $transaction->account?->name,
            ],
            'occurred_at' => now(),
        ]);
    }

    /**
     * Log inventory activities
     */
    public static function logStockAdjustment(User $user, $product, float $oldStock, float $newStock, string $reason): ActivityLog
    {
        $adjustment = $newStock - $oldStock;
        $description = "Stock adjustment for {$product->name}: {$oldStock} -> {$newStock} (" . ($adjustment > 0 ? '+' : '') . $adjustment . ")";

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'branch_id' => $product->branch_id,
            'branch_name' => $product->branch?->name,
            'action_type' => 'stock_adjustment',
            'model_type' => get_class($product),
            'model_id' => $product->id,
            'model_name' => $product->name,
            'description' => $description,
            'old_values' => ['stock_quantity' => $oldStock],
            'new_values' => ['stock_quantity' => $newStock],
            'changed_fields' => ['stock_quantity'],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'metadata' => [
                'reason' => $reason,
                'adjustment' => $adjustment,
                'product_sku' => $product->sku,
            ],
            'occurred_at' => now(),
        ]);
    }

    /**
     * Log branch switching
     */
    public static function logBranchSwitch(User $user, Branch $newBranch, Branch $oldBranch = null): ActivityLog
    {
        $description = $oldBranch
            ? "Switched from branch '{$oldBranch->name}' to '{$newBranch->name}'"
            : "Switched to branch '{$newBranch->name}'";

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'branch_id' => $newBranch->id,
            'branch_name' => $newBranch->name,
            'action_type' => 'branch_switch',
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'metadata' => [
                'old_branch_id' => $oldBranch?->id,
                'old_branch_name' => $oldBranch?->name,
                'new_branch_id' => $newBranch->id,
                'new_branch_name' => $newBranch->name,
            ],
            'occurred_at' => now(),
        ]);
    }

    /**
     * Log system configuration changes
     */
    public static function logSystemConfigChange(User $user, string $configKey, $oldValue, $newValue): ActivityLog
    {
        $description = "System configuration changed: {$configKey}";

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action_type' => 'system_config_change',
            'description' => $description,
            'old_values' => [$configKey => $oldValue],
            'new_values' => [$configKey => $newValue],
            'changed_fields' => [$configKey],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'metadata' => [
                'config_key' => $configKey,
                'change_type' => 'system_setting',
            ],
            'occurred_at' => now(),
        ]);
    }

    /**
     * Log bulk operations
     */
    public static function logBulkOperation(User $user, string $operation, string $modelType, int $recordCount, array $details = []): ActivityLog
    {
        $modelName = class_basename($modelType);
        $description = "Bulk {$operation} of {$recordCount} {$modelName} records";

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'branch_id' => session('active_branch'),
            'action_type' => "bulk_{$operation}",
            'model_type' => $modelType,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'metadata' => array_merge([
                'operation' => $operation,
                'record_count' => $recordCount,
                'model_type' => $modelType,
            ], $details),
            'occurred_at' => now(),
        ]);
    }

    /**
     * Log security events
     */
    public static function logSecurityEvent(string $eventType, string $description, array $details = []): ActivityLog
    {
        $user = auth()->user();

        return ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'action_type' => "security_{$eventType}",
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'metadata' => array_merge([
                'security_event' => $eventType,
                'severity' => $details['severity'] ?? 'medium',
            ], $details),
            'occurred_at' => now(),
        ]);
    }

    /**
     * Log business rule violations
     */
    public static function logBusinessRuleViolation(User $user, string $rule, string $description, array $context = []): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'branch_id' => session('active_branch'),
            'action_type' => 'business_rule_violation',
            'description' => "Business rule violation: {$rule} - {$description}",
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'metadata' => array_merge([
                'rule' => $rule,
                'violation_type' => 'business_rule',
            ], $context),
            'occurred_at' => now(),
        ]);
    }

    /**
     * Get activity summary for dashboard
     */
    public static function getActivitySummary(int $days = 7): array
    {
        $startDate = now()->subDays($days);

        return [
            'total_activities' => ActivityLog::where('occurred_at', '>=', $startDate)->count(),
            'user_activities' => ActivityLog::where('occurred_at', '>=', $startDate)
                ->whereNotNull('user_id')
                ->distinct('user_id')
                ->count('user_id'),
            'top_actions' => ActivityLog::selectRaw('action_type, COUNT(*) as count')
                ->where('occurred_at', '>=', $startDate)
                ->groupBy('action_type')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'recent_activities' => ActivityLog::with(['user', 'branch'])
                ->where('occurred_at', '>=', $startDate)
                ->orderBy('occurred_at', 'desc')
                ->limit(10)
                ->get(),
        ];
    }

    /**
     * Clean old activity logs (data retention)
     */
    public static function cleanOldLogs(int $daysToKeep = 365): int
    {
        return ActivityLog::where('occurred_at', '<', now()->subDays($daysToKeep))->delete();
    }
}