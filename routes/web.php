<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\RecurringTemplateController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout']);

// Demo routes
Route::get('/demo/login', [DemoController::class, 'login'])->name('demo.login');
Route::get('/demo/check', [DemoController::class, 'checkDemoMode'])->name('demo.check');

// Block registration routes for own business
Route::get('/register', function () {
    return redirect('/login')->with('error', 'Pendaftaran akun tidak tersedia. Silakan hubungi administrator untuk mendapatkan akses.');
})->name('register');

Route::post('/register', function () {
    return redirect('/login')->with('error', 'Pendaftaran akun tidak tersedia. Silakan hubungi administrator untuk mendapatkan akses.');
});

Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->withoutMiddleware([\App\Http\Middleware\BranchIsolation::class]);
    Route::get('/api/dashboard', [DashboardController::class, 'data'])->name('dashboard.data');

    // Demo routes (authenticated)
    Route::post('/demo/reset', [DemoController::class, 'reset'])->name('demo.reset');
    Route::get('/demo/stats', [DemoController::class, 'stats'])->name('demo.stats');

    // Chart of Accounts routes
    Route::resource('chart-of-accounts', \App\Http\Controllers\ChartOfAccountsController::class);
    Route::post('chart-of-accounts/{chartOfAccount}/toggle-active', [\App\Http\Controllers\ChartOfAccountsController::class, 'toggleActive'])->name('chart-of-accounts.toggle-active');

    Route::resource('accounts', AccountController::class);
    Route::get('accounts/{account}/ledger', [AccountController::class, 'ledger'])->name('accounts.ledger');
    Route::post('accounts/{account}/export', [AccountController::class, 'export'])->middleware('throttle:10,1')->name('accounts.export');
    Route::get('accounts/{account}/reconcile', [AccountController::class, 'reconcile'])->name('accounts.reconcile');
    Route::post('accounts/{account}/toggle-reconcile', [AccountController::class, 'toggleReconcile'])->name('accounts.toggle-reconcile');
    Route::resource('categories', CategoryController::class);
    Route::resource('incomes', IncomeController::class)->middleware('throttle:30,1');
    Route::resource('expenses', ExpenseController::class)->middleware('throttle:30,1');
    Route::resource('transfers', TransferController::class)->middleware('throttle:60,1');
    Route::resource('recurring-templates', RecurringTemplateController::class);
    Route::post('recurring-templates/{id}/execute', [RecurringTemplateController::class, 'execute'])->name('recurring-templates.execute');
    Route::post('recurring-templates/{id}/toggle', [RecurringTemplateController::class, 'toggle'])->name('recurring-templates.toggle');

    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('services', ServiceController::class);
    // DEBUG: Temporary route to show all services
    Route::get('services-debug', [ServiceController::class, 'debugIndex'])->name('services.debug');
    Route::resource('customers', CustomerController::class);
    Route::resource('stock-movements', StockMovementController::class);
    Route::resource('branches', BranchController::class);
    Route::post('branches/{branchId}/switch', [BranchController::class, 'switch'])->name('branches.switch');
    Route::get('branches/available', [BranchController::class, 'available'])->name('branches.available');
    Route::get('branch/select', [BranchController::class, 'select'])->name('branch.select');
    Route::post('branch/set', [BranchController::class, 'setBranch'])->name('branch.set');
    // Tax Management Routes
    Route::get('tax', [TaxController::class, 'index'])->name('tax.index');
    Route::get('tax/settings', [TaxController::class, 'settings'])->name('tax.settings');
    Route::put('tax/settings', [TaxController::class, 'updateSettings'])->name('tax.settings.update');
    Route::get('tax/invoices', [TaxController::class, 'invoices'])->name('tax.invoices');
    Route::get('tax/invoices/{taxInvoice}', [TaxController::class, 'showInvoice'])->name('tax.invoices.show');
    Route::post('tax/invoices/{taxInvoice}/send', [TaxController::class, 'sendToCoreTax'])->name('tax.invoices.send');
    Route::post('tax/invoices/{taxInvoice}/check-status', [TaxController::class, 'checkInvoiceStatus'])->name('tax.invoices.check-status');
    Route::get('tax/invoices/{taxInvoice}/download', [TaxController::class, 'downloadInvoice'])->name('tax.invoices.download');
    Route::get('tax/logs', [TaxController::class, 'logs'])->name('tax.logs');
    Route::post('tax/validate-npwp', [TaxController::class, 'validateNpwp'])->name('tax.validate-npwp');
    Route::resource('users', UserController::class);
    Route::get('profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('users.updateProfile');
    Route::put('profile/password', [UserController::class, 'changePassword'])->name('users.changePassword');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/weekly', [ReportController::class, 'weekly'])->name('reports.weekly');
    Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit-loss');
    Route::get('/reports/cash-flow', [ReportController::class, 'cashFlow'])->name('reports.cash-flow');
    Route::get('/reports/accounts', [ReportController::class, 'accounts'])->name('reports.accounts');
    Route::get('/reports/transfers', [ReportController::class, 'transfers'])->name('reports.transfers');
    Route::get('/reports/reconciliation', [ReportController::class, 'reconciliation'])->name('reports.reconciliation');

    // Sales Reports
    Route::get('/reports/total-sales', [ReportController::class, 'totalSales'])->name('reports.total-sales');
    Route::get('/reports/top-products', [ReportController::class, 'topProducts'])->name('reports.top-products');
    Route::get('/reports/sales-by-customer', [ReportController::class, 'salesByCustomer'])->name('reports.sales-by-customer');

    // Inventory Reports
    Route::get('/reports/stock-levels', [ReportController::class, 'stockLevels'])->name('reports.stock-levels');
    Route::get('/reports/stock-movements', [ReportController::class, 'stockMovements'])->name('reports.stock-movements');
    Route::get('/reports/inventory-value', [ReportController::class, 'inventoryValue'])->name('reports.inventory-value');

    // Financial Reports
    Route::get('/reports/balance-sheet', [ReportController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('/reports/trial-balance', [ReportController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/reports/income-statement', [ReportController::class, 'incomeStatement'])->name('reports.income-statement');

    // Activity Logs (Admin only)
    Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
    Route::get('activity-logs/summary', [ActivityLogController::class, 'summary'])->name('activity-logs.summary');
    Route::get('activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::post('activity-logs/clean', [ActivityLogController::class, 'clean'])->name('activity-logs.clean');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/branches', [SettingController::class, 'branches'])->name('settings.branches');
    Route::put('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::put('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.updatePassword');

    // General Settings
    Route::put('/settings/general', [SettingController::class, 'updateGeneralSettings'])->name('settings.updateGeneral');

    // System Settings
    Route::put('/settings/system', [SettingController::class, 'updateSystemSettings'])->name('settings.updateSystem');
    Route::post('/settings/system/clear-cache', [SettingController::class, 'clearCache'])->name('settings.clearCache');
    Route::post('/settings/system/optimize', [SettingController::class, 'optimizeApplication'])->name('settings.optimize');
    Route::post('/settings/system/backup', [SettingController::class, 'backupDatabase'])->name('settings.backup');
    Route::get('/settings/system/logs', [SettingController::class, 'getLogs'])->name('settings.getLogs');
    Route::post('/settings/system/clear-logs', [SettingController::class, 'clearLogs'])->name('settings.clearLogs');

    // Notification Settings
    Route::put('/settings/notifications', [SettingController::class, 'updateNotificationSettings'])->name('settings.updateNotifications');

    // Transaction Settings
    Route::put('/settings/transactions', [SettingController::class, 'updateTransactionSettings'])->name('settings.updateTransactions');

    // UI Settings
    Route::put('/settings/ui', [SettingController::class, 'updateUISettings'])->name('settings.updateUI');

    // WhatsApp Management (Unified Controller)
    Route::prefix('settings/whatsapp')->group(function () {
        Route::get('/', [\App\Http\Controllers\WhatsAppController::class, 'settings'])->name('settings.whatsapp.index');
        Route::put('/', [\App\Http\Controllers\WhatsAppController::class, 'updateSettings'])->name('settings.whatsapp.update');
        Route::post('/test-connection', [\App\Http\Controllers\WhatsAppController::class, 'testConnection'])->name('settings.whatsapp.test-connection');
        Route::post('/send-manual', [\App\Http\Controllers\WhatsAppController::class, 'sendManualReport'])->name('settings.whatsapp.send-manual');
        Route::get('/logs', [\App\Http\Controllers\WhatsAppController::class, 'getLogs'])->name('settings.whatsapp.logs');
        Route::get('/logs/{log}', [\App\Http\Controllers\WhatsAppController::class, 'getLogDetail'])->name('settings.whatsapp.logs.detail');
        Route::post('/logs-table', [\App\Http\Controllers\WhatsAppController::class, 'getLogsTable'])->name('settings.whatsapp.logs-table');
        Route::get('/branches', [\App\Http\Controllers\WhatsAppController::class, 'getBranches'])->name('settings.whatsapp.branches');
    });

    // WhatsApp Reports Monitoring
    Route::get('/reports/whatsapp', [\App\Http\Controllers\WhatsAppController::class, 'reports'])->name('reports.whatsapp.index');

    // Role Management
    Route::post('/settings/roles', [SettingController::class, 'createRole'])->name('settings.createRole');
    Route::put('/settings/roles/{role}', [SettingController::class, 'updateRole'])->name('settings.updateRole');
    Route::delete('/settings/roles/{role}', [SettingController::class, 'deleteRole'])->name('settings.deleteRole');
    Route::post('/settings/users/{user}/assign-role', [SettingController::class, 'assignRole'])->name('settings.assignRole');
});
