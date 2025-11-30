# Dokumentasi Lengkap Sistem Akuntansi Sibuku

## Pendahuluan

Dokumen ini merupakan blueprint lengkap untuk sistem akuntansi "Akuntansi Sibuku". Dokumen ini mencakup penjelasan mendalam, rinci, dan sistematis untuk setiap menu dan fitur, termasuk fungsi, alur pengguna, tujuan bisnis, data yang diolah, hubungan antar fitur, integrasi, potensi error dan validasi, serta panduan UI/UX. Dokumen ini dibagi menjadi tiga tahap pengembangan, dimulai dari sistem dasar hingga sistem expert.

Dokumen ini tidak mengandung kode apapun dan dirancang sebagai panduan untuk pengembangan, brainstorming, dan implementasi oleh developer atau AI agent.

---

## Daftar Isi

1. [Tahap 1 - Sistem Dasar (Pencatatan Keuangan)](#tahap-1--sistem-dasar-pencatatan-keuangan)
   - [1. Home (Dashboard)](#1-home-dashboard)
   - [2. Uang Masuk (Income / Pemasukan)](#2-uang-masuk-income--pemasukan)
   - [3. Uang Keluar (Expense / Pengeluaran)](#3-uang-keluar-expense--pengeluaran)
   - [4. Rekening & Kas](#4-rekening--kas)
   - [5. Kategori](#5-kategori)
   - [6. Laporan](#6-laporan)
   - [7. Pengaturan](#7-pengaturan)

2. [Tahap 2 - Sistem Menengah (Manajemen Persediaan)](#tahap-2--sistem-menengah-manajemen-persediaan)
   - [1. Produk & Layanan](#1-produk--layanan)
   - [2. Persediaan](#2-persediaan)
   - [3. Pelanggan](#3-pelanggan)
   - [4. Laporan Tambahan Tahap 2](#4-laporan-tambahan-tahap-2)

3. [Tahap 3 - Sistem Expert (Perpajakan & Multi-Cabang)](#tahap-3--sistem-expert-perpajakan--multi-cabang)
   - [1. Pajak](#1-pajak)
   - [2. Multi-Cabang](#2-multi-cabang)
   - [3. Pengguna](#3-pengguna)
   - [4. Laporan Tambahan](#4-laporan-tambahan)

4. [Flowchart Level Tinggi](#flowchart-level-tinggi)
   - [Flowchart 0 - High-level system overview](#flowchart-0--high-level-system-overview-entry-point)
   - [Flow A - Login & Session](#flow-a--login--session-auth-flow)
   - [Flow B - Dashboard Data Load](#flow-b--dashboard-data-load-on-login--page-open)
   - [Flow C - Tambah Pemasukan](#flow-c--tambah-pemasukan-single-transaction-flow)
   - [Flow D - Tambah Pengeluaran](#flow-d--tambah-pengeluaran-mirror-of-income-with-overdraft-check)
   - [Flow E - Transfer Antar Rekening](#flow-e--transfer-antar-rekening-double-entry-internal-transfer)
   - [Flow F - Recurring Templates](#flow-f--recurring-templates-scheduler)
   - [Flow G - Mutasi Rekening & Reconciliation](#flow-g--mutasi-rekening--reconciliation)
   - [Flow H - Category Management](#flow-h--category-management-affects-reports)
   - [Flow I - Reports Generation](#flow-i--reports-generation-ad-hoc--scheduled)
   - [Flow J - Inventory: Sale → Stock Adjustment](#flow-j--inventory-sale--stock-adjustment-tahap-2)
   - [Flow K - Tax Calculation & Invoice](#flow-k--tax-calculation--invoice-tahap-3)
   - [Flow L - Multi-Branch](#flow-l--multi-branch-scope--switch)
   - [Flow M - User & Permission Changes](#flow-m--user--permission-changes-impact)
   - [Flow N - Backup & Restore](#flow-n--backup--restore)
   - [Flow O - Error Handling & Bug Hunter](#flow-o--error-handling--bug-hunter-observability-flow)
   - [Flow P - API / Event Contracts](#flow-p--api--event-contracts-high-level-mapping)

---

# Tahap 1 — Sistem Dasar (Pencatatan Keuangan)

Tahap ini fokus pada pencatatan transaksi, laporan, dan manajemen kas.

## 1. Home (Dashboard)

### 📊 Dashboard Utama

#### Tujuan:
Memberikan gambaran finansial secara cepat tanpa harus membuka laporan detail.

#### Fungsi yang ditampilkan:
1. **Ringkasan Keuangan**
   - Total uang masuk (periode berjalan)
   - Total uang keluar
   - Selisih (bersih)
   - Perbandingan bulan lalu (persentase naik/turun)
   - Menampilkan notifikasi jika cashflow negatif

2. **Posisi Kas & Bank**
   - Daftar seluruh akun:
     - Kas Toko
     - Bank Mandiri
     - BCA
     - E-Wallet (Dana/OVO)
   - Menampilkan saldo real-time setiap akun
   - Indikator warna:
     - Hijau: sehat
     - Oranye: menipis
     - Merah: mendekati nol

3. **Grafik Cash Flow**
   - Grafik pemasukan vs pengeluaran (line chart)
   - Bisa pilih filter:
     - Harian
     - Mingguan
     - Bulanan
   - Menampilkan tren naik/turun untuk forecasting

#### Alur Pengguna:
- Pengguna membuka halaman dashboard.
- Sistem memuat data ringkasan secara otomatis.
- Pengguna dapat mengklik elemen untuk drill-down ke detail transaksi atau laporan.

#### Tujuan Bisnis:
- Memungkinkan pemilik usaha untuk memantau kesehatan keuangan secara real-time, mendukung pengambilan keputusan cepat.

#### Data yang Diolah:
- Transaksi pemasukan dan pengeluaran.
- Saldo rekening.
- Data historis untuk perbandingan.

#### Hubungan Antar Fitur:
- Terintegrasi dengan modul Uang Masuk, Uang Keluar, dan Rekening & Kas untuk data real-time.
- Data dashboard mempengaruhi notifikasi di Pengaturan.

#### Integrasi:
- Dashboard memuat data dari API transaksi dan rekening.
- Event transaksi baru memicu refresh dashboard.

#### Potensi Error & Kontrol Validasi:
- Jika data tidak tersedia (misal koneksi database bermasalah), tampilkan pesan error dan gunakan cache.
- Validasi: Pastikan data numerik valid, hindari pembagian nol pada persentase.

#### UI/UX:
- Layout responsif dengan kartu-kartu KPI.
- Loading skeleton saat memuat data.
- Tooltip untuk penjelasan indikator warna.

---

## 2. Uang Masuk (Income / Pemasukan)

### A. Tambah Pemasukan

#### Tujuan:
Mencatat transaksi pemasukan dengan struktur data standar.

#### Input yang diperlukan:
- Tanggal pemasukan
- Jumlah uang
- Kategori pemasukan
- Rekening penerima (kas/bank/e-wallet)
- Sumber pemasukan (opsional: pelanggan)
- Metode pembayaran
- Catatan tambahan
- Upload bukti (foto/nota)

#### Alur UI/UX:
1. User klik "Tambah Pemasukan"
2. Form terbuka
3. User isi data
4. Sistem validasi:
   - jumlah tidak boleh 0
   - rekening wajib diisi
   - tanggal tidak boleh lebih besar dari hari ini (opsional rules)
5. Sistem simpan data
6. Sistem update saldo rekening otomatis

#### Tujuan Bisnis:
- Memastikan semua pemasukan tercatat akurat untuk laporan keuangan.

#### Data yang Diolah:
- Transaksi pemasukan dengan metadata lengkap.

#### Hubungan Antar Fitur:
- Terhubung dengan Rekening & Kas untuk update saldo.
- Kategori digunakan dari modul Kategori.

#### Integrasi:
- Setelah simpan, dispatch event untuk update dashboard dan laporan.

#### Potensi Error & Kontrol Validasi:
- Validasi format tanggal, jumlah positif.
- Jika rekening tidak aktif, tolak transaksi.

#### UI/UX:
- Form modal dengan validasi real-time.
- Preview bukti upload.

### B. Daftar Pemasukan

#### Fungsi:
- Menampilkan seluruh pemasukan dalam bentuk tabel
- Bisa filter berdasarkan:
  - tanggal
  - kategori
  - rekening
  - nominal rentang
  - keyword catatan

#### Aksi yang tersedia:
- Edit
- Hapus
- Lihat detail transaksi
- Export Excel/PDF

#### Alur Pengguna:
- Pengguna filter dan cari transaksi.
- Klik aksi untuk edit/hapus/detail.

#### Tujuan Bisnis:
- Memudahkan review dan audit transaksi pemasukan.

#### Data yang Diolah:
- List transaksi pemasukan dengan filter.

#### Hubungan Antar Fitur:
- Edit terintegrasi dengan Tambah Pemasukan.
- Export menggunakan modul Laporan.

#### Integrasi:
- Query database dengan filter dinamis.

#### Potensi Error & Kontrol Validasi:
- Validasi permission untuk edit/hapus.

#### UI/UX:
- Tabel sortable dengan pagination.
- Modal konfirmasi untuk hapus.

### C. Template Pemasukan Berulang

#### Tujuan:
Menghindari input manual untuk pemasukan rutin.

#### Contoh penggunaan:
- Piutang pelanggan bulanan
- Pendapatan sewa
- Pendapatan rutin lain

#### Fungsi:
- Set jadwal: harian / mingguan / bulanan
- Sistem otomatis membuat transaksi saat jadwal tiba
- User dapat mengaktifkan atau menonaktifkan template

#### Alur Pengguna:
- Buat template dengan payload transaksi.
- Sistem jalankan scheduler.

#### Tujuan Bisnis:
- Otomasi untuk efisiensi.

#### Data yang Diolah:
- Template dengan jadwal.

#### Hubungan Antar Fitur:
- Terintegrasi dengan Tambah Pemasukan untuk generate transaksi.

#### Integrasi:
- Scheduler sebagai background job.

#### Potensi Error & Kontrol Validasi:
- Validasi jadwal tidak konflik.

#### UI/UX:
- Form template dengan preview jadwal.

### D. Pemasukan Multi-Item (Kasir Mode)

#### Tujuan:
Mendukung transaksi penjualan dengan multiple produk dalam satu transaksi, seperti mode kasir.

#### Input yang diperlukan:
- Rekening penerima
- Kategori pemasukan
- Multiple produk dengan jumlah masing-masing
- Deskripsi transaksi
- Tanggal

#### Alur UI/UX:
1. User pilih mode "Dari Produk"
2. Sistem tampilkan grid produk yang tersedia
3. User klik produk untuk menambah ke keranjang
4. User atur jumlah per produk di keranjang
5. Sistem hitung total otomatis
6. User submit transaksi
7. Sistem validasi stok dan buat transaksi

#### Logika Utama:
- Validasi stok sebelum transaksi
- Kurangi stok otomatis setelah transaksi berhasil
- Buat record income_items untuk setiap produk
- Hitung total dari semua item

#### Tujuan Bisnis:
- Efisiensi untuk penjualan retail dengan multiple item
- Tracking penjualan per produk
- Otomasi update inventory

#### Data yang Diolah:
- Transaksi utama dengan total amount
- Income_items table untuk detail per produk
- Stock movements untuk tracking inventory

#### Hubungan Antar Fitur:
- Terhubung dengan Produk & Layanan untuk data produk
- Update Persediaan secara otomatis
- Terintegrasi dengan Laporan Penjualan

#### Integrasi:
- Event StockAdjusted setelah transaksi
- Update dashboard dan laporan real-time

#### Potensi Error & Kontrol Validasi:
- Stok tidak mencukupi untuk salah satu produk
- Produk tidak aktif atau tidak tersedia
- Validasi permission untuk produk tertentu

#### UI/UX:
- Interface keranjang belanja dengan tabel item
- Real-time calculation total
- Validasi stok dengan alert
- Responsive design untuk tablet POS

---

## 3. Uang Keluar (Expense / Pengeluaran)

Struktur sama seperti pemasukan, tetapi fokus ke transaksi keluar.

### A. Tambah Pengeluaran

#### Input:
- tanggal
- jumlah
- kategori pengeluaran
- rekening sumber
- vendor (opsional)
- metode pembayaran
- catatan
- upload bukti

#### Logika utama:
- Sistem mengecek saldo rekening:
  - Jika saldo kurang → muncul peringatan
  - User tetap dapat override jika diberikan permission

#### Alur UI/UX:
Mirip dengan Tambah Pemasukan, dengan tambahan check saldo.

#### Tujuan Bisnis:
- Kontrol pengeluaran untuk mencegah overdraft.

#### Data yang Diolah:
- Transaksi pengeluaran.

#### Hubungan Antar Fitur:
- Check saldo dari Rekening & Kas.

#### Integrasi:
- Event untuk update dashboard.

#### Potensi Error & Kontrol Validasi:
- Warning jika saldo tidak cukup, tapi allow jika permission.

#### UI/UX:
- Alert warna merah untuk saldo rendah.

### B. Daftar Pengeluaran

Tabel transaksi keluar lengkap dengan filter & export.

Mirip dengan Daftar Pemasukan.

### C. Template Pengeluaran Berulang

Digunakan untuk biaya rutin:
- gaji
- sewa kantor
- listrik
- internet

Mirip dengan Template Pemasukan.

---

## 4. Rekening & Kas

### A. Daftar Rekening

#### Tujuan:
Mengelola akun tempat penyimpanan uang.

#### Data rekening yang disimpan:
- Nama rekening / jenis
- Bank / tipe
- Nomor rekening
- Saldo awal
- Saldo berjalan
- Status aktif/non-aktif

#### Alur Pengguna:
- Lihat list rekening.
- Tambah/edit rekening.

#### Tujuan Bisnis:
- Pusat manajemen akun keuangan.

#### Data yang Diolah:
- Master data rekening.

#### Hubungan Antar Fitur:
- Digunakan di semua transaksi untuk rekening sumber/tujuan.

#### Integrasi:
- Update saldo otomatis dari transaksi.

#### Potensi Error & Kontrol Validasi:
- Validasi nomor rekening unik.

#### UI/UX:
- List dengan status badge.

### B. Transfer Antar Rekening

#### Fungsi:
Memindahkan uang antar akun secara internal.

#### Alur proses:
1. User memilih rekening sumber → rekening tujuan
2. Input jumlah
3. Sistem cek saldo sumber
4. Sistem membuat 2 transaksi otomatis:
   - pengeluaran di rekening sumber
   - pemasukan di rekening tujuan

#### Tujuan bisnis:
Agar pergerakan uang internal tercatat rapi.

#### Data yang Diolah:
- Transaksi transfer linked.

#### Hubungan Antar Fitur:
- Menggunakan modul Uang Masuk/Keluar untuk create transaksi.

#### Integrasi:
- Double-entry bookkeeping.

#### Potensi Error & Kontrol Validasi:
- Sumber != tujuan.

#### UI/UX:
- Form transfer dengan preview.

### C. Mutasi Rekening

#### Fungsi:
Menampilkan seluruh transaksi yang hanya terkait rekening tertentu.

#### Filter:
- rentang tanggal
- tipe transaksi (masuk / keluar / transfer)

#### Alur Pengguna:
- Pilih rekening, filter, lihat mutasi.

#### Tujuan Bisnis:
- Rekonsiliasi bank.

#### Data yang Diolah:
- Transaksi per rekening.

#### Hubungan Antar Fitur:
- Terintegrasi dengan Transfer dan transaksi biasa.

#### Integrasi:
- Import bank statement untuk matching.

#### Potensi Error & Kontrol Validasi:
- Matching fuzzy untuk rekonsiliasi.

#### UI/UX:
- Tabel mutasi dengan reconcile button.

---

## 5. Kategori

Kategori adalah basis pengelompokan data.

### Kategori Pemasukan

Contoh:
- Penjualan
- Investasi masuk
- Refund
- Bonus

### Kategori Pengeluaran

Contoh:
- Operasional
- Gaji
- Transportasi
- Maintenance

#### Fungsi kategori:
- Mempermudah pelaporan
- Memperdalam analisis cashflow
- Digunakan untuk grafik

#### Alur Pengguna:
- Tambah/edit kategori.
- Assign ke transaksi.

#### Tujuan Bisnis:
- Segmentasi data untuk insight.

#### Data yang Diolah:
- Master kategori.

#### Hubungan Antar Fitur:
- Required di semua transaksi.

#### Integrasi:
- Filter di laporan.

#### Potensi Error & Kontrol Validasi:
- Tidak bisa hapus jika digunakan.

#### UI/UX:
- Tree view untuk sub-kategori.

---

## 6. Laporan

Modul paling penting untuk analisis dan evaluasi keuangan.

### A. Laporan Harian

- Total pemasukan hari itu
- Total pengeluaran
- Saldo per rekening
- Aktivitas transaksi per jam

### B. Laporan Mingguan

- Tren pemasukan/pengeluaran
- Grafik cashflow mingguan
- Analisis minggu terbaik/terburuk

### C. Laporan Bulanan

- Rekap full bulan
- Dibandingkan bulan lalu
- Pie chart kategori terbanyak

### D. Laporan Laba/Rugi

- Penghasilan bersih
- Beban biaya
- Margin keuntungan

### E. Laporan Arus Kas

- Cashflow operasional
- Cashflow investasi
- Cashflow pendanaan

### F. Laporan per Rekening

- Semua pemasukan/keluaran per akun
- Bisa export

#### Alur Pengguna:
- Pilih tipe laporan, filter, generate.

#### Tujuan Bisnis:
- Analisis performa keuangan.

#### Data yang Diolah:
- Aggregasi transaksi.

#### Hubungan Antar Fitur:
- Menggunakan data dari semua modul transaksi.

#### Integrasi:
- Scheduled reports via email.

#### Potensi Error & Kontrol Validasi:
- Validasi range tanggal.

#### UI/UX:
- Interactive charts, export options.

---

## 7. Pengaturan

### A. Profil Usaha

- Nama usaha
- Logo
- Alamat
- Telepon
- Email perusahaan

### B. Mata Uang

- Mata uang default
- Format desimal
- Pemisah ribuan

### C. Backup Data

- Download database
- Restore database
- Jadwal backup otomatis

#### Alur Pengguna:
- Edit settings.

#### Tujuan Bisnis:
- Konfigurasi sistem.

#### Data yang Diolah:
- Config data.

#### Hubungan Antar Fitur:
- Mempengaruhi seluruh sistem.

#### Integrasi:
- Backup sebagai job.

#### Potensi Error & Kontrol Validasi:
- Validasi format email.

#### UI/UX:
- Form settings dengan save button.

---

# Tahap 2 — Sistem Menengah (Manajemen Persediaan)

Tahap 2 menambah modul INVENTORY & CUSTOMER.

## 1. Produk & Layanan

### A. Daftar Produk

- melihat seluruh produk
- melihat stok
- melihat harga modal & harga jual

### B. Tambah Produk

Input:
- kategori produk
- kode barang
- harga modal
- harga jual
- stok awal

### C. Kategori Produk

Pengelompokan produk untuk laporan

### D. Daftar Layanan

- Layanan jasa tanpa stok (cont: service, cutter, konsultasi)

### E. Tambah Layanan

- Nama layanan
- harga layanan

#### Alur Pengguna:
- Manage produk/layanan.

#### Tujuan Bisnis:
- Basis untuk inventory.

#### Data yang Diolah:
- Master produk/layanan.

#### Hubungan Antar Fitur:
- Terhubung dengan Persediaan untuk stok.

#### Integrasi:
- Produk linked ke transaksi penjualan.

#### Potensi Error & Kontrol Validasi:
- Kode unik.

#### UI/UX:
- CRUD interface.

---

## 2. Persediaan

### A. Stok Bahan

- list bahan baku
- jumlah stok
- lokasi penyimpanan
- nilai persediaan

### B. Tambah Stok

- input stok masuk
- alasan stok masuk
- pemasok

### C. Koreksi Stok

Digunakan jika stok fisik != stok sistem:
- rusak
- hilang
- audit fisik

### D. Notifikasi Stok Minimum

Sistem memberikan alert jika stok berada di bawah minimum

#### Alur Pengguna:
- Monitor dan adjust stok.

#### Tujuan Bisnis:
- Kontrol inventory.

#### Data yang Diolah:
- Stock movements.

#### Hubungan Antar Fitur:
- Adjust stok dari penjualan.

#### Integrasi:
- Event dari transaksi.

#### Potensi Error & Kontrol Validasi:
- Stok tidak negatif tanpa permission.

#### UI/UX:
- Alerts untuk low stock.

---

## 3. Pelanggan

### A. Daftar Pelanggan

- Nama
- No telp
- Email
- Riwayat transaksi

### B. Tambah Pelanggan

### C. Riwayat Transaksi

- melihat seluruh transaksi terkait pelanggan

#### Alur Pengguna:
- Manage customer data.

#### Tujuan Bisnis:
- CRM dasar.

#### Data yang Diolah:
- Customer master.

#### Hubungan Antar Fitur:
- Linked ke transaksi.

#### Integrasi:
- Import dari penjualan.

#### Potensi Error & Kontrol Validasi:
- Email valid.

#### UI/UX:
- Customer profile page.

---

## 4. Laporan Tambahan Tahap 2

### A. Laporan Penjualan

- total penjualan
- produk terlaris
- penjualan per pelanggan

### B. Laporan Persediaan

- stok masuk/keluar
- koreksi stok
- nilai persediaan

Mirip dengan Laporan Tahap 1.

---

# Tahap 3 — Sistem Expert (Perpajakan & Multi-Cabang)

---

## 1. Pajak

### A. Hitung Pajak

- menghitung PPN, PPh, pajak transaksi
- bisa atur tarif

### B. Faktur Pajak

- auto-generate faktur
- nomor seri faktur

### C. Laporan Pajak

- export untuk pelaporan ke kantor pajak

#### Alur Pengguna:
- Configure tax, generate invoices.

#### Tujuan Bisnis:
- Compliance pajak.

#### Data yang Diolah:
- Tax calculations.

#### Hubungan Antar Fitur:
- Integrated ke transaksi.

#### Integrasi:
- Tax service.

#### Potensi Error & Kontrol Validasi:
- Tarif valid.

#### UI/UX:
- Tax settings.

---

## 2. Multi-Cabang

### A. Daftar Cabang

- nama cabang
- alamat
- PIC
- jumlah staff

### B. Tambah Cabang

### C. Switch Cabang

- user dapat bekerja di cabang tertentu
- data transaksi terpisah antar cabang

#### Alur Pengguna:
- Switch branch context.

#### Tujuan Bisnis:
- Multi-location management.

#### Data yang Diolah:
- Branch scope.

#### Hubungan Antar Fitur:
- Scope all data.

#### Integrasi:
- Branch filter.

#### Potensi Error & Kontrol Validasi:
- Permission per branch.

#### UI/UX:
- Branch selector.

---

## 3. Pengguna

### A. Daftar Pengguna

Melihat semua user

### B. Tambah Pengguna

- nama
- email
- role
- cabang (opsional)

### C. Atur Permission

- setiap role punya akses:
  - view
  - create
  - update
  - delete

#### Alur Pengguna:
- Manage users and roles.

#### Tujuan Bisnis:
- Access control.

#### Data yang Diolah:
- User roles.

#### Hubungan Antar Fitur:
- Affects all modules.

#### Integrasi:
- Auth system.

#### Potensi Error & Kontrol Validasi:
- Unique email.

#### UI/UX:
- Role matrix.

---

## 4. Laporan Tambahan

### A. Laporan Multi-Cabang

- perbandingan performa cabang
- pemasukan/pengeluaran per cabang

### B. Laporan Kinerja Cabang

- efisiensi
- profit cabang
- cost cabang

Mirip dengan laporan sebelumnya.

---

# Flowchart Level Tinggi

## Cara baca

* Setiap blok ditulis: **Nama Blok — (Peran)**
* Panah / urutan: `Step 1 → Step 2`
* Keputusan ditandai `?` dan bercabang: `Jika YA → ... / Jika TIDAK → ...`
* Event internal / webhook ditandai `Event: ...`
* Output / artefak (data/file/email) dicantumkan setelah `->`

## Flowchart 0 — High-level system overview (entry point)

**User** → **Auth Gateway** → **Role Router** → [Dashboard | Finance | Inventory | Tax/Multi-Branch]

* Auth Gateway: autentikasi + policy check
* Role Router: jika role = admin → Admin Dashboard; jika role = finance → Finance Dashboard; dll.

## Flow A — Login & Session (Auth Flow)

1. **User accesses login page** (User)
2. **Submit credentials** → **Auth Service**
3. **Auth Service validates**:
   * if credentials invalid → **Return error** → show error UI → end
   * if valid → continue
4. **Auth Service checks user status**:
   * inactive/locked? → show message "Akun non-aktif" → end
5. **Auth Service loads roles & permissions** → attach to session token
6. **Role Router**:
   * if single role → redirect to role-default dashboard
   * if multi-role → show role-selection UI (or default last-used)
7. **Session established** → `Event: user.logged_in` dispatched
8. **Post-login hooks**:
   * update last_login timestamp
   * push notifications (pending items), load quick cache for dashboard

**Errors / Edge:** account locked after N failures; 2FA required → extra OTP flow between step 3–5.

## Flow B — Dashboard Data Load (on login / page open)

1. **Dashboard UI** requests summary APIs concurrently:
   * `GET /summary/kas-bank`
   * `GET /summary/cashflow?range=30d`
   * `GET /transactions/recent?limit=10`
2. **Backend** aggregates:
   * query `accounts`, `transactions`, `recurring_templates`
   * apply permission filters (per business/branch)
3. **Backend** returns:
   * KPI cards, chart series, recent transactions
4. **UI**:
   * render skeleton → fill on response
   * attach action buttons for quick-add (open form modals)

**Errors / Edge:** slow report queries → return cached snapshot + background refresh.

## Flow C — Tambah Pemasukan (single transaction flow)

Actors: User (role with `transactions.create`), System

1. **User opens Add Income form** → UI gets account list, category list
2. **User fills form** → click Submit
3. **Client validation** (non-empty, positive amount)
4. **Submit -> Backend Transaction API**
5. **Backend** validates business rules:
   * account exists & active?
   * category valid?
   * currency match or exchange rate present?
   * user has permission & branch scope?
   * if recurring flag set → validate recurring params
6. **If validation fails** → return error -> UI shows message -> stop
7. **If passes** → **Begin DB transaction**:
   * insert `transactions` record (type = income)
   * increment account balance atomically
   * append audit_log entry
   * if attachment uploaded -> store file and link
8. **Commit DB transaction**
9. **Dispatch Event: TransactionCreated** (payload: txn_id, business_id, amount, account_id)
10. **Event listeners**:
    * update analytics cache
    * notify watchers (e.g., accounting manager)
    * if product linked (Tahap 2) → dispatch StockAdjusted
11. **Return success** → UI shows toast + detail view

**Edge cases / decisions:**
* If DB commit fails -> rollback -> show error and create incident log.
* If amount > approval_threshold -> create txn as `pending` instead of posted; notify approver.

## Flow D — Tambah Pengeluaran (mirror of income with overdraft check)

Same sequence as Flow C with these differences:
* On validation: check `account_balance >= amount` ?
  * If NO and overdraft not allowed -> block and show warning
  * If NO but user has `allow_overdraft` -> allow with flagged negative balance
* On commit: decrement account balance
* Event: TransactionCreated (type=expense)

## Flow E — Transfer Antar Rekening (double-entry internal transfer)

1. **User opens Transfer form**
2. **User selects FromAccount, ToAccount, Amount, Fee (optional)**
3. **Backend validation**:
   * accounts differ?
   * fromAccount balance sufficient? (or check permission)
4. **If validation fails** -> error
5. **If passes** -> Begin DB transaction:
   * insert transaction A: type = transfer_out (account=fromAccount, amount)
   * insert transaction B: type = transfer_in (account=toAccount, amount)
   * link both via `linked_txn_id`
   * if fee present -> insert third transaction fee (account=feeAccount)
   * update balances for involved accounts
   * audit log entries
6. **Commit**
7. **Dispatch Event: TransferExecuted** (payload includes linked txn ids)
8. **UI**: success message + entries visible in mutasi both accounts

**Edge cases:**
* Currency difference: must include exchange rate conversion; store rate on each side.

## Flow F — Recurring Templates (scheduler)

1. **User creates Template** (name, payload (transaction template), frequency, start/end, auto_post flag)
2. **Template saved with next_run_at computed**
3. **Scheduler (cron/queue worker)** periodically:
   * find templates where `next_run_at <= now` and `enabled = true`
   * for each template:
     * acquire unique lock (prevent double-run)
     * create transaction(s) from payload:
       * if `auto_post = true` -> create posted transaction
       * else -> create draft for manual review
     * set `last_run_at = now`, compute `next_run_at = computeNext(template)`
     * on success -> emit `RecurringTemplateExecuted` (status=success)
     * on failure -> mark template `last_failed_at`, emit `RecurringTemplateExecuted` (status=failure) and notify admin
4. **Notifications**:
   * success emails (optional)
   * failure -> admin in-app + email with error details

**Idempotency**: template execution must be idempotent (store run_id) to avoid duplicate transactions on retries.

## Flow G — Mutasi Rekening & Reconciliation

1. **User opens Account Mutations view** (for one account)
2. **System loads all transactions for account** (filterable)
3. **User can import bank statement** (CSV/MT940)
4. **Matching process**:
   * auto-match by amount + date fuzzy (configurable tolerance)
   * if single match -> mark reconciled
   * if ambiguous -> show candidate matches to user
5. **User confirms matches** -> system marks `reconciled = true` and writes audit log
6. **Unmatched bank rows remain** -> user can create new transactions (via Add Expense/Income) or mark as fees

**Edge cases:**
* Duplicate bank entries -> require manual resolution
* Partial matches (split payment) -> allow splitting bank row into multiple txn matches

## Flow H — Category Management (affects reports)

1. **Admin creates category (income/expense)**
2. **Category used in transactions**
3. **If user tries to delete category**:
   * system checks: is category used in transactions?
     * if YES -> prevent hard delete, allow `deactivate` only
     * if NO -> allow delete
4. **When deactivated** -> transactions keep reference, but category no longer selectable in new transactions

**Impact:** reports aggregate by category; deactivating affects available filters but not historical aggregations.

## Flow I — Reports Generation (ad hoc & scheduled)

1. **User selects report type + filters (date range, accounts, categories, branch)**
2. **Backend validates filters & permissions**
3. **Report engine**:
   * either run query real-time (fast indexes/aggregations)
   * or use pre-aggregated cache for heavy queries (daily aggregates)
4. **Return results**:
   * chart series
   * table with drill-down links (each cell clickable → list of transactions)
5. **Export**:
   * user can request PDF/Excel → if heavy -> enqueue job `GenerateReport` and provide download link when ready
6. **Schedule**:
   * user can save report template and schedule email delivery; scheduler runs like recurring templates

**Error handling:** if report job fails -> record failure, retry with backoff, notify user.

## Flow J — Inventory: Sale → Stock Adjustment (Tahap 2)

1. **Sale transaction created linked to product(s)**
2. **On TransactionCreated event**:
   * listener checks `meta.products` payload
   * for each product -> create `stock_movement` (type = out, quantity)
   * update product `stock_level` atomically
   * if stock_level < min_threshold -> emit `LowStockAlert`
3. **If stock negative after update**:
   * mark product `backorder = true`
   * notify inventory manager

**Reconciliation:** stock adjustments must be auditable (who adjusted, reason).

## Flow K — Tax Calculation & Invoice (Tahap 3)

1. **Transaction created flagged with taxable items**
2. **Tax service** calculates taxes per item (using tax classes, rates) and adds tax lines to transaction meta
3. **If invoice required**:
   * generate invoice (numbering rules per business/branch)
   * produce PDF invoice + tax lines
4. **Tax reporting**:
   * aggregated per period for filing
   * export format compatible with local tax authority (if applicable)

**Edge cases:** tax-exempt customers, multiple tax rates, credit notes.

## Flow L — Multi-Branch (scope & switch)

1. **User assigned to 0..N branches**
2. **On branch switch**:
   * UI sets active_branch in session
   * subsequent reads/writes scoped to active_branch (unless user is super-admin)
3. **Creating transaction**:
   * include `branch_id` on transaction record
   * reports can be filtered per branch or consolidated
4. **Inter-branch transfer**:
   * similar to Transfer flow but create two transactions tied to different branches and record inter-branch transfer record for consolidation

**Edge:** permissions can be limited per-branch (user can create in assigned branches only).

## Flow M — User & Permission Changes (impact)

1. **Admin updates role/permission for a user**
2. **Changes applied immediately**:
   * if user currently logged in -> session/claims may need refresh (force reauth or lazy policy check)
3. **If permission removed while user editing**:
   * subsequent save attempt fails with authorization error and prompts re-login or contact admin

**Security note:** always perform policy checks server-side on each action, not only in UI.

## Flow N — Backup & Restore

1. **Admin triggers backup** (manual or scheduled)
2. **System exports DB dump + storage assets** to secure storage (S3) with retention policy
3. **Restore**: admin uploads backup -> system validates schema compatibility -> dry-run validation -> restore (with warnings)
4. **On restore** -> emit `SystemRestored` event and log audit entry

**Safeguards:** require admin re-confirmation + multi-step confirmation to prevent accidental overwrite.

## Flow O — Error Handling & Bug Hunter (observability flow)

1. **Any unhandled exception** -> global error handler:
   * capture structured error (message, stack, user_id, request_id, payload)
   * log to persistent storage (DB/log aggregator)
   * send to error tracker (Sentry) with severity
   * if critical -> create incident ticket/notify on-call
2. **Bug hunter module**:
   * periodically scans logs for patterns (repeated error signatures)
   * groups similar errors into incidents
   * suggests possible root causes and recommended fixes (AI-assisted heuristics)
   * enables "reproduction steps" capture (user session replay / traces)
3. **DevOps flow**:
   * alert -> triage -> assign -> deploy patch -> close incident

## Flow P — API / Event Contracts (high-level mapping)

* `TransactionCreated` → payload (id, type, amount, account, meta)
* `TransferExecuted` → payload (from_txn_id, to_txn_id, amount)
* `RecurringTemplateExecuted` → (template_id, result, created_txn_id)
* `StockAdjusted` → (product_id, delta, reason, reference_txn_id)
* `UserLoggedIn` → (user_id, ip, time)

Use these events to decouple modules (analytics, notifications, inventory, tax) and facilitate microservice integration later.

## Final: Decision table / quick reference (when building flows)

* Transaction > approval_threshold? → create `pending` & notify approver
* Transfer across currencies? → require exchange rate and store it
* Recurring job failure? → mark template failed & notify admin; retry with exponential backoff
* Category delete requested and used in transactions? → prevent deletion; set `inactive` only
* Backup restore invoked? → require 2-step auth + admin confirmation
* Role change while session active? → force token refresh or re-evaluate policies per request

---

Dokumen ini selesai dan siap digunakan sebagai blueprint untuk pengembangan sistem Akuntansi Sibuku.