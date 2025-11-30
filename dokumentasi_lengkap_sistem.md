# Dokumentasi Lengkap Sistem Akuntansi Sibuku

## 📋 **Sistem Overview**

Sistem Akuntansi Sibuku adalah aplikasi web berbasis Laravel yang dirancang untuk mengelola keuangan pribadi dan bisnis dengan fitur lengkap untuk pencatatan transaksi, manajemen rekening, kategori, produk, dan pelaporan keuangan.

### **Teknologi Utama:**
- **Framework**: Laravel 11.x
- **Database**: SQLite (dapat dikonfigurasi ke MySQL/PostgreSQL)
- **Frontend**: Blade Templates dengan Tailwind CSS
- **JavaScript**: Vanilla JS dengan Chart.js untuk grafik
- **Authentication**: Laravel Sanctum/Breeze

---

## ⚠️ **1. KEKURANGAN SISTEM & SOLUSI (Detail + Dampak)**

### **1.1 Form Keuangan Terlalu Kompleks untuk Pemula**
- **Masalah**: Banyak field tidak perlu; validasi/flow membingungkan
- **Dampak**: Pengguna non-akuntan tidak pakai fitur penuh, banyak entri salah
- **Solusi**: Mode *Simple* vs *Advanced*, smart defaults, inline help, prefilled categories, quick-create buttons

### **1.2 Tidak Ada Kategori Expense Custom yang Fleksibel**
- **Masalah**: Kategori statis; sulit untuk pencatatan granular
- **Dampak**: Laporan kurang akurat; sulit filter
- **Solusi**: CRUD kategori per user/company + tagging kategori (hierarki & reusable templates)

### **1.3 Tidak Ada Fitur Tagging Transaksi**
- **Masalah**: Kategori saja kaku, tidak mendukung multi-tag (project, campaign, client)
- **Dampak**: Sulit analisis multi-dimensi (mis. pengeluaran per campaign)
- **Solusi**: Model many-to-many `tags` dan endpoint filter by tag

### **1.4 Laporan Kurang Visual (Tidak Ada Grafik)**
- **Masalah**: Hanya tabel/angka
- **Dampak**: User sulit lihat tren cepat
- **Solusi**: Backend sediakan aggregates + time-series endpoints untuk Chart.js/echarts pada frontend

### **1.5 Dashboard Kurang Informatif**
- **Masalah**: Ringkasan minim, tak ada KPI/alert
- **Dampak**: Pengguna tidak segera terinformasi kondisi kritis (negative cashflow)
- **Solusi**: KPI (cashflow7d, burn rate, top expense), alert engine (notifications)

### **1.6 Tidak Ada Integrasi WhatsApp untuk Reminder Pembayaran**
- **Masalah**: Tak ada channel pengingat/penagihan otomatis
- **Dampak**: Penagihan manual; kehilangan piutang
- **Solusi**: Integrasi WhatsApp via provider (Twilio / WABA / gateway lokal) + webhook + template message

### **1.7 Tidak Ada OCR Struk Otomatis**
- **Masalah**: Input manual struk/nota
- **Dampak**: Repot, salah input
- **Solusi**: Integrasi OCR (Google Vision / Tesseract / paid OCR API) + auto parse total/tanggal/vendor → draft transaksi

### **1.8 UI/UX Kurang Konsisten**
- **Masalah**: UI tidak konsisten antar modul
- **Dampak**: Kebingungan user; adopsi berkurang
- **Solusi**: Definisi design system; backend sediakan endpoints yang konsisten, response uniform

### **1.9 Tidak Ada Role Permission Detail**
- **Masalah**: Roles sangat umum (admin/user)
- **Dampak**: Security & compliance lemah
- **Solusi**: Pakai Spatie Permission; granular permissions (account.create, transaction.approve, report.view.branch)

### **1.10 Tidak Ada Multi-Cabang dengan Hak Akses**
- **Masalah**: Tidak mendukung branch isolation
- **Dampak**: Perusahaan multi-cabang susah manajemen
- **Solusi**: Branch model + scoping queries per branch + role assignment per branch

### **1.11 Tidak Ada Stok Manajemen Terhubung ke Transaksi**
- **Masalah**: Transaksi tidak memengaruhi stok
- **Dampak**: Stok tidak sinkron dengan transaksi penjualan/pembelian
- **Solusi**: Link product_id pada transaksi; mekanisme stock movements (+ reverse on edit/delete)

### **1.12 Tidak Ada Notifikasi Otomatis ketika Cashflow Negatif**
- **Masalah**: User tidak tahu jika saldo turun kritis
- **Dampak**: Downtime operasional
- **Solusi**: Rule-based alerts: threshold per account/company + send email/wa/in-app

### **1.13 Tidak Ada Integrasi Export Profesional**
- **Masalah**: Export ad hoc; format tidak auditor-friendly
- **Dampak**: Akuntan/investor kesulitan
- **Solusi**: Export via Laravel-Excel, PDF/A via DomPDF / Snappy. Templates standar (trial balance, ledger, P&L, balance sheet) sesuai format akuntan

---

## ➕ **2. MENU & FITUR YANG AKAN DITAMBAHKAN (Spesifik)**

### **Menu Utama yang Akan Ditambahkan/Diimprove:**
- **Dashboard** (KPI, Alerts, Charts)
- **Accounts (Rekening)** — ledger, reconciliation, alerts
- **Transactions (Income/Expense)** — Simple/Advanced mode, tags, attachments (receipt OCR)
- **Transfers** (double-entry)
- **Recurring Templates** (scheduler / artisan command)
- **Products & Services** — inventory, price, stock thresholds
- **Stock Movements** — history, adjustments, receipts
- **Customers & Suppliers** — contacts + aging
- **Invoicing & Receivables** — invoice generation, WA reminders
- **Purchases & Bills** — supplier invoices, PO link (opsional)
- **Reports** — Financial (P&L, Balance Sheet, Cashflow), Sales, Inventory, Branch reports
- **Users & Roles** — Spatie permission, branch assignments
- **Exports** — PDF/A, Excel templates
- **Integrations** — OCR, WhatsApp, Payment Gateway (opsional), Accounting export (CSV)
- **Admin / Settings** — Tax, Branch, Company profile, SMTP

---

## 🔄 **3. ALUR FLOW (Use Cases Utama)**

### **3.1 Create Income (Simple Mode)**
1. User pilih `Create Income` → form sederhana (account, category, amount, date, optional tags, attach receipt)
2. Backend: validate, create `transactions` row (type = income), update account balance (`accounts.balance += amount`)
3. If receipt attached → push to OCR queue → parse candidate → suggest parsing to user
4. Emit event `TransactionCreated` → listeners: `UpdateDashboardMetrics` & `NotifyIfThresholdBreached`

### **3.2 Create Expense (with Product)**
1. User pilih `Expense` → pilih product(s) → qty → backend creates transaction and decreases product stock via `stock_movements`
2. Create double entry if needed (debit expense account, kredit cash/bank)
3. If stock below min_stock → create notification

### **3.3 Transfer between Accounts (Double Entry)**
1. Create Transfer → backend creates two transactions (debit target, credit source) or one transfer record with linked ledger entries
2. Update both accounts balances atomically in DB transaction

### **3.4 Recurring Template Processing**
- Cron runs `php artisan recurring:process` → queue job creates transactions for templates with `next_date` → update `next_date` based on frequency → logs

### **3.5 Invoice & WhatsApp Reminder**
1. Create Invoice → status draft / sent / paid
2. When due_date approaching → `DueReminderJob` sends WA/in-app/email reminder using message templates
3. Payment linked to invoice will mark invoice paid and create income transactions

### **3.6 OCR Receipt Flow**
1. Upload receipt image → store file → dispatch OCR job
2. OCR returns structured data → create draft transaction for user confirmation → user confirm/save

---

## 🛠️ **4. LARAVEL BACKEND — IMPLEMENTATION DETAIL**

### **4.1 Rekomendasi Package Utama**
- `spatie/laravel-permission` — roles & permissions
- `maatwebsite/excel` — Laravel Excel export
- `barryvdh/laravel-dompdf` atau `knplabs/knp-snappy` — PDF export
- `league/flysystem` (already in Laravel) — file storage
- `laravel/sanctum` — API authentication (mobile/web SPA)
- `spatie/laravel-activitylog` — optional audit trail
- Queue drivers: redis / database
- For OCR: integrate external API (Google Vision / OCR.space / Tesseract wrapper)
- For WhatsApp: Twilio or Business API gateway (or local gateway)

---

## 🏠 **1. DASHBOARD**

### **Fitur Utama:**
- **Ringkasan Keuangan**: Total saldo, pemasukan bulan ini, pengeluaran bulan ini
- **Grafik Tren**: Visualisasi pemasukan vs pengeluaran bulanan
- **Transaksi Terbaru**: 5-10 transaksi terakhir
- **Rekening Overview**: Saldo semua rekening aktif
- **Quick Actions**: Tombol cepat untuk transaksi baru

### **Flow Pengguna:**
1. Login → Dashboard (halaman utama)
2. Melihat ringkasan keuangan
3. Mengakses menu cepat atau navigasi

---

## 💰 **2. MANAJEMEN REKENING (Accounts)**

### **Fitur Lengkap:**

#### **2.1 Index (Daftar Rekening)**
- **Tabel Rekening**: Nama, tipe, saldo, status aktif
- **Filter & Search**: Cari berdasarkan nama atau tipe
- **Aksi**: Lihat detail, edit, hapus, rekonsiliasi
- **Statistik**: Total rekening, total saldo

#### **2.2 Create (Tambah Rekening)**
- **Form Input**:
  - Nama rekening (wajib, unik per user)
  - Tipe rekening (Kas, Bank, E-wallet, dll)
  - Saldo awal (opsional)
  - Deskripsi (opsional)
  - Cabang (jika multi-branch)
- **Validasi**: Nama unik, saldo numerik positif

#### **2.3 Show (Detail Rekening)**
- **Informasi Rekening**: Detail lengkap rekening
- **Riwayat Transaksi**: Semua transaksi di rekening ini
- **Saldo Saat Ini**: Update real-time
- **Ledger/Buku Besar**: Detail debit-kredit

#### **2.4 Edit (Ubah Rekening)**
- **Form Edit**: Sama dengan create, tapi untuk update
- **Validasi Unik**: Mengecualikan rekening sendiri
- **Update Saldo**: Otomatis update semua transaksi terkait

#### **2.5 Ledger (Buku Besar)**
- **Filter Periode**: Bulan/tahun tertentu
- **Export**: PDF/Excel laporan buku besar
- **Pencarian**: Cari transaksi spesifik

#### **2.6 Rekonsiliasi**
- **Toggle Status**: Tandai rekening sudah direkonsiliasi
- **Riwayat Rekonsiliasi**: Track perubahan status

### **Flow Pengguna:**
```
Dashboard → Accounts → Create (tambah rekening baru)
                    → Show (lihat detail rekening)
                    → Edit (ubah rekening)
                    → Ledger (lihat buku besar)
                    → Reconcile (rekonsiliasi)
```

---

## 📊 **3. KATEGORI TRANSAKSI (Categories)**

### **Fitur Lengkap:**

#### **3.1 Index (Daftar Kategori)**
- **Grid/List View**: Kategori berdasarkan tipe (Income/Expense)
- **Filter**: Berdasarkan tipe transaksi
- **Statistik**: Jumlah kategori per tipe

#### **3.2 CRUD Operations**
- **Create**: Tambah kategori baru dengan tipe
- **Show**: Detail kategori dengan transaksi terkait
- **Edit**: Update nama dan deskripsi kategori
- **Delete**: Hapus kategori (dengan validasi)

### **Flow Pengguna:**
```
Dashboard → Categories → Create (tambah kategori)
                      → Show (lihat transaksi dalam kategori)
                      → Edit (ubah kategori)
```

---

## 💸 **4. PEMASUKAN (Income)**

### **Fitur Lengkap:**

#### **4.1 Index (Daftar Pemasukan)**
- **Tabel Transaksi**: Tanggal, rekening, kategori, jumlah, deskripsi
- **Filter**: Berdasarkan tanggal, rekening, kategori
- **Total**: Sum pemasukan dalam periode
- **Export**: CSV/PDF laporan pemasukan

#### **4.2 Create (Tambah Pemasukan)**
- **Form Input**:
  - Rekening tujuan (wajib)
  - Kategori pemasukan (wajib)
  - Jumlah (wajib, > 0)
  - Deskripsi (opsional)
  - Tanggal (wajib, default hari ini)
  - Produk terkait (opsional, untuk inventory)
- **Validasi**: Semua field wajib terisi
- **Auto-update**: Saldo rekening otomatis bertambah

#### **4.3 Show (Detail Pemasukan)**
- **Informasi Lengkap**: Semua detail transaksi
- **Riwayat**: Track perubahan
- **Aksi**: Edit atau hapus

#### **4.4 Edit/Update**
- **Form Edit**: Sama dengan create
- **Validasi**: Cek saldo rekening jika rekening berubah
- **Revert & Update**: Otomatis update saldo

#### **4.5 Delete**
- **Konfirmasi**: Pop-up konfirmasi hapus
- **Revert Saldo**: Otomatis kurangi saldo rekening

### **Flow Pengguna:**
```
Dashboard → Income → Create (catat pemasukan)
                 → Show (lihat detail)
                 → Edit (ubah pemasukan)
                 → Delete (hapus pemasukan)
```

---

## 💳 **5. PENGELUARAN (Expense)**

### **Fitur Identik dengan Income:**
- **Index**: Daftar pengeluaran dengan filter
- **Create**: Form pengeluaran dengan produk inventory
- **Show**: Detail pengeluaran
- **Edit**: Update pengeluaran
- **Delete**: Hapus dengan revert saldo

### **Fitur Khusus Expense:**
- **Stock Management**: Otomatis kurangi stok produk
- **Balance Check**: Validasi saldo rekening cukup
- **Product Integration**: Link dengan inventory system

---

## 🔄 **6. TRANSFER ANTAR REKENING (Transfers)**

### **Fitur Lengkap:**

#### **6.1 Index (Daftar Transfer)**
- **Tabel Transfer**: Dari rekening, ke rekening, jumlah, tanggal
- **Filter**: Berdasarkan rekening atau periode
- **Statistik**: Total transfer, rata-rata transfer

#### **6.2 Create (Buat Transfer)**
- **Form Input**:
  - Rekening asal (wajib, punya saldo)
  - Rekening tujuan (wajib, beda dari asal)
  - Jumlah transfer (wajib, <= saldo asal)
  - Deskripsi (opsional)
  - Tanggal (wajib)
- **Validasi**: Rekening berbeda, saldo cukup
- **Double Entry**: Buat 2 transaksi (debit asal, kredit tujuan)

#### **6.3 Show/Edit/Delete**
- **Detail Transfer**: Informasi lengkap
- **Edit**: Update transfer dengan revert & re-apply
- **Delete**: Hapus dengan revert saldo

### **Flow Pengguna:**
```
Dashboard → Transfers → Create (transfer antar rekening)
                    → Show (lihat detail transfer)
                    → Edit (ubah transfer)
```

---

## 🔄 **7. TEMPLATE BERULANG (Recurring Templates)**

### **Fitur Lengkap:**

#### **7.1 Index (Daftar Template)**
- **Tabel Template**: Nama, frekuensi, jumlah, status
- **Filter**: Aktif/non-aktif, tipe transaksi
- **Auto-Process**: Cron job untuk eksekusi otomatis

#### **7.2 CRUD Operations**
- **Create**: Template transaksi berulang
- **Show**: Detail template dengan riwayat eksekusi
- **Edit**: Update template
- **Delete**: Hapus template

#### **7.3 Auto Processing**
- **Command**: `php artisan recurring:process`
- **Scheduler**: Jalankan harian/mingguan/bulanan
- **Logging**: Track eksekusi template

### **Flow Pengguna:**
```
Dashboard → Recurring Templates → Create (buat template)
                                → Show (lihat detail & riwayat)
                                → Edit (ubah template)
```

---

## 📦 **8. MANAJEMEN PRODUK (Products)**

### **Fitur Lengkap:**

#### **8.1 Index (Daftar Produk)**
- **Grid/List**: Produk dengan foto, nama, harga, stok
- **Filter**: Berdasarkan kategori, status stok
- **Search**: Cari berdasarkan nama

#### **8.2 CRUD Operations**
- **Create**: Tambah produk dengan detail lengkap
- **Show**: Detail produk dengan riwayat transaksi
- **Edit**: Update informasi produk
- **Delete**: Hapus produk (dengan validasi)

#### **8.3 Stock Management**
- **Stock Tracking**: Update stok otomatis
- **Low Stock Alerts**: Notifikasi stok rendah
- **Stock Movements**: Riwayat perubahan stok

---

## 📁 **9. KATEGORI PRODUK (Product Categories)**

### **Fitur Lengkap:**

#### **9.1 Index (Daftar Kategori Produk)**
- **Grid View**: Kategori dengan statistik produk/layanan
- **Filter**: Dengan produk, dengan layanan, kosong
- **Search**: Cari kategori

#### **9.2 CRUD Operations**
- **Create**: Tambah kategori produk
- **Show**: Detail kategori dengan produk terkait
- **Edit**: Update kategori dengan validasi unik
- **Delete**: Hapus dengan proteksi

#### **9.3 Statistics Dashboard**
- **Metrics**: Total produk, layanan, transaksi terakhir
- **Charts**: Sparkline untuk tren
- **Quick Actions**: Link ke create produk/layanan

---

## 🛍️ **10. LAYANAN (Services)**

### **Fitur Mirip Products:**
- **Index**: Daftar layanan
- **CRUD**: Create, Show, Edit, Delete
- **Integration**: Link dengan kategori produk
- **Transaction Tracking**: Riwayat transaksi layanan

---

## 👥 **11. PELANGGAN (Customers)**

### **Fitur Lengkap:**
- **Index**: Daftar pelanggan
- **CRUD**: Kelola data pelanggan
- **Transaction History**: Riwayat transaksi per pelanggan
- **Contact Info**: Detail kontak pelanggan

---

## 📈 **12. STOCK MOVEMENTS**

### **Fitur Lengkap:**
- **Index**: Riwayat perubahan stok
- **Filter**: Berdasarkan produk, tipe (in/out), periode
- **Auto-tracking**: Update otomatis dari transaksi
- **Reports**: Laporan pergerakan stok

---

## 🏢 **13. CABANG (Branches)**

### **Fitur Multi-branch:**
- **Index**: Daftar cabang
- **Switch Branch**: Pindah konteks cabang
- **Branch-specific Data**: Data terpisah per cabang
- **User Assignment**: User dapat assign ke cabang

---

## 👤 **14. PENGGUNA (Users)**

### **Fitur Lengkap:**
- **Index**: Daftar pengguna (admin only)
- **CRUD**: Kelola pengguna
- **Roles**: Sistem role-based access
- **Profile**: Edit profil sendiri
- **Password**: Ganti password

---

## 📊 **15. PELAPORAN (Reports)**

### **Jenis Laporan:**

#### **15.1 Financial Reports**
- **Daily/Monthly/Weekly**: Laporan harian/bulanan/mingguan
- **Profit & Loss**: Laporan laba rugi
- **Cash Flow**: Arus kas
- **Balance Sheet**: Neraca

#### **15.2 Sales Reports**
- **Total Sales**: Total penjualan
- **Top Products**: Produk terlaris
- **Sales by Customer**: Penjualan per pelanggan

#### **15.3 Inventory Reports**
- **Stock Levels**: Level stok saat ini
- **Stock Movements**: Pergerakan stok
- **Inventory Value**: Nilai inventory

#### **15.4 Account Reports**
- **Account Ledger**: Buku besar per rekening
- **Reconciliation**: Rekonsiliasi rekening

### **Fitur Export:**
- **PDF**: Export laporan ke PDF
- **Excel/CSV**: Export data ke spreadsheet
- **Print**: Print-friendly views

---

## ⚙️ **16. PENGATURAN (Settings)**

### **Fitur Lengkap:**
- **Profile Settings**: Update profil pengguna
- **Password Change**: Ganti password
- **System Preferences**: Pengaturan sistem
- **Tax Settings**: Konfigurasi pajak
- **Branch Settings**: Pengaturan cabang

---

## 🔐 **17. SISTEM KEAMANAN**

### **Security Features:**

#### **17.1 Authentication**
- **Login/Logout**: Sistem autentikasi standar
- **Session Management**: Session timeout
- **Password Security**: Hashing bcrypt

#### **17.2 Authorization**
- **User Scoping**: Semua query scoped per user
- **Route Protection**: Middleware auth
- **CSRF Protection**: Token CSRF di semua form

#### **17.3 Data Validation**
- **Server-side Validation**: Validasi di controller
- **Unique Constraints**: Database level constraints
- **Input Sanitization**: Laravel auto-sanitization

#### **17.4 Access Control**
- **Role-based Access**: Sistem role (admin/user)
- **Branch Isolation**: Data terpisah per cabang
- **Resource Ownership**: User hanya akses data sendiri

---

## 🗄️ **5. STRUKTUR DATABASE (Detail Implementation)**

### **Tabel Inti + Kolom Utama:**

#### **users**
- `id`, `name`, `email`, `password`
- `branch_id` (nullable), `role_id`, `created_at`, `updated_at`

#### **branches**
- `id`, `company_id`, `name`, `address`, `phone`
- `created_at`, `updated_at`

#### **companies**
- `id`, `name`, `tax_id`, `default_currency`
- `created_at`, `updated_at`

#### **roles, permissions** (via Spatie Laravel Permission)

#### **accounts**
- `id`, `company_id`, `branch_id` (nullable), `name`, `type` (cash/bank/ewallet)
- `balance` (decimal), `currency`, `is_active`, `created_at`, `updated_at`

#### **categories**
- `id`, `company_id`, `name`, `type` (income/expense)
- `parent_id` (nullable), `is_active`

#### **tags**
- `id`, `company_id`, `name`, `color`

#### **products**
- `id`, `company_id`, `product_category_id`, `name`, `sku`
- `price`, `stock_quantity`, `min_stock`, `is_active`

#### **stock_movements**
- `id`, `company_id`, `product_id`, `type` (in/out/adjustment)
- `quantity`, `reference_type`, `reference_id`, `notes`, `created_at`

#### **transactions**
- `id`, `company_id`, `branch_id`, `user_id`, `account_id`, `category_id`
- `type` (income/expense), `amount`, `currency`, `description`, `date`
- `reconciled` (bool), `reference_type`, `reference_id`, `created_at`, `updated_at`

#### **transaction_entries** (Double-Entry Ledger)
- `id`, `transaction_id`, `account_id`, `debit` (decimal), `credit` (decimal)
- `description`, `created_at`, `updated_at`

*Rationale: `transactions` is the business object; `transaction_entries` holds the double-entry ledger lines for audit and reporting.*

#### **transfers**
- `id`, `company_id`, `user_id`, `from_account_id`, `to_account_id`
- `amount`, `date`, `description`

#### **invoices**
- `id`, `company_id`, `customer_id`, `invoice_number`, `status`
- `total`, `due_date`, `issued_date`, `created_at`, `updated_at`

#### **recurring_templates**
- `id`, `company_id`, `name`, `type`, `frequency` (daily/weekly/monthly)
- `data` (json), `next_date`, `is_active`

#### **stock_alerts**
- `id`, `product_id`, `triggered_at`, `resolved_at`, `severity`

#### **ocr_jobs / receipts**
- `id`, `company_id`, `user_id`, `file_path`, `parsed_data` (json)
- `status`, `created_at`

#### **notifications** (Laravel default notifications)

---

## 🔌 **6. API ENDPOINTS (RESTful)**

*Notes: Protect with Sanctum & permission middleware.*

### **Auth**
- `POST /api/auth/register`
- `POST /api/auth/login`
- `POST /api/auth/logout`
- `GET /api/auth/me`

### **Companies & Branches**
- `GET /api/companies`
- `POST /api/companies`
- `GET /api/companies/{id}/branches`
- `POST /api/branches`

### **Accounts**
- `GET /api/accounts` (filter by branch/company)
- `POST /api/accounts`
- `GET /api/accounts/{id}`
- `PUT /api/accounts/{id}`
- `DELETE /api/accounts/{id}`
- `POST /api/accounts/{id}/reconcile`

### **Transactions**
- `GET /api/transactions` (filters: date_from, date_to, account_id, tags, branch_id)
- `POST /api/transactions` (create income/expense)
- `GET /api/transactions/{id}`
- `PUT /api/transactions/{id}`
- `DELETE /api/transactions/{id}`
- `GET /api/transactions/{id}/entries` (ledger lines)

### **Transfers**
- `POST /api/transfers`
- `GET /api/transfers`

### **Products**
- `GET /api/products`
- `POST /api/products`
- `PUT /api/products/{id}`

### **Stock Movements**
- `GET /api/products/{id}/stock-movements`
- `POST /api/products/{id}/stock-movements` (adjustments)

### **Recurring Templates**
- `GET /api/recurring-templates`
- `POST /api/recurring-templates`

### **OCR / Receipts**
- `POST /api/receipts/upload` (multipart) → returns parsed/suspected fields
- `GET /api/receipts/{id}`

### **Reports / Chart Aggregates**
- `GET /api/reports/pnl?from=&to=&branch_id=`
- `GET /api/reports/cashflow?from=&to=`
- `GET /api/reports/ledger?account_id=&from=&to=`
- `GET /api/reports/inventory/valuation?branch_id=`

### **Invoicing**
- `POST /api/invoices`
- `POST /api/invoices/{id}/send` (send via email/wa)
- `POST /api/invoices/{id}/record-payment`

### **Exports**
- `GET /api/exports/ledger?from=&to=&format=excel|pdf`

---

## 📝 **7. CONTOH MIGRATION (Transactions + TransactionEntries)**

```php
// database/migrations/2025_01_01_000001_create_transactions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type',['income','expense','adjustment']);
            $table->decimal('amount', 20, 2);
            $table->string('currency',5)->default('IDR');
            $table->text('description')->nullable();
            $table->date('date');
            $table->boolean('reconciled')->default(false);
            $table->json('meta')->nullable(); // store tags, product ids etc
            $table->timestamps();
        });

        Schema::create('transaction_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->decimal('debit',20,2)->default(0);
            $table->decimal('credit',20,2)->default(0);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_entries');
        Schema::dropIfExists('transactions');
    }
}
```

---

## 🏗️ **8. CONTOH MODEL (Transaction)**

```php
// app/Models/Transaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $guarded = [];
    protected $casts = ['meta' => 'array', 'date' => 'date'];

    public function entries(): HasMany
    {
        return $this->hasMany(TransactionEntry::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
```

---

## 🔧 **9. CONTOH SERVICE: Create Transaction (Double-Entry + Stock Update)**

*Important: Do it inside DB transaction.*

```php
// app/Services/TransactionService.php
namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionEntry;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionService
{
    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function() use ($data) {
            $transaction = Transaction::create([
                'company_id' => $data['company_id'],
                'branch_id' => $data['branch_id'] ?? null,
                'user_id' => $data['user_id'],
                'account_id' => $data['account_id'],
                'category_id' => $data['category_id'] ?? null,
                'type' => $data['type'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'IDR',
                'description' => $data['description'] ?? null,
                'date' => $data['date'] ?? Carbon::now()->toDateString(),
                'meta' => $data['meta'] ?? null,
            ]);

            // Build ledger entries: simple mapping example
            if($data['type'] === 'income') {
                // Debit: account (increase balance)
                $transaction->entries()->create([
                    'account_id' => $data['account_id'],
                    'debit' => $data['amount'],
                    'credit' => 0,
                    'description' => 'Income to account',
                ]);
                // Credit: income revenue account (category->linked_account_id)
                $revenueAccountId = $data['revenue_account_id'];
                $transaction->entries()->create([
                    'account_id' => $revenueAccountId,
                    'debit' => 0,
                    'credit' => $data['amount'],
                    'description' => 'Revenue recognized',
                ]);
                // Update balances (simple approach)
                \DB::table('accounts')->where('id', $data['account_id'])->increment('balance', $data['amount']);
                \DB::table('accounts')->where('id', $revenueAccountId)->decrement('balance', $data['amount']);
            } else { // expense
                $expenseAccountId = $data['expense_account_id'];
                // Debit expense account
                $transaction->entries()->create([
                    'account_id' => $expenseAccountId,
                    'debit' => $data['amount'],
                    'credit' => 0,
                ]);
                // Credit cash/bank account
                $transaction->entries()->create([
                    'account_id' => $data['account_id'],
                    'debit' => 0,
                    'credit' => $data['amount'],
                ]);
                \DB::table('accounts')->where('id', $expenseAccountId)->increment('balance', $data['amount']);
                \DB::table('accounts')->where('id', $data['account_id'])->decrement('balance', $data['amount']);
            }

            // If products included, update stock movements
            if(!empty($data['products'])) {
                foreach($data['products'] as $p) {
                    $product = Product::findOrFail($p['product_id']);
                    // create stock movement
                    $product->decrement('stock_quantity', $p['qty']);
                    \DB::table('stock_movements')->insert([
                        'company_id' => $data['company_id'],
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $p['qty'],
                        'reference_type' => Transaction::class,
                        'reference_id' => $transaction->id,
                        'notes' => $p['notes'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    // Low stock check -> create Notification / Alert job
                    if($product->stock_quantity <= $product->min_stock) {
                        // dispatch alert job or create alert record
                    }
                }
            }

            // Dispatch events
            event(new \App\Events\TransactionCreated($transaction));
            return $transaction;
        });
    }
}
```

*Catatan: Contoh di atas sederhana—untuk environment production gunakan Account ledger accounts mapping, currency handling, and proper rounding/precision, serta full validation.*

---

## ⏰ **10. RECURRING TEMPLATES — Artisan Command**

*Create command `recurring:process` that finds active templates with `next_date <= today` and dispatch job to create transactions and update `next_date`.*

```php
// app/Console/Commands/ProcessRecurring.php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\RecurringTemplate;
use Carbon\Carbon;

class ProcessRecurring extends Command
{
    protected $signature = 'recurring:process';
    protected $description = 'Process recurring templates';

    public function handle()
    {
        $today = Carbon::today();
        $templates = RecurringTemplate::where('is_active', true)
            ->where('next_date','<=',$today)
            ->get();

        foreach($templates as $t) {
            // Dispatch job to create transaction based on $t->data
            \App\Jobs\ProcessRecurringTemplate::dispatch($t);
            // update next_date inside job or here
        }
        return 0;
    }
}
```

*Schedule it in `app/Console/Kernel.php`:*

```php
$schedule->command('recurring:process')->daily();
```

---

## 📱 **11. WHATSAPP REMINDERS FLOW**

- Create `Reminder` model for invoices with schedule
- Job `SendInvoiceReminderJob` composes WA template and calls WA API provider
- Provider adapter config (Twilio/Tengkulak API) — env-driven
- Keep templates in DB for localization and audit

---

## 📊 **12. EXPORTS & REPORTING**

- Use queries with aggregates (Eloquent + DB::raw) to prepare dataset
- For big datasets, use chunking and queue export tasks
- Use `Maatwebsite\Excel` to generate Excel, `Dompdf` to create PDF/A

---

## 🔒 **13. SECURITY & PERMISSIONS**

- Use `spatie/laravel-permission` to define permissions
- Add global scope on models to restrict by `company_id` and optionally `branch_id` (multi-tenancy support)
- Validate token-based API with Sanctum
- Activity log for critical operations (create/update/delete transactions)

---

## 📋 **14. CONTOH ENDPOINT & REQUEST/RESPONSE**

**POST /api/transactions**

*Payload:*
```json
{
  "company_id": 1,
  "branch_id": 2,
  "user_id": 5,
  "account_id": 10,
  "category_id": 20,
  "type": "expense",
  "amount": 150000,
  "currency": "IDR",
  "description": "Beli bahan baku",
  "date": "2025-11-20",
  "products": [
    {"product_id": 3, "qty": 2, "notes": "Item A"}
  ],
  "meta": {"tags": ["project-x","promo-nov"]}
}
```

*Response (201 Created):*
```json
{
  "id": 123,
  "status": "created",
  "entries": [
    {"account_id": 50, "debit": 150000, "credit": 0},
    {"account_id": 10, "debit": 0, "credit": 150000}
  ]
}
```

---

## 🚀 **15. ROADMAP IMPLEMENTASI (Sprint-Level)**

### **Sprint 0 (Setup)**
- Install packages (spatie, sanctum, excel, dompdf)
- Base models, migrations (accounts, transactions, entries)
- Auth + multi-company scoping

### **Sprint 1 (Transactions & Ledger)**
- Transaction create/edit/delete with double-entry
- Accounts balance updates
- Unit tests

### **Sprint 2 (Products & Stock)**
- Products, stock_movements, auto-update on transactions
- Low-stock alerts

### **Sprint 3 (Recurring & Scheduler)**
- Recurring templates + artisan command + job

### **Sprint 4 (OCR & Receipts)**
- Upload endpoint + OCR job + draft flow

### **Sprint 5 (Invoicing & WA Reminders)**
- Invoice model + send via WA integration

### **Sprint 6 (Reports & Exports)**
- P&L, Balance sheet, Cashflow endpoints + Excel/PDF export

### **Sprint 7 (Roles, Branches & UI Hooks)**
- Spatie permissions, branch scoping, admin endpoints

---

## ⚠️ **16. OPERATIONAL NOTES & PITFALLS**

- **Atomicity:** Selalu DB transaction saat membuat entries + update balances + stock movements
- **Rounding & Currency:** Design for multi-currency later (store in smallest units or decimal(20,2))
- **Audit:** Keep `transaction_entries` immutable after creation except through reversal entries
- **Data Retention & Backups:** Automated daily DB backups and file backups for receipts
- **Performance:** Use indexes on date/account/company, use caching for dashboard aggregates
- **Legal/Pajak:** Provide hooks to map taxes per transaction

---

## 🗄️ **17. STRUKTUR DATABASE**

### **Tabel Utama:**

#### **18.1 Users**
- `id`, `name`, `email`, `password`
- `branch_id`, `role_id`, `created_at`, `updated_at`

#### **18.2 Branches**
- `id`, `name`, `address`, `phone`, `user_id`

#### **18.3 Accounts**
- `id`, `user_id`, `branch_id`, `name`, `type`, `balance`
- `description`, `is_active`, `created_at`, `updated_at`

#### **18.4 Categories**
- `id`, `user_id`, `name`, `type`, `description`
- `is_active`, `created_at`, `updated_at`

#### **18.5 Transactions**
- `id`, `user_id`, `account_id`, `category_id`
- `amount`, `description`, `date`, `type`
- `transfer_id`, `product_id`, `reconciled`

#### **18.6 Transfers**
- `id`, `user_id`, `from_account_id`, `to_account_id`
- `amount`, `description`, `date`

#### **18.7 Products**
- `id`, `user_id`, `product_category_id`, `name`
- `description`, `price`, `stock_quantity`, `min_stock`

#### **18.8 Product Categories**
- `id`, `user_id`, `name`, `description`

#### **18.9 Services**
- `id`, `user_id`, `product_category_id`, `name`
- `description`, `price`

#### **18.10 Customers**
- `id`, `user_id`, `name`, `email`, `phone`, `address`

#### **18.11 Stock Movements**
- `id`, `user_id`, `product_id`, `type`, `quantity`
- `date`, `reference`, `notes`

#### **18.12 Recurring Templates**
- `id`, `user_id`, `name`, `type`, `frequency`
- `account_id`, `category_id`, `amount`, `description`
- `next_date`, `is_active`

---

## 🔄 **19. BUSINESS FLOW**

### **19.1 Complete Transaction Flow**
```
1. User Login → Dashboard
2. Pilih Tipe Transaksi (Income/Expense/Transfer)
3. Pilih Rekening → Pilih Kategori → Input Detail
4. Validasi & Simpan → Update Saldo Rekening
5. Redirect ke Detail Transaksi
```

### **19.2 Inventory Management Flow**
```
1. Buat Kategori Produk
2. Tambah Produk ke Kategori
3. Catat Transaksi (Income/Expense)
4. Auto-update Stock (jika produk terkait)
5. Monitor Stock Levels & Alerts
```

### **19.3 Reporting Flow**
```
1. Akses Menu Reports
2. Pilih Tipe Laporan & Periode
3. Generate Laporan
4. Export/Print/Save
```

---

## 🚀 **20. DEPLOYMENT & MAINTENANCE**

### **20.1 Installation**
```bash
git clone <repo>
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### **20.2 Cron Jobs**
```bash
# Recurring templates processing
* * * * * php artisan recurring:process
```

### **20.3 Backup Strategy**
- **Database**: Daily backup SQLite file
- **Files**: Backup uploaded images/documents
- **Logs**: Rotate application logs

---

## 📈 **21. PERFORMANCE & SCALING**

### **21.1 Optimization Features**
- **Database Indexing**: Optimized queries
- **Caching**: Laravel cache untuk frequent data
- **Lazy Loading**: Eager loading relationships
- **Pagination**: Efficient data pagination

### **21.2 Monitoring**
- **Logs**: Comprehensive logging
- **Performance**: Query optimization
- **Error Tracking**: Exception handling

---

## 🎯 **22. FITUR KHUSUS**

### **22.1 Multi-branch Support**
- Data isolation per branch
- User assignment to branches
- Branch-specific reporting

### **22.2 Tax Integration**
- Tax calculation settings
- Tax tracking per transaction
- Tax reports

### **22.3 Inventory Integration**
- Real-time stock updates
- Low stock notifications
- Inventory valuation

### **22.4 Recurring Transactions**
- Automated transaction creation
- Flexible scheduling (daily/weekly/monthly)
- Template management

---

## 📞 **23. SUPPORT & MAINTENANCE**

### **23.1 User Support**
- **Documentation**: Comprehensive guides
- **Help Sections**: In-app help
- **Error Messages**: User-friendly notifications

### **23.2 System Maintenance**
- **Updates**: Regular security updates
- **Backups**: Automated backup system
- **Monitoring**: System health checks

---

**Sistem Akuntansi Sibuku** adalah solusi lengkap untuk manajemen keuangan dengan fitur enterprise-grade, keamanan tinggi, dan user experience yang excellent.