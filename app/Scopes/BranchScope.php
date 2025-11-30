<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super admin can see all branches (unless they have active branch selected)
        if ($user->hasRole('super-admin') && !session('active_branch')) {
            return;
        }

        // Demo users can only see demo branch data
        if ($user->demo_mode) {
            $builder->where('branch_id', 999); // Demo branch ID
            return;
        }

        // Regular users only see their assigned branch or active branch
        $branchId = session('active_branch') ?? ($user->branch_id ?? null);
        if ($branchId) {
            $builder->where('branch_id', $branchId);
        } else {
            // Fallback: show data without branch_id for backward compatibility
            $builder->where(function ($query) {
                $query->whereNull('branch_id')
                      ->orWhere('branch_id', 0);
            });
        }
    }
}