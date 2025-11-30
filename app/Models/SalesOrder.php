<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Scopes\BranchScope;

class SalesOrder extends Model
{

    protected $fillable = [
        'so_number',
        'order_date',
        'delivery_date',
        'valid_until',
        'status',
        'payment_terms',
        'shipping_method',
        'customer_id',
        'branch_id',
        'user_id',
        'quotation_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'customer_notes',
        'internal_notes',
        'shipping_address',
        'shipping_contact',
        'shipping_phone',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'metadata',
        'reference_number',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'valid_until' => 'date',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Apply branch scope
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope());
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function deliveryOrders(): HasMany
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('delivery_date', '<', now())
                    ->whereNotIn('status', ['delivered', 'cancelled']);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute()
    {
        return $this->delivery_date < now() &&
               !in_array($this->status, ['delivered', 'cancelled']);
    }

    public function getProgressPercentageAttribute()
    {
        $totalItems = $this->items->count();
        if ($totalItems === 0) return 0;

        $fulfilledItems = $this->items->where('fulfillment_status', 'fulfilled')->count();
        return round(($fulfilledItems / $totalItems) * 100);
    }

    // Business Logic Methods
    public function canBeApproved()
    {
        return $this->status === 'draft' && $this->items->count() > 0;
    }

    public function canBeInvoiced()
    {
        return in_array($this->status, ['confirmed', 'processing']) &&
               $this->invoices()->sum('total_amount') < $this->total_amount;
    }

    public function canBeDelivered()
    {
        return in_array($this->status, ['confirmed', 'processing']) &&
               $this->items->where('fulfillment_status', '!=', 'fulfilled')->count() === 0;
    }

    public function approve(User $user)
    {
        $this->update([
            'status' => 'confirmed',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        // Log activity
        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['old_status' => 'draft', 'new_status' => 'confirmed'])
            ->log('Sales order approved');
    }

    public function cancel(User $user, $reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_by' => $user->id,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        // Log activity
        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['reason' => $reason])
            ->log('Sales order cancelled');
    }

    public function calculateTotals()
    {
        $subtotal = $this->items->sum('subtotal');
        $taxAmount = $this->items->sum('tax_amount');
        $discountAmount = $this->items->sum('discount_amount');

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $subtotal + $taxAmount - $discountAmount + $this->shipping_cost,
            'remaining_amount' => ($subtotal + $taxAmount - $discountAmount + $this->shipping_cost) - $this->paid_amount,
        ]);
    }

    // Auto-generate SO number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($salesOrder) {
            if (empty($salesOrder->so_number)) {
                $salesOrder->so_number = static::generateSoNumber();
            }
        });
    }

    public static function generateSoNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = static::where('so_number', 'like', "SO{$date}%")
                          ->orderBy('so_number', 'desc')
                          ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->so_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "SO{$date}{$newNumber}";
    }
}
