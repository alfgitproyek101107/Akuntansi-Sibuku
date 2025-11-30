# Dokumentasi Lengkap Menu dan Fungsi Sistem Akuntansi Sibuku

## Daftar Isi
1. [Dashboard](#dashboard)
2. [Akun (Accounts)](#akun-accounts)
3. [Kategori (Categories)](#kategori-categories)
4. [Pemasukan (Income)](#pemasukan-income)
5. [Pengeluaran (Expenses)](#pengeluaran-expenses)
6. [Transfer](#transfer)
7. [Chart of Accounts](#chart-of-accounts)
8. [Berulang (Recurring Templates)](#berulang-recurring-templates)
9. [Inventori (Inventory)](#inventori-inventory)
   - [Kategori Produk (Product Categories)](#kategori-produk-product-categories)
   - [Produk (Products)](#produk-products)
   - [Layanan (Services)](#layanan-services)
   - [Pelanggan (Customers)](#pelanggan-customers)
   - [Pergerakan Stok (Stock Movements)](#pergerakan-stok-stock-movements)
10. [Laporan (Reports)](#laporan-reports)
    - [Ringkasan Laporan (Report Summary)](#ringkasan-laporan-report-summary)
11. [Manajemen (Management)](#manajemen-management)
    - [Cabang (Branches)](#cabang-branches)
    - [Pengguna (Users)](#pengguna-users)
12. [Pengaturan (Settings)](#pengaturan-settings)
    - [Umum (General)](#umum-general)
    - [WhatsApp](#whatsapp)

---

## Dashboard

**URL:** `/dashboard`  
**Controller:** `DashboardController`  
**Fungsi Utama:**
- Menampilkan ringkasan keseluruhan sistem akuntansi
- Menunjukkan statistik utama seperti total pemasukan, pengeluaran, dan saldo
- Grafik trend keuangan bulanan/tahunan
- Daftar transaksi terbaru
- Status persetujuan yang menunggu (untuk approval workflow)
- Notifikasi penting sistem

**Fitur Detail:**
- **Statistik Utama:**
  - Total Pemasukan Bulan Ini
  - Total Pengeluaran Bulan Ini
  - Saldo Kas/Bank
  - Laba/Rugi Bersih
- **Grafik dan Chart:**
  - Line chart pemasukan vs pengeluaran
  - Pie chart distribusi pengeluaran per kategori
  - Bar chart trend bulanan
- **Aktivitas Terbaru:**
  - 10 transaksi terakhir
  - Approval requests pending
  - Low stock alerts
- **Quick Actions:**
  - Tambah pemasukan baru
  - Tambah pengeluaran baru
  - Buat transfer antar akun

---

## Akun (Accounts)

**URL:** `/accounts`  
**Controller:** `AccountController`  
**Fungsi Utama:**
- Mengelola rekening bank, kas, dan akun keuangan lainnya
- Melacak saldo dan mutasi setiap akun
- Mengelola rekening bank untuk multi-cabang

**Fitur Detail:**
- **Daftar Akun:**
  - Nama akun, nomor rekening, bank
  - Saldo saat ini
  - Tipe akun (Kas, Bank, Piutang, dll)
  - Status aktif/non-aktif
- **Tambah/Edit Akun:**
  - Informasi dasar (nama, kode, tipe)
  - Detail bank (nama bank, nomor rekening, atas nama)
  - Setting saldo awal
  - Branch assignment
- **Riwayat Transaksi:**
  - Semua transaksi yang melibatkan akun tersebut
  - Filter berdasarkan tanggal, tipe transaksi
  - Export laporan per akun
- **Rekonsiliasi Bank:**
  - Upload mutasi bank
  - Matching otomatis dengan transaksi
  - Menandai transaksi yang sudah direkonsiliasi

---

## Kategori (Categories)

**URL:** `/categories`  
**Controller:** `CategoryController`  
**Fungsi Utama:**
- Mengorganisir transaksi ke dalam kategori pemasukan dan pengeluaran
- Hierarki kategori untuk klasifikasi yang lebih detail
- Budget planning per kategori

**Fitur Detail:**
- **Daftar Kategori:**
  - Kategori pemasukan dan pengeluaran
  - Hierarki parent-child
  - Warna kode untuk visualisasi
  - Status aktif/non-aktif
- **Manajemen Kategori:**
  - Tambah kategori baru
  - Edit nama, deskripsi, warna
  - Set parent kategori
  - Assign ke branch tertentu
- **Budget per Kategori:**
  - Set budget bulanan/tahunan
  - Tracking pengeluaran vs budget
  - Alert ketika mendekati limit
- **Laporan per Kategori:**
  - Trend pengeluaran per kategori
  - Perbandingan bulan ke bulan
  - Analisis kontribusi kategori terhadap total

---

## Pemasukan (Income)

**URL:** `/incomes`  
**Controller:** `IncomeController`  
**Fungsi Utama:**
- Mencatat semua jenis pemasukan bisnis
- Mendukung transaksi tunggal dan multi-item (produk/layanan)
- Otomatis menghitung pajak untuk transaksi produk
- Integrasi dengan inventory management

**Mode Input:**
1. **Simple Mode:** Transaksi tunggal dengan jumlah nominal
2. **Product Mode:** Multi-item dengan produk dari inventory
3. **Service Mode:** Multi-item dengan layanan

**Fitur Detail:**
- **Pencatatan Transaksi:**
  - Pilih akun penerima
  - Pilih kategori pemasukan
  - Input tanggal dan deskripsi
  - Mode input: Simple/Product/Service
- **Product Mode:**
  - Pilih produk dari inventory
  - Input quantity dan harga
  - Otomatis kurangi stok
  - Hitung pajak otomatis (PPN 11%)
  - Buat journal entries untuk akuntansi
- **Service Mode:**
  - Pilih layanan yang tersedia
  - Input quantity dan harga
  - Hitung subtotal otomatis
- **Tax Calculation:**
  - Otomatis hitung PPN untuk produk
  - Simpan breakdown pajak per item
  - Buat journal entries untuk tax accounting
- **Stock Integration:**
  - Kurangi stok otomatis saat penjualan
  - Catat stock movement
  - Validasi ketersediaan stok

---

## Pengeluaran (Expenses)

**URL:** `/expenses`  
**Controller:** `ExpenseController`  
**Fungsi Utama:**
- Mencatat semua jenis pengeluaran bisnis
- Mendukung transaksi tunggal dan multi-item
- Approval workflow untuk pengeluaran besar
- Budget control dan monitoring

**Fitur Detail:**
- **Pencatatan Transaksi:**
  - Pilih akun pengeluaran
  - Pilih kategori pengeluaran
  - Input tanggal, jumlah, deskripsi
  - Upload bukti pengeluaran
- **Approval Workflow:**
  - Set threshold untuk approval
  - Multi-level approval berdasarkan jumlah
  - Notification ke approver
  - Tracking status approval
- **Budget Control:**
  - Alert ketika melebihi budget kategori
  - Approval otomatis untuk pengeluaran kecil
  - Reporting pengeluaran vs budget
- **Expense Categories:**
  - Kategori tetap (gaji, sewa, utilitas)
  - Kategori variabel (operasional, marketing)
  - Custom kategori sesuai kebutuhan

---

## Transfer

**URL:** `/transfers`  
**Controller:** `TransferController`  
**Fungsi Utama:**
- Memindahkan dana antar rekening internal
- Mencatat transfer antar cabang
- Tracking biaya transfer bank
- Approval untuk transfer besar

**Fitur Detail:**
- **Transfer Internal:**
  - Pilih rekening sumber dan tujuan
  - Input jumlah transfer
  - Catat tanggal dan deskripsi
  - Otomatis update saldo kedua rekening
- **Transfer Antar Cabang:**
  - Pilih cabang sumber dan tujuan
  - Hitung biaya transfer
  - Approval untuk transfer besar
- **Biaya Transfer:**
  - Catat biaya administrasi bank
  - Otomatis buat journal entry
  - Laporan biaya transfer bulanan
- **Approval Workflow:**
  - Threshold-based approval
  - Multi-level approver
  - Audit trail lengkap

---

## Chart of Accounts

**URL:** `/chart-of-accounts`  
**Controller:** `ChartOfAccountsController`  
**Fungsi Utama:**
- Sistem akun lengkap berdasarkan standar akuntansi
- Hierarki akun 5 digit (asset, liability, equity, income, expense)
- Mapping dengan kategori transaksi

**Fitur Detail:**
- **Struktur Akun:**
  - Asset (10000-19999)
  - Liability (20000-29999)
  - Equity (30000-39999)
  - Income (40000-49999)
  - Expense (50000-59999)
- **Manajemen Akun:**
  - Tambah akun baru
  - Edit nama, deskripsi, tipe
  - Set parent account
  - Aktif/non-aktif akun
- **Integration:**
  - Mapping dengan kategori transaksi
  - Auto-create journal entries
  - Financial reporting berdasarkan COA
- **Reporting:**
  - Balance sheet berdasarkan COA
  - Income statement
  - Trial balance

---

## Berulang (Recurring Templates)

**URL:** `/recurring-templates`  
**Controller:** `RecurringTemplateController`  
**Fungsi Utama:**
- Template untuk transaksi berulang
- Otomatisasi pencatatan transaksi rutin
- Scheduling fleksibel (harian, mingguan, bulanan, tahunan)

**Fitur Detail:**
- **Template Management:**
  - Buat template pemasukan/pengeluaran
  - Set frekuensi dan jadwal
  - Tentukan tanggal mulai dan akhir
  - Status aktif/non-aktif
- **Scheduling Options:**
  - Harian: setiap hari, hari kerja saja
  - Mingguan: pilih hari dalam seminggu
  - Bulanan: tanggal tertentu, hari ke-N dalam bulan
  - Tahunan: bulan dan tanggal tertentu
- **Auto Execution:**
  - Background job untuk eksekusi
  - Notification saat transaksi dibuat
  - Error handling untuk transaksi gagal
- **Template Types:**
  - Pemasukan rutin (sewa, royalti)
  - Pengeluaran rutin (gaji, sewa, utilitas)
  - Transfer rutin antar rekening

---

## Inventori (Inventory)

### Kategori Produk (Product Categories)

**URL:** `/product-categories`  
**Controller:** `ProductCategoryController`  
**Fungsi Utama:**
- Mengorganisir produk ke dalam kategori
- Hierarki kategori produk
- Management atribut produk per kategori

**Fitur Detail:**
- **Kategori Management:**
  - Struktur hierarki kategori
  - Deskripsi dan atribut khusus
  - Status aktif/non-aktif
- **Product Attributes:**
  - Field tambahan per kategori
  - Unit measurement
  - Default tax settings
- **Reporting:**
  - Penjualan per kategori
  - Profit margin per kategori
  - Stock level per kategori

### Produk (Products)

**URL:** `/products`  
**Controller:** `ProductController`  
**Fungsi Utama:**
- Manajemen katalog produk
- Tracking stok dan harga
- Tax settings per produk
- Integrasi dengan penjualan

**Fitur Detail:**
- **Product Information:**
  - Nama, SKU, deskripsi
  - Kategori produk
  - Harga beli dan jual
  - Unit measurement
- **Stock Management:**
  - Quantity saat ini
  - Minimum stock level
  - Auto reorder alerts
  - Stock movement history
- **Tax Settings:**
  - Tax rule assignment
  - Tax percentage
  - Tax included/excluded
- **Sales Integration:**
  - Link dengan income transactions
  - Auto stock reduction
  - Sales reporting per product

### Layanan (Services)

**URL:** `/services`  
**Controller:** `ServiceController`  
**Fungsi Utama:**
- Katalog layanan yang ditawarkan
- Pricing dan deskripsi layanan
- Tracking penjualan layanan

**Fitur Detail:**
- **Service Information:**
  - Nama layanan, deskripsi
  - Kategori layanan
  - Harga dasar
  - Unit measurement
- **Service Management:**
  - Status aktif/non-aktif
  - Branch assignment
  - Custom attributes
- **Sales Tracking:**
  - Link dengan income transactions
  - Service revenue reporting
  - Performance analytics

### Pelanggan (Customers)

**URL:** `/customers`  
**Controller:** `CustomerController`  
**Fungsi Utama:**
- Database pelanggan
- Riwayat transaksi per pelanggan
- Customer relationship management

**Fitur Detail:**
- **Customer Information:**
  - Data pribadi (nama, email, telepon)
  - Alamat billing dan shipping
  - NPWP untuk faktur pajak
  - Customer type (individual/company)
- **Transaction History:**
  - Semua transaksi dengan customer
  - Total purchase value
  - Last transaction date
  - Payment terms
- **Customer Analytics:**
  - Customer lifetime value
  - Purchase frequency
  - Average order value
  - Customer segmentation

### Pergerakan Stok (Stock Movements)

**URL:** `/stock-movements`  
**Controller:** `StockMovementController`  
**Fungsi Utama:**
- Tracking semua perubahan stok
- Audit trail pergerakan inventory
- Reporting stock flow

**Fitur Detail:**
- **Movement Types:**
  - Stock In (pembelian, adjustment)
  - Stock Out (penjualan, adjustment)
  - Transfer antar lokasi
  - Stock opname
- **Movement Tracking:**
  - Tanggal dan waktu
  - Quantity dan produk
  - Reference ke transaksi
  - User yang melakukan
- **Reporting:**
  - Stock movement history
  - Stock turnover ratio
  - Slow moving items
  - Stock aging report

---

## Laporan (Reports)

### Ringkasan Laporan (Report Summary)

**URL:** `/reports`  
**Controller:** `ReportController`  
**Fungsi Utama:**
- Dashboard laporan komprehensif
- Financial statements lengkap
- Custom report builder
- Export ke berbagai format

**Fitur Detail:**
- **Financial Reports:**
  - Income Statement (Laba Rugi)
  - Balance Sheet (Neraca)
  - Cash Flow Statement
  - Trial Balance
- **Operational Reports:**
  - Sales reports by product/service
  - Customer analysis
  - Inventory reports
  - Expense analysis by category
- **Period Selection:**
  - Daily, weekly, monthly, yearly
  - Custom date range
  - Comparative periods
- **Export Options:**
  - PDF format
  - Excel spreadsheet
  - CSV data export
  - Scheduled email reports

---

## Manajemen (Management)

### Cabang (Branches)

**URL:** `/branches`  
**Controller:** `BranchController`  
**Fungsi Utama:**
- Multi-branch management
- Branch-specific data isolation
- Inter-branch transactions
- Branch performance reporting

**Fitur Detail:**
- **Branch Setup:**
  - Branch information (nama, alamat, telepon)
  - Branch manager assignment
  - Operating hours
  - Branch-specific settings
- **Data Isolation:**
  - Branch-scoped data access
  - User-branch permissions
  - Branch-specific COA
- **Inter-branch Operations:**
  - Transfer antar cabang
  - Consolidated reporting
  - Branch performance comparison
- **Branch Analytics:**
  - Revenue per branch
  - Expense per branch
  - Profit margin per branch
  - Branch efficiency metrics

### Pengguna (Users)

**URL:** `/users`  
**Controller:** `UserController`  
**Fungsi Utama:**
- User management dan role assignment
- Permission system
- User activity monitoring
- Profile management

**Fitur Detail:**
- **User Management:**
  - Tambah/edit user
  - Role assignment (super-admin, admin, user)
  - Branch assignment
  - Status aktif/non-aktif
- **Role & Permissions:**
  - Predefined roles
  - Custom permissions
  - Menu access control
  - Approval limits
- **User Activity:**
  - Login history
  - Action logs
  - Session management
- **Profile Management:**
  - Personal information
  - Password change
  - Avatar upload
  - Notification preferences

---

## Pengaturan (Settings)

### Umum (General)

**URL:** `/settings`  
**Controller:** `SettingController`  
**Fungsi Utama:**
- Konfigurasi sistem umum
- Company information
- System preferences
- Backup dan maintenance

**Fitur Detail:**
- **Company Settings:**
  - Company name, address, contact
  - Logo dan branding
  - Business registration details
- **System Preferences:**
  - Default currency
  - Date format
  - Number format
  - Language settings
- **Financial Settings:**
  - Default accounts
  - Tax settings
  - Approval workflows
- **System Maintenance:**
  - Database backup
  - Cache management
  - Log cleanup
  - System health check

### WhatsApp

**URL:** `/settings/whatsapp`  
**Controller:** `WhatsAppSettingsController`  
**Fungsi Utama:**
- Konfigurasi integrasi WhatsApp
- Automated reporting via WhatsApp
- Message templates
- API settings

**Fitur Detail:**
- **WhatsApp Integration:**
  - API token configuration
  - Phone number setup
  - Country code settings
- **Automated Reports:**
  - Daily financial summary
  - Weekly business report
  - Monthly performance report
  - Custom report schedules
- **Message Templates:**
  - Predefined report formats
  - Custom message templates
  - Multi-language support
- **Delivery Settings:**
  - Recipient phone numbers
  - Delivery time preferences
  - Failure retry settings

---

## Fitur Tambahan Sistem

### Approval Workflow
- Multi-level approval untuk transaksi besar
- Custom approval rules berdasarkan amount dan kategori
- Email notifications untuk approver
- Audit trail lengkap

### Multi-Branch Support
- Data isolation per branch
- Inter-branch transfers
- Consolidated reporting
- Branch-specific settings

### Tax Management
- Automatic tax calculation untuk produk
- Tax rule management
- Tax invoice generation
- Integration dengan CoreTax API

### Inventory Management
- Real-time stock tracking
- Low stock alerts
- Stock movement audit
- Cost of goods calculation

### Financial Reporting
- Comprehensive financial statements
- Custom report builder
- Multi-format export
- Scheduled report delivery

### Security & Audit
- Role-based access control
- Activity logging
- Data encryption
- Session management

---

*Dokumentasi ini mencakup semua menu dan fungsi yang terlihat di sidebar navigasi sistem Akuntansi Sibuku. Menu WhatsApp Report dan Tax telah di-hide sesuai permintaan.*