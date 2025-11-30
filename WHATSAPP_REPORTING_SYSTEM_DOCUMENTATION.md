# WhatsApp Reporting System - Dokumentasi Lengkap

## 📋 Daftar Isi
1. [Ikhtisar Sistem](#ikhtisar-sistem)
2. [Arsitektur & Komponen](#arsitektur--komponen)
3. [Menu & Navigasi](#menu--navigasi)
4. [Konfigurasi & Pengaturan](#konfigurasi--pengaturan)
5. [Fitur Laporan](#fitur-laporan)
6. [Flow Sistem](#flow-sistem)
7. [Troubleshooting](#troubleshooting)
8. [Error Codes & Solutions](#error-codes--solutions)

---

## 🎯 Ikhtisar Sistem

Sistem WhatsApp Reporting adalah fitur lengkap untuk mengirim laporan keuangan otomatis dan manual via WhatsApp menggunakan API Fonnte. Sistem ini terintegrasi penuh dengan aplikasi Akuntansi Sibuku dan menyediakan berbagai jenis laporan yang dapat dikirim secara terjadwal atau manual.

### ✨ Fitur Utama
- ✅ Pengiriman laporan otomatis (harian, mingguan, bulanan)
- ✅ Pengiriman laporan manual on-demand
- ✅ Test koneksi WhatsApp
- ✅ Logging lengkap semua aktivitas
- ✅ Multi-format laporan (sederhana/detail)
- ✅ Dukungan multi-cabang
- ✅ Error handling & retry mechanism
- ✅ UI/UX modern dan responsive

---

## 🏗️ Arsitektur & Komponen

### Backend Components

#### 1. Models
```php
// app/Models/WhatsAppLog.php
- Tabel: whatsapp_logs
- Fields: report_type, phone_number, status, message, response_data, error_message, retry_count, sent_at, user_id, branch_id
- Relationships: belongsTo User, belongsTo Branch

// app/Models/AppSetting.php
- Menyimpan semua konfigurasi WhatsApp
- Keys: whatsapp_*
- Groups: whatsapp
```

#### 2. Controllers
```php
// app/Http/Controllers/WhatsAppReportController.php
- Route: /reports/whatsapp/*
- Handle: Tampilan laporan, manual send, schedules management

// app/Http/Controllers/WhatsAppSettingsController.php
- Route: /settings/whatsapp/*
- Handle: Konfigurasi, test connection, manual reports
```

#### 3. Services
```php
// app/Services/WhatsAppService.php
- Core WhatsApp functionality
- Methods: sendMessage(), sendPDF(), validatePhoneNumber(), checkConnection()
- Providers: fonnte, ultramsg, cloud_api

// app/Services/WhatsAppReportGenerator.php
- Generate report content
- Methods: generateDaily(), generateWeekly(), generateMonthly()
```

#### 4. Jobs
```php
// app/Jobs/SendWhatsAppReport.php
- Queue job untuk pengiriman laporan
- Handle: Report generation, WhatsApp sending, logging
```

#### 5. Commands
```php
// app/Console/Commands/SendScheduledWhatsAppReports.php
- Artisan command: whatsapp-reports:send-scheduled
- Cron job untuk pengiriman terjadwal
```

### Database Tables

#### whatsapp_logs
```sql
CREATE TABLE whatsapp_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    report_type VARCHAR(255), -- daily, weekly, monthly, manual, test
    phone_number VARCHAR(255),
    status VARCHAR(255), -- success, failed, pending
    message TEXT NULL,
    response_data JSON NULL,
    error_message VARCHAR(255) NULL,
    retry_count INT DEFAULT 0,
    sent_at TIMESTAMP NULL,
    user_id BIGINT UNSIGNED NULL,
    branch_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (branch_id) REFERENCES branches(id) ON DELETE SET NULL,
    INDEX idx_report_type_status (report_type, status),
    INDEX idx_user_created_at (user_id, created_at)
);
```

#### app_settings (WhatsApp related)
```sql
-- WhatsApp Settings stored in app_settings table
whatsapp_owner_number VARCHAR(255) -- Nomor WhatsApp pemilik
whatsapp_api_key VARCHAR(255) -- Token API Fonnte
whatsapp_country_code VARCHAR(255) -- Kode negara (default: 62)
whatsapp_reports_enabled BOOLEAN -- Status aktif/nonaktif
whatsapp_daily_time TIME -- Waktu kirim laporan harian
whatsapp_weekly_day INT -- Hari kirim laporan mingguan (0-6)
whatsapp_weekly_time TIME -- Waktu kirim laporan mingguan
whatsapp_monthly_day INT -- Tanggal kirim laporan bulanan (1-31)
whatsapp_monthly_time TIME -- Waktu kirim laporan bulanan
whatsapp_report_format VARCHAR(255) -- simple/detailed
```

### Frontend Components

#### Views
```php
// resources/views/settings/whatsapp.blade.php
- Halaman pengaturan WhatsApp lengkap
- Form konfigurasi API & jadwal
- Test connection & manual send

// resources/views/reports/whatsapp/index.blade.php
- Halaman monitoring laporan
- Daftar jadwal (legacy, redirect ke settings)
- Manual send form
- Link ke settings page
```

#### Routes
```php
// routes/web.php
Route::prefix('settings/whatsapp')->group(function () {
    Route::get('/', [WhatsAppSettingsController::class, 'index']);
    Route::put('/', [WhatsAppSettingsController::class, 'update']);
    Route::post('/test-connection', [WhatsAppSettingsController::class, 'testConnection']);
    Route::post('/send-manual', [WhatsAppSettingsController::class, 'sendManualReport']);
    Route::get('/logs', [WhatsAppSettingsController::class, 'getLogs']);
});

Route::prefix('reports/whatsapp')->group(function () {
    Route::get('/', [WhatsAppReportController::class, 'index']);
    Route::post('/send-manual', [WhatsAppReportController::class, 'sendManual']);
    // ... other legacy routes
});
```

---

## 🧭 Menu & Navigasi

### Sidebar Navigation
```php
<!-- resources/views/layouts/app.blade.php -->
<li class="nav-item">
    <div class="nav-link submenu-toggle" onclick="toggleSubmenu('reportsMenu')">
        <i class="fas fa-chart-bar nav-icon"></i>
        <span class="nav-text">Laporan</span>
        <i class="fas fa-chevron-down submenu-chevron"></i>
    </div>
    <ul class="submenu" id="reportsMenu">
        <li><a class="submenu-link" href="{{ route('reports.index') }}">Ringkasan Laporan</a></li>
        <li><a class="submenu-link" href="{{ route('reports.whatsapp.index') }}">Laporan WhatsApp</a></li>
    </ul>
</li>

<!-- Settings Menu -->
<li class="nav-item">
    <div class="nav-link submenu-toggle" onclick="toggleSubmenu('settingsMenu')">
        <i class="fas fa-cogs nav-icon"></i>
        <span class="nav-text">Pengaturan</span>
        <i class="fas fa-chevron-down submenu-chevron"></i>
    </div>
    <ul class="submenu" id="settingsMenu">
        <li><a class="submenu-link" href="{{ route('settings.index') }}">Umum</a></li>
        <li><a class="submenu-link" href="{{ route('settings.whatsapp.index') }}">WhatsApp</a></li>
    </ul>
</li>
```

### Page Structure

#### 1. Reports Page (/reports/whatsapp)
```
┌─ Header ──────────────────────────────────────────────────────────┐
│ 🔔 Laporan WhatsApp                                                │
│ Pantau jadwal laporan dan kirim laporan manual via WhatsApp       │
│ [Pengaturan WhatsApp] [Buat Jadwal]                                │
└────────────────────────────────────────────────────────────────────┘

┌─ Schedules Section ────────────────────────────────────────────────┐
│ 📅 Jadwal Laporan                                                  │
│                                                                    │
│ ┌─ Schedule Card ──────────────────────────────────────────────┐   │
│ │ 📊 Daily Report                                             │   │
│ │ 📱 +6281234567890                                           │   │
│ │ 🕐 08:00                                                    │   │
│ │ ✅ Aktif                                                    │   │
│ │ [Edit Pengaturan] [Toggle] [Delete]                         │   │
│ └─────────────────────────────────────────────────────────────┘   │
└────────────────────────────────────────────────────────────────────┘

┌─ Manual Send Section ──────────────────────────────────────────────┐
│ 📤 Kirim Manual                                                   │
│                                                                    │
│ Tipe Laporan: [Daily ▼]                                           │
│ Nomor WhatsApp: [6281234567890]                                   │
│ Cabang: [Semua Cabang ▼]                                          │
│ [Kirim Laporan]                                                   │
└────────────────────────────────────────────────────────────────────┘
```

#### 2. Settings Page (/settings/whatsapp)
```
┌─ Header ──────────────────────────────────────────────────────────┐
│ ⚙️ Pengaturan WhatsApp                                            │
│ Konfigurasi pengiriman laporan otomatis via WhatsApp              │
└────────────────────────────────────────────────────────────────────┘

┌─ API Configuration ────────────────────────────────────────────────┐
│ 🔑 Konfigurasi API                                                │
│                                                                    │
│ Nomor WhatsApp Pemilik: [6281234567890]                           │
│ Token API Fonnte: [••••••••••••••••]                               │
│ Kode Negara: [62]                                                 │
└────────────────────────────────────────────────────────────────────┘

┌─ Automated Reports ────────────────────────────────────────────────┐
│ ⏰ Pengaturan Laporan Otomatis                                     │
│                                                                    │
│ ☑️ Aktifkan pengiriman laporan otomatis via WhatsApp              │
│                                                                    │
│ Waktu Laporan Harian: [08:00]                                     │
│ Hari Laporan Mingguan: [Senin ▼]                                  │
│ Waktu Laporan Mingguan: [08:00]                                   │
│ Tanggal Laporan Bulanan: [1 ▼]                                    │
│ Waktu Laporan Bulanan: [08:00]                                    │
│ Format Laporan: [Detail ▼]                                        │
│                                                                    │
│ [Simpan Pengaturan]                                               │
└────────────────────────────────────────────────────────────────────┘

┌─ Test & Manual Send ───────────────────────────────────────────────┐
│ 🧪 Test Koneksi WhatsApp                                          │
│ Nomor WhatsApp Test: [6281234567890]                              │
│ [Kirim Pesan Test]                                                │
│                                                                    │
│ 📤 Kirim Laporan Manual                                           │
│ Tipe Laporan: [Harian ▼]                                          │
│ Nomor WhatsApp: [6281234567890]                                   │
│ [Kirim Laporan]                                                   │
└────────────────────────────────────────────────────────────────────┘
```

---

## ⚙️ Konfigurasi & Pengaturan

### Environment Variables
```bash
# .env file
WHATSAPP_PROVIDER=fonnte
FONNTE_API_KEY=your_fonnte_api_key_here
FONNTE_URL=https://api.fonnte.com
WHATSAPP_DEMO_ENABLED=false
WHATSAPP_DEMO_TEST_NUMBER=6281234567890
```

### App Settings Configuration
Settings disimpan dalam tabel `app_settings` dengan keys:

| Key | Type | Default | Description |
|-----|------|---------|-------------|
| whatsapp_owner_number | string | '' | Nomor WhatsApp pemilik |
| whatsapp_api_key | string | '' | Token API Fonnte |
| whatsapp_country_code | string | '62' | Kode negara |
| whatsapp_reports_enabled | boolean | false | Status aktif laporan |
| whatsapp_daily_time | string | '08:00' | Waktu kirim harian |
| whatsapp_weekly_day | integer | 1 | Hari mingguan (0=Sunday) |
| whatsapp_weekly_time | string | '08:00' | Waktu kirim mingguan |
| whatsapp_monthly_day | integer | 1 | Tanggal bulanan (1-31) |
| whatsapp_monthly_time | string | '08:00' | Waktu kirim bulanan |
| whatsapp_report_format | string | 'detailed' | Format laporan |

### Scheduler Configuration
```php
// bootstrap/app.php
->withSchedule(function (Schedule $schedule): void {
    // WhatsApp automated reports
    $schedule->command('whatsapp-reports:send-scheduled')->everyMinute();
})
```

---

## 📊 Fitur Laporan

### Jenis Laporan

#### 1. Laporan Harian
**Trigger**: Setiap hari pada waktu yang ditentukan
**Isi**:
- Total pemasukan hari ini
- Total pengeluaran hari ini
- Saldo akhir rekening
- Total transaksi
- 5 transaksi terbaru

#### 2. Laporan Mingguan
**Trigger**: Hari tertentu dalam seminggu
**Isi**:
- Rekap 7 hari terakhir
- Chart sederhana (emoji based)
- Produk terlaris
- Pengeluaran terbesar
- Pemasukan bersih

#### 3. Laporan Bulanan
**Trigger**: Tanggal tertentu dalam bulan
**Isi**:
- Rekap 1 bulan penuh
- Laba rugi ringkas
- Arus kas
- Kategori pemasukan/pengeluaran terbanyak
- Performa cabang (jika multi-cabang)

### Format Pesan WhatsApp

#### Header Format
```
📊 LAPORAN KEUANGAN - [Tanggal]
🏢 Sistem Sibuku
```

#### Daily Report Example
```
📊 LAPORAN KEUANGAN - 25 Jan 2025
🏢 Sistem Sibuku

💰 Pemasukan: Rp 3.500.000
💸 Pengeluaran: Rp 1.200.000
📈 Laba Bersih: Rp 2.300.000

🏦 Saldo Rekening:
• BCA: Rp 8.000.000
• Cash: Rp 1.200.000

📝 Transaksi Terbaru:
➕ Penjualan Produk A - Rp 250.000
➖ Pembelian Bahan Baku - Rp 120.000

📊 Total Transaksi: 15
```

#### Weekly/Monthly Report Example
```
📊 LAPORAN MINGGUAN - 20-26 Jan 2025
🏢 Sistem Sibuku

📈 Ringkasan:
• Pemasukan: Rp 15.500.000
• Pengeluaran: Rp 8.200.000
• Laba Bersih: Rp 7.300.000

📊 Tren:
📈 Pemasukan tertinggi: Senin (Rp 3.200.000)
📉 Pengeluaran tertinggi: Jumat (Rp 2.100.000)

🏆 Produk Terlaris:
🥇 Produk A: 25 unit (Rp 1.250.000)
🥈 Produk B: 18 unit (Rp 900.000)
🥉 Produk C: 12 unit (Rp 600.000)
```

---

## 🔄 Flow Sistem

### Flow Otomatis

#### 1. Scheduler Execution
```php
// app/Console/Commands/SendScheduledWhatsAppReports.php
public function handle()
{
    // 1. Check if WhatsApp reports are enabled
    if (!config('app_settings.whatsapp_reports_enabled', false)) {
        return; // Exit if disabled
    }

    // 2. Check current time against scheduled times
    $now = now();
    $currentTime = $now->format('H:i');
    $currentDayOfWeek = $now->dayOfWeek;
    $currentDayOfMonth = $now->day;

    // 3. Determine which reports to send
    $reportsToSend = [];
    if ($currentTime === config('app_settings.whatsapp_daily_time')) {
        $reportsToSend[] = ['type' => 'daily'];
    }
    // ... check weekly and monthly

    // 4. Send reports
    foreach ($reportsToSend as $report) {
        $result = match ($report['type']) {
            'daily' => $this->whatsApp->sendDailyReport($ownerNumber),
            'weekly' => $this->whatsApp->sendWeeklyReport($ownerNumber),
            'monthly' => $this->whatsApp->sendMonthlyReport($ownerNumber),
        };
    }
}
```

#### 2. Report Generation Flow
```php
// app/Services/WhatsAppService.php
public function sendDailyReport(string $phoneNumber): array
{
    // 1. Validate phone number
    if (!$this->validatePhoneNumber($phoneNumber)) {
        return ['success' => false, 'message' => 'Invalid phone number'];
    }

    // 2. Generate report content
    $reportGenerator = app(WhatsAppReportGenerator::class);
    $reportData = $reportGenerator->generateDaily();

    // 3. Format message
    $message = $this->formatDailyReport($reportData);

    // 4. Send via WhatsApp
    $result = $this->sendMessage($phoneNumber, $message);

    // 5. Log the result
    $this->logWhatsAppActivity('daily', $phoneNumber, $result);

    return $result;
}
```

### Flow Manual

#### 1. User Initiated Send
```javascript
// Manual send from frontend
function sendManualReport(event) {
    event.preventDefault();
    const formData = new FormData(form);

    fetch('/settings/whatsapp/send-manual', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    });
}
```

#### 2. Controller Processing
```php
// app/Http/Controllers/WhatsAppSettingsController.php
public function sendManualReport(Request $request)
{
    // 1. Validate input
    $request->validate([
        'report_type' => 'required|in:daily,weekly,monthly',
        'phone_number' => 'required|string|regex:/^[0-9+\-\s()]+$/',
    ]);

    // 2. Send report
    $result = $this->whatsApp->sendManualReport(
        $request->phone_number,
        $request->report_type,
        session('active_branch'),
        auth()->id()
    );

    // 3. Return response
    return response()->json([
        'success' => $result['success'],
        'message' => $result['message']
    ]);
}
```

---

## 🔧 Troubleshooting

### Common Issues & Solutions

#### 1. "Table 'whatsapp_logs' doesn't exist"
**Cause**: Migration belum dijalankan
**Solution**:
```bash
php artisan migrate
# atau specific migration
php artisan migrate --path=database/migrations/2025_11_26_074356_create_whatsapp_logs_table.php
```

#### 2. "Class 'WhatsAppLog' not found"
**Cause**: Model belum dibuat atau namespace salah
**Solution**:
```bash
php artisan make:model WhatsAppLog
# Pastikan namespace di file model benar
```

#### 3. "Table 'whats_app_logs' doesn't exist"
**Cause**: Laravel pluralization salah, table name tidak sesuai
**Solution**: Tambahkan protected $table di model:
```php
class WhatsAppLog extends Model
{
    protected $table = 'whatsapp_logs';
    // ...
}
```

#### 4. Settings tidak tersimpan
**Cause**: AppSetting::setValue() hanya update existing records
**Solution**: Gunakan updateOrCreate():
```php
AppSetting::updateOrCreate(
    ['key' => $key],
    ['value' => $value, /* other fields */]
);
```

#### 5. WhatsApp API Error
**Cause**: Token salah atau koneksi bermasalah
**Solution**:
- Cek token API di settings
- Test koneksi via halaman settings
- Cek log WhatsApp untuk detail error

#### 6. Scheduled reports tidak terkirim
**Cause**: Cron job tidak berjalan
**Solution**:
```bash
# Cek cron job
crontab -l

# Manual run untuk test
php artisan whatsapp-reports:send-scheduled --dry-run

# Cek scheduler
php artisan schedule:list
```

#### 7. Queue job tidak berjalan
**Cause**: Queue worker tidak aktif
**Solution**:
```bash
# Jalankan queue worker
php artisan queue:work

# Atau untuk development
php artisan queue:listen
```

### Debug Commands

#### Check Database
```bash
# Cek apakah table ada
php artisan tinker
>>> Schema::hasTable('whatsapp_logs')
>>> Schema::hasTable('app_settings')

# Cek data settings
>>> AppSetting::where('key', 'like', 'whatsapp_%')->get()

# Cek logs
>>> WhatsAppLog::latest()->take(5)->get()
```

#### Test WhatsApp Service
```bash
php artisan tinker
>>> $whatsApp = app(\App\Services\WhatsAppService::class);
>>> $whatsApp->validatePhoneNumber('6281234567890')
>>> $whatsApp->checkConnection()
```

#### Test Report Generation
```bash
php artisan tinker
>>> $generator = app(\App\Services\WhatsAppReportGenerator::class);
>>> $generator->generateDaily()
```

#### Manual Send Test
```bash
php artisan tinker
>>> $whatsApp = app(\App\Services\WhatsAppService::class);
>>> $whatsApp->sendMessage('6281234567890', 'Test message')
```

---

## 🚨 Error Codes & Solutions

### WhatsApp API Errors

#### FONNTE_400 - Bad Request
**Message**: "Format data tidak valid"
**Cause**: Parameter API salah format
**Solution**:
- Cek format nomor telepon
- Pastikan token API valid
- Cek parameter message

#### FONNTE_401 - Unauthorized
**Message**: "Token API tidak valid"
**Cause**: Token Fonnte salah atau expired
**Solution**:
- Update token di settings
- Cek token di dashboard Fonnte
- Pastikan token aktif

#### FONNTE_429 - Too Many Requests
**Message**: "Rate limit tercapai"
**Cause**: Terlalu banyak request dalam waktu singkat
**Solution**:
- Tunggu beberapa menit
- Kurangi frekuensi pengiriman
- Implementasi retry dengan delay

#### FONNTE_500 - Internal Server Error
**Message**: "Server WhatsApp bermasalah"
**Cause**: Masalah di sisi Fonnte
**Solution**:
- Coba lagi nanti
- Cek status Fonnte
- Gunakan retry mechanism

### Application Errors

#### VALIDATION_ERROR
**Message**: "Format nomor WhatsApp tidak valid"
**Cause**: Nomor telepon tidak sesuai format Indonesia
**Solution**:
- Format yang benar: 6281234567890
- Atau: +6281234567890
- Atau: 081234567890

#### CONNECTION_FAILED
**Message**: "Tidak dapat terhubung ke WhatsApp API"
**Cause**: Masalah koneksi internet atau API down
**Solution**:
- Cek koneksi internet
- Cek status API Fonnte
- Coba lagi nanti

#### FILE_NOT_FOUND
**Message**: "File PDF tidak ditemukan"
**Cause**: File laporan tidak berhasil digenerate
**Solution**:
- Cek storage permissions
- Cek disk space
- Cek log Laravel untuk error generation

### Database Errors

#### FOREIGN_KEY_CONSTRAINT
**Cause**: User atau Branch dihapus tapi masih ada referensi
**Solution**:
```sql
-- Set null untuk records yang orphaned
UPDATE whatsapp_logs SET user_id = NULL WHERE user_id NOT IN (SELECT id FROM users);
UPDATE whatsapp_logs SET branch_id = NULL WHERE branch_id NOT IN (SELECT id FROM branches);
```

#### DUPLICATE_ENTRY
**Cause**: Duplicate unique constraint
**Solution**:
- Cek unique constraints di migration
- Pastikan tidak ada duplicate data

### Queue & Scheduler Errors

#### QUEUE_TIMEOUT
**Cause**: Job terlalu lama dieksekusi
**Solution**:
```php
// config/queue.php
'timeout' => 90, // Increase timeout
'retry_after' => 90,
```

#### SCHEDULER_NOT_RUNNING
**Cause**: Cron job tidak aktif
**Solution**:
```bash
# Install cron job
crontab -e
# Add: * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📝 Log Analysis

### WhatsApp Logs Structure
```json
{
    "id": 1,
    "report_type": "daily",
    "phone_number": "6281234567890",
    "status": "success",
    "message": "Laporan harian berhasil dikirim",
    "response_data": {
        "id": "fonnte_message_id",
        "status": "sent"
    },
    "error_message": null,
    "retry_count": 0,
    "sent_at": "2025-01-25 08:00:00",
    "user_id": 1,
    "branch_id": null,
    "created_at": "2025-01-25 08:00:00",
    "updated_at": "2025-01-25 08:00:00"
}
```

### Log Status Types
- **success**: Pesan berhasil dikirim
- **failed**: Pesan gagal dikirim (akan di-retry)
- **pending**: Pesan dalam antrian
- **error**: Error permanen (tidak di-retry)

### Monitoring Commands
```bash
# Cek logs terbaru
php artisan tinker
>>> \App\Models\WhatsAppLog::latest()->take(10)->get()

# Cek statistik
>>> \App\Models\WhatsAppLog::selectRaw('status, COUNT(*) as count')->groupBy('status')->get()

# Cek logs hari ini
>>> \App\Models\WhatsAppLog::whereDate('created_at', today())->get()

# Cek failed logs
>>> \App\Models\WhatsAppLog::where('status', 'failed')->get()
```

---

## 🎯 Best Practices

### Performance Optimization
1. **Queue Jobs**: Selalu gunakan queue untuk pengiriman WhatsApp
2. **Caching**: Cache settings untuk performa
3. **Batch Processing**: Proses multiple reports secara bersamaan
4. **Rate Limiting**: Implementasi delay antar pengiriman

### Security Considerations
1. **Token Storage**: Simpan token dengan aman
2. **Input Validation**: Validasi semua input user
3. **Access Control**: Pastikan hanya admin yang bisa akses settings
4. **Audit Logging**: Log semua aktivitas perubahan settings

### Maintenance Tasks
1. **Regular Cleanup**: Hapus logs lama secara berkala
2. **Monitor Queue**: Pastikan queue worker berjalan
3. **Check API Status**: Monitor status API Fonnte
4. **Backup Settings**: Backup konfigurasi WhatsApp

---

## 📞 Support & Contact

Untuk bantuan lebih lanjut atau troubleshooting khusus, silakan hubungi tim development atau buat issue di repository project.

**Version**: 1.0.0
**Last Updated**: 26 November 2025
**Framework**: Laravel 11.x
**WhatsApp API**: Fonnte