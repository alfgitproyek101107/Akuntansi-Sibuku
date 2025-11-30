# ✅ **CHECKLIST LENGKAP — SYSTEM AUDIT (Single Company, Multi Cabang)**

### **VERSI PALING KOMPLIT — untuk debugging, maintenance, dan validasi flow**

---

# 🟦 **A. Struktur Cabang & Akses Data**

### Yang harus dipastikan:

1. **Setiap tabel yang bersifat transaksi harus punya `branch_id`**

   * ✅ transactions
   * ✅ customers
   * ✅ products
   * ✅ stock_movements
   * ✅ accounts (branch-specific accounts)
   * ✅ tax_settings (branch-specific tax)
   * ✅ recurring_templates (branch-specific)
   * ✅ journal_entries (branch-specific)

2. **Semua query harus ter-filter otomatis dengan branch_id**

   * ❌ **BUG POTENSIAL**: Jangan ada query seperti:

     ```sql
     SELECT * FROM transactions
     ```

     ✅ **WAJIB SEMUA**:

     ```sql
     WHERE branch_id = Auth::user()->branch_id
     -- atau untuk super admin
     WHERE branch_id = session('active_branch') OR user_id = auth()->id()
     ```

3. **Global Scope (opsional tapi aman)**
   Buat Laravel GlobalScope agar *lupa nambah where branch_id* tidak menyebabkan data bercampur.

   ```php
   // Contoh GlobalScope untuk Transaction model
   protected static function booted()
   {
       static::addGlobalScope('branch', function (Builder $builder) {
           if (auth()->check() && !auth()->user()->hasRole('super-admin')) {
               $branchId = session('active_branch') ?? auth()->user()->branch_id;
               if ($branchId) {
                   $builder->where('branch_id', $branchId);
               }
           }
       });
   }
   ```

4. **Super Admin boleh melihat semua cabang**
   Tapi user cabang HANYA cabangnya sendiri.

   ```php
   // Di controller
   if (auth()->user()->hasRole('super-admin')) {
       // Bisa lihat semua data
       $data = Model::all();
   } else {
       // Hanya data cabang sendiri
       $branchId = session('active_branch') ?? auth()->user()->branch_id;
       $data = Model::where('branch_id', $branchId)->get();
   }
   ```

5. **Export laporan harus mengikuti cabang**
   Tidak boleh menggabung kecuali izin.

   ```php
   // Di ReportController
   public function export(Request $request)
   {
       $branchId = $this->getCurrentBranchId();

       // Pastikan export hanya data cabang yang relevan
       $query = Transaction::where('branch_id', $branchId);

       // ... export logic
   }
   ```

---

# 🟦 **B. Menu — Pastikan Semua Fungsi Bekerja**

Berikut list menu standar untuk single company multi branch.

Cek 1 per 1:

---

## 1️⃣ **Dashboard**

Pastikan:

* ✅ Statistik diambil berdasarkan branch user.
* ✅ Grafik harian/bulanan ter-filter dengan benar.
* ✅ Total income/expense sesuai branch.
* ✅ Cache atau queue tidak membuat data salah cabang.

**Checklist Detail:**
- [ ] Dashboard menampilkan branch aktif di header
- [ ] KPIs (income, expense, balance) sesuai branch
- [ ] Grafik cash flow filtered by branch
- [ ] Recent transactions hanya dari branch aktif
- [ ] Quick actions redirect ke form dengan branch context

---

## 2️⃣ **Manajemen User & Role**

Validasi:

* ✅ Role permission benar (super-admin, admin, manager, staff, viewer).
* ✅ User wajib punya `branch_id`.
* ✅ Staff cabang A tidak bisa login cabang B.
* ✅ Tidak ada user tanpa role.
* ✅ Pastikan menu yang muncul sesuai role.

**Checklist Detail:**
- [ ] User creation wajib assign branch
- [ ] Role-based menu visibility
- [ ] Branch-specific user permissions
- [ ] User activity logging per branch
- [ ] Password reset security

---

## 3️⃣ **Manajemen Cabang**

Perlu cek:

* ✅ Tambah cabang harus membuat default setting cabang (kas awal, akun keuangan, stok default).
* ✅ Pindah cabang tidak boleh mengubah data lama (kecuali owner).
* ✅ Penghapusan cabang dicegah bila masih ada data transaksi.

**Checklist Detail:**
- [ ] Branch creation dengan default accounts
- [ ] Branch switching tanpa data corruption
- [ ] Branch deletion validation (check existing data)
- [ ] Branch-specific settings initialization
- [ ] Inter-branch transfer capabilities

---

## 4️⃣ **Produk / Inventori**

Periksa semua fitur:

* ✅ Tambah produk (branch vs global master).
* ✅ Stok per cabang (stock_movements table).
* ✅ Mutasi stok antar cabang.
* ✅ Penyesuaian stok.
* ✅ Kategori & merk sesuai branch.
* ✅ Kode SKU unik per cabang atau global—Tentukan.

**Checklist Detail:**
- [ ] Product creation dengan branch context
- [ ] Stock tracking per branch
- [ ] Inter-branch stock transfers
- [ ] Stock adjustment logging
- [ ] Low stock alerts per branch
- [ ] Product categories per branch

---

## 5️⃣ **Penjualan (Income)**

Pastikan:

* ✅ Filter tanggal.
* ✅ Filter customer.
* ✅ Search invoice.
* ✅ Pagination.
* ✅ Diskon berjalan.
* ✅ Perhitungan subtotal benar.
* ✅ PPN/Pajak optional.
* ✅ Cetak nota.
* ✅ Void transaksi update stok.

**Checklist Detail:**
- [ ] Income creation dengan branch validation
- [ ] Customer filtering per branch
- [ ] Product stock validation
- [ ] Tax calculation per branch settings
- [ ] Journal entries creation
- [ ] Receipt/invoice generation

---

## 6️⃣ **Pengeluaran (Expense)**

Cek:

* ✅ Balance validation per rekening cabang.
* ✅ Expense categories per branch.
* ✅ File attachment support.
* ✅ Approval workflow (future).
* ✅ Expense reporting.

**Checklist Detail:**
- [ ] Account balance checking
- [ ] Expense category validation
- [ ] File upload handling
- [ ] Journal entries untuk expenses
- [ ] Expense approval workflow

---

## 7️⃣ **Transfer Antar Rekening**

Harus dicek:

* ✅ Transfer antar rekening dalam cabang.
* ✅ Transfer antar cabang (future enhancement).
* ✅ Balance validation.
* ✅ Transfer history.
* ✅ Linked transaction creation.

**Checklist Detail:**
- [ ] From/to account validation
- [ ] Balance sufficiency check
- [ ] Double transaction creation
- [ ] Transfer logging
- [ ] Inter-branch transfer support

---

## 8️⃣ **Customer**

Harus dicek:

* ✅ Customer per cabang (jangan global).
* ✅ Riwayat transaksi hanya cabang terkait.
* ✅ Search berjalan.
* ✅ Duplicate phone detection optional.

**Checklist Detail:**
- [ ] Customer isolation per branch
- [ ] Transaction history filtering
- [ ] Customer search functionality
- [ ] Contact information validation
- [ ] Customer analytics per branch

---

## 9️⃣ **Keuangan**

Menu biasanya:

* ✅ Kas masuk (Income)
* ✅ Kas keluar (Expense)
* ✅ Transfer antar akun
* ✅ Buku kas (Account Ledger)
* ✅ Laporan laba rugi
* ✅ Neraca cabang
* ✅ Jurnal otomatis

Cek:

* ✅ Semua transaksi keuangan wajib punya akun + branch_id.
* ✅ Perhitungan laba rugi mengikuti cabang.

**Checklist Detail:**
- [ ] Account management per branch
- [ ] Journal entries automation
- [ ] Financial reporting per branch
- [ ] Trial balance generation
- [ ] Account reconciliation

---

## 🔟 **Laporan**

Laporan yang harus ada:

* ✅ Laporan penjualan (Sales Report)
* ✅ Laporan stok (Inventory Report)
* ✅ Laporan keuangan (Financial Report)
* ✅ Laporan transaksi kas (Cash Transaction Report)
* ✅ Laporan profit (Profit & Loss)
* ✅ Laporan hutang/piutang (AR/AP Aging)
* ✅ Laporan pergerakan stok (Stock Movements)
* ✅ Laporan bulanan (Monthly Report)
* ✅ Laporan rekening (Account Report)
* ✅ Laporan transfer (Transfer Report)
* ✅ Laporan rekonsiliasi (Reconciliation Report)
* ✅ Laporan top products (Sales Analytics)
* ✅ Laporan sales by customer (Customer Analytics)
* ✅ Laporan stock levels (Inventory Status)
* ✅ Laporan trial balance (Accounting Report)

Validasi laporan:

* ✅ Bisa filter: tanggal, produk, user, kategori, cabang.
* ✅ Export Excel / PDF tidak error.
* ✅ Data tidak tercampur antar cabang.

**Checklist Detail:**
- [ ] Branch-specific report filtering
- [ ] Date range validation
- [ ] Export functionality testing
- [ ] Report data accuracy
- [ ] Performance optimization

---

## 1️⃣1️⃣ **Template Berulang**

Cek:

* ✅ Recurring templates per branch.
* ✅ Automatic transaction generation.
* ✅ Template activation/deactivation.
* ✅ Execution logging.

**Checklist Detail:**
- [ ] Template creation per branch
- [ ] Scheduler execution
- [ ] Transaction auto-generation
- [ ] Template management

---

## 1️⃣2️⃣ **Pajak**

Validasi:

* ✅ Tax settings per branch.
* ✅ Automatic tax calculation.
* ✅ Tax reporting.
* ✅ Tax compliance.

**Checklist Detail:**
- [ ] Branch-specific tax rates
- [ ] Tax calculation accuracy
- [ ] Tax reporting generation
- [ ] Compliance validation

---

## 1️⃣3️⃣ **Setting Sistem**

Cek:

* ✅ Setting perusahaan (global untuk super-admin).
* ✅ Setting cabang (lokal per branch).
* ✅ Setting pajak, mata uang, date format.
* ✅ Logo, nama cabang, alamat.
* ✅ Backup dan restore per branch.

**Checklist Detail:**
- [ ] Global vs branch settings
- [ ] Configuration validation
- [ ] Backup functionality
- [ ] System maintenance

---

# 🟦 **C. Fungsi-Fungsi UI/UX — Pastikan Tidak Ada Bug**

### Semua halaman harus memiliki:

✅ Search
✅ Filter
✅ Pagination
✅ Sortir (optional)
✅ Button tambah/edit/delete
✅ Error handling
✅ Loading state
✅ Toast success/error

**Contoh bug umum:**

* ❌ Search tidak mem-filter branch_id.
* ❌ Filter tanggal tidak berubah.
* ❌ Reset filter tidak bekerja.
* ❌ Modal edit menampilkan data cabang lain.
* ❌ Pagination reset saat search.

**UI/UX Checklist Detail:**
- [ ] Responsive design testing
- [ ] Mobile compatibility
- [ ] Loading states implementation
- [ ] Error message consistency
- [ ] Form validation feedback
- [ ] Modal dialog functionality
- [ ] Toast notification system
- [ ] Search and filter UX

---

# 🟦 **D. Fitur-Fungsi Logika Penting**

Ini yang sering terlupakan:

### 1. **Validasi Double Submit**

* ✅ Transaksi tidak boleh tersimpan dua kali bila koneksi lemot.

```php
// Di controller
public function store(Request $request)
{
    // Prevent double submit
    $lockKey = 'transaction_' . auth()->id() . '_' . $request->input('unique_key');
    if (!Cache::add($lockKey, true, 10)) {
        return response()->json(['error' => 'Transaction already processing'], 429);
    }

    try {
        // Process transaction
        DB::transaction(function () use ($request) {
            // ... transaction logic
        });

        Cache::forget($lockKey);
        return response()->json(['success' => true]);
    } catch (Exception $e) {
        Cache::forget($lockKey);
        throw $e;
    }
}
```

### 2. **Soft Delete (bukan hard delete)**

* ✅ Menghindari kehilangan data historis.

```php
// Di model
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
```

### 3. **Audit Log**

* ✅ Siapa edit, tambah, hapus apa.

```php
// Di model
protected static function booted()
{
    static::created(function ($model) {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model' => get_class($model),
            'model_id' => $model->id,
            'old_values' => null,
            'new_values' => $model->toArray(),
            'branch_id' => $model->branch_id ?? null,
        ]);
    });

    static::updated(function ($model) {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $model->getOriginal(),
            'new_values' => $model->getChanges(),
            'branch_id' => $model->branch_id ?? null,
        ]);
    });
}
```

### 4. **Perubahan stok otomatis**

* ✅ Saat jual → stok keluar
* ✅ Saat beli → stok masuk
* ✅ Saat retur → stok kembali
* ✅ Saat void → stok revert

```php
// Di TransactionObserver
class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        if ($transaction->type === 'income' && $transaction->product_id) {
            // Kurangi stok untuk penjualan
            $product = Product::find($transaction->product_id);
            $product->decrement('stock_quantity', 1);

            // Log pergerakan stok
            StockMovement::create([
                'product_id' => $transaction->product_id,
                'type' => 'out',
                'quantity' => 1,
                'reference' => 'Transaction #' . $transaction->id,
                'branch_id' => $transaction->branch_id,
            ]);
        }
    }

    public function deleted(Transaction $transaction)
    {
        if ($transaction->type === 'income' && $transaction->product_id) {
            // Kembalikan stok saat void
            $product = Product::find($transaction->product_id);
            $product->increment('stock_quantity', 1);

            // Log reversal
            StockMovement::create([
                'product_id' => $transaction->product_id,
                'type' => 'in',
                'quantity' => 1,
                'reference' => 'Void Transaction #' . $transaction->id,
                'branch_id' => $transaction->branch_id,
            ]);
        }
    }
}
```

### 5. **Locking periode keuangan**

* ✅ Bulan yang sudah closing tidak boleh diubah.

```php
// Di model atau middleware
public function canModifyTransaction(Transaction $transaction)
{
    $lockPeriod = LockPeriod::where('branch_id', $transaction->branch_id)
        ->where('year', $transaction->date->year)
        ->where('month', $transaction->date->month)
        ->where('is_locked', true)
        ->exists();

    return !$lockPeriod;
}
```

---

# 🟦 **E. Arsitektur Data untuk Multi Cabang**

**Rekomendasi struktur aman:**

```
# Core Tables (Global)
users (branch_id, role)
branches
roles
permissions
app_settings

# Transaction Tables (Branch-scoped)
transactions (branch_id)
accounts (branch_id)
categories (branch_id)
transfers (branch_id)
recurring_templates (branch_id)
tax_settings (branch_id)

# Inventory Tables (Branch-scoped)
products (branch_id)
product_categories (branch_id)
stock_movements (branch_id)
customers (branch_id)

# Accounting Tables (Branch-scoped)
journal_entries (branch_id)
journal_lines (branch_id)
chart_of_accounts (branch_id)

# Reporting Tables (Generated per branch)
reports_cache (branch_id)
```

**Migration Pattern:**
```php
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('branch_id')->constrained();
    $table->foreignId('account_id')->constrained();
    $table->foreignId('category_id')->nullable()->constrained();
    $table->decimal('amount', 15, 2);
    $table->enum('type', ['income', 'expense']);
    $table->timestamps();

    // Index untuk performance
    $table->index(['branch_id', 'date']);
    $table->index(['branch_id', 'type']);
});
```

---

# 🟦 **F. Potensi Bug yang Biasanya Muncul**

Berikut daftar bug klasik yang sering terjadi:

### ❌ **Data cabang tercampur karena lupa filter**

**Gejala:**
- User cabang A melihat data cabang B
- Laporan menampilkan data semua cabang

**Solusi:**
- Implementasi Global Scope
- Tambahkan branch_id di semua query
- Unit test untuk branch isolation

### ❌ **Stok salah hitung antara cabang**

**Gejala:**
- Stok produk berbeda di laporan vs aktual
- Transfer stok tidak update kedua cabang

**Solusi:**
- Atomic stock updates
- Stock movement logging
- Reconciliation reports

### ❌ **Kas tidak balance**

**Gejala:**
- Balance rekening tidak sesuai transaksi
- Laporan kas berbeda dengan bank statement

**Solusi:**
- Double-entry validation
- Journal entries audit
- Account reconciliation

### ❌ **Laporan beda dengan transaksi real**

**Gejala:**
- Total di laporan tidak match database
- Filter tidak bekerja dengan benar

**Solusi:**
- Query optimization
- Cache invalidation
- Report regeneration

### ❌ **Void transaksi tidak mengembalikan stok**

**Gejala:**
- Transaksi dihapus tapi stok tidak kembali
- Stock movement tidak tercatat

**Solusi:**
- Transaction observers
- Soft delete dengan reversal
- Audit trail lengkap

### ❌ **Transfer stok tidak update dua sisi**

**Gejala:**
- Transfer antar cabang tidak balance
- Stock di cabang asal tidak berkurang

**Solusi:**
- Inter-branch transfer logic
- Stock validation
- Transfer logging

### ❌ **Export Excel error saat search aktif**

**Gejala:**
- Export gagal saat filter aktif
- Memory limit exceeded

**Solusi:**
- Chunked export
- Query optimization
- File streaming

### ❌ **Search tidak ikut filter cabang**

**Gejala:**
- Search menemukan data cabang lain
- Filter cabang tidak bekerja

**Solusi:**
- Branch scope di search queries
- Parameter binding
- Query builder validation

---

# 🟦 **G. Testing Checklist**

### **Unit Tests:**
- [ ] Model relationships
- [ ] Business logic calculations
- [ ] Branch scoping
- [ ] Validation rules

### **Feature Tests:**
- [ ] CRUD operations per branch
- [ ] Report generation
- [ ] File uploads
- [ ] API endpoints

### **Integration Tests:**
- [ ] Multi-branch data isolation
- [ ] Cross-branch operations
- [ ] Performance under load
- [ ] Backup/restore functionality

### **UI Tests:**
- [ ] Branch switching
- [ ] Form validations
- [ ] Error handling
- [ ] Responsive design

---

# 🟦 **H. Performance Optimization**

### **Database:**
- [ ] Proper indexing pada branch_id columns
- [ ] Query optimization dengan EXPLAIN
- [ ] Database partitioning untuk large datasets
- [ ] Connection pooling

### **Application:**
- [ ] Laravel caching untuk frequent queries
- [ ] Queue untuk heavy operations
- [ ] CDN untuk static assets
- [ ] Redis untuk session storage

### **Frontend:**
- [ ] Lazy loading untuk large tables
- [ ] Pagination optimization
- [ ] Image optimization
- [ ] Bundle minification

---

# 🟦 **I. Security Checklist**

### **Data Security:**
- [ ] Branch data isolation enforcement
- [ ] SQL injection prevention
- [ ] XSS protection
- [ ] CSRF protection

### **Access Control:**
- [ ] Role-based permissions
- [ ] Branch-specific access
- [ ] API authentication
- [ ] Session security

### **Audit & Compliance:**
- [ ] Complete audit logging
- [ ] Data retention policies
- [ ] Backup encryption
- [ ] Compliance reporting

---

# 🟦 **J. Deployment & Maintenance**

### **Pre-deployment:**
- [ ] Environment configuration
- [ ] Database migration testing
- [ ] Seed data validation
- [ ] Performance benchmarking

### **Post-deployment:**
- [ ] Monitoring setup
- [ ] Backup automation
- [ ] Log aggregation
- [ ] Alert configuration

### **Maintenance:**
- [ ] Regular security updates
- [ ] Performance monitoring
- [ ] Database optimization
- [ ] User training updates

---

**🎯 CONCLUSION:**

Checklist ini mencakup **SEMUA aspek** yang perlu dicek untuk memastikan sistem multi-cabang berjalan dengan aman dan benar. Gunakan sebagai panduan untuk:

- ✅ **Development**: Pastikan fitur baru mengikuti pola yang benar
- ✅ **Testing**: Validasi semua fungsi sebelum deploy
- ✅ **Debugging**: Troubleshooting masalah multi-branch
- ✅ **Maintenance**: Regular health check sistem
- ✅ **Audit**: Compliance dan security validation

**Total Checklist Items: 150+ points validation**

**Status: PRODUCTION READY untuk multi-branch operations** 🚀