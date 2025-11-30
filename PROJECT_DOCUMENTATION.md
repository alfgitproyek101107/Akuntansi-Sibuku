# 📊 SISTEM AKUNTANSI DIGITAL - DOKUMENTASI LENGKAP

## 🎯 **OVERVIEW SISTEM**

Sistem Akuntansi Digital adalah aplikasi web berbasis Laravel yang menyediakan solusi lengkap untuk pengelolaan keuangan bisnis dengan implementasi **Double Entry Accounting System** yang profesional. Sistem ini dirancang untuk UMKM dan bisnis kecil-menengah dengan fitur lengkap manajemen keuangan, inventory, dan pelaporan.

---

## 🏗️ **ARSITEKTUR SISTEM**

### **Teknologi Stack**
- **Backend**: Laravel 11.x Framework
- **Database**: SQLite (Development), MySQL/PostgreSQL (Production)
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Authentication**: Laravel Sanctum
- **File Storage**: Local Storage
- **Queue System**: Database Queue

### **Arsitektur Database**
```
Users (Multi-tenant)
├── Accounts (Rekening Bank/Kas)
├── Categories (Kategori Pemasukan/Pengeluaran)
├── Transactions (Transaksi Double Entry)
├── Transfers (Transfer Antar Rekening)
├── Products (Barang Dagangan)
├── ProductCategories (Kategori Produk)
├── Customers (Pelanggan)
├── StockMovements (Pergerakan Stok)
├── Branches (Cabang Perusahaan)
├── TaxSettings (Pengaturan Pajak)
├── RecurringTemplates (Template Berulang)
├── ChartOfAccounts (Bagan Akun)
├── JournalEntries (Jurnal Umum)
└── JournalLines (Baris Jurnal)
```

---

## 🎛️ **DAFTAR MENU & FITUR LENGKAP**

### **1. DASHBOARD**
**Path**: `/dashboard`
**Controller**: `DashboardController`
**Features**:
- ✅ Ringkasan Keuangan Real-time
- ✅ Grafik Pemasukan vs Pengeluaran
- ✅ Statistik Transaksi Bulanan
- ✅ Quick Actions Menu
- ✅ Notifikasi & Alerts

**API Endpoint**: `GET /api/dashboard`

---

### **2. MANAJEMEN AKUN KEUANGAN**
**Path**: `/accounts`
**Controller**: `AccountController`
**Features**:
- ✅ **Index**: List semua rekening dengan saldo
- ✅ **Create**: Tambah rekening baru (Bank/Kas)
- ✅ **Show**: Detail rekening dengan riwayat transaksi
- ✅ **Edit**: Update informasi rekening
- ✅ **Delete**: Hapus rekening (jika belum ada transaksi)
- ✅ **Ledger**: Buku besar per rekening
- ✅ **Export**: Export data rekening
- ✅ **Reconcile**: Rekonsiliasi rekening

**Security**: User-scoped (hanya rekening milik user yang bisa diakses)

---

### **3. KATEGORI TRANSAKSI**
**Path**: `/categories`
**Controller**: `CategoryController`
**Features**:
- ✅ **Index**: List kategori by type (income/expense)
- ✅ **Create**: Tambah kategori baru
- ✅ **Show**: Detail kategori dengan statistik
- ✅ **Edit**: Update kategori
- ✅ **Delete**: Hapus kategori (dengan validasi)

**Types**: Income Categories, Expense Categories

---

### **4. PEMASUKAN (INCOME)**
**Path**: `/incomes`
**Controller**: `IncomeController`
**Features**:
- ✅ **Index**: List semua pemasukan
- ✅ **Create**: Form input pemasukan baru
- ✅ **Show**: Detail pemasukan dengan validasi ownership
- ✅ **Edit**: Edit pemasukan dengan rollback balance
- ✅ **Delete**: Hapus pemasukan dengan rollback

**Business Logic**:
- Auto-update account balance (+)
- Link to products (optional)
- Stock movement for sales
- Tax calculation support

---

### **5. PENGELUARAN (EXPENSE)**
**Path**: `/expenses`
**Controller**: `ExpenseController`
**Features**:
- ✅ **Index**: List semua pengeluaran
- ✅ **Create**: Form input pengeluaran baru
- ✅ **Show**: Detail pengeluaran dengan validasi
- ✅ **Edit**: Edit pengeluaran dengan balance check
- ✅ **Delete**: Hapus pengeluaran dengan rollback

**Business Logic**:
- Balance validation (tidak boleh minus)
- Auto-update account balance (-)
- Product purchase tracking
- Stock increment for purchases

---

### **6. TRANSFER ANTAR REKENING**
**Path**: `/transfers`
**Controller**: `TransferController`
**Features**:
- ✅ **Index**: List semua transfer
- ✅ **Create**: Form transfer antar rekening
- ✅ **Show**: Detail transfer dengan validasi
- ✅ **Edit**: Edit transfer dengan balance rollback
- ✅ **Delete**: Hapus transfer dengan balance rollback

**Business Logic**:
- From account balance validation
- Double transaction creation
- Auto balance updates (from -, to +)

---

### **7. TEMPLATE BERULANG**
**Path**: `/recurring-templates`
**Controller**: `RecurringTemplateController`
**Features**:
- ✅ **Index**: List template berulang
- ✅ **Create**: Buat template baru
- ✅ **Show**: Detail template
- ✅ **Edit**: Edit template
- ✅ **Delete**: Hapus template
- ✅ **Execute**: Jalankan template (manual)

**Types**: Daily, Weekly, Monthly, Yearly

---

### **8. MANAJEMEN PRODUK**
**Path**: `/products`
**Controller**: `ProductController`
**Features**:
- ✅ **Index**: List produk dengan stok
- ✅ **Create**: Tambah produk baru
- ✅ **Show**: Detail produk
- ✅ **Edit**: Edit produk
- ✅ **Delete**: Hapus produk

**Integration**: Linked dengan transactions untuk sales/purchase

---

### **9. KATEGORI PRODUK**
**Path**: `/product-categories`
**Controller**: `ProductCategoryController`
**Features**:
- ✅ **Index**: List kategori produk
- ✅ **Create**: Tambah kategori
- ✅ **Show**: Detail kategori
- ✅ **Edit**: Edit kategori
- ✅ **Delete**: Hapus kategori

---

### **10. PELANGGAN**
**Path**: `/customers`
**Controller**: `CustomerController`
**Features**:
- ✅ **Index**: List pelanggan
- ✅ **Create**: Tambah pelanggan
- ✅ **Show**: Detail pelanggan
- ✅ **Edit**: Edit pelanggan
- ✅ **Delete**: Hapus pelanggan

**Integration**: Linked dengan sales transactions

---

### **11. PERGERAKAN STOK**
**Path**: `/stock-movements`
**Controller**: `StockMovementController`
**Features**:
- ✅ **Index**: List pergerakan stok
- ✅ **Create**: Manual stock adjustment
- ✅ **Show**: Detail pergerakan
- ✅ **Edit**: Edit adjustment
- ✅ **Delete**: Hapus adjustment

**Types**: In (Masuk), Out (Keluar), Adjustment

---

### **12. CABANG PERUSAHAAN**
**Path**: `/branches`
**Controller**: `BranchController`
**Features**:
- ✅ **Index**: List cabang
- ✅ **Create**: Tambah cabang
- ✅ **Show**: Detail cabang
- ✅ **Edit**: Edit cabang
- ✅ **Delete**: Hapus cabang
- ✅ **Switch**: Pindah cabang aktif

---

### **13. PENGATURAN PAJAK**
**Path**: `/tax`
**Controller**: `TaxController`
**Features**:
- ✅ **Index**: List pengaturan pajak
- ✅ **Create**: Tambah pengaturan pajak
- ✅ **Show**: Detail pengaturan
- ✅ **Edit**: Edit pengaturan
- ✅ **Delete**: Hapus pengaturan
- ✅ **Calculate**: Hitung pajak otomatis

**Types**: PPN, PPh, dll.

---

### **14. MANAJEMEN USER**
**Path**: `/users`
**Controller**: `UserController`
**Features**:
- ✅ **Index**: List users (Admin only)
- ✅ **Create**: Tambah user baru
- ✅ **Show**: Detail user
- ✅ **Edit**: Edit user
- ✅ **Delete**: Hapus user
- ✅ **Profile**: Edit profile sendiri
- ✅ **Change Password**: Ganti password

---

### **15. CHART OF ACCOUNTS (BAGAN AKUN)**
**Path**: `/chart-of-accounts`
**Controller**: `ChartOfAccountsController`
**Features**:
- ✅ **Index**: Hierarchical account structure
- ✅ **Create**: Tambah akun baru
- ✅ **Show**: Detail akun dengan journal history
- ✅ **Edit**: Edit akun
- ✅ **Delete**: Hapus akun (dengan validasi)
- ✅ **Toggle Active**: Aktif/Nonaktif akun
- ✅ **Trial Balance**: Neraca saldo

**Structure**: 5-level hierarchy (Asset, Liability, Equity, Revenue, Expense)

---

### **16. SISTEM PELAPORAN**
**Path**: `/reports`
**Controller**: `ReportController`
**Reports Available**:
- ✅ **Daily**: Laporan harian
- ✅ **Weekly**: Laporan mingguan
- ✅ **Monthly**: Laporan bulanan
- ✅ **Profit & Loss**: Laba rugi
- ✅ **Cash Flow**: Arus kas
- ✅ **Balance Sheet**: Neraca
- ✅ **Accounts**: Laporan per rekening
- ✅ **Transfers**: Laporan transfer
- ✅ **Reconciliation**: Rekonsiliasi
- ✅ **Total Sales**: Total penjualan
- ✅ **Top Products**: Produk terlaris
- ✅ **Sales by Customer**: Penjualan per pelanggan
- ✅ **Stock Levels**: Level stok
- ✅ **Stock Movements**: Pergerakan stok
- ✅ **Inventory Value**: Nilai inventory

---

### **17. PENGATURAN SISTEM**
**Path**: `/settings`
**Controller**: `SettingController`
**Features**:
- ✅ **Profile**: Edit profil user
- ✅ **Password**: Ganti password
- ✅ **Preferences**: Pengaturan aplikasi

---

## 🔄 **DETAILED FUNCTION FLOWS**

### **FLOW 1: INPUT PEMASUKAN (INCOME)**
```
1. User akses /incomes/create
2. Pilih Account (Kas/Bank)
3. Pilih Category (Kategori Pemasukan)
4. Input Amount & Description
5. Pilih Product (optional untuk sales)
6. Input Date
7. Submit Form
8. Validation:
   - Account ownership check
   - Category ownership & type check
   - Product ownership (if selected)
9. Create Transaction record
10. Update Account Balance (+ amount)
11. If product selected: Create StockMovement (out)
12. Update Product stock (-1)
13. Redirect to index with success message
```

### **FLOW 2: INPUT PENGELUARAN (EXPENSE)**
```
1. User akses /expenses/create
2. Pilih Account (Kas/Bank)
3. Pilih Category (Kategori Pengeluaran)
4. Input Amount & Description
5. Pilih Product (optional untuk purchase)
6. Input Date
7. Validation: Balance check (tidak boleh minus)
8. Submit Form
9. Create Transaction record
10. Update Account Balance (- amount)
11. If product selected: Create StockMovement (in)
12. Update Product stock (+1)
13. Redirect to index with success message
```

### **FLOW 3: TRANSFER ANTAR REKENING**
```
1. User akses /transfers/create
2. Pilih From Account
3. Pilih To Account (different from From)
4. Input Amount & Description
5. Input Date
6. Validation: From account balance check
7. Submit Form
8. Create Transfer record
9. Create 2 Transaction records:
   - From account: type='transfer', amount negative
   - To account: type='transfer', amount positive
10. Update both account balances
11. Redirect to index with success message
```

### **FLOW 4: DOUBLE ENTRY ACCOUNTING**
```
1. Transaction created (Income/Expense/Transfer)
2. AccountingService triggered
3. Create JournalEntry (draft status)
4. Create JournalLines based on transaction type:
   - Income: Debit Cash/Bank, Credit Revenue
   - Expense: Debit Expense, Credit Cash/Bank
   - Transfer: Debit To Account, Credit From Account
5. Validate: Total Debit = Total Credit
6. Post JournalEntry (status = 'posted')
7. Update ChartOfAccount balances
8. Transaction complete with full audit trail
```

### **FLOW 5: STOCK MANAGEMENT**
```
1. Product created/updated
2. StockMovement created for:
   - Manual adjustments
   - Sales transactions (out)
   - Purchase transactions (in)
3. Update Product.stock_quantity
4. Validation: Stock tidak boleh minus
5. Audit trail maintained
```

### **FLOW 6: REPORTING SYSTEM**
```
1. User selects report type
2. System queries relevant data:
   - Transactions with date filters
   - Account balances
   - Product movements
   - Customer data
3. Apply business logic calculations
4. Format data for display
5. Generate charts/graphs
6. Export options (PDF/Excel)
```

---

## 🛡️ **SECURITY FEATURES**

### **Authentication & Authorization**
- ✅ Laravel Sanctum authentication
- ✅ User-scoped data access
- ✅ Route model binding security fixes
- ✅ CSRF protection on all forms
- ✅ Password hashing & validation

### **Data Validation**
- ✅ Server-side validation on all inputs
- ✅ Business logic validation (balance checks)
- ✅ Ownership validation (user_id checks)
- ✅ Unique constraints enforcement
- ✅ Data type validation

### **Audit Trail**
- ✅ All transactions logged
- ✅ Journal entries for accounting audit
- ✅ Stock movements tracked
- ✅ User action logging

---

## 📊 **DATABASE SCHEMA DETAIL**

### **Core Tables**

#### **users**
```sql
- id (PK)
- name, email, password
- branch_id (FK)
- user_role_id (FK)
- email_verified_at
- timestamps
```

#### **accounts**
```sql
- id (PK)
- user_id (FK)
- name, description
- balance (decimal)
- account_number
- bank_name
- is_active
- timestamps
```

#### **transactions**
```sql
- id (PK)
- user_id (FK)
- account_id (FK)
- category_id (FK, nullable)
- product_id (FK, nullable)
- amount (decimal)
- description
- date
- type (income/expense/transfer)
- transfer_id (FK, nullable)
- reconciled
- timestamps
```

#### **chart_of_accounts**
```sql
- id (PK)
- code (unique)
- name
- type (asset/liability/equity/revenue/expense)
- category
- parent_id (FK, self-reference)
- level (1-5)
- normal_balance (debit/credit)
- balance (decimal)
- is_active
- description
- timestamps
```

#### **journal_entries**
```sql
- id (PK)
- date
- reference
- description
- total_debit, total_credit
- status (draft/posted/voided)
- created_by (FK users)
- posted_at
- notes
- timestamps
```

#### **journal_lines**
```sql
- id (PK)
- journal_entry_id (FK)
- chart_of_account_id (FK)
- debit, credit (decimal)
- description
- line_number
- timestamps
```

---

## 🔌 **API ENDPOINTS**

### **Authentication**
- `POST /login` - User login
- `POST /logout` - User logout
- `GET /user` - Get current user

### **Dashboard**
- `GET /api/dashboard` - Dashboard data & statistics

### **CRUD Endpoints** (All RESTful)
```
GET    /accounts           - List accounts
POST   /accounts           - Create account
GET    /accounts/{id}      - Show account
PUT    /accounts/{id}      - Update account
DELETE /accounts/{id}      - Delete account
```

### **Special Endpoints**
- `GET /accounts/{id}/ledger` - Account ledger
- `POST /accounts/{id}/export` - Export account data
- `GET /trial-balance` - Trial balance report
- `POST /tax/calculate` - Tax calculation

---

## 🎨 **UI/UX FEATURES**

### **Design System**
- ✅ Modern responsive design
- ✅ Tailwind CSS framework
- ✅ Consistent component library
- ✅ Indonesian language interface
- ✅ Mobile-friendly layouts

### **Interactive Features**
- ✅ Real-time form validation
- ✅ Dynamic dropdowns
- ✅ Modal dialogs
- ✅ Toast notifications
- ✅ Loading states
- ✅ Search & filtering
- ✅ Pagination
- ✅ Export functionality

### **Navigation**
- ✅ Sidebar navigation
- ✅ Breadcrumb navigation
- ✅ Quick action buttons
- ✅ Contextual menus

---

## 🔧 **TECHNICAL IMPLEMENTATION**

### **Models & Relationships**
```php
// User Model
class User extends Authenticatable {
    public function accounts() { return $this->hasMany(Account::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
    public function transfers() { return $this->hasMany(Transfer::class); }
    // ... other relationships
}

// Transaction Model
class Transaction extends Model {
    public function user() { return $this->belongsTo(User::class); }
    public function account() { return $this->belongsTo(Account::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
```

### **Services Layer**
```php
// AccountingService
class AccountingService {
    public function createJournalEntry(array $data): JournalEntry
    public function createIncomeJournalEntry(Transaction $transaction): JournalEntry
    public function createExpenseJournalEntry(Transaction $transaction): JournalEntry
    public function postJournalEntry(JournalEntry $journal): bool
    public function getTrialBalance(string $start, string $end): array
}
```

### **Middleware & Security**
```php
// Route Middleware
Route::middleware(['auth'])->group(function () {
    // All protected routes
});

// Controller Security
public function show($id) {
    $transaction = Auth::user()->transactions()->findOrFail($id);
    // User-scoped data access
}
```

---

## 📈 **PERFORMANCE & SCALABILITY**

### **Optimization Features**
- ✅ Database indexing on foreign keys
- ✅ Eager loading relationships
- ✅ Query optimization
- ✅ Caching strategies
- ✅ Pagination on large datasets

### **Scalability Considerations**
- ✅ Multi-tenant architecture ready
- ✅ Queue system for heavy operations
- ✅ Database optimization
- ✅ CDN ready for assets
- ✅ API rate limiting

---

## 🧪 **TESTING & QUALITY**

### **Test Coverage**
- ✅ Unit tests for models
- ✅ Feature tests for controllers
- ✅ Integration tests for workflows
- ✅ Security tests for vulnerabilities

### **Code Quality**
- ✅ PSR standards compliance
- ✅ Laravel conventions
- ✅ Clean code principles
- ✅ Documentation comments
- ✅ Type hinting

---

## 🚀 **DEPLOYMENT & MAINTENANCE**

### **Deployment Checklist**
- ✅ Environment configuration
- ✅ Database migration
- ✅ Seeders execution
- ✅ Storage permissions
- ✅ Queue worker setup
- ✅ Cron job configuration

### **Maintenance Tasks**
- ✅ Regular backup procedures
- ✅ Log monitoring
- ✅ Performance monitoring
- ✅ Security updates
- ✅ Database optimization

---

## 📋 **FUTURE ENHANCEMENTS**

### **Phase 2 Features**
- 🔄 Multi-company support
- 🔄 Advanced reporting (PDF/Excel export)
- 🔄 Budget planning & tracking
- 🔄 Invoice generation
- 🔄 Payment gateway integration
- 🔄 API for third-party integrations
- 🔄 Mobile app companion
- 🔄 Advanced analytics dashboard

### **Technical Improvements**
- 🔄 Redis caching implementation
- 🔄 Elasticsearch for search
- 🔄 Microservices architecture
- 🔄 Real-time notifications
- 🔄 Advanced audit logging

---

## 📞 **SUPPORT & DOCUMENTATION**

### **Documentation Files**
- ✅ `dokumentasi.md` - System documentation
- ✅ `PROJECT_DOCUMENTATION.md` - This comprehensive guide
- ✅ Inline code documentation
- ✅ API documentation

### **Support Resources**
- ✅ Error handling & logging
- ✅ Troubleshooting guides
- ✅ Performance monitoring
- ✅ Backup & recovery procedures

---

## 🎉 **CONCLUSION**

Sistem Akuntansi Digital ini merupakan solusi lengkap untuk pengelolaan keuangan bisnis dengan implementasi **Double Entry Accounting System** yang profesional. Sistem ini telah diuji dan siap untuk digunakan dalam production environment dengan fitur-fitur lengkap untuk manajemen keuangan, inventory, dan pelaporan yang akurat.

**Total Features Implemented**: 50+ menu dan fungsi
**Security Level**: Enterprise-grade dengan multi-layer protection
**Scalability**: Ready untuk growth dari UMKM ke perusahaan besar
**Technology**: Modern Laravel stack dengan best practices

**Status**: ✅ **PRODUCTION READY** 🎯