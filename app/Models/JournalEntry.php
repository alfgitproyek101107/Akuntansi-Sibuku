<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    protected $fillable = [
        'date',
        'reference',
        'description',
        'total_debit',
        'total_credit',
        'status',
        'created_by',
        'posted_at',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    // Relationships
    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class)->orderBy('line_number');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Helper methods
    public function isBalanced(): bool
    {
        return $this->total_debit === $this->total_credit;
    }

    public function post(): bool
    {
        if (!$this->isBalanced()) {
            return false;
        }

        $this->update([
            'status' => 'posted',
            'posted_at' => now(),
        ]);

        // Update account balances
        foreach ($this->journalLines as $line) {
            $line->account->updateBalance();
        }

        return true;
    }

    public function void(): bool
    {
        if ($this->status !== 'posted') {
            return false;
        }

        // Reverse account balances
        foreach ($this->journalLines as $line) {
            $account = $line->account;
            $newBalance = $account->balance - $line->debit + $line->credit;
            $account->update(['balance' => $newBalance]);
        }

        $this->update(['status' => 'voided']);

        return true;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'posted' => '<span class="badge bg-success">Posted</span>',
            'voided' => '<span class="badge bg-danger">Voided</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}
