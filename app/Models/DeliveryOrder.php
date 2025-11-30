<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Scopes\BranchScope;

class DeliveryOrder extends Model
{

    protected $fillable = [
        'do_number',
        'reference_number',
        'do_date',
        'delivery_date',
        'expected_delivery_date',
        'status',
        'shipping_method',
        'sales_order_id',
        'invoice_id',
        'customer_id',
        'branch_id',
        'user_id',
        'shipping_address',
        'shipping_contact',
        'shipping_phone',
        'shipping_email',
        'courier_name',
        'tracking_number',
        'shipping_cost',
        'shipping_notes',
        'shipped_at',
        'delivered_at',
        'delivered_by',
        'delivery_notes',
        'recipient_signature',
        'total_items',
        'total_quantity',
        'delivered_quantity',
        'remaining_quantity',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'quality_check',
        'quality_notes',
        'quality_checked_by',
        'quality_checked_at',
        'metadata',
        'internal_notes',
    ];

    protected $casts = [
        'do_date' => 'date',
        'delivery_date' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'quality_checked_at' => 'datetime',
        'shipping_cost' => 'decimal:2',
        'total_quantity' => 'decimal:2',
        'delivered_quantity' => 'decimal:2',
        'remaining_quantity' => 'decimal:2',
        'metadata' => 'array',
    ];

    // Apply branch scope
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope());
    }

    // Relationships
    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function qualityCheckedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'quality_checked_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['cancelled', 'delivered']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('expected_delivery_date', '<', now())
                    ->whereNotIn('status', ['delivered', 'cancelled']);
    }

    public function scopeInTransit($query)
    {
        return $query->where('status', 'in_transit');
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->expected_delivery_date < now() &&
               !in_array($this->status, ['delivered', 'cancelled']);
    }

    public function getDeliveryProgressAttribute()
    {
        if ($this->total_quantity <= 0) return 0;
        return round(($this->delivered_quantity / $this->total_quantity) * 100);
    }

    // Business Logic Methods
    public function canBeShipped()
    {
        return $this->status === 'confirmed' && !$this->shipped_at;
    }

    public function canBeDelivered()
    {
        return $this->status === 'in_transit' && !$this->delivered_at;
    }

    public function ship(User $user, $trackingNumber = null)
    {
        $this->update([
            'status' => 'in_transit',
            'shipped_at' => now(),
            'tracking_number' => $trackingNumber,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['tracking_number' => $trackingNumber])
            ->log('Delivery order shipped');
    }

    public function deliver(User $user, $deliveredBy = null, $notes = null)
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
            'delivered_by' => $deliveredBy,
            'delivery_notes' => $notes,
            'delivered_quantity' => $this->total_quantity,
            'remaining_quantity' => 0,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties([
                'delivered_by' => $deliveredBy,
                'delivery_notes' => $notes
            ])
            ->log('Delivery order completed');
    }

    public function cancel(User $user, $reason = null)
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_by' => $user->id,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['reason' => $reason])
            ->log('Delivery order cancelled');
    }

    public function performQualityCheck(User $user, $status, $notes = null)
    {
        $this->update([
            'quality_check' => $status,
            'quality_notes' => $notes,
            'quality_checked_by' => $user->id,
            'quality_checked_at' => now(),
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties([
                'quality_status' => $status,
                'quality_notes' => $notes
            ])
            ->log('Quality check performed');
    }

    // Auto-generate DO number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deliveryOrder) {
            if (empty($deliveryOrder->do_number)) {
                $deliveryOrder->do_number = static::generateDoNumber();
            }
        });
    }

    public static function generateDoNumber()
    {
        $date = now()->format('Ymd');
        $lastDO = static::where('do_number', 'like', "DO{$date}%")
                       ->orderBy('do_number', 'desc')
                       ->first();

        if ($lastDO) {
            $lastNumber = intval(substr($lastDO->do_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "DO{$date}{$newNumber}";
    }
}
