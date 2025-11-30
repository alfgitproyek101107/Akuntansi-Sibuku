<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'name',
        'email',
        'phone',
        'address',
        'type',
        'status',
        'notes'
    ];

    protected $casts = [
        'status' => 'string',
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
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
