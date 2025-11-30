# 🚀 **ENTERPRISE ACCOUNTING SYSTEM - COMPLETE IMPLEMENTATION PROMPT**

## 🎯 **OVERVIEW**

This prompt is designed to transform the existing Laravel accounting system into a **FULL ENTERPRISE-GRADE ACCOUNTING SOLUTION** by implementing all missing critical features identified in the gap analysis.

---

## 📋 **CURRENT SYSTEM STATUS**

The existing system already has:
- ✅ Double Entry Accounting
- ✅ Chart of Accounts (hierarchical)
- ✅ Journal Entries & Lines
- ✅ Stock Management
- ✅ Trial Balance
- ✅ Multi-branch support
- ✅ Recurring Templates
- ✅ Comprehensive Reporting
- ✅ Product + Transaction Integration

**BUT MISSING**: 18 critical enterprise features

---

## 🎯 **MISSING FEATURES TO IMPLEMENT**

### **1. ACCOUNTS RECEIVABLE (A/R) - INVOICE SYSTEM**
### **2. ACCOUNTS PAYABLE (A/P) - BILL SYSTEM**
### **3. COST OF GOODS SOLD (COGS) - HPP CALCULATION**
### **4. BANK RECONCILIATION**
### **5. BUDGETING & VARIANCE REPORTING**
### **6. ADVANCED ROLE & PERMISSION SYSTEM**
### **7. APPROVAL WORKFLOW**
### **8. WHATSAPP INTEGRATION**
### **9. OCR RECEIPT SCANNING**
### **10. CASH FLOW FORECASTING**
### **11. AGING REPORTS**
### **12. MULTI-COMPANY CONSOLIDATION**
### **13. ADVANCED ANALYTICS DASHBOARD**
### **14. PROFESSIONAL PDF/EXCEL EXPORT**
### **15. DATA IMPORT SYSTEM**
### **16. AUDIT TRAIL ENHANCEMENT**
### **17. AUTOMATED RECURRING TRANSACTIONS**
### **18. API INTEGRATION FRAMEWORK**

---

## 🏗️ **DETAILED IMPLEMENTATION REQUIREMENTS**

### **MODULE 1: ACCOUNTS RECEIVABLE (INVOICE SYSTEM)**

#### **Database Tables**
```sql
-- Invoices Table
CREATE TABLE invoices (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    customer_id BIGINT NOT NULL,
    branch_id BIGINT NULL,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    reference_number VARCHAR(100) NULL,
    date DATE NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('draft', 'sent', 'partial', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    currency VARCHAR(3) DEFAULT 'IDR',
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
    tax_rate DECIMAL(5,2) DEFAULT 0,
    tax_amount DECIMAL(15,2) DEFAULT 0,
    discount_rate DECIMAL(5,2) DEFAULT 0,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    total DECIMAL(15,2) NOT NULL DEFAULT 0,
    notes TEXT NULL,
    terms TEXT NULL,
    created_by BIGINT NOT NULL,
    approved_by BIGINT NULL,
    approved_at TIMESTAMP NULL,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Invoice Items Table
CREATE TABLE invoice_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    invoice_id BIGINT NOT NULL,
    product_id BIGINT NULL,
    description TEXT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    unit_price DECIMAL(15,2) NOT NULL,
    discount_rate DECIMAL(5,2) DEFAULT 0,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    tax_rate DECIMAL(5,2) DEFAULT 0,
    tax_amount DECIMAL(15,2) DEFAULT 0,
    line_total DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- Invoice Payments Table
CREATE TABLE invoice_payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    invoice_id BIGINT NOT NULL,
    account_id BIGINT NOT NULL,
    payment_date DATE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'check', 'credit_card', 'other') DEFAULT 'bank_transfer',
    reference_number VARCHAR(100) NULL,
    notes TEXT NULL,
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

#### **Models**
```php
// Invoice Model
class Invoice extends Model {
    protected $fillable = [...];
    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function payments() { return $this->hasMany(InvoicePayment::class); }
    public function journalEntries() { return $this->hasMany(JournalEntry::class, 'reference', 'invoice_number'); }

    // Business Logic Methods
    public function getPaidAmount() { return $this->payments->sum('amount'); }
    public function getRemainingAmount() { return $this->total - $this->getPaidAmount(); }
    public function isOverdue() { return $this->due_date < now() && $this->getRemainingAmount() > 0; }
    public function getAgingDays() { return $this->due_date->diffInDays(now()); }
    public function getStatusBadge() { /* Return appropriate status badge */ }
}
```

#### **Controller Methods**
```php
class InvoiceController extends Controller {
    public function index() { /* List with filters & search */ }
    public function create() { /* Create invoice form */ }
    public function store(Request $request) { /* Store with journal entries */ }
    public function show(Invoice $invoice) { /* Detail with payments */ }
    public function edit(Invoice $invoice) { /* Edit draft invoices */ }
    public function update(Request $request, Invoice $invoice) { /* Update with validation */ }
    public function destroy(Invoice $invoice) { /* Delete with journal reversal */ }
    public function send(Invoice $invoice) { /* Mark as sent */ }
    public function recordPayment(Request $request, Invoice $invoice) { /* Record payment */ }
    public function generatePdf(Invoice $invoice) { /* Professional PDF */ }
    public function email(Invoice $invoice) { /* Send via email */ }
}
```

#### **Business Logic - Journal Entries**
```php
// When creating invoice
public function createInvoiceJournal(Invoice $invoice) {
    return JournalEntry::create([
        'date' => $invoice->date,
        'reference' => 'INV-' . $invoice->invoice_number,
        'description' => 'Invoice to ' . $invoice->customer->name,
        'status' => 'posted',
        'lines' => [
            // Debit: Accounts Receivable
            ['chart_of_account_id' => $this->getARAccountId(), 'debit' => $invoice->total],
            // Credit: Revenue
            ['chart_of_account_id' => $this->getRevenueAccountId(), 'credit' => $invoice->total]
        ]
    ]);
}

// When recording payment
public function createPaymentJournal(InvoicePayment $payment) {
    return JournalEntry::create([
        'date' => $payment->payment_date,
        'reference' => 'PAY-' . $payment->invoice->invoice_number,
        'description' => 'Payment received from ' . $payment->invoice->customer->name,
        'status' => 'posted',
        'lines' => [
            // Debit: Cash/Bank
            ['chart_of_account_id' => $payment->account_id, 'debit' => $payment->amount],
            // Credit: Accounts Receivable
            ['chart_of_account_id' => $this->getARAccountId(), 'credit' => $payment->amount]
        ]
    ]);
}
```

---

### **MODULE 2: ACCOUNTS PAYABLE (BILL SYSTEM)**

#### **Database Tables**
```sql
-- Bills Table (Similar to invoices but for payables)
CREATE TABLE bills (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    vendor_name VARCHAR(255) NOT NULL,
    bill_number VARCHAR(50) UNIQUE NOT NULL,
    date DATE NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('draft', 'received', 'partial', 'paid', 'overdue') DEFAULT 'draft',
    currency VARCHAR(3) DEFAULT 'IDR',
    subtotal DECIMAL(15,2) NOT NULL DEFAULT 0,
    tax_amount DECIMAL(15,2) DEFAULT 0,
    total DECIMAL(15,2) NOT NULL DEFAULT 0,
    notes TEXT NULL,
    created_by BIGINT NOT NULL,
    approved_by BIGINT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bill Items Table
CREATE TABLE bill_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    bill_id BIGINT NOT NULL,
    description TEXT NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    unit_price DECIMAL(15,2) NOT NULL,
    line_total DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE
);

-- Bill Payments Table
CREATE TABLE bill_payments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    bill_id BIGINT NOT NULL,
    account_id BIGINT NOT NULL,
    payment_date DATE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'check', 'credit_card', 'other') DEFAULT 'bank_transfer',
    reference_number VARCHAR(100) NULL,
    notes TEXT NULL,
    created_by BIGINT NOT NULL,
    FOREIGN KEY (bill_id) REFERENCES bills(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

#### **Journal Logic for Bills**
```php
// When creating bill
public function createBillJournal(Bill $bill) {
    return JournalEntry::create([
        'date' => $bill->date,
        'reference' => 'BILL-' . $bill->bill_number,
        'description' => 'Bill from ' . $bill->vendor_name,
        'status' => 'posted',
        'lines' => [
            // Debit: Expense
            ['chart_of_account_id' => $this->getExpenseAccountId(), 'debit' => $bill->total],
            // Credit: Accounts Payable
            ['chart_of_account_id' => $this->getAPAccountId(), 'credit' => $bill->total]
        ]
    ]);
}

// When paying bill
public function createBillPaymentJournal(BillPayment $payment) {
    return JournalEntry::create([
        'date' => $payment->payment_date,
        'reference' => 'PAY-' . $payment->bill->bill_number,
        'description' => 'Payment to ' . $payment->bill->vendor_name,
        'status' => 'posted',
        'lines' => [
            // Debit: Accounts Payable
            ['chart_of_account_id' => $this->getAPAccountId(), 'debit' => $payment->amount],
            // Credit: Cash/Bank
            ['chart_of_account_id' => $payment->account_id, 'credit' => $payment->amount]
        ]
    ]);
}
```

---

### **MODULE 3: COST OF GOODS SOLD (COGS) - HPP CALCULATION**

#### **Enhanced Database Tables**
```sql
-- Inventory Layers (for FIFO tracking)
CREATE TABLE inventory_layers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    product_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    quantity_received DECIMAL(10,2) NOT NULL,
    quantity_remaining DECIMAL(10,2) NOT NULL,
    unit_cost DECIMAL(15,2) NOT NULL,
    total_cost DECIMAL(15,2) NOT NULL,
    received_date DATE NOT NULL,
    reference VARCHAR(100) NULL, -- Purchase invoice/bill reference
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- COGS Settings
CREATE TABLE cogs_settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    method ENUM('fifo', 'weighted_average', 'lifo') DEFAULT 'fifo',
    auto_calculate BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_cogs (user_id)
);
```

#### **COGS Calculation Engine**
```php
class COGSEngine {
    public function calculateCOGS(Product $product, $quantitySold, $saleDate) {
        $method = $this->getCOGSMethod($product->user_id);

        switch ($method) {
            case 'fifo':
                return $this->calculateFIFOCOGS($product, $quantitySold, $saleDate);
            case 'weighted_average':
                return $this->calculateWeightedAverageCOGS($product, $quantitySold);
            case 'lifo':
                return $this->calculateLIFOCOGS($product, $quantitySold, $saleDate);
        }
    }

    private function calculateFIFOCOGS(Product $product, $quantitySold, $saleDate) {
        $layers = InventoryLayer::where('product_id', $product->id)
            ->where('quantity_remaining', '>', 0)
            ->orderBy('received_date', 'asc')
            ->get();

        $totalCOGS = 0;
        $remainingQuantity = $quantitySold;

        foreach ($layers as $layer) {
            if ($remainingQuantity <= 0) break;

            $quantityFromLayer = min($remainingQuantity, $layer->quantity_remaining);
            $cogsFromLayer = $quantityFromLayer * $layer->unit_cost;

            $totalCOGS += $cogsFromLayer;
            $remainingQuantity -= $quantityFromLayer;

            // Reduce remaining quantity
            $layer->decrement('quantity_remaining', $quantityFromLayer);
        }

        return $totalCOGS;
    }
}
```

#### **Journal Entries for COGS**
```php
public function createCOGSJournal(Transaction $saleTransaction, $cogsAmount) {
    return JournalEntry::create([
        'date' => $saleTransaction->date,
        'reference' => 'COGS-' . $saleTransaction->id,
        'description' => 'Cost of Goods Sold for sale transaction',
        'status' => 'posted',
        'lines' => [
            // Debit: COGS
            ['chart_of_account_id' => $this->getCOGSAccountId(), 'debit' => $cogsAmount],
            // Credit: Inventory
            ['chart_of_account_id' => $this->getInventoryAccountId(), 'credit' => $cogsAmount]
        ]
    ]);
}
```

---

### **MODULE 4: BANK RECONCILIATION**

#### **Database Tables**
```sql
-- Bank Statements Table
CREATE TABLE bank_statements (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    account_id BIGINT NOT NULL,
    statement_date DATE NOT NULL,
    description TEXT NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    type ENUM('debit', 'credit') NOT NULL,
    reference_number VARCHAR(100) NULL,
    matched_transaction_id BIGINT NULL,
    status ENUM('unmatched', 'matched', 'cleared') DEFAULT 'unmatched',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE,
    FOREIGN KEY (matched_transaction_id) REFERENCES transactions(id) ON DELETE SET NULL
);

-- Reconciliation Sessions
CREATE TABLE reconciliation_sessions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    account_id BIGINT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    opening_balance DECIMAL(15,2) NOT NULL,
    closing_balance DECIMAL(15,2) NOT NULL,
    status ENUM('in_progress', 'completed', 'cancelled') DEFAULT 'in_progress',
    notes TEXT NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE CASCADE
);
```

#### **Reconciliation Logic**
```php
class BankReconciliationService {
    public function uploadBankStatement(Request $request) {
        $file = $request->file('statement');
        $accountId = $request->account_id;

        // Parse CSV/Excel file
        $statements = $this->parseBankStatement($file);

        foreach ($statements as $statement) {
            BankStatement::create([
                'user_id' => auth()->id(),
                'account_id' => $accountId,
                'statement_date' => $statement['date'],
                'description' => $statement['description'],
                'amount' => $statement['amount'],
                'type' => $statement['type'],
                'reference_number' => $statement['reference'],
            ]);
        }

        // Auto-match transactions
        $this->autoMatchTransactions($accountId);

        return response()->json(['message' => 'Bank statement uploaded successfully']);
    }

    private function autoMatchTransactions($accountId) {
        $unmatchedStatements = BankStatement::where('account_id', $accountId)
            ->where('status', 'unmatched')
            ->get();

        foreach ($unmatchedStatements as $statement) {
            // Find matching transaction by amount and date proximity
            $matchingTransaction = Transaction::where('account_id', $accountId)
                ->where('amount', abs($statement->amount))
                ->whereBetween('date', [
                    $statement->statement_date->copy()->subDays(3),
                    $statement->statement_date->copy()->addDays(3)
                ])
                ->whereDoesntHave('bankStatement')
                ->first();

            if ($matchingTransaction) {
                $statement->update([
                    'matched_transaction_id' => $matchingTransaction->id,
                    'status' => 'matched'
                ]);
            }
        }
    }

    public function reconcile(Account $account, array $matchedIds) {
        DB::transaction(function () use ($account, $matchedIds) {
            // Mark matched statements as cleared
            BankStatement::whereIn('id', $matchedIds)
                ->update(['status' => 'cleared']);

            // Update account reconciliation status
            $account->update(['last_reconciled_at' => now()]);
        });
    }
}
```

---

### **MODULE 5: BUDGETING & VARIANCE REPORTING**

#### **Database Tables**
```sql
-- Budgets Table
CREATE TABLE budgets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    branch_id BIGINT NULL,
    category_id BIGINT NULL,
    account_id BIGINT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    period ENUM('monthly', 'quarterly', 'yearly') DEFAULT 'monthly',
    year YEAR NOT NULL,
    month TINYINT NULL, -- 1-12 for monthly budgets
    quarter TINYINT NULL, -- 1-4 for quarterly budgets
    budgeted_amount DECIMAL(15,2) NOT NULL,
    actual_amount DECIMAL(15,2) DEFAULT 0,
    variance_amount DECIMAL(15,2) DEFAULT 0,
    variance_percentage DECIMAL(5,2) DEFAULT 0,
    status ENUM('draft', 'active', 'completed', 'cancelled') DEFAULT 'draft',
    created_by BIGINT NOT NULL,
    approved_by BIGINT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (account_id) REFERENCES accounts(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id)
);

-- Budget Items (for detailed budgeting)
CREATE TABLE budget_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    budget_id BIGINT NOT NULL,
    description VARCHAR(255) NOT NULL,
    budgeted_amount DECIMAL(15,2) NOT NULL,
    actual_amount DECIMAL(15,2) DEFAULT 0,
    category VARCHAR(100) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (budget_id) REFERENCES budgets(id) ON DELETE CASCADE
);
```

#### **Budget Engine**
```php
class BudgetService {
    public function calculateBudgetVariance(Budget $budget) {
        // Calculate actual amount based on transactions
        $actualAmount = $this->calculateActualAmount($budget);

        $varianceAmount = $budget->budgeted_amount - $actualAmount;
        $variancePercentage = $budget->budgeted_amount > 0
            ? ($varianceAmount / $budget->budgeted_amount) * 100
            : 0;

        $budget->update([
            'actual_amount' => $actualAmount,
            'variance_amount' => $varianceAmount,
            'variance_percentage' => $variancePercentage,
        ]);

        return $budget;
    }

    private function calculateActualAmount(Budget $budget) {
        $query = Transaction::where('user_id', $budget->user_id);

        // Apply filters based on budget scope
        if ($budget->category_id) {
            $query->where('category_id', $budget->category_id);
        }

        if ($budget->account_id) {
            $query->where('account_id', $budget->account_id);
        }

        if ($budget->branch_id) {
            $query->whereHas('user', function ($q) use ($budget) {
                $q->where('branch_id', $budget->branch_id);
            });
        }

        // Apply date filters based on period
        switch ($budget->period) {
            case 'monthly':
                $query->whereYear('date', $budget->year)
                      ->whereMonth('date', $budget->month);
                break;
            case 'quarterly':
                $startMonth = ($budget->quarter - 1) * 3 + 1;
                $endMonth = $budget->quarter * 3;
                $query->whereYear('date', $budget->year)
                      ->whereMonth('date', '>=', $startMonth)
                      ->whereMonth('date', '<=', $endMonth);
                break;
            case 'yearly':
                $query->whereYear('date', $budget->year);
                break;
        }

        return $query->sum('amount');
    }

    public function generateVarianceReport($userId, $year, $month = null) {
        $budgets = Budget::where('user_id', $userId)
            ->where('year', $year)
            ->when($month, fn($q) => $q->where('month', $month))
            ->get();

        $report = [];
        foreach ($budgets as $budget) {
            $this->calculateBudgetVariance($budget);
            $report[] = [
                'budget_name' => $budget->name,
                'budgeted' => $budget->budgeted_amount,
                'actual' => $budget->actual_amount,
                'variance' => $budget->variance_amount,
                'variance_percent' => $budget->variance_percentage,
                'status' => $this->getVarianceStatus($budget->variance_percentage),
            ];
        }

        return $report;
    }

    private function getVarianceStatus($percentage) {
        if ($percentage > 10) return 'over_budget';
        if ($percentage < -10) return 'under_budget';
        return 'on_track';
    }
}
```

---

### **MODULE 6: ADVANCED ROLE & PERMISSION SYSTEM**

#### **Install Spatie Permission Package**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

#### **Database Tables** (Auto-generated by package)
- roles
- permissions
- model_has_permissions
- model_has_roles
- role_has_permissions

#### **Permission Setup**
```php
class PermissionSeeder extends Seeder {
    public function run() {
        // Create permissions
        $permissions = [
            // Dashboard
            'view dashboard',

            // Accounts
            'view accounts', 'create accounts', 'edit accounts', 'delete accounts',

            // Transactions
            'view transactions', 'create transactions', 'edit transactions', 'delete transactions',

            // Invoices
            'view invoices', 'create invoices', 'edit invoices', 'delete invoices', 'send invoices',

            // Bills
            'view bills', 'create bills', 'edit bills', 'delete bills', 'pay bills',

            // Reports
            'view reports', 'export reports',

            // Users
            'view users', 'create users', 'edit users', 'delete users',

            // Settings
            'manage settings',

            // Approvals
            'approve transactions', 'approve invoices', 'approve budgets',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::create(['name' => 'super-admin']);
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $staff = Role::create(['name' => 'staff']);
        $viewer = Role::create(['name' => 'viewer']);

        // Assign permissions to roles
        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'view dashboard', 'manage settings',
            'view accounts', 'create accounts', 'edit accounts',
            'view transactions', 'create transactions', 'edit transactions',
            'view invoices', 'create invoices', 'edit invoices', 'send invoices',
            'view bills', 'create bills', 'edit bills', 'pay bills',
            'view reports', 'export reports',
            'view users', 'create users', 'edit users',
            'approve transactions', 'approve invoices', 'approve budgets',
        ]);

        $manager->givePermissionTo([
            'view dashboard',
            'view accounts', 'create accounts', 'edit accounts',
            'view transactions', 'create transactions', 'edit transactions',
            'view invoices', 'create invoices', 'edit invoices', 'send invoices',
            'view bills', 'create bills', 'edit bills', 'pay bills',
            'view reports', 'export reports',
            'approve transactions', 'approve invoices',
        ]);

        $staff->givePermissionTo([
            'view dashboard',
            'view accounts', 'create accounts',
            'view transactions', 'create transactions',
            'view invoices', 'create invoices',
            'view bills', 'create bills',
            'view reports',
        ]);

        $viewer->givePermissionTo([
            'view dashboard',
            'view accounts', 'view transactions', 'view invoices', 'view bills', 'view reports',
        ]);
    }
}
```

#### **Middleware for Permission Checks**
```php
class CheckPermission {
    public function handle($request, Closure $next, $permission) {
        if (!auth()->user()->can($permission)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}

// Register middleware in Kernel.php
protected $routeMiddleware = [
    'permission' => \App\Http\Middleware\CheckPermission::class,
];
```

#### **Controller with Permissions**
```php
class InvoiceController extends Controller {
    public function __construct() {
        $this->middleware('permission:view invoices')->only('index', 'show');
        $this->middleware('permission:create invoices')->only('create', 'store');
        $this->middleware('permission:edit invoices')->only('edit', 'update');
        $this->middleware('permission:delete invoices')->only('destroy');
        $this->middleware('permission:send invoices')->only('send');
    }

    // Controller methods...
}
```

---

### **MODULE 7: APPROVAL WORKFLOW**

#### **Database Tables**
```sql
-- Approval Workflows
CREATE TABLE approval_workflows (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    module ENUM('transaction', 'invoice', 'bill', 'budget', 'expense') NOT NULL,
    user_id BIGINT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Approval Workflow Steps
CREATE TABLE approval_workflow_steps (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    workflow_id BIGINT NOT NULL,
    step_order INT NOT NULL,
    approver_role VARCHAR(100) NOT NULL, -- Role name or user ID
    approver_type ENUM('role', 'user') DEFAULT 'role',
    min_amount DECIMAL(15,2) NULL, -- Minimum amount for this step
    max_amount DECIMAL(15,2) NULL, -- Maximum amount for this step
    conditions JSON NULL, -- Additional conditions
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (workflow_id) REFERENCES approval_workflows(id) ON DELETE CASCADE
);

-- Approvals Table
CREATE TABLE approvals (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    approvable_type VARCHAR(255) NOT NULL, -- Transaction, Invoice, etc.
    approvable_id BIGINT NOT NULL,
    workflow_id BIGINT NOT NULL,
    current_step_id BIGINT NULL,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    requested_by BIGINT NOT NULL,
    approved_by BIGINT NULL,
    approved_at TIMESTAMP NULL,
    rejected_by BIGINT NULL,
    rejected_at TIMESTAMP NULL,
    rejection_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (workflow_id) REFERENCES approval_workflows(id),
    FOREIGN KEY (current_step_id) REFERENCES approval_workflow_steps(id),
    FOREIGN KEY (requested_by) REFERENCES users(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (rejected_by) REFERENCES users(id)
);
```

#### **Approval Service**
```php
class ApprovalService {
    public function submitForApproval($approvable) {
        $workflow = $this->getApplicableWorkflow($approvable);

        if (!$workflow) {
            // Auto-approve if no workflow
            $approvable->update(['status' => 'approved']);
            return;
        }

        $approval = Approval::create([
            'approvable_type' => get_class($approvable),
            'approvable_id' => $approvable->id,
            'workflow_id' => $workflow->id,
            'requested_by' => auth()->id(),
            'status' => 'pending',
        ]);

        // Set initial step
        $firstStep = $workflow->steps()->orderBy('step_order')->first();
        $approval->update(['current_step_id' => $firstStep->id]);

        // Notify first approver
        $this->notifyApprover($firstStep, $approval);

        // Update approvable status
        $approvable->update(['status' => 'pending_approval']);
    }

    public function approve(Approval $approval, $approverId, $comments = null) {
        DB::transaction(function () use ($approval, $approverId, $comments) {
            $approval->update([
                'approved_by' => $approverId,
                'approved_at' => now(),
                'status' => 'approved',
            ]);

            // Check if there are more steps
            $nextStep = $this->getNextStep($approval);

            if ($nextStep) {
                // Move to next step
                $approval->update(['current_step_id' => $nextStep->id]);
                $this->notifyApprover($nextStep, $approval);
            } else {
                // All steps approved - finalize
                $this->finalizeApproval($approval);
            }
        });
    }

    public function reject(Approval $approval, $rejectorId, $reason) {
        $approval->update([
            'rejected_by' => $rejectorId,
            'rejected_at' => now(),
            'status' => 'rejected',
            'rejection_reason' => $reason,
        ]);

        // Update approvable status
        $approvable = $approval->approvable;
        $approvable->update(['status' => 'rejected']);

        // Notify requester
        $this->notifyRejection($approval);
    }

    private function finalizeApproval(Approval $approval) {
        $approvable = $approval->approvable;

        // Update status based on type
        switch ($approvable->getTable()) {
            case 'transactions':
                $approvable->update(['status' => 'approved']);
                // Create journal entries if needed
                break;
            case 'invoices':
                $approvable->update(['status' => 'approved']);
                // Create invoice journal entries
                break;
            // Handle other types...
        }

        $this->notifyApprovalComplete($approval);
    }

    private function getApplicableWorkflow($approvable) {
        return ApprovalWorkflow::where('module', $this->getModuleFromApprovable($approvable))
            ->where('user_id', $approvable->user_id)
            ->where('is_active', true)
            ->first();
    }

    private function getModuleFromApprovable($approvable) {
        $table = $approvable->getTable();
        return match($table) {
            'transactions' => 'transaction',
            'invoices' => 'invoice',
            'bills' => 'bill',
            'budgets' => 'budget',
            default => 'expense'
        };
    }
}
```

---

### **MODULE 8: WHATSAPP INTEGRATION**

#### **WhatsApp Service**
```php
class WhatsAppService {
    private $apiKey;
    private $sender;

    public function __construct() {
        $this->apiKey = config('services.whatsapp.api_key');
        $this->sender = config('services.whatsapp.sender');
    }

    public function sendMessage($phone, $message) {
        $url = 'https://api.fonnte.com/send';

        $data = [
            'target' => $this->formatPhoneNumber($phone),
            'message' => $message,
            'countryCode' => '62', // Indonesia
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
        ])->post($url, $data);

        return $response->successful();
    }

    public function sendInvoiceReminder(Invoice $invoice) {
        if (!$invoice->isOverdue()) return;

        $customer = $invoice->customer;
        $message = "🔔 *REMINDER TAGIHAN*\n\n" .
                  "Halo {$customer->name},\n\n" .
                  "Kami ingin mengingatkan bahwa tagihan Anda dengan nomor:\n" .
                  "*{$invoice->invoice_number}*\n\n" .
                  "Sebesar: *Rp " . number_format($invoice->total, 0, ',', '.') . "*\n" .
                  "Jatuh tempo: *" . $invoice->due_date->format('d M Y') . "*\n" .
                  "Telah melewati batas waktu pembayaran.\n\n" .
                  "Mohon segera lakukan pembayaran untuk menghindari denda.\n\n" .
                  "Terima kasih atas perhatiannya.\n" .
                  "Salam,\n" .
                  config('app.name');

        return $this->sendMessage($customer->phone, $message);
    }

    public function sendPaymentConfirmation(InvoicePayment $payment) {
        $invoice = $payment->invoice;
        $customer = $invoice->customer;

        $message = "✅ *KONFIRMASI PEMBAYARAN*\n\n" .
                  "Halo {$customer->name},\n\n" .
                  "Pembayaran Anda untuk invoice *{$invoice->invoice_number}* telah diterima:\n\n" .
                  "💰 Jumlah: *Rp " . number_format($payment->amount, 0, ',', '.') . "*\n" .
                  "🏦 Rekening: *{$payment->account->name}*\n" .
                  "📅 Tanggal: *" . $payment->payment_date->format('d M Y') . "*\n\n" .
                  "Sisa tagihan: *Rp " . number_format($invoice->getRemainingAmount(), 0, ',', '.') . "*\n\n" .
                  "Terima kasih atas pembayarannya! 🙏";

        return $this->sendMessage($customer->phone, $message);
    }

    public function sendLowStockAlert(Product $product) {
        $users = User::where('branch_id', $product->branch_id ?? null)
            ->orWhere('id', $product->user_id)
            ->get();

        $message = "⚠️ *PERINGATAN STOK RENDAH*\n\n" .
                  "Produk: *{$product->name}*\n" .
                  "Stok saat ini: *{$product->stock_quantity}*\n" .
                  "Stok minimum: *{$product->min_stock}*\n\n" .
                  "Mohon segera lakukan restock untuk menghindari kekurangan stok.";

        foreach ($users as $user) {
            if ($user->phone) {
                $this->sendMessage($user->phone, $message);
            }
        }
    }

    private function formatPhoneNumber($phone) {
        // Remove any non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Remove leading 0 and add country code if needed
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '62')) {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
```

#### **Automated Notifications**
```php
class NotificationService {
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp) {
        $this->whatsapp = $whatsapp;
    }

    public function sendOverdueReminders() {
        $overdueInvoices = Invoice::where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->where('due_date', '>', now()->subDays(30)) // Last 30 days
            ->with('customer')
            ->get();

        foreach ($overdueInvoices as $invoice) {
            // Send reminder every 7 days
            $lastReminder = $invoice->reminders()->latest()->first();
            if (!$lastReminder || $lastReminder->created_at < now()->subDays(7)) {
                $this->whatsapp->sendInvoiceReminder($invoice);

                // Log reminder
                $invoice->reminders()->create([
                    'type' => 'overdue_reminder',
                    'sent_via' => 'whatsapp',
                ]);
            }
        }
    }

    public function sendLowStockAlerts() {
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'min_stock')
            ->where('min_stock', '>', 0)
            ->get();

        foreach ($lowStockProducts as $product) {
            // Send alert once per day
            $lastAlert = $product->stockAlerts()->where('created_at', '>', now()->subDay())->first();
            if (!$lastAlert) {
                $this->whatsapp->sendLowStockAlert($product);

                // Log alert
                $product->stockAlerts()->create([
                    'alert_type' => 'low_stock',
                    'sent_via' => 'whatsapp',
                ]);
            }
        }
    }
}
```

#### **Scheduled Commands**
```php
// In Console/Kernel.php
protected $commands = [
    Commands\SendOverdueReminders::class,
    Commands\SendLowStockAlerts::class,
];

protected function schedule(Schedule $schedule) {
    $schedule->command('reminders:overdue')
             ->dailyAt('09:00');

    $schedule->command('alerts:low-stock')
             ->dailyAt('08:00');
}
```

---

### **MODULE 9: OCR RECEIPT SCANNING**

#### **OCR Service**
```php
class OCRService {
    public function processReceiptImage($imageFile) {
        // Upload to Google Vision API or Tesseract
        $extractedText = $this->extractTextFromImage($imageFile);

        // Parse the text to extract transaction data
        $parsedData = $this->parseReceiptText($extractedText);

        return [
            'merchant' => $parsedData['merchant'] ?? null,
            'date' => $parsedData['date'] ?? null,
            'amount' => $parsedData['amount'] ?? null,
            'items' => $parsedData['items'] ?? [],
            'raw_text' => $extractedText,
        ];
    }

    private function extractTextFromImage($file) {
        // Using Google Vision API
        $imageData = file_get_contents($file->getRealPath());
        $base64Image = base64_encode($imageData);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->getGoogleAccessToken(),
            'Content-Type' => 'application/json',
        ])->post('https://vision.googleapis.com/v1/images:annotate', [
            'requests' => [
                [
                    'image' => ['content' => $base64Image],
                    'features' => [['type' => 'TEXT_DETECTION']],
                ]
            ]
        ]);

        if ($response->successful()) {
            return $response->json()['responses'][0]['textAnnotations'][0]['description'] ?? '';
        }

        // Fallback to Tesseract if Google fails
        return $this->extractWithTesseract($file);
    }

    private function parseReceiptText($text) {
        $data = [];

        // Extract date patterns
        preg_match('/\b\d{1,2}[-\/]\d{1,2}[-\/]\d{2,4}\b/', $text, $dateMatches);
        if (!empty($dateMatches)) {
            $data['date'] = $this->parseDate($dateMatches[0]);
        }

        // Extract amount patterns (look for currency symbols)
        preg_match_all('/(?:Rp\.?|IDR|USD|\$)\s*[\d,]+\.?\d*/i', $text, $amountMatches);
        if (!empty($amountMatches[0])) {
            $data['amount'] = $this->parseAmount(end($amountMatches[0]));
        }

        // Extract merchant name (usually at the top)
        $lines = explode("\n", $text);
        $data['merchant'] = trim($lines[0] ?? '');

        // Extract items (look for quantity x price patterns)
        $data['items'] = $this->extractItems($text);

        return $data;
    }

    private function extractItems($text) {
        $items = [];
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            // Look for patterns like "2 x 15000" or "Item Name 30000"
            if (preg_match('/(.+?)\s+(\d+(?:[.,]\d+)?)\s*x?\s*(\d+(?:[.,]\d+)?)/i', $line, $matches)) {
                $items[] = [
                    'description' => trim($matches[1]),
                    'quantity' => (float) str_replace(',', '.', $matches[2]),
                    'price' => (float) str_replace(',', '.', $matches[3] ?? $matches[2]),
                ];
            }
        }

        return $items;
    }

    private function parseDate($dateString) {
        // Try different date formats
        $formats = ['d/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y', 'Y-m-d'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $dateString)->format('Y-m-d');
            } catch (Exception $e) {
                continue;
            }
        }

        return null;
    }

    private function parseAmount($amountString) {
        // Remove currency symbols and clean up
        $cleanAmount = preg_replace('/[^\d,.\s]/', '', $amountString);
        $cleanAmount = str_replace([' ', ','], ['', '.'], $cleanAmount);

        return (float) $cleanAmount;
    }
}
```

#### **OCR Controller**
```php
class OCRController extends Controller {
    public function uploadReceipt(Request $request) {
        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        try {
            $ocrService = new OCRService();
            $extractedData = $ocrService->processReceiptImage($request->file('receipt_image'));

            // Store the image
            $imagePath = $request->file('receipt_image')->store('receipts', 'public');

            // Create a draft transaction with extracted data
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'description' => $extractedData['merchant'] ?? 'Receipt from OCR',
                'amount' => $extractedData['amount'] ?? 0,
                'date' => $extractedData['date'] ?? now()->format('Y-m-d'),
                'type' => 'expense',
                '