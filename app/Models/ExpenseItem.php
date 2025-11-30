<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpenseItem extends Model
{
    protected $fillable = [
        'expense_id',
        'product_id',
        'service_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the expense transaction that owns this item.
     */
    public function expense(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'expense_id');
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
     * Calculate subtotal automatically when quantity or price changes.
     */
    protected static function booted()
    {
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->price;
        });
    }
}
