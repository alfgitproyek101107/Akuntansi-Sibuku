<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TaxInvoice extends Model
{
    protected $fillable = [
        'transaction_id',
        'branch_id',
        'user_id',
        'invoice_number',
        'invoice_date',
        'invoice_type',
        'tax_type',
        'subtotal',
        'tax_amount',
        'total_amount',
        'tax_rate',
        'customer_name',
        'customer_npwp',
        'customer_nik',
        'customer_address',
        'customer_type',
        'coretax_invoice_id',
        'coretax_qr_code',
        'coretax_serial_number',
        'coretax_status',
        'coretax_sent_at',
        'coretax_approved_at',
        'coretax_response',
        'status',
        'notes',
        'items',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'coretax_sent_at' => 'datetime',
        'coretax_approved_at' => 'datetime',
        'coretax_response' => 'array',
        'items' => 'array',
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
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taxLogs()
    {
        return $this->hasMany(TaxLog::class);
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

    public function scopeByType($query, $type)
    {
        return $query->where('tax_type', $type);
    }

    // Helper methods
    public function isOutputTax()
    {
        return $this->tax_type === 'output';
    }

    public function isInputTax()
    {
        return $this->tax_type === 'input';
    }

    public function isSentToCoreTax()
    {
        return !is_null($this->coretax_sent_at);
    }

    public function isApprovedByCoreTax()
    {
        return $this->coretax_status === 'approved';
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'draft' => 'badge-secondary',
            'generated' => 'badge-info',
            'sent' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getCoreTaxStatusBadgeClass()
    {
        return match($this->coretax_status) {
            'draft' => 'badge-secondary',
            'sent' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    public function getTaxTypeDisplayName()
    {
        return match($this->tax_type) {
            'ppn' => 'PPN',
            'ppn_umkm' => 'PPN UMKM',
            'pph_21' => 'PPh 21',
            'pph_22' => 'PPh 22',
            'pph_23' => 'PPh 23',
            default => ucfirst(str_replace('_', ' ', $this->tax_type)),
        };
    }
}
