<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Branch extends Model
{
    protected $fillable = [
        'code',
        'name',
        'address',
        'phone',
        'email',
        'manager_name',
        'establishment_date',
        'is_active',
        'is_head_office',
        'is_demo',
        'settings',
    ];

    protected $casts = [
        'establishment_date' => 'date',
        'is_active' => 'boolean',
        'is_head_office' => 'boolean',
        'is_demo' => 'boolean',
        'settings' => 'array',
    ];

    // Relationships
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_branches')
                    ->withPivot('role_name', 'is_default', 'is_active')
                    ->withTimestamps();
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    public function recurringTemplates(): HasMany
    {
        return $this->hasMany(RecurringTemplate::class);
    }

    public function approvalWorkflows(): HasMany
    {
        return $this->hasMany(ApprovalWorkflow::class);
    }

    public function lockPeriods(): HasMany
    {
        return $this->hasMany(LockPeriod::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHeadOffice($query)
    {
        return $query->where('is_head_office', true);
    }

    // Helper methods
    public function getActiveUsers()
    {
        return $this->users()->wherePivot('is_active', true)->get();
    }

    public function getUsersByRole($role)
    {
        return $this->users()->wherePivot('role_name', $role)->wherePivot('is_active', true)->get();
    }

    public function isUserAssigned(User $user, $role = null): bool
    {
        $query = $this->users()->where('users.id', $user->id)->wherePivot('is_active', true);

        if ($role) {
            $query->wherePivot('role_name', $role);
        }

        return $query->exists();
    }
}
