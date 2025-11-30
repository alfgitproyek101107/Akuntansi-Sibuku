<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderItem extends Model
{
    protected $fillable = [
        'sales_order_id',
        'product_id',
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
        'remaining_quantity',
        'fulfillment_status',
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
        'remaining_quantity' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Relationships
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getMarginAttribute()
    {
        if ($this->cost_price <= 0) return 0;
        return (($this->unit_price - $this->cost_price) / $this->cost_price) * 100;
    }

    public function getMarginAmountAttribute()
    {
        return ($this->unit_price - $this->cost_price) * $this->quantity;
    }

    public function getIsFullyDeliveredAttribute()
    {
        return $this->delivered_quantity >= $this->quantity;
    }

    // Business Logic Methods
    public function calculateLineTotal()
    {
        $subtotal = $this->quantity * $this->unit_price;
        $discountAmount = ($subtotal * $this->discount_percent / 100) + $this->discount_amount;
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = $taxableAmount * ($this->tax_rate / 100);

        $this->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $taxableAmount + $taxAmount,
        ]);
    }

    public function updateDeliveredQuantity($deliveredQty)
    {
        $this->delivered_quantity = $deliveredQty;
        $this->remaining_quantity = max(0, $this->quantity - $deliveredQty);

        if ($this->remaining_quantity <= 0) {
            $this->fulfillment_status = 'fulfilled';
        } elseif ($this->delivered_quantity > 0) {
            $this->fulfillment_status = 'partial';
        }

        $this->save();

        // Update parent sales order totals if needed
        $this->salesOrder->calculateTotals();
    }

    // Boot method for auto-calculations
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            // Auto-calculate remaining quantity
            $item->remaining_quantity = max(0, $item->quantity - $item->delivered_quantity);

            // Auto-set fulfillment status
            if ($item->remaining_quantity <= 0) {
                $item->fulfillment_status = 'fulfilled';
            } elseif ($item->delivered_quantity > 0) {
                $item->fulfillment_status = 'partial';
            } else {
                $item->fulfillment_status = 'pending';
            }
        });

        static::saved(function ($item) {
            // Recalculate parent sales order totals
            if ($item->salesOrder) {
                $item->salesOrder->calculateTotals();
            }
        });
    }
}
