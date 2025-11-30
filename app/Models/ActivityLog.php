<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'branch_id',
        'branch_name',
        'action_type',
        'model_type',
        'model_id',
        'model_name',
        'old_values',
        'new_values',
        'changed_fields',
        'ip_address',
        'user_agent',
        'session_id',
        'request_data',
        'description',
        'notes',
        'status',
        'metadata',
        'occurred_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'request_data' => 'array',
        'metadata' => 'array',
        'occurred_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Scopes
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    public function scopeForAction($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('occurred_at', '>=', now()->subDays($days));
    }

    /**
     * Static methods for logging activities
     */
    public static function log($actionType, $description = null, array $data = [])
    {
        $user = auth()->user();
        $branch = session('active_branch') ? Branch::find(session('active_branch')) : ($user ? $user->branch : null);

        $logData = array_merge([
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : null,
            'user_email' => $user ? $user->email : null,
            'branch_id' => $branch ? $branch->id : null,
            'branch_name' => $branch ? $branch->name : null,
            'action_type' => $actionType,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'occurred_at' => now(),
        ], $data);

        return static::create($logData);
    }

    public static function logModelChange(Model $model, string $action, array $oldValues = [], array $newValues = [])
    {
        $user = auth()->user();
        $branch = session('active_branch') ? Branch::find(session('active_branch')) : ($user ? $user->branch : null);

        // Determine changed fields
        $changedFields = [];
        if (!empty($oldValues) && !empty($newValues)) {
            $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
        }

        // Get model name for display
        $modelName = method_exists($model, 'getAuditName')
            ? $model->getAuditName()
            : (isset($model->name) ? $model->name : $model->id);

        $description = self::generateModelChangeDescription($model, $action, $modelName);

        return static::create([
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : null,
            'user_email' => $user ? $user->email : null,
            'branch_id' => $branch ? $branch->id : null,
            'branch_name' => $branch ? $branch->name : null,
            'action_type' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->getKey(),
            'model_name' => $modelName,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changed_fields' => $changedFields,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'occurred_at' => now(),
        ]);
    }

    public static function logLogin($user, $status = 'success')
    {
        return static::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action_type' => 'login',
            'description' => $status === 'success' ? 'User logged in successfully' : 'Failed login attempt',
            'status' => $status,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'occurred_at' => now(),
        ]);
    }

    public static function logLogout($user)
    {
        return static::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action_type' => 'logout',
            'description' => 'User logged out',
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'occurred_at' => now(),
        ]);
    }

    public static function logExport($user, $exportType, $recordCount, $filters = [])
    {
        return static::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'action_type' => 'export',
            'description' => "Exported {$recordCount} {$exportType} records",
            'metadata' => [
                'export_type' => $exportType,
                'record_count' => $recordCount,
                'filters' => $filters,
            ],
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'session_id' => session()->getId(),
            'occurred_at' => now(),
        ]);
    }

    /**
     * Helper methods
     */
    private static function generateModelChangeDescription(Model $model, string $action, string $modelName): string
    {
        $modelType = class_basename($model);

        switch ($action) {
            case 'created':
                return "Created new {$modelType}: {$modelName}";
            case 'updated':
                return "Updated {$modelType}: {$modelName}";
            case 'deleted':
                return "Deleted {$modelType}: {$modelName}";
            case 'viewed':
                return "Viewed {$modelType}: {$modelName}";
            default:
                return ucfirst($action) . " {$modelType}: {$modelName}";
        }
    }

    /**
     * Get formatted change summary
     */
    public function getChangeSummary(): array
    {
        if (empty($this->changed_fields)) {
            return [];
        }

        $summary = [];
        foreach ($this->changed_fields as $field) {
            $oldValue = $this->old_values[$field] ?? null;
            $newValue = $this->new_values[$field] ?? null;

            $summary[$field] = [
                'old' => $oldValue,
                'new' => $newValue,
                'changed' => $oldValue !== $newValue,
            ];
        }

        return $summary;
    }

    /**
     * Get activity icon based on action type
     */
    public function getActivityIcon(): string
    {
        return match($this->action_type) {
            'created' => 'plus-circle',
            'updated' => 'edit',
            'deleted' => 'trash',
            'login' => 'sign-in-alt',
            'logout' => 'sign-out-alt',
            'export' => 'download',
            'viewed' => 'eye',
            default => 'circle',
        };
    }

    /**
     * Get activity color based on action type
     */
    public function getActivityColor(): string
    {
        return match($this->action_type) {
            'created' => 'success',
            'updated' => 'warning',
            'deleted' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            'export' => 'primary',
            'viewed' => 'light',
            default => 'secondary',
        };
    }
}
