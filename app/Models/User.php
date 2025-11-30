<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'branch_id',
        'user_role_id',
        'demo_mode',
        'failed_login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'demo_mode' => 'boolean',
            'locked_until' => 'datetime',
        ];
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function recurringTemplates()
    {
        return $this->hasMany(RecurringTemplate::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'user_branches')
                    ->withPivot('role_name', 'is_default', 'is_active')
                    ->withTimestamps();
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function taxSettings()
    {
        return $this->hasMany(TaxSetting::class);
    }

    /**
     * Check if the user account is currently locked
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Lock the user account for a specified duration
     *
     * @param int $minutes
     * @return void
     */
    public function lockAccount(int $minutes = 15): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'failed_login_attempts' => 0, // Reset attempts after lock
        ]);
    }

    /**
     * Unlock the user account
     *
     * @return void
     */
    public function unlockAccount(): void
    {
        $this->update([
            'locked_until' => null,
            'failed_login_attempts' => 0,
        ]);
    }

    /**
     * Increment failed login attempts
     *
     * @return void
     */
    public function incrementFailedLoginAttempts(): void
    {
        $this->increment('failed_login_attempts');
    }

    /**
     * Reset failed login attempts (on successful login)
     *
     * @return void
     */
    public function resetFailedLoginAttempts(): void
    {
        $this->update(['failed_login_attempts' => 0]);
    }

    /**
     * Check if user has exceeded maximum login attempts
     *
     * @param int $maxAttempts
     * @return bool
     */
    public function hasExceededMaxLoginAttempts(int $maxAttempts = 5): bool
    {
        return $this->failed_login_attempts >= $maxAttempts;
    }

    /**
     * Get remaining lockout time in minutes
     *
     * @return int|null
     */
    public function getRemainingLockoutTime(): ?int
    {
        if (!$this->isLocked()) {
            return null;
        }

        return now()->diffInMinutes($this->locked_until, false);
    }
}
