# 🚨 ANALISIS RISIKO DATA CABANG TERCAMPUR

## ⚠️ **STATUS KRITIS: HIGH RISK**

Berdasarkan analisis mendalam terhadap kode sistem Akuntansi Sibuku, **ditemukan risiko KRITIS data cabang tercampur** yang dapat menyebabkan:

- ❌ Stok produk tercampur antar cabang
- ❌ Transaksi keuangan tercampur
- ❌ Laporan keuangan tidak akurat
- ❌ Data pelanggan tercampur
- ❌ Journal entries tercampur

---

## 📊 **ANALISIS DETAIL RISIKO**

### **1. Tabel yang SUDAH Punya branch_id ✅**

| Tabel | Status | Keterangan |
|-------|--------|------------|
| `transactions` | ✅ Ada (nullable) | Sudah ada branch_id |
| `accounts` | ✅ Ada (nullable) | Rekening kas per cabang |
| `categories` | ✅ Ada (nullable) | Kategori per cabang |
| `transfers` | ✅ Ada (nullable) | Transfer per cabang |
| `recurring_templates` | ✅ Ada (nullable) | Template per cabang |

### **2. Tabel yang MISSING branch_id ❌**

| Tabel | Risiko | Dampak |
|-------|--------|--------|
| `products` | **KRITIS** | Stok tercampur antar cabang |
| `customers` | **TINGGI** | Data pelanggan tercampur |
| `stock_movements` | **KRITIS** | Riwayat stok tercampur |
| `product_categories` | **SEDANG** | Kategori produk tercampur |
| `services` | **SEDANG** | Layanan tercampur |
| `tax_settings` | **TINGGI** | Pajak tercampur |
| `journal_entries` | **KRITIS** | Jurnal umum tercampur |
| `journal_lines` | **KRITIS** | Baris jurnal tercampur |
| `chart_of_accounts` | **TINGGI** | Bagan akun tercampur |
| `invoices` | **KRITIS** | Invoice tercampur |
| `payments` | **KRITIS** | Pembayaran tercampur |

### **3. Masalah Implementasi Kode**

#### **A. Tidak Ada Global Scope ❌**
```php
// AppServiceProvider.php - TIDAK ADA global scope
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Tidak ada global scope untuk branch isolation
    }
}
```

#### **B. Controller Tidak Branch-Aware ❌**
```php
// IncomeController.php - Hanya user-scoped, bukan branch-scoped
public function index()
{
    $incomes = Auth::user()->transactions()  // ❌ Tidak filter branch
        ->where('type', 'income')
        ->get();
}
```

#### **C. Model Tidak Punya Branch Relationship Lengkap ❌**
```php
// Product.php - Tidak ada branch relationship
class Product extends Model
{
    protected $fillable = ['user_id', 'product_category_id', 'name', ...];
    // ❌ Tidak ada branch_id di fillable
    // ❌ Tidak ada branch relationship
}
```

#### **D. Tidak Ada Middleware Branch Access ❌**
```php
// Tidak ada middleware untuk validasi akses branch
// User bisa akses data branch lain jika punya permission
```

---

## 🔧 **REKOMENDASI PERBAIKAN**

### **1. Tambahkan branch_id ke Tabel Missing**

#### **Migration untuk Products:**
```php
Schema::table('products', function (Blueprint $table) {
    $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
    $table->index(['branch_id', 'sku']);
});
```

#### **Migration untuk Stock Movements:**
```php
Schema::table('stock_movements', function (Blueprint $table) {
    $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
    $table->index(['branch_id', 'product_id', 'date']);
});
```

#### **Migration untuk Journal Entries:**
```php
Schema::table('journal_entries', function (Blueprint $table) {
    $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
    $table->index(['branch_id', 'date']);
});
```

### **2. Implementasi Global Scope**

#### **Buat BranchScope:**
```php
<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Jika user adalah super admin dan tidak ada active branch, skip scope
        if (auth()->check() &&
            auth()->user()->userRole?->name === 'super_admin' &&
            !session('active_branch')) {
            return;
        }

        // Jika ada active branch, filter berdasarkan branch tersebut
        if (session('active_branch')) {
            $builder->where('branch_id', session('active_branch'));
        }
        // Jika user punya branch default, filter berdasarkan branch user
        elseif (auth()->check() && auth()->user()->branch_id) {
            $builder->where('branch_id', auth()->user()->branch_id);
        }
    }
}
```

#### **Apply ke Model:**
```php
// Di AppServiceProvider.php
use App\Scopes\BranchScope;

public function boot(): void
{
    // Apply branch scope ke model kritis
    Transaction::addGlobalScope(new BranchScope());
    Account::addGlobalScope(new BranchScope());
    Product::addGlobalScope(new BranchScope());
    Customer::addGlobalScope(new BranchScope());
    StockMovement::addGlobalScope(new BranchScope());
    JournalEntry::addGlobalScope(new BranchScope());
}
```

### **3. Update Model Relationships**

#### **Update Product Model:**
```php
class Product extends Model
{
    protected $fillable = [
        'user_id', 'branch_id', 'product_category_id',
        'name', 'sku', 'price', 'cost_price',
        'stock_quantity', 'unit'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Global scope
    protected static function booted()
    {
        static::addGlobalScope(new BranchScope());
    }
}
```

### **4. Buat Middleware Branch Access**

#### **BranchAccessMiddleware:**
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BranchAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Super admin bisa akses semua
        if ($user->userRole?->name === 'super_admin') {
            return $next($request);
        }

        // Cek apakah user punya akses ke branch yang diminta
        $requestedBranchId = $request->route('branch') ??
                           $request->input('branch_id') ??
                           session('active_branch');

        if ($requestedBranchId) {
            $hasAccess = $user->branches()->where('branches.id', $requestedBranchId)->exists();

            if (!$hasAccess) {
                abort(403, 'Anda tidak memiliki akses ke cabang ini.');
            }
        }

        return $next($request);
    }
}
```

#### **Register Middleware:**
```php
// Di bootstrap/app.php atau Kernel.php
protected $middlewareAliases = [
    'branch.access' => \App\Http\Middleware\BranchAccessMiddleware::class,
];
```

### **5. Update Controller Logic**

#### **Update IncomeController:**
```php
public function index()
{
    $incomes = Transaction::where('type', 'income')
        ->where('user_id', auth()->id())
        ->when(session('active_branch'), function($query) {
            return $query->where('branch_id', session('active_branch'));
        })
        ->with(['account', 'category'])
        ->orderBy('date', 'desc')
        ->get();

    return view('incomes.index', compact('incomes'));
}
```

### **6. Update Form Input**

#### **Tambahkan Branch Selection di Form:**
```php
// Di create/edit form
@if(auth()->user()->userRole?->name === 'super_admin' || auth()->user()->branches->count() > 1)
<div class="form-group">
    <label>Cabang</label>
    <select name="branch_id" required>
        @foreach(auth()->user()->branches as $branch)
            <option value="{{ $branch->id }}" {{ old('branch_id', $model->branch_id ?? session('active_branch')) == $branch->id ? 'selected' : '' }}>
                {{ $branch->name }}
            </option>
        @endforeach
    </select>
</div>
@endif
```

### **7. Update Validation Rules**

#### **Tambahkan Branch Validation:**
```php
$validator = Validator::make($request->all(), [
    'branch_id' => 'required|exists:branches,id',
    // Validasi user punya akses ke branch
    'branch_id' => [
        'required',
        'exists:branches,id',
        function ($attribute, $value, $fail) {
            $user = auth()->user();
            if ($user->userRole?->name !== 'super_admin') {
                $hasAccess = $user->branches()->where('branches.id', $value)->exists();
                if (!$hasAccess) {
                    $fail('Anda tidak memiliki akses ke cabang ini.');
                }
            }
        },
    ],
]);
```

### **8. Update Report Controllers**

#### **Branch-Aware Reports:**
```php
public function monthly()
{
    $branchId = session('active_branch') ?? auth()->user()->branch_id;

    $report = Transaction::where('branch_id', $branchId)
        ->whereBetween('date', [$startDate, $endDate])
        ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(amount) as total')
        ->groupBy('month')
        ->get();

    return view('reports.monthly', compact('report'));
}
```

### **9. Migration Strategy**

#### **Safe Migration Approach:**
```php
// 1. Tambahkan kolom nullable dulu
Schema::table('products', function (Blueprint $table) {
    $table->foreignId('branch_id')->nullable()->after('user_id');
});

// 2. Populate data existing dengan branch user default
DB::statement("
    UPDATE products
    SET branch_id = (
        SELECT branch_id FROM users WHERE users.id = products.user_id
    )
    WHERE branch_id IS NULL
");

// 3. Make kolom required setelah data populated
Schema::table('products', function (Blueprint $table) {
    $table->foreignId('branch_id')->nullable(false)->change();
});
```

### **10. Testing Strategy**

#### **Unit Tests untuk Branch Isolation:**
```php
public function test_user_cannot_access_other_branch_data()
{
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $branch1 = Branch::factory()->create();
    $branch2 = Branch::factory()->create();

    $product1 = Product::factory()->create([
        'user_id' => $user1->id,
        'branch_id' => $branch1->id
    ]);

    $product2 = Product::factory()->create([
        'user_id' => $user2->id,
        'branch_id' => $branch2->id
    ]);

    // User 1 login
    $this->actingAs($user1);

    // User 1 hanya bisa lihat product branch sendiri
    $response = $this->get('/products');
    $response->assertSee($product1->name);
    $response->assertDontSee($product2->name);
}
```

---

## 🎯 **PRIORITAS PERBAIKAN**

### **Phase 1: Critical Fixes (1-2 hari)**
1. ✅ Tambahkan branch_id ke tabel products, stock_movements, journal_entries
2. ✅ Implementasi BranchScope global
3. ✅ Update model relationships
4. ✅ Buat BranchAccessMiddleware

### **Phase 2: Controller Updates (2-3 hari)**
1. ✅ Update semua controller untuk branch-aware
2. ✅ Update form validation
3. ✅ Update report queries
4. ✅ Test branch isolation

### **Phase 3: UI/UX Updates (1 hari)**
1. ✅ Tambahkan branch selector di forms
2. ✅ Update navigation untuk multi-branch
3. ✅ Branch switcher functionality

### **Phase 4: Testing & Validation (1 hari)**
1. ✅ Comprehensive testing
2. ✅ Data migration validation
3. ✅ Performance testing

---

## 🚨 **DAMPAK JIKA TIDAK DIPERBAIKI**

### **Risiko Bisnis:**
- ❌ **Data Corruption**: Stok dan keuangan tercampur
- ❌ **Audit Failure**: Tidak bisa audit per cabang
- ❌ **Legal Issues**: Pelanggaran compliance multi-branch
- ❌ **Financial Loss**: Kesalahan laporan keuangan
- ❌ **Customer Complaints**: Data pelanggan tercampur

### **Risiko Teknis:**
- ❌ **System Instability**: Query tidak predictable
- ❌ **Performance Issues**: Query tanpa proper indexing
- ❌ **Security Breach**: Data leakage antar cabang
- ❌ **Maintenance Nightmare**: Debugging sulit

---

## ✅ **KESIMPULAN**

Sistem Akuntansi Sibuku memiliki **fondasi multi-branch yang baik** namun **implementasi branch isolation sangat KRITIS missing**. 

**Status Saat Ini: HIGH RISK** - Data bisa tercampur antar cabang.

**Rekomendasi: SEGERA implementasi perbaikan branch isolation** sebelum sistem digunakan di production dengan multiple branches.

**Estimated Effort: 5-7 hari development + testing**

**Business Impact: CRITICAL** - Menentukan kesuksesan multi-branch operations.