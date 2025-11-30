<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChartOfAccount extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'category',
        'parent_id',
        'balance',
        'is_active',
        'description',
        'level',
        'normal_balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function journalLines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Helper methods
    public function getFullCodeAttribute(): string
    {
        $code = $this->code;
        $parent = $this->parent;

        while ($parent) {
            $code = $parent->code . '.' . $code;
            $parent = $parent->parent;
        }

        return $code;
    }

    public function getFullNameAttribute(): string
    {
        $name = $this->name;
        $parent = $this->parent;

        while ($parent) {
            $name = $parent->name . ' > ' . $name;
            $parent = $parent->parent;
        }

        return $name;
    }

    public function isDebitNormal(): bool
    {
        return $this->normal_balance === 'debit';
    }

    public function isCreditNormal(): bool
    {
        return $this->normal_balance === 'credit';
    }

    public function updateBalance(): void
    {
        $balance = $this->journalLines()
            ->selectRaw('SUM(debit) - SUM(credit) as balance')
            ->value('balance') ?? 0;

        $this->update(['balance' => $balance]);
    }

    public function getBalanceAttribute($value)
    {
        // If balance is not calculated, calculate it
        if ($value === null) {
            $this->updateBalance();
            return $this->fresh()->balance;
        }

        return $value;
    }
}
