<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TaxLog extends Model
{
    protected $fillable = [
        'branch_id',
        'user_id',
        'tax_invoice_id',
        'endpoint',
        'method',
        'action',
        'request_payload',
        'response_data',
        'http_status_code',
        'status',
        'error_message',
        'error_code',
        'attempt_number',
        'max_attempts',
        'next_retry_at',
        'completed_at',
        'external_reference',
        'processing_time',
        'notes',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'response_data' => 'array',
        'next_retry_at' => 'datetime',
        'completed_at' => 'datetime',
        'processing_time' => 'decimal:3',
        'attempt_number' => 'integer',
        'max_attempts' => 'integer',
        'http_status_code' => 'integer',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Global scope for branch isolation
        static::addGlobalScope('branch', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();

                // Super admin can see all branches
                if ($user->hasRole('super-admin')) {
                    return;
                }

                // Demo users can only see demo branch data
                if ($user->demo_mode) {
                    $builder->where('branch_id', 999); // Demo branch ID
                    return;
                }

                // Regular users only see their assigned branch or active branch
                $branchId = session('active_branch') ?? ($user->branch_id ?? null);
                if ($branchId) {
                    $builder->where('branch_id', $branchId);
                }
            }
        });
    }

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taxInvoice()
    {
        return $this->belongsTo(TaxInvoice::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRetry($query)
    {
        return $query->where('status', 'retry');
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeReadyForRetry($query)
    {
        return $query->where('status', 'failed')
                    ->where('attempt_number', '<', \DB::raw('max_attempts'))
                    ->where(function ($q) {
                        $q->whereNull('next_retry_at')
                          ->orWhere('next_retry_at', '<=', now());
                    });
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSuccess()
    {
        return $this->status === 'success';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function canRetry()
    {
        return $this->isFailed() && $this->attempt_number < $this->max_attempts;
    }

    public function shouldRetry()
    {
        return $this->canRetry() && (is_null($this->next_retry_at) || $this->next_retry_at->isPast());
    }

    public function markAsSuccess($responseData = null, $externalReference = null)
    {
        $this->update([
            'status' => 'success',
            'response_data' => $responseData,
            'external_reference' => $externalReference,
            'completed_at' => now(),
            'error_message' => null,
            'error_code' => null,
        ]);
    }

    public function markAsFailed($errorMessage = null, $errorCode = null, $nextRetryAt = null)
    {
        $updateData = [
            'status' => $this->canRetry() ? 'retry' : 'failed',
            'error_message' => $errorMessage,
            'error_code' => $errorCode,
            'completed_at' => $this->canRetry() ? null : now(),
        ];

        if ($nextRetryAt) {
            $updateData['next_retry_at'] = $nextRetryAt;
        }

        $this->update($updateData);
    }

    public function incrementAttempt()
    {
        $this->update([
            'attempt_number' => $this->attempt_number + 1,
        ]);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'success' => 'badge-success',
            'failed' => 'badge-danger',
            'retry' => 'badge-info',
            default => 'badge-secondary',
        };
    }

    public function getActionDisplayName()
    {
        return match($this->action) {
            'validate_npwp' => 'Validasi NPWP',
            'create_invoice' => 'Buat Faktur',
            'sync_data' => 'Sinkronisasi Data',
            'check_status' => 'Cek Status',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}
