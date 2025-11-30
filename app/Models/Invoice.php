<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Scopes\BranchScope;

class Invoice extends Model
{

    protected $fillable = [
        'invoice_number',
        'reference_number',
        'invoice_date',
        'due_date',
        'payment_date',
        'status',
        'payment_terms',
        'customer_id',
        'branch_id',
        'user_id',
        'sales_order_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'shipping_cost',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_count',
        'last_payment_amount',
        'last_payment_date',
        'billing_address',
        'shipping_address',
        'billing_contact',
        'billing_phone',
        'billing_email',
        'notes',
        'terms_and_conditions',
        'footer_text',
        'approved_by',
        'approved_at',
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'sent_at',
        'viewed_at',
        'email_count',
        'last_email_sent_at',
        'is_recurring',
        'recurring_frequency',
        'next_invoice_date',
        'metadata',
        'currency',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'date',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'last_email_sent_at' => 'datetime',
        'last_payment_date' => 'date',
        'next_invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'last_payment_amount' => 'decimal:2',
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

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
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
        return $this->hasMany(InvoiceItem::class);
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
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['paid', 'cancelled']);
    }

    public function scopeUnpaid($query)
    {
        return $query->where('remaining_amount', '>', 0)
                    ->whereNotIn('status', ['cancelled']);
    }

    // Accessors & Mutators
    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() &&
               !in_array($this->status, ['paid', 'cancelled']) &&
               $this->remaining_amount > 0;
    }

    public function getDaysOverdueAttribute()
    {
        if (!$this->is_overdue) return 0;
        return now()->diffInDays($this->due_date);
    }

    public function getPaymentProgressAttribute()
    {
        if ($this->total_amount <= 0) return 0;
        return round(($this->paid_amount / $this->total_amount) * 100);
    }

    // Business Logic Methods
    public function canBeSent()
    {
        return $this->status === 'draft' && $this->items->count() > 0;
    }

    public function canBePaid()
    {
        return in_array($this->status, ['sent', 'viewed', 'partial']) &&
               $this->remaining_amount > 0;
    }

    public function canBeCancelled()
    {
        return !in_array($this->status, ['paid', 'cancelled']);
    }

    public function send(User $user)
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
            'email_count' => $this->email_count + 1,
            'last_email_sent_at' => now(),
        ]);

        // Log activity
        activity()
            ->performedOn($this)
            ->causedBy($user)
            ->log('Invoice sent to customer');
    }

    public function markAsViewed()
    {
        if (!$this->viewed_at) {
            $this->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);

            // Log activity
            activity()
                ->performedOn($this)
                ->log('Invoice viewed by customer');
        }
    }

    public function recordPayment($amount, $paymentDate = null, User $user = null)
    {
        $paymentDate = $paymentDate ?: now();

        $this->paid_amount += $amount;
        $this->remaining_amount = max(0, $this->total_amount - $this->paid_amount);
        $this->payment_count += 1;
        $this->last_payment_amount = $amount;
        $this->last_payment_date = $paymentDate;

        if ($this->remaining_amount <= 0) {
            $this->status = 'paid';
            $this->payment_date = $paymentDate;
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        }

        $this->save();

        // Log activity
        if ($user) {
            activity()
                ->performedOn($this)
                ->causedBy($user)
                ->withProperties([
                    'amount' => $amount,
                    'payment_date' => $paymentDate,
                    'remaining_amount' => $this->remaining_amount
                ])
                ->log('Payment recorded');
        }
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
            ->log('Invoice cancelled');
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

    // Auto-generate invoice number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = static::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $lastInvoice = static::where('invoice_number', 'like', "INV{$date}%")
                           ->orderBy('invoice_number', 'desc')
                           ->first();

        if ($lastInvoice) {
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "INV{$date}{$newNumber}";
    }
}
