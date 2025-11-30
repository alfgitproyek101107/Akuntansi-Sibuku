<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BranchScope;

class Service extends Model
{
    protected $fillable = ['user_id', 'branch_id', 'product_category_id', 'name', 'description', 'price', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Apply branch scope
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}
