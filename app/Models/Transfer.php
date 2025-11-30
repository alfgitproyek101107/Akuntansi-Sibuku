<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transfer extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'is_demo',
        'from_account_id',
        'to_account_id',
        'amount',
        'description',
        'date',
        'fee',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'is_demo' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Global scope for branch isolation
        static::addGlobalScope('branch', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();

                // Super admin can see all branches
                if ($user->hasRole('super-admin')) {
                    return;
                }

                // Regular users only see their assigned branch or active branch
                $branchId = session('active_branch') ?? ($user->branch_id ?? null);
                if ($branchId) {
                    $builder->where('branch_id', $branchId);
                }
            }
        });

        // Global scope for demo mode isolation
        static::addGlobalScope('demo', function (Builder $builder) {
            if (auth()->check()) {
                $user = auth()->user();

                // Demo users should only see demo data
                if ($user->is_demo || session('is_demo')) {
                    $builder->where('is_demo', true);
                } else {
                    // Regular users should not see demo data
                    $builder->where('is_demo', false);
                }
            } else {
                // For non-authenticated requests, only show non-demo data
                $builder->where('is_demo', false);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function fromAccount()
    {
        return $this->belongsTo(Account::class, 'from_account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(Account::class, 'to_account_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
