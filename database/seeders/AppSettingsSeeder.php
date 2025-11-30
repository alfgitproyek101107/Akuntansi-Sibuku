<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Settings
            [
                'key' => 'app_name',
                'value' => 'Sistem Akuntansi Digital',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nama Aplikasi',
                'description' => 'Nama aplikasi yang ditampilkan di header dan title',
                'is_public' => true,
            ],
            [
                'key' => 'app_description',
                'value' => 'Sistem akuntansi lengkap untuk UMKM dengan double entry accounting',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Deskripsi Aplikasi',
                'description' => 'Deskripsi singkat tentang aplikasi',
                'is_public' => true,
            ],
            [
                'key' => 'app_logo',
                'value' => '/images/logo.png',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Logo Aplikasi',
                'description' => 'Path ke file logo aplikasi',
                'is_public' => true,
            ],
            [
                'key' => 'company_name',
                'value' => 'PT. Digital Accounting',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nama Perusahaan',
                'description' => 'Nama perusahaan pemilik aplikasi',
                'is_public' => false,
            ],
            [
                'key' => 'company_address',
                'value' => 'Jl. Raya No. 123, Jakarta',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Alamat Perusahaan',
                'description' => 'Alamat lengkap perusahaan',
                'is_public' => false,
            ],
            [
                'key' => 'company_phone',
                'value' => '+62 21 12345678',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Telepon Perusahaan',
                'description' => 'Nomor telepon perusahaan',
                'is_public' => false,
            ],
            [
                'key' => 'company_email',
                'value' => 'info@digitalaccounting.com',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Email Perusahaan',
                'description' => 'Alamat email perusahaan',
                'is_public' => false,
            ],
            [
                'key' => 'default_currency',
                'value' => 'IDR',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Mata Uang Default',
                'description' => 'Mata uang yang digunakan secara default',
                'is_public' => true,
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Format Tanggal',
                'description' => 'Format penulisan tanggal (PHP date format)',
                'is_public' => true,
            ],
            [
                'key' => 'timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Timezone',
                'description' => 'Timezone yang digunakan aplikasi',
                'is_public' => true,
            ],

            // System Settings
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Mode Maintenance',
                'description' => 'Aktifkan mode maintenance untuk perbaikan sistem',
                'is_public' => false,
            ],
            [
                'key' => 'debug_mode',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Debug Mode',
                'description' => 'Tampilkan informasi debug untuk developer',
                'is_public' => false,
            ],
            [
                'key' => 'backup_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'system',
                'label' => 'Backup Otomatis',
                'description' => 'Aktifkan backup database otomatis',
                'is_public' => false,
            ],
            [
                'key' => 'backup_frequency',
                'value' => 'daily',
                'type' => 'string',
                'group' => 'system',
                'label' => 'Frekuensi Backup',
                'description' => 'Frekuensi pembuatan backup (daily, weekly, monthly)',
                'is_public' => false,
            ],
            [
                'key' => 'max_upload_size',
                'value' => '10',
                'type' => 'integer',
                'group' => 'system',
                'label' => 'Max Upload Size (MB)',
                'description' => 'Ukuran maksimal file upload dalam MB',
                'is_public' => false,
            ],
            [
                'key' => 'session_timeout',
                'value' => '7200',
                'type' => 'integer',
                'group' => 'system',
                'label' => 'Session Timeout (detik)',
                'description' => 'Waktu timeout session dalam detik',
                'is_public' => false,
            ],

            // Notification Settings
            [
                'key' => 'email_sender_name',
                'value' => 'Sistem Akuntansi Digital',
                'type' => 'string',
                'group' => 'notifications',
                'label' => 'Nama Pengirim Email',
                'description' => 'Nama yang muncul sebagai pengirim email',
                'is_public' => false,
            ],
            [
                'key' => 'email_sender_address',
                'value' => 'noreply@digitalaccounting.com',
                'type' => 'string',
                'group' => 'notifications',
                'label' => 'Alamat Email Pengirim',
                'description' => 'Alamat email yang digunakan untuk mengirim notifikasi',
                'is_public' => false,
            ],
            [
                'key' => 'whatsapp_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'notifications',
                'label' => 'WhatsApp Notifications',
                'description' => 'Aktifkan notifikasi WhatsApp',
                'is_public' => false,
            ],
            [
                'key' => 'whatsapp_api_key',
                'value' => '',
                'type' => 'string',
                'group' => 'notifications',
                'label' => 'WhatsApp API Key',
                'description' => 'API Key untuk WhatsApp gateway',
                'is_public' => false,
            ],

            // Transaction Settings
            [
                'key' => 'auto_numbering_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'transactions',
                'label' => 'Auto Numbering',
                'description' => 'Aktifkan penomoran otomatis untuk transaksi',
                'is_public' => false,
            ],
            [
                'key' => 'default_transaction_prefix',
                'value' => 'TRX',
                'type' => 'string',
                'group' => 'transactions',
                'label' => 'Prefix Transaksi',
                'description' => 'Prefix untuk nomor transaksi otomatis',
                'is_public' => false,
            ],
            [
                'key' => 'require_transaction_description',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'transactions',
                'label' => 'Wajibkan Deskripsi',
                'description' => 'Buat deskripsi transaksi menjadi wajib',
                'is_public' => false,
            ],
            [
                'key' => 'allow_negative_balance',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'transactions',
                'label' => 'Izinkan Saldo Negatif',
                'description' => 'Izinkan rekening memiliki saldo negatif',
                'is_public' => false,
            ],

            // UI Settings
            [
                'key' => 'primary_color',
                'value' => '#0ea5e9',
                'type' => 'string',
                'group' => 'ui',
                'label' => 'Warna Primary',
                'description' => 'Warna utama tema aplikasi',
                'is_public' => true,
            ],
            [
                'key' => 'secondary_color',
                'value' => '#64748b',
                'type' => 'string',
                'group' => 'ui',
                'label' => 'Warna Secondary',
                'description' => 'Warna sekunder tema aplikasi',
                'is_public' => true,
            ],
            [
                'key' => 'dark_mode_enabled',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'ui',
                'label' => 'Dark Mode',
                'description' => 'Aktifkan mode gelap untuk aplikasi',
                'is_public' => true,
            ],
            [
                'key' => 'items_per_page',
                'value' => '25',
                'type' => 'integer',
                'group' => 'ui',
                'label' => 'Items Per Halaman',
                'description' => 'Jumlah item yang ditampilkan per halaman',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::create($setting);
        }
    }
}