<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'product_id',
        'sales_order_item_id',
        'product_name',
        'product_sku',
        'product_unit',
        'product_description',
        'quantity',
        'unit_price',
        'cost_price',
        'discount_percent',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'subtotal',
        'total_amount',
        'delivered_quantity',
        'invoiced_quantity',
        'remaining_quantity',
        'notes',
        'metadata',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivered_quantity' => 'decimal:2',
        'invoiced_quantity' => 'decimal:2',
        'remaining_quantity' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Relationships
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function salesOrderItem(): BelongsTo
    {
        return $this->belongsTo(SalesOrderItem::class);
    }

    // Accessors
    public function getMarginAttribute()
    {
        if ($this->cost_price <= 0) return 0;
        return (($this->unit_price - $this->cost_price) / $this->cost_price) * 100;
    }

    // Boot method for auto-calculations
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Auto-calculate remaining quantity for future invoicing
            $item->remaining_quantity = max(0, $item->delivered_quantity - $item->invoiced_quantity);
        });

        static::saved(function ($item) {
            // Recalculate parent invoice totals
            if ($item->invoice) {
                $item->invoice->calculateTotals();
            }
        });
    }
}
