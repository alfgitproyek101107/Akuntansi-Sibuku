<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TaxSetting extends Model
{
    protected $fillable = [
        'branch_id',
        'user_id',
        'company_name',
        'npwp',
        'company_address',
        'is_pkp',
        'ppn_rate',
        'ppn_umkm_rate',
        'pph_21_rate',
        'pph_22_rate',
        'pph_23_rate',
        'coretax_api_token',
        'coretax_base_url',
        'auto_sync_enabled',
        'sync_retry_attempts',
        'include_tax_in_price',
        'auto_calculate_tax',
        'require_tax_invoice',
        'default_tax_type',
        'enable_branch_tax',
        'tax_exempt_products',
    ];

    protected $casts = [
        'is_pkp' => 'boolean',
        'ppn_rate' => 'decimal:2',
        'ppn_umkm_rate' => 'decimal:2',
        'pph_21_rate' => 'decimal:2',
        'pph_22_rate' => 'decimal:2',
        'pph_23_rate' => 'decimal:2',
        'auto_sync_enabled' => 'boolean',
        'sync_retry_attempts' => 'integer',
        'include_tax_in_price' => 'boolean',
        'auto_calculate_tax' => 'boolean',
        'require_tax_invoice' => 'boolean',
        'enable_branch_tax' => 'boolean',
        'tax_exempt_products' => 'array',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Helper methods
    public function isPKP()
    {
        return $this->is_pkp;
    }

    public function getTaxRate($taxType = null)
    {
        $taxType = $taxType ?? $this->default_tax_type;

        return match($taxType) {
            'ppn' => $this->ppn_rate,
            'ppn_umkm' => $this->ppn_umkm_rate,
            'pph_21' => $this->pph_21_rate,
            'pph_22' => $this->pph_22_rate,
            'pph_23' => $this->pph_23_rate,
            default => 0,
        };
    }

    public function isTaxExempt($productId)
    {
        if (!$this->tax_exempt_products) {
            return false;
        }

        return in_array($productId, $this->tax_exempt_products);
    }

    public function shouldAutoCalculateTax()
    {
        return $this->auto_calculate_tax && $this->enable_branch_tax;
    }

    public function requiresTaxInvoice()
    {
        return $this->require_tax_invoice && $this->is_pkp;
    }

    public function canSyncWithCoreTax()
    {
        return $this->auto_sync_enabled && !empty($this->coretax_api_token);
    }

    // Static methods for getting settings
    public static function getForBranch($branchId)
    {
        return static::where('branch_id', $branchId)->first();
    }

    public static function getForCurrentBranch()
    {
        $branchId = session('active_branch') ?? (auth()->user()->branch_id ?? null);
        return $branchId ? static::getForBranch($branchId) : null;
    }

    public static function getDefaultSettings()
    {
        return [
            'company_name' => '',
            'npwp' => null,
            'company_address' => null,
            'is_pkp' => false,
            'ppn_rate' => 11.00,
            'ppn_umkm_rate' => 1.10,
            'pph_21_rate' => 5.00,
            'pph_22_rate' => 1.50,
            'pph_23_rate' => 2.00,
            'coretax_api_token' => null,
            'coretax_base_url' => 'https://api.coretax.com',
            'auto_sync_enabled' => false,
            'sync_retry_attempts' => 3,
            'include_tax_in_price' => false,
            'auto_calculate_tax' => true,
            'require_tax_invoice' => false,
            'default_tax_type' => 'ppn',
            'enable_branch_tax' => true,
            'tax_exempt_products' => [],
        ];
    }
}
