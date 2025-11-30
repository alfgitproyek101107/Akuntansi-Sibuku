# 🔄 **REFATORING SISTEM PAJAK - FOKUS INTEGRASI PRODUK & TRANSAKSI**

## 📋 **Masalah Sistem Pajak Saat Ini**

Sistem pajak yang ada **terlalu kompleks** dan **salah fokus**:

❌ **Fokus saat ini:** e-Faktur DJP, CoreTax API, nomor faktur DJP
❌ **Kompleksitas:** TaxInvoice model, TaxController, CoreTax integration
❌ **Tidak praktis:** Untuk UMKM yang butuh simpel

✅ **Fokus yang benar:** Integrasi pajak di level produk & transaksi
✅ **Simple & Praktis:** Seperti Tokopedia, Moka, Jurnal, BukuKas
✅ **Legal & Otomatis:** Tetap patuh pajak tapi mudah digunakan

---

## 🎯 **Arsitektur Sistem Pajak yang Benar**

### **1. Produk sebagai "Tax Entity"**

Setiap produk harus punya **aturan pajak sendiri**:

```php
// Model Product - tambahan field
class Product extends Model {
    protected $fillable = [
        // ... field existing
        'tax_type',           // 'ppn', 'ppn_umkm', 'pph23', 'final_umkm', 'none'
        'tax_rate',           // decimal (11.00 untuk PPN)
        'include_tax',        // boolean - harga sudah include pajak?
        'is_taxable',         // boolean - kena pajak?
        'auto_calculate_tax', // boolean - hitung otomatis?
    ];
}
```

### **2. Transaction Items sebagai "Tax Calculator"**

Pajak dihitung **per item**, bukan per transaksi:

```php
// Model TransactionItem - tambahan field
class TransactionItem extends Model {
    protected $fillable = [
        // ... field existing
        'tax_type',             // copy dari produk
        'tax_rate',             // copy dari produk
        'tax_amount',           // hasil perhitungan
        'subtotal_before_tax',  // harga sebelum pajak
        'subtotal_after_tax',   // harga setelah pajak
    ];
}
```

### **3. Transaksi sebagai "Tax Aggregator"**

Transaksi hanya **menjumlahkan** pajak dari item:

```php
// Model Transaction - tambahan field
class Transaction extends Model {
    protected $fillable = [
        // ... field existing
        'total_tax_amount',     // sum dari semua item
        'tax_summary',          // json summary per jenis pajak
    ];
}
```

---

## 🔧 **Implementasi Step-by-Step**

### **Step 1: Database Migration**

```php
// 1. Tambah field ke products table
Schema::table('products', function (Blueprint $table) {
    $table->enum('tax_type', ['ppn', 'ppn_umkm', 'pph23', 'final_umkm', 'none'])->default('none');
    $table->decimal('tax_rate', 5, 2)->default(0);
    $table->boolean('include_tax')->default(false);
    $table->boolean('is_taxable')->default(false);
    $table->boolean('auto_calculate_tax')->default(true);
});

// 2. Tambah field ke transaction_items table
Schema::table('transaction_items', function (Blueprint $table) {
    $table->enum('tax_type', ['ppn', 'ppn_umkm', 'pph23', 'final_umkm', 'none'])->default('none');
    $table->decimal('tax_rate', 5, 2)->default(0);
    $table->decimal('tax_amount', 15, 2)->default(0);
    $table->decimal('subtotal_before_tax', 15, 2)->default(0);
    $table->decimal('subtotal_after_tax', 15, 2)->default(0);
});

// 3. Tambah field ke transactions table
Schema::table('transactions', function (Blueprint $table) {
    $table->decimal('total_tax_amount', 15, 2)->default(0);
    $table->json('tax_summary')->nullable();
});
```

### **Step 2: Tax Calculator Service**

```php
class TaxCalculatorService
{
    public function calculateItemTax(TransactionItem $item, Product $product = null)
    {
        // Copy tax settings dari produk
        $item->tax_type = $product->tax_type ?? 'none';
        $item->tax_rate = $product->tax_rate ?? 0;
        $item->is_taxable = $product->is_taxable ?? false;

        if (!$item->is_taxable || $item->tax_type === 'none') {
            $item->tax_amount = 0;
            $item->subtotal_before_tax = $item->subtotal;
            $item->subtotal_after_tax = $item->subtotal;
            return $item;
        }

        $quantity = $item->quantity ?? 1;
        $unitPrice = $item->unit_price ?? $item->subtotal / $quantity;
        $subtotal = $unitPrice * $quantity;

        if ($product->include_tax) {
            // Reverse tax calculation
            $taxRate = 1 + ($item->tax_rate / 100);
            $beforeTax = $subtotal / $taxRate;
            $taxAmount = $subtotal - $beforeTax;

            $item->subtotal_before_tax = $beforeTax;
            $item->subtotal_after_tax = $subtotal;
            $item->tax_amount = $taxAmount;
        } else {
            // Forward tax calculation
            $taxAmount = $subtotal * ($item->tax_rate / 100);

            $item->subtotal_before_tax = $subtotal;
            $item->subtotal_after_tax = $subtotal + $taxAmount;
            $item->tax_amount = $taxAmount;
        }

        return $item;
    }

    public function calculateTransactionTax(Transaction $transaction)
    {
        $totalTax = $transaction->items->sum('tax_amount');
        $taxSummary = $transaction->items
            ->groupBy('tax_type')
            ->map(function ($items) {
                return [
                    'total_amount' => $items->sum('tax_amount'),
                    'rate' => $items->first()->tax_rate,
                    'count' => $items->count()
                ];
            });

        $transaction->update([
            'total_tax_amount' => $totalTax,
            'tax_summary' => $taxSummary
        ]);

        return $transaction;
    }
}
```

### **Step 3: Update Transaction Controllers**

```php
class IncomeController extends Controller
{
    public function store(Request $request)
    {
        $items = $request->items; // array of items

        DB::transaction(function () use ($request, $items) {
            // Create transaction
            $transaction = Transaction::create([...]);

            $taxCalculator = new TaxCalculatorService();

            foreach ($items as $itemData) {
                $product = Product::find($itemData['product_id']);

                $item = TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal' => $itemData['quantity'] * $itemData['unit_price'],
                ]);

                // Calculate tax
                $taxCalculator->calculateItemTax($item, $product);
                $item->save();
            }

            // Calculate total tax
            $taxCalculator->calculateTransactionTax($transaction);
        });
    }
}
```

### **Step 4: Tax Reports Module**

```php
class TaxReportController extends Controller
{
    public function ppnOutput(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $branchId = session('active_branch');

        $ppnItems = TransactionItem::whereHas('transaction', function ($q) use ($startDate, $endDate, $branchId) {
            $q->whereBetween('date', [$startDate, $endDate])
              ->where('branch_id', $branchId)
              ->where('type', 'income');
        })
        ->where('tax_type', 'ppn')
        ->with(['transaction', 'product'])
        ->get();

        $totalTax = $ppnItems->sum('tax_amount');
        $totalBeforeTax = $ppnItems->sum('subtotal_before_tax');

        return view('reports.tax.ppn-output', compact('ppnItems', 'totalTax', 'totalBeforeTax'));
    }

    public function umkmFinal(Request $request)
    {
        // Hitung omzet + pajak final 0.5%
        $omzet = Transaction::where('branch_id', session('active_branch'))
            ->whereBetween('date', [$request->start_date, $request->end_date])
            ->where('type', 'income')
            ->sum('amount');

        $taxAmount = $omzet * 0.005; // 0.5%

        return view('reports.tax.umkm-final', compact('omzet', 'taxAmount'));
    }
}
```

---

## 📊 **Jenis Laporan Pajak**

### **1. PPN Keluaran (Output)**
- Ambil semua `transaction_items` dengan `tax_type = 'ppn'`
- Filter transaksi income dalam periode
- Sum `tax_amount` dan `subtotal_before_tax`

### **2. PPN Masukan (Input)**
- Ambil `transaction_items` dengan `tax_type = 'ppn'`
- Filter transaksi expense dalam periode

### **3. PPh 23**
- Ambil items dengan `tax_type = 'pph23'`
- Biasanya untuk transaksi expense (pemotongan)

### **4. UMKM Final 0.5%**
- Hitung total omzet dalam periode
- Pajak = 0.5% × omzet

### **5. Rekap Pajak Bulanan**
- Summary semua jenis pajak per bulan
- Breakdown per cabang

---

## 🔄 **Update Jurnal Otomatis**

### **Penjualan dengan PPN (exclude tax):**
```
Debit: Kas/Bank          | 111.000
Kredit: Penjualan        | 100.000
Kredit: PPN Keluaran     | 11.000
```

### **Penjualan dengan PPN (include tax):**
```
Debit: Kas/Bank          | 100.000
Kredit: Penjualan        | 90.091
Kredit: PPN Keluaran     | 9.909
```

### **Pembelian dengan PPh 23:**
```
Debit: Biaya             | 100.000
Debit: PPh 23            | 2.000
Kredit: Kas/Bank         | 98.000
```

---

## ✅ **Validasi Penting**

```php
// Di Product model
public function save(array $options = [])
{
    if ($this->is_taxable && empty($this->tax_rate)) {
        throw new \Exception('Produk taxable harus punya tax_rate');
    }

    if ($this->include_tax && $this->tax_type === 'none') {
        throw new \Exception('Include tax tidak boleh tanpa tax_type');
    }

    if ($this->tax_type === 'pph23' && $this->include_tax) {
        throw new \Exception('PPh 23 tidak boleh include tax');
    }

    return parent::save($options);
}
```

---

## 🎯 **Prompt Lengkap untuk Agent Kilo**

```
Perbaikan Sistem Pajak - Fokus Produk & Transaksi

Tolong refactor sistem pajak pada aplikasi Akuntansi Sibuku dengan fokus utama pada integrasi pajak di level produk dan transaction items. JANGAN buat sistem e-Faktur atau integrasi CoreTax.

Tujuan: Buat sistem pajak yang praktis untuk UMKM seperti Tokopedia/Moka/Jurnal.

Yang perlu diperbaiki:

1. Tambah field pajak di tabel products:
   - tax_type (enum: ppn, ppn_umkm, pph23, final_umkm, none)
   - tax_rate (decimal)
   - include_tax (boolean)
   - is_taxable (boolean)
   - auto_calculate_tax (boolean)

2. Tambah field pajak di tabel transaction_items:
   - tax_type, tax_rate, tax_amount
   - subtotal_before_tax, subtotal_after_tax

3. Buat TaxCalculatorService untuk:
   - Hitung pajak per item otomatis
   - Support include tax (reverse calculation)
   - Support exclude tax (forward calculation)
   - Support PPh (potong)

4. Update semua transaction controllers (income, expense, invoice):
   - Auto load tax dari produk
   - Hitung pajak saat create transaksi
   - Support multi-item dengan tax berbeda

5. Buat modul laporan pajak:
   - PPN Keluaran (dari transaction_items tax_type=ppn)
   - PPN Masukan (jika perlu)
   - PPh 23 (dari items pph23)
   - UMKM Final 0.5% (dari omzet)
   - Filter per cabang & periode

6. Update jurnal otomatis untuk tax accounts

7. Pastikan branch isolation untuk tax settings

8. Validasi: taxable products harus punya rate, PPh tidak boleh include tax

Hapus semua kode yang berkaitan dengan TaxInvoice, TaxController, dan CoreTax integration.
```

---

## 📈 **Manfaat Sistem Baru**

✅ **Simple:** Tidak perlu setup CoreTax/API
✅ **Otomatis:** Pajak hitung sendiri saat transaksi
✅ **Akurat:** Per-item calculation
✅ **Legal:** Tetap patuh pajak untuk pembukuan
✅ **Scalable:** Support multi-branch & multi-produk
✅ **Praktis:** Seperti aplikasi UMKM populer

---

## 🚀 **Next Steps**

1. **Backup** kode tax yang ada
2. **Drop** tabel TaxInvoice, TaxLog, TaxSetting (jika tidak perlu)
3. **Migrate** database dengan field baru
4. **Refactor** models dan controllers
5. **Test** dengan berbagai skenario pajak
6. **Deploy** dan monitor

Sistem pajak yang benar adalah **simple, integrated, automatic** - bukan complex e-Faktur system.