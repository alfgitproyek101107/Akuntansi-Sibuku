<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'user_id',
        'branch_id',
        'notifiable_type',
        'notifiable_id',
        'data',
        'is_read',
        'read_at',
        'expires_at',
        'priority',
        'channel',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    // Helper methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function getPriorityColor()
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'normal' => 'blue',
            'low' => 'gray',
            default => 'blue',
        };
    }

    public function getIcon()
    {
        return match ($this->type) {
            'approval_request' => 'fas fa-user-check',
            'due_date' => 'fas fa-calendar-times',
            'low_balance' => 'fas fa-exclamation-triangle',
            'transaction_approved' => 'fas fa-check-circle',
            'transaction_rejected' => 'fas fa-times-circle',
            'period_locked' => 'fas fa-lock',
            'stock_low' => 'fas fa-boxes',
            default => 'fas fa-bell',
        };
    }

    // Static methods for creating notifications
    public static function createApprovalRequest($userId, $approvable, $workflow, $branchId = null)
    {
        return static::create([
            'type' => 'approval_request',
            'title' => 'Permintaan Persetujuan',
            'message' => "Ada permintaan persetujuan yang menunggu untuk {$approvable->getTable()}",
            'user_id' => $userId,
            'branch_id' => $branchId,
            'notifiable_type' => get_class($approvable),
            'notifiable_id' => $approvable->id,
            'data' => ['workflow_id' => $workflow->id],
            'priority' => 'high',
        ]);
    }

    public static function createDueDateReminder($userId, $item, $dueDate, $branchId = null)
    {
        $itemName = isset($item->name) ? $item->name : 'tidak diketahui';
        return static::create([
            'type' => 'due_date',
            'title' => 'Pengingat Jatuh Tempo',
            'message' => "Item {$itemName} akan jatuh tempo pada " . $dueDate->format('d M Y'),
            'user_id' => $userId,
            'branch_id' => $branchId,
            'notifiable_type' => get_class($item),
            'notifiable_id' => $item->id,
            'data' => ['due_date' => $dueDate],
            'priority' => 'urgent',
            'expires_at' => $dueDate->copy()->addDays(1),
        ]);
    }

    public static function createLowBalanceAlert($userId, $account, $branchId = null)
    {
        return static::create([
            'type' => 'low_balance',
            'title' => 'Saldo Rendah',
            'message' => "Saldo rekening {$account->name} berada di bawah batas minimum",
            'user_id' => $userId,
            'branch_id' => $branchId,
            'notifiable_type' => get_class($account),
            'notifiable_id' => $account->id,
            'data' => ['balance' => $account->balance],
            'priority' => 'high',
        ]);
    }
}
