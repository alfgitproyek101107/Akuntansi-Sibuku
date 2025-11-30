# 📋 MENU & FUNGSI SISTEM AKUNTANSI SIBUKU

## Daftar Isi
1. [Navigasi Utama](#navigasi-utama)
2. [Menu Dashboard](#menu-dashboard)
3. [Menu Keuangan](#menu-keuangan)
4. [Menu Inventori](#menu-inventori)
5. [Menu Laporan](#menu-laporan)
6. [Menu Manajemen](#menu-manajemen)
7. [Menu Pengaturan](#menu-pengaturan)
8. [Fitur Khusus](#fitur-khusus)

---

## 🧭 Navigasi Utama

### Struktur Menu Sidebar
```
├── Dashboard
├── Akun (Accounts)
├── Kategori (Categories)
├── Pemasukan (Income)
├── Pengeluaran (Expenses)
├── Transfer
├── Chart of Accounts
├── Berulang (Recurring)
├── Inventori (Inventory)
│   ├── Kategori Produk
│   ├── Produk
│   ├── Layanan
│   ├── Pelanggan
│   └── Pergerakan Stok
├── Laporan (Reports)
└── Manajemen (Management)
    ├── Cabang
    ├── Pengguna
    └── Pajak
```

### Header Actions
- **Branch Switcher**: Pindah antar cabang (multi-branch)
- **User Menu**: Profil, Pengaturan, Keluar
- **Demo Watermark**: Indikator mode demo

---

## 📊 Menu Dashboard

### **URL**: `/dashboard`
### **Controller**: `DashboardController`
### **Fungsi Utama**:
- **Real-time KPIs**: Total saldo, transaksi bulanan, profit/loss
- **Interactive Charts**: Tren pendapatan, breakdown pengeluaran
- **Branch-wise Reports**: Performa per cabang
- **Quick Actions**: Akses cepat ke operasi umum

### **Komponen Dashboard**:
1. **Metric Cards**:
   - Total Saldo Semua Akun
   - Pemasukan Bulan Ini
   - Pengeluaran Bulan Ini
   - Profit/Loss

2. **Charts & Graphs**:
   - Tren Pendapatan 12 Bulan
   - Breakdown Pengeluaran per Kategori
   - Cash Flow Statement
   - Top Products Performance

3. **Recent Activities**:
   - Transaksi terbaru
   - Stock movements
   - Approval requests

4. **Quick Actions**:
   - Tambah Pemasukan
   - Tambah Pengeluaran
   - Transfer Dana
   - Lihat Laporan

---

## 💰 Menu Keuangan

### **1. Akun (Accounts)**
#### **URL**: `/accounts`
#### **Controller**: `AccountController`
#### **Fungsi**:
- **Kelola rekening bank**: BCA, Mandiri, BRI, dll
- **Multi-currency support**: IDR, USD, EUR
- **Balance tracking**: Real-time saldo
- **Reconciliation**: Pencocokan mutasi

#### **Operasi CRUD**:
- ✅ **Index**: List semua akun dengan saldo
- ✅ **Create**: Tambah rekening baru
- ✅ **Edit**: Update informasi rekening
- ✅ **Delete**: Hapus rekening (dengan validasi)
- ✅ **Ledger**: Buku besar per rekening
- ✅ **Export**: Export data ke PDF/Excel

#### **Fitur Khusus**:
- **Account Types**: Checking, Savings, Credit
- **Branch Assignment**: Akun per cabang
- **Balance Validation**: Cek saldo sebelum transaksi

### **2. Kategori (Categories)**
#### **URL**: `/categories`
#### **Controller**: `CategoryController`
#### **Fungsi**:
- **Hierarchical categories**: Parent-child relationship
- **Income vs Expense**: Kategori terpisah
- **Color coding**: Visual identification
- **Icon assignment**: FontAwesome icons

#### **Operasi CRUD**:
- ✅ **Index**: List kategori dengan tree structure
- ✅ **Create**: Tambah kategori baru
- ✅ **Edit**: Update nama, warna, icon
- ✅ **Delete**: Hapus kategori (cascade delete)

### **3. Pemasukan (Income)**
#### **URL**: `/incomes`
#### **Controller**: `IncomeController`
#### **Fungsi**:
- **Record revenue**: Penjualan produk, jasa, dll
- **Product integration**: Link ke produk inventory
- **Tax calculation**: PPN 11%
- **Receipt upload**: Bukti pembayaran

#### **Mode Input**:
1. **Simple Mode**: Jumlah manual
2. **Product Mode**: Pilih produk → auto calculate

#### **Operasi CRUD**:
- ✅ **Index**: List semua pemasukan
- ✅ **Create**: Form pemasukan baru
- ✅ **Edit**: Update transaksi
- ✅ **Delete**: Hapus transaksi
- ✅ **Show**: Detail transaksi

### **4. Pengeluaran (Expenses)**
#### **URL**: `/expenses`
#### **Controller**: `ExpenseController`
#### **Fungsi**:
- **Record expenses**: Pembelian, operasional, dll
- **Product purchase**: Otomatis update stock
- **Cost vs Price**: Beli dengan harga pokok
- **Receipt management**: Upload bukti

#### **Mode Input**:
1. **Simple Mode**: Jumlah manual
2. **Product Mode**: Pilih produk → auto harga pokok → update stock

#### **Operasi CRUD**:
- ✅ **Index**: List semua pengeluaran
- ✅ **Create**: Form pengeluaran baru
- ✅ **Edit**: Update transaksi
- ✅ **Delete**: Hapus transaksi
- ✅ **Show**: Detail transaksi

### **5. Transfer**
#### **URL**: `/transfers`
#### **Controller**: `TransferController`
#### **Fungsi**:
- **Inter-account transfers**: Antar rekening
- **Branch transfers**: Antar cabang
- **Balance validation**: Cek saldo sebelum transfer
- **Audit trail**: Track semua transfer

#### **Operasi CRUD**:
- ✅ **Index**: List semua transfer
- ✅ **Create**: Form transfer baru
- ✅ **Edit**: Update transfer
- ✅ **Delete**: Hapus transfer
- ✅ **Show**: Detail transfer

### **6. Chart of Accounts**
#### **URL**: `/chart-of-accounts`
#### **Controller**: `ChartOfAccountsController`
#### **Fungsi**:
- **COA Management**: Struktur akuntansi
- **Trial Balance**: Neraca saldo
- **Journal Entries**: Jurnal umum
- **Financial Reports**: Laporan keuangan

#### **Fitur**:
- **Hierarchical Structure**: Parent-child accounts
- **Account Types**: Asset, Liability, Equity, Revenue, Expense
- **Balance Tracking**: Real-time balances
- **Reporting**: Trial balance, balance sheet

### **7. Berulang (Recurring)**
#### **URL**: `/recurring-templates`
#### **Controller**: `RecurringTemplateController`
#### **Fungsi**:
- **Automated transactions**: Transaksi berulang
- **Template management**: Simpan template
- **Schedule execution**: Cron job processing
- **End date support**: Tanggal berakhir

#### **Jenis Recurring**:
- **Income**: Pemasukan berulang (sewa, komisi)
- **Expense**: Pengeluaran berulang (langganan, gaji)

---

## 📦 Menu Inventori

### **1. Kategori Produk (Product Categories)**
#### **URL**: `/product-categories`
#### **Controller**: `ProductCategoryController`
#### **Fungsi**:
- **Product grouping**: Kategori produk
- **Hierarchical structure**: Sub-kategori
- **Branch assignment**: Kategori per cabang

### **2. Produk (Products)**
#### **URL**: `/products`
#### **Controller**: `ProductController`
#### **Fungsi**:
- **Product management**: SKU, nama, deskripsi
- **Pricing**: Harga jual & harga pokok
- **Stock tracking**: Quantity management
- **Branch isolation**: Stock per cabang

#### **Fitur Produk**:
- **SKU Management**: Unique product codes
- **Multi-unit**: pcs, kg, liter, dll
- **Cost vs Price**: Separate pricing
- **Stock Alerts**: Low stock notifications
- **Category Assignment**: Link ke kategori

### **3. Layanan (Services)**
#### **URL**: `/services`
#### **Controller**: `ServiceController`
#### **Fungsi**:
- **Service catalog**: Jasa yang ditawarkan
- **Pricing**: Harga per unit/jam
- **Tax calculation**: PPN untuk jasa
- **Service tracking**: Link ke transaksi

### **4. Pelanggan (Customers)**
#### **URL**: `/customers`
#### **Controller**: `CustomerController`
#### **Fungsi**:
- **Customer database**: Data pelanggan
- **Contact management**: Email, phone, address
- **Credit limits**: Batas kredit
- **Tax ID**: NPWP pelanggan

#### **Data Pelanggan**:
- **Personal Info**: Nama, email, telepon
- **Address**: Alamat lengkap
- **Tax Info**: NPWP, credit limit
- **Branch Assignment**: Pelanggan per cabang

### **5. Pergerakan Stok (Stock Movements)**
#### **URL**: `/stock-movements`
#### **Controller**: `StockMovementController`
#### **Fungsi**:
- **Stock tracking**: Masuk/keluar
- **Audit trail**: Riwayat perubahan stock
- **Reference linking**: Link ke transaksi
- **Branch isolation**: Movement per cabang

#### **Tipe Movement**:
- **IN**: Stock masuk (pembelian, adjustment)
- **OUT**: Stock keluar (penjualan, adjustment)
- **ADJUSTMENT**: Koreksi stock

---

## 📈 Menu Laporan

### **URL**: `/reports`
### **Controller**: `ReportController`

### **1. Laporan Harian (Daily)**
- **Today's transactions**: Semua transaksi hari ini
- **Daily summary**: Ringkasan harian
- **Cash flow**: Arus kas harian

### **2. Laporan Mingguan (Weekly)**
- **Week overview**: Ringkasan mingguan
- **Trend analysis**: Pola mingguan
- **Performance metrics**: KPI mingguan

### **3. Laporan Bulanan (Monthly)**
- **Monthly P&L**: Profit & Loss bulanan
- **Expense breakdown**: Breakdown pengeluaran
- **Revenue analysis**: Analisis pendapatan
- **Comparative charts**: Perbandingan bulan

### **4. Laporan Arus Kas (Cash Flow)**
- **Operating cash flow**: Arus kas operasional
- **Investing cash flow**: Arus kas investasi
- **Financing cash flow**: Arus kas pendanaan
- **Net cash flow**: Arus kas bersih

### **5. Laporan Akun (Accounts)**
- **Account balances**: Saldo semua akun
- **Account activity**: Aktivitas per akun
- **Balance trends**: Tren saldo
- **Reconciliation status**: Status reconciliasi

### **6. Laporan Transfer**
- **Transfer history**: Riwayat transfer
- **Transfer amounts**: Jumlah transfer
- **Branch transfers**: Transfer antar cabang
- **Transfer fees**: Biaya transfer

### **7. Laporan Rekonsiliasi (Reconciliation)**
- **Bank reconciliation**: Pencocokan bank
- **Unreconciled items**: Item belum reconcile
- **Reconciliation history**: Riwayat reconciliasi

### **8. Laporan Penjualan (Sales Reports)**
- **Total Sales**: Total penjualan
- **Top Products**: Produk terlaris
- **Sales by Customer**: Penjualan per pelanggan
- **Sales Trends**: Tren penjualan

### **9. Laporan Inventori (Inventory Reports)**
- **Stock Levels**: Level stock saat ini
- **Stock Movements**: Pergerakan stock
- **Inventory Value**: Nilai inventory
- **Low Stock Alerts**: Peringatan stock rendah

### **10. Laporan Keuangan (Financial Reports)**
- **Balance Sheet**: Neraca
- **Income Statement**: Laporan laba rugi
- **Trial Balance**: Neraca saldo
- **General Ledger**: Buku besar

---

## ⚙️ Menu Manajemen

### **1. Cabang (Branches)**
#### **URL**: `/branches`
#### **Controller**: `BranchController`
#### **Fungsi** (Super Admin only):
- **Branch management**: Tambah/hapus cabang
- **Branch settings**: Konfigurasi per cabang
- **Branch switching**: Pindah konteks cabang
- **Branch isolation**: Data isolation

#### **Data Cabang**:
- **Basic Info**: Kode, nama, alamat
- **Contact**: Telepon, email
- **Settings**: Mata uang, zona waktu
- **Status**: Aktif/Head Office

### **2. Pengguna (Users)**
#### **URL**: `/users`
#### **Controller**: `UserController`
#### **Fungsi**:
- **User management**: CRUD users
- **Role assignment**: Assign roles
- **Branch assignment**: Assign ke cabang
- **Profile management**: Update profil

#### **Role System**:
- **Super Admin**: Full access semua cabang
- **Admin**: Manage cabang sendiri
- **Manager**: Limited management
- **Staff**: Basic operations
- **Kasir**: Cash transactions only

### **3. Pajak (Tax)**
#### **URL**: `/tax`
#### **Controller**: `TaxController`
#### **Fungsi**:
- **Tax settings**: Konfigurasi pajak
- **Tax rates**: Tarif PPN
- **Tax calculation**: Hitung otomatis
- **Tax reports**: Laporan pajak

#### **Fitur Pajak**:
- **PPN 11%**: Standard rate
- **Tax exemptions**: Pengecualian pajak
- **Tax periods**: Periode pajak
- **Tax reporting**: Laporan SPT

---

## 🔧 Menu Pengaturan

### **URL**: `/settings`
### **Controller**: `SettingController`

### **1. Pengaturan Umum (General Settings)**
- **App Name**: Nama aplikasi
- **Company Info**: Info perusahaan
- **Logo & Branding**: Logo perusahaan
- **Default Settings**: Default values

### **2. Pengaturan Sistem (System Settings)**
- **Cache Management**: Clear cache
- **Optimization**: System optimization
- **Backup**: Database backup
- **Logs**: System logs viewer

### **3. Pengaturan Notifikasi (Notification Settings)**
- **Email Notifications**: Konfigurasi email
- **SMS Alerts**: Notifikasi SMS
- **In-app Notifications**: Notifikasi dalam app
- **Alert Thresholds**: Threshold peringatan

### **4. Pengaturan Transaksi (Transaction Settings)**
- **Default Accounts**: Akun default
- **Auto-numbering**: Penomoran otomatis
- **Approval Workflows**: Workflow approval
- **Tax Settings**: Pengaturan pajak

### **5. Pengaturan UI (UI Settings)**
- **Theme**: Light/Dark mode
- **Language**: Bahasa Indonesia/English
- **Date Format**: Format tanggal
- **Currency Display**: Format mata uang

### **6. Pengaturan Cabang (Branch Settings)**
- **Branch-specific settings**: Per cabang
- **Local preferences**: Preferensi lokal
- **Branch permissions**: Hak akses cabang

---

## 🎯 Fitur Khusus

### **1. Demo Mode**
#### **Fungsi**:
- **Safe testing**: Testing tanpa risiko
- **Demo data**: Data sample lengkap
- **Isolation**: Terpisah dari production
- **Reset capability**: Reset demo data

#### **Demo Features**:
- **Demo Login**: `/demo/login`
- **Demo Reset**: Reset semua data demo
- **Demo Stats**: Statistik penggunaan demo
- **Demo Watermark**: Visual indicator

### **2. Multi-Branch Architecture**
#### **Branch Isolation**:
- **Data Separation**: Database level isolation
- **Scope Implementation**: Laravel global scopes
- **Branch Context**: Session-based context
- **Cross-branch Operations**: Transfer antar cabang

#### **Branch Management**:
- **Branch Creation**: Tambah cabang baru
- **Branch Settings**: Konfigurasi per cabang
- **Branch Switching**: Seamless switching
- **Branch Permissions**: Role-based access

### **3. Approval Workflows**
#### **Workflow Types**:
- **Transaction Approvals**: Approval transaksi besar
- **Budget Approvals**: Approval budget
- **Purchase Approvals**: Approval pembelian

#### **Approval Levels**:
- **Single Level**: 1 orang approve
- **Multi-level**: Sequential approval
- **Any Level**: Any approver can approve

### **4. Advanced Reporting**
#### **Report Types**:
- **Real-time Reports**: Live data
- **Scheduled Reports**: Automated reports
- **Custom Reports**: User-defined reports
- **Export Formats**: PDF, Excel, CSV

#### **Report Features**:
- **Date Filtering**: Custom date ranges
- **Branch Filtering**: Per cabang
- **Category Filtering**: Per kategori
- **Comparative Analysis**: Period comparison

### **5. Security Features**
#### **Authentication**:
- **Multi-factor**: 2FA support
- **Session Management**: Secure sessions
- **Password Policies**: Strong passwords
- **Login Attempts**: Brute force protection

#### **Authorization**:
- **Role-based Access**: RBAC system
- **Branch Permissions**: Branch-level access
- **Resource Ownership**: User ownership validation
- **Audit Logging**: Complete activity logs

### **6. API Endpoints**
#### **RESTful API**:
- **Authentication**: `/api/login`, `/api/logout`
- **Resources**: Full CRUD untuk semua entities
- **Filtering**: Advanced filtering & search
- **Pagination**: Efficient pagination
- **Rate Limiting**: API rate limiting

#### **Webhook Support**:
- **Event-driven**: Real-time notifications
- **Third-party Integration**: External system integration
- **Security**: Signed webhooks
- **Retry Logic**: Failed delivery retry

---

## 📱 User Experience Features

### **1. Responsive Design**
- **Mobile-first**: Optimized untuk mobile
- **Tablet Support**: Tablet compatibility
- **Desktop Enhancement**: Full desktop features
- **Touch-friendly**: Touch-optimized interface

### **2. Accessibility**
- **WCAG Compliance**: Accessibility standards
- **Keyboard Navigation**: Full keyboard support
- **Screen Reader**: Screen reader compatible
- **High Contrast**: High contrast mode

### **3. Performance**
- **Fast Loading**: Optimized loading times
- **Lazy Loading**: Progressive content loading
- **Caching**: Multi-level caching
- **CDN Support**: Static asset optimization

### **4. User Interface**
- **Modern Design**: Clean, modern interface
- **Intuitive Navigation**: Easy-to-use navigation
- **Visual Feedback**: Clear user feedback
- **Progressive Disclosure**: Information hierarchy

---

## 🔧 Technical Specifications

### **System Requirements**
- **Server**: PHP 8.2+, MySQL 8.0+
- **Web Server**: Apache/Nginx
- **RAM**: 2GB minimum, 4GB recommended
- **Storage**: 500MB+ disk space
- **SSL**: HTTPS required

### **Browser Support**
- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+
- **Mobile Browsers**: iOS Safari, Chrome Mobile

### **API Specifications**
- **RESTful Design**: Standard REST principles
- **JSON Responses**: Consistent JSON format
- **Error Handling**: Standardized error responses
- **Documentation**: OpenAPI/Swagger docs

---

## 📞 Support & Documentation

### **User Guides**
- **Getting Started**: Quick start guide
- **Feature Tutorials**: Step-by-step tutorials
- **Video Tutorials**: Screencast tutorials
- **FAQ**: Frequently asked questions

### **Technical Documentation**
- **API Reference**: Complete API documentation
- **Integration Guide**: Third-party integration
- **Customization**: System customization
- **Troubleshooting**: Common issues & solutions

### **Support Channels**
- **Help Desk**: Ticketing system
- **Live Chat**: Real-time support
- **Email Support**: support@sibuku.com
- **Phone Support**: Premium support

---

**📅 Version**: 2.0
**📅 Last Updated**: November 2025
**👥 User Roles**: Super Admin, Admin, Manager, Staff, Kasir
**🌐 Multi-branch**: Unlimited branches
**📊 Reports**: 15+ report types
**🔒 Security**: Enterprise-grade