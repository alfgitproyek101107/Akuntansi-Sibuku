<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StockMovement;
use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use App\Models\Branch;
use App\Observers\TransactionObserver;
use App\Observers\TransferObserver;
use App\Observers\ActivityLogObserver;
use App\Scopes\BranchScope;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        Transaction::observe(TransactionObserver::class);
        Transfer::observe(TransferObserver::class);

        // Register Activity Log Observer for audit trail
        Transaction::observe(ActivityLogObserver::class);
        Transfer::observe(ActivityLogObserver::class);
        Product::observe(ActivityLogObserver::class);
        Customer::observe(ActivityLogObserver::class);
        Account::observe(ActivityLogObserver::class);
        Category::observe(ActivityLogObserver::class);
        User::observe(ActivityLogObserver::class);
        Branch::observe(ActivityLogObserver::class);

        // Apply Branch Scope to critical models for data isolation
        Product::addGlobalScope(new BranchScope());
        Customer::addGlobalScope(new BranchScope());
        StockMovement::addGlobalScope(new BranchScope());
        Transaction::addGlobalScope(new BranchScope());
        Account::addGlobalScope(new BranchScope());
        Category::addGlobalScope(new BranchScope());
        Transfer::addGlobalScope(new BranchScope());
    }
}
