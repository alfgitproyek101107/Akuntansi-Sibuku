<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalLine extends Model
{
    protected $fillable = [
        'journal_entry_id',
        'chart_of_account_id',
        'debit',
        'credit',
        'description',
        'line_number',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'line_number' => 'integer',
    ];

    // Relationships
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    // Helper methods
    public function isDebit(): bool
    {
        return $this->debit > 0;
    }

    public function isCredit(): bool
    {
        return $this->credit > 0;
    }

    public function getAmountAttribute(): float
    {
        return max($this->debit, $this->credit);
    }

    public function getTypeAttribute(): string
    {
        return $this->isDebit() ? 'debit' : 'credit';
    }

    public function getFormattedAmountAttribute(): string
    {
        $amount = $this->amount;
        return 'Rp' . number_format($amount, 0, ',', '.');
    }

    public function getFormattedDebitAttribute(): string
    {
        return $this->debit > 0 ? 'Rp' . number_format($this->debit, 0, ',', '.') : '-';
    }

    public function getFormattedCreditAttribute(): string
    {
        return $this->credit > 0 ? 'Rp' . number_format($this->credit, 0, ',', '.') : '-';
    }
}
