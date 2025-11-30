<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Scopes\BranchScope;

class Quotation extends Model
{

    protected $fillable = [
        'quotation_number',
        'reference_number',
        'quotation_date',
        'valid_until',
        'expected_delivery_date',
        'status',
        'payment_terms',
        'shipping_method',
        'customer_id',
        'branch_id',
        'user_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'customer_notes',
        'internal_notes',
        'shipping_address',
        'shipping_contact',
        'shipping_phone',
        'terms_and_conditions',
        'warranty_info',
        'payment_terms_text',
        'sent_at',
        'viewed_at',
        'responded_at',
        'response_type',
        'response_notes',
        'responded_by',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'next_follow_up',
        'follow_up_notes',
        'follow_up_count',
        'metadata',
        'currency',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'expected_delivery_date' => 'date',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'responded_at' => 'datetime',
        'next_follow_up' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function respondedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('valid_until', '>=', now())
                    ->whereNotIn('status', ['cancelled', 'expired', 'rejected']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeExpired($query)
    {
        return $query->where('valid_until', '<', now())
                    ->whereNotIn('status', ['accepted', 'cancelled']);
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        return $this->valid_until < now() &&
               !in_array($this->status, ['accepted', 'cancelled']);
    }

    public function getDaysUntilExpiryAttribute()
    {
        if ($this->is_expired) return 0;
        return now()->diffInDays($this->valid_until);
    }

    // Business Logic Methods
    public function canBeSent()
    {
        return $this->status === 'draft';
    }

    public function canBeAccepted()
    {
        return $this->status === 'sent' && !$this->is_expired;
    }

    public function send(User $user)
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->log('Quotation sent to customer');
    }

    public function accept(User $user, $notes = null)
    {
        $this->update([
            'status' => 'accepted',
            'responded_at' => now(),
            'response_type' => 'accepted',
            'response_notes' => $notes,
            'responded_by' => $user->id,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['response_notes' => $notes])
            ->log('Quotation accepted');
    }

    public function reject(User $user, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'responded_at' => now(),
            'response_type' => 'rejected',
            'response_notes' => $notes,
            'responded_by' => $user->id,
        ]);

        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->withProperties(['response_notes' => $notes])
            ->log('Quotation rejected');
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
            ->log('Quotation cancelled');
    }

    // Auto-generate quotation number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            if (empty($quotation->quotation_number)) {
                $quotation->quotation_number = static::generateQuotationNumber();
            }
        });
    }

    public static function generateQuotationNumber()
    {
        $date = now()->format('Ymd');
        $lastQuotation = static::where('quotation_number', 'like', "QUO{$date}%")
                             ->orderBy('quotation_number', 'desc')
                             ->first();

        if ($lastQuotation) {
            $lastNumber = intval(substr($lastQuotation->quotation_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "QUO{$date}{$newNumber}";
    }
}
