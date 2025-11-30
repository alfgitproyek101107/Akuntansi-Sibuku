# Sistem Manajemen Pajak - Akuntansi Sibuku

## 📋 Ringkasan Sistem Pajak

Sistem manajemen pajak pada aplikasi Akuntansi Sibuku adalah modul komprehensif yang dirancang untuk mengelola semua aspek perpajakan dalam bisnis, dengan integrasi penuh ke dalam sistem transaksi dan harga produk/jasa.

## 🎯 Fungsi Utama Sistem Pajak

### 1. **Manajemen Pengaturan Pajak**
- Konfigurasi tarif pajak per cabang bisnis
- Pengaturan jenis pajak yang didukung (PPN, PPN UMKM, PPh 21, PPh 22, PPh 23)
- Pengaturan NPWP dan informasi perusahaan
- Konfigurasi integrasi CoreTax

### 2. **Perhitungan Pajak Otomatis**
- Perhitungan pajak otomatis pada setiap transaksi
- Dukungan multiple jenis pajak dengan tarif berbeda
- Perhitungan pajak berdasarkan subtotal transaksi
- Opsi untuk memasukkan pajak ke dalam harga atau terpisah

### 3. **Generasi Faktur Pajak**
- Pembuatan faktur pajak otomatis dari transaksi
- Penomoran faktur pajak sesuai standar DJP
- Pelacakan status faktur pajak (Draft, Generated, Sent, Approved, Rejected)
- Manajemen data penjual dan pembeli

### 4. **Integrasi CoreTax**
- Kirim faktur pajak ke sistem CoreTax DJP
- Cek status faktur pajak secara real-time
- Pelacakan QR Code untuk faktur yang disetujui
- Logging lengkap untuk semua interaksi API

## 🔧 Fitur Lengkap Sistem Pajak

### **Dashboard Pajak**
- Ringkasan statistik pajak (total faktur, total pajak, faktur disetujui, log error)
- Quick actions untuk pengaturan dan manajemen faktur
- Daftar faktur terbaru dengan status terkini

### **Pengaturan Pajak**
- Konfigurasi per cabang bisnis
- Pengaturan tarif pajak (PPN: 11%, PPN UMKM: 0.5%, dll)
- Informasi perusahaan (nama, NPWP, alamat)
- Pengaturan CoreTax API (token, URL, retry attempts)
- Opsi auto-sync dan auto-calculation

### **Manajemen Faktur Pajak**
- Daftar semua faktur pajak dengan filter dan pencarian
- Detail lengkap faktur dengan informasi penjual/pembeli
- Tabel item dengan perhitungan pajak
- Ringkasan subtotal, pajak, dan total
- Referensi ke transaksi asal

### **Log Aktivitas**
- Pelacakan semua interaksi dengan CoreTax
- Status success/failed/retry untuk setiap request
- Response message dari API CoreTax
- History lengkap untuk audit trail

## 🔗 Integrasi dengan Sistem

### **Integrasi dengan Transaksi**
✅ **SUDAH TERINTEGRASI**
- Setiap transaksi income/expense otomatis membuat faktur pajak
- Tax invoice ter-link ke transaction melalui `transaction_id`
- Perhitungan pajak berdasarkan amount transaksi
- Sinkronisasi status antara transaksi dan faktur pajak

### **Integrasi dengan Harga Produk/Jasa**
✅ **SUDAH TERINTEGRASI**
- Sistem dapat meng-include pajak dalam harga (`include_tax_in_price`)
- Atau menampilkan pajak terpisah dari harga pokok
- Perhitungan pajak pada level item transaksi
- Dukungan multiple tax rates per produk/jasa

### **Integrasi dengan Cabang Bisnis**
✅ **SUDAH TERINTEGRASI**
- Setiap cabang memiliki pengaturan pajak sendiri
- Faktur pajak di-scope per cabang
- Branch isolation untuk data pajak
- Multi-branch tax compliance

## 📊 Alur Kerja Sistem Pajak

### **1. Setup Awal**
```
Pengguna → Pengaturan Pajak → Konfigurasi tarif, NPWP, CoreTax API
```

### **2. Transaksi dengan Pajak**
```
Transaksi Dibuat → Sistem Cek Pengaturan Pajak → Faktur Pajak Auto-Generated
```

### **3. Pengiriman ke CoreTax**
```
Faktur Generated → Kirim ke CoreTax → Update Status → QR Code
```

### **4. Monitoring & Audit**
```
Dashboard Pajak → Log Aktivitas → Status Real-time → Audit Trail
```

## 🏗️ Arsitektur Teknis

### **Model Database**
- `TaxSetting`: Konfigurasi pajak per cabang
- `TaxInvoice`: Data faktur pajak
- `TaxLog`: Log interaksi CoreTax
- `Transaction`: Enhanced dengan field pajak

### **Service Layer**
- `CoreTaxService`: Integrasi API CoreTax
- `TaxInvoiceService`: Logic pembuatan faktur
- `TaxCalculator`: Perhitungan pajak

### **Controller Layer**
- `TaxController`: Main tax management
- Enhanced transaction controllers dengan tax logic

## 🎯 Jenis Pajak yang Didukung

| Kode | Nama | Tarif Default | Keterangan |
|------|------|---------------|------------|
| ppn | PPN | 11% | Pajak Pertambahan Nilai |
| ppn_umkm | PPN UMKM | 0.5% | PPN khusus UMKM |
| pph_21 | PPh 21 | Variabel | Pajak Penghasilan Pasal 21 |
| pph_22 | PPh 22 | Variabel | Pajak Penghasilan Pasal 22 |
| pph_23 | PPh 23 | 2% | Pajak Penghasilan Pasal 23 |

## 📈 Status Faktur Pajak

| Status | Deskripsi | Action Selanjutnya |
|--------|-----------|-------------------|
| draft | Faktur dalam draft | Generate faktur |
| generated | Faktur sudah dibuat | Kirim ke CoreTax |
| sent | Sudah dikirim ke CoreTax | Cek status |
| approved | Disetujui DJP | QR Code tersedia |
| rejected | Ditolak DJP | Perbaiki dan kirim ulang |

## 🔒 Keamanan & Compliance

- **Branch Isolation**: Data pajak terpisah per cabang
- **Audit Trail**: Log lengkap semua aktivitas
- **API Security**: Secure communication dengan CoreTax
- **Data Validation**: Validasi NPWP dan data faktur
- **Error Handling**: Comprehensive error tracking

## 🚀 Keunggulan Sistem

1. **Otomasi Penuh**: Dari transaksi ke faktur pajak otomatis
2. **Multi-Branch**: Support bisnis dengan multiple cabang
3. **Real-time Sync**: Integrasi real-time dengan DJP
4. **User-Friendly**: Interface yang intuitif dan mudah digunakan
5. **Scalable**: Mendukung pertumbuhan bisnis
6. **Compliant**: Sesuai dengan regulasi perpajakan Indonesia

## 📝 Kesimpulan

Sistem pajak pada Akuntansi Sibuku adalah solusi komprehensif yang **SUDAH TERINTEGRASI PENUH** dengan sistem transaksi dan harga. Sistem ini mengotomasi seluruh proses perpajakan mulai dari perhitungan pajak, pembuatan faktur, hingga pengiriman ke CoreTax DJP. Dengan dukungan multi-branch dan audit trail lengkap, sistem ini memastikan compliance perpajakan yang optimal untuk bisnis pengguna.