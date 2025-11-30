<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchScope;

class TaxRule extends Model
{
    protected $fillable = [
        'name',
        'type',
        'percentage',
        'code',
        'description',
        'is_active',
        'created_by',
        'branch_id'
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Apply branch scope
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope());
    }

    /**
     * Scope a query to only include active tax rules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Scope a query to filter by type (input/output).
     */
    public function scopeForType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the branch that owns the tax rule.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the user who created the tax rule.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the products that use this tax rule.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'tax_rule_id');
    }

    /**
     * Calculate tax amount based on base amount.
     */
    public function getTaxAmount($baseAmount)
    {
        return ($baseAmount * $this->percentage) / 100;
    }

    /**
     * Check if this is an input tax rule.
     */
    public function isInputTax()
    {
        return $this->type === 'input';
    }

    /**
     * Check if this is an output tax rule.
     */
    public function isOutputTax()
    {
        return $this->type === 'output';
    }

    /**
     * Get formatted percentage display.
     */
    public function getFormattedPercentage()
    {
        return number_format($this->percentage, 2) . '%';
    }

    /**
     * Create default tax rules.
     */
    public static function createDefaultRules($branchId = null, $createdBy = null)
    {
        $defaultRules = [
            [
                'name' => 'PPN 11%',
                'type' => 'output',
                'percentage' => 11.00,
                'code' => 'PPN11',
                'description' => 'Pajak Pertambahan Nilai 11%',
                'is_active' => true,
                'created_by' => $createdBy,
                'branch_id' => $branchId,
            ],
            [
                'name' => 'Non Pajak',
                'type' => 'output',
                'percentage' => 0.00,
                'code' => 'NONPAJAK',
                'description' => 'Tidak dikenakan pajak',
                'is_active' => true,
                'created_by' => $createdBy,
                'branch_id' => $branchId,
            ],
            [
                'name' => 'PPN Masukan 11%',
                'type' => 'input',
                'percentage' => 11.00,
                'code' => 'PPNMASUKAN11',
                'description' => 'Pajak Pertambahan Nilai Masukan 11%',
                'is_active' => true,
                'created_by' => $createdBy,
                'branch_id' => $branchId,
            ],
        ];

        foreach ($defaultRules as $ruleData) {
            static::create($ruleData);
        }
    }
}