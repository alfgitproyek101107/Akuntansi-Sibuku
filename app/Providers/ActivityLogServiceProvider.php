<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Branch;
use App\Observers\ActivityLogObserver;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register model observers for automatic audit logging
        $this->registerModelObservers();
    }

    /**
     * Register observers for models that need audit logging
     */
    private function registerModelObservers(): void
    {
        // Core accounting models
        User::observe(ActivityLogObserver::class);
        Account::observe(ActivityLogObserver::class);
        Category::observe(ActivityLogObserver::class);
        Transaction::observe(ActivityLogObserver::class);
        Transfer::observe(ActivityLogObserver::class);

        // Inventory models
        Product::observe(ActivityLogObserver::class);
        Customer::observe(ActivityLogObserver::class);

        // Branch management
        Branch::observe(ActivityLogObserver::class);
    }
}
