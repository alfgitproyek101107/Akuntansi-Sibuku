<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeItem extends Model
{
    protected $fillable = [
        'income_id',
        'product_id',
        'service_id',
        'quantity',
        'price',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'tax_type',
        'total_with_tax',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_with_tax' => 'decimal:2',
    ];

    /**
     * Get the income transaction that owns this item.
     */
    public function income(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'income_id');
    }

    /**
     * Get the product associated with this item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the service associated with this item.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Calculate subtotal and tax automatically when quantity, price, or tax rate changes.
     */
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->price;

            // Calculate tax amount if tax rate is set
            if ($item->tax_rate > 0) {
                $item->tax_amount = ($item->subtotal * $item->tax_rate) / 100;
                $item->total_with_tax = $item->subtotal + $item->tax_amount;
            } else {
                $item->tax_amount = 0;
                $item->total_with_tax = $item->subtotal;
            }
        });
    }
}
