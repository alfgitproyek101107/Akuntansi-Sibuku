<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'amount',
        'description',
        'receipt_image',
        'date',
        'type',
        'transfer_id',
        'reconciled',
        'product_id',
        'service_id',
        'tax_rate',
        'tax_amount',
        'tax_type',
        'is_taxable',
        'tax_invoice_id',
        'customer_name',
        'customer_npwp',
        'customer_nik',
        'customer_address',
        'customer_type',
        'tax_included_in_price',
        'generate_tax_invoice',
        'branch_id',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'is_taxable' => 'boolean',
        'tax_included_in_price' => 'boolean',
        'generate_tax_invoice' => 'boolean',
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

                // Regular users only see their assigned branch or active branch
                $branchId = session('active_branch') ?? ($user->branch_id ?? null);
                if ($branchId) {
                    $builder->where('branch_id', $branchId);
                }
            }
        });

        // Check for approval requirements on creation
        static::creating(function (Transaction $transaction) {
            $transaction->checkApprovalRequirement();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function taxInvoice()
    {
        return $this->belongsTo(TaxInvoice::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the income items for this transaction (for multi-item income transactions).
     */
    public function incomeItems()
    {
        return $this->hasMany(IncomeItem::class, 'income_id');
    }

    /**
     * Get the expense items for this transaction (for multi-item expense transactions).
     */
    public function expenseItems()
    {
        return $this->hasMany(ExpenseItem::class, 'expense_id');
    }

    /**
     * Approval-related methods
     */
    public function checkApprovalRequirement(): void
    {
        // Skip if already has status set (e.g., from import or specific creation)
        if ($this->status) {
            return;
        }

        // For now, bypass approval and directly post transactions
        $this->status = 'posted';
    }

    public function approve(): void
    {
        $user = auth()->user();
        $this->update([
            'status' => 'posted',
            'approved_by' => $user ? $user->id : null,
            'approved_at' => now(),
        ]);
    }

    public function reject(): void
    {
        $this->update(['status' => 'rejected']);
    }

    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function isApproved(): bool
    {
        return $this->status === 'posted' && $this->approved_at;
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get the approval request for this transaction
     */
    public function approval()
    {
        return $this->morphOne(\App\Models\Approval::class, 'approvable');
    }

    /**
     * Get approval status text
     */
    public function getApprovalStatusText(): string
    {
        return match($this->status) {
            'pending_approval' => 'Menunggu Persetujuan',
            'posted' => $this->approved_at ? 'Disetujui' : 'Diposting',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Get the receipt image URL
     */
    public function getReceiptImageUrl(): ?string
    {
        return $this->receipt_image ? asset('storage/receipts/' . $this->receipt_image) : null;
    }

    /**
     * Check if transaction has a receipt image
     */
    public function hasReceiptImage(): bool
    {
        return !empty($this->receipt_image);
    }

    /**
     * Tax-related methods
     */
    public function calculateTax(): void
    {
        if (!$this->is_taxable || $this->tax_amount > 0) {
            return;
        }

        $taxSettings = \App\Models\TaxSetting::getForCurrentBranch();
        if (!$taxSettings || !$taxSettings->shouldAutoCalculateTax()) {
            return;
        }

        // Determine tax type based on transaction type and settings
        $this->tax_type = $this->determineTaxType($taxSettings);

        // Get tax rate
        $this->tax_rate = $taxSettings->getTaxRate($this->tax_type);

        // Calculate tax amount
        if ($this->tax_included_in_price) {
            // Tax is included in the price
            $this->tax_amount = ($this->amount * $this->tax_rate) / (100 + $this->tax_rate);
        } else {
            // Tax is added to the price
            $this->tax_amount = ($this->amount * $this->tax_rate) / 100;
        }
    }

    private function determineTaxType(\App\Models\TaxSetting $taxSettings): string
    {
        // Determine tax type based on transaction type and product/service
        if ($this->type === 'income') {
            // For sales transactions
            if ($this->product && !$taxSettings->isTaxExempt($this->product->id)) {
                return $taxSettings->default_tax_type;
            }
            if ($this->service) {
                // Services might have different tax rules (PPh 23)
                return 'pph_23';
            }
        } elseif ($this->type === 'expense') {
            // For purchase transactions - input tax
            return $taxSettings->default_tax_type;
        }

        return $taxSettings->default_tax_type;
    }

    public function shouldGenerateTaxInvoice(): bool
    {
        if (!$this->generate_tax_invoice || !$this->is_taxable) {
            return false;
        }

        $taxSettings = \App\Models\TaxSetting::getForCurrentBranch();
        return $taxSettings && $taxSettings->requiresTaxInvoice();
    }

    public function getTaxableAmount(): float
    {
        if ($this->tax_included_in_price) {
            return $this->amount - $this->tax_amount;
        }
        return $this->amount;
    }

    public function getTotalAmountWithTax(): float
    {
        if ($this->tax_included_in_price) {
            return $this->amount;
        }
        return $this->amount + $this->tax_amount;
    }

    public function isPPN(): bool
    {
        return in_array($this->tax_type, ['ppn', 'ppn_umkm']);
    }

    public function isPPh(): bool
    {
        return str_starts_with($this->tax_type, 'pph');
    }

    public function hasTaxInvoice(): bool
    {
        return !is_null($this->tax_invoice_id);
    }

    public function getTaxTypeDisplayName(): string
    {
        return match($this->tax_type) {
            'ppn' => 'PPN 11%',
            'ppn_umkm' => 'PPN UMKM 1.1%',
            'pph_21' => 'PPh 21',
            'pph_22' => 'PPh 22',
            'pph_23' => 'PPh 23',
            default => 'Tidak Kena Pajak',
        };
    }
}
