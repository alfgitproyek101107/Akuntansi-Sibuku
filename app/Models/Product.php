<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchScope;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'product_category_id',
        'name',
        'description',
        'sku',
        'tax_rule_id',
        'purchase_price',
        'sale_price',
        'tax_percentage',
        'is_tax_included',
        'tax_type',
        'stock_quantity',
        'unit',
        'is_active'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'is_tax_included' => 'boolean',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
    ];

    // Apply branch scope
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope());
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to include products with tax rules.
     */
    public function scopeWithTax($query)
    {
        return $query->whereNotNull('tax_rule_id');
    }

    /**
     * Scope a query to only include taxable products.
     */
    public function scopeTaxable($query)
    {
        return $query->where('tax_percentage', '>', 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function taxRule()
    {
        return $this->belongsTo(TaxRule::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the price attribute for backward compatibility.
     */
    public function getPriceAttribute()
    {
        return $this->sale_price;
    }

    /**
     * Get the cost_price attribute for backward compatibility.
     */
    public function getCostPriceAttribute()
    {
        return $this->purchase_price;
    }

    /**
     * Get sale price including tax.
     */
    public function getSalePriceWithTax()
    {
        if ($this->is_tax_included) {
            return $this->sale_price;
        }
        return $this->sale_price + $this->getTaxAmountForSale();
    }

    /**
     * Get purchase price including tax.
     */
    public function getPurchasePriceWithTax()
    {
        if ($this->is_tax_included) {
            return $this->purchase_price;
        }
        return $this->purchase_price + $this->getTaxAmountForPurchase();
    }

    /**
     * Calculate tax amount for a base amount.
     */
    public function calculateTaxAmount($baseAmount, $isIncluded = null)
    {
        $isIncluded = $isIncluded ?? $this->is_tax_included;
        $taxRate = $this->tax_percentage / 100;

        if ($isIncluded) {
            // Tax is included in the price
            return $baseAmount - ($baseAmount / (1 + $taxRate));
        } else {
            // Tax is added to the price
            return $baseAmount * $taxRate;
        }
    }

    /**
     * Get tax amount for sale price.
     */
    public function getTaxAmountForSale()
    {
        return $this->calculateTaxAmount($this->sale_price);
    }

    /**
     * Get tax amount for purchase price.
     */
    public function getTaxAmountForPurchase()
    {
        return $this->calculateTaxAmount($this->purchase_price);
    }

    /**
     * Get net sale price (without tax).
     */
    public function getNetSalePrice()
    {
        if ($this->is_tax_included) {
            return $this->sale_price / (1 + ($this->tax_percentage / 100));
        }
        return $this->sale_price;
    }

    /**
     * Get net purchase price (without tax).
     */
    public function getNetPurchasePrice()
    {
        if ($this->is_tax_included) {
            return $this->purchase_price / (1 + ($this->tax_percentage / 100));
        }
        return $this->purchase_price;
    }
}
