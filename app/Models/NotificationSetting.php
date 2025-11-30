<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'notification_type',
        'event_type',
        'is_enabled',
        'settings',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Get user's notification settings for a specific event
     */
    public static function getUserSettings(int $userId, string $eventType): array
    {
        return static::where('user_id', $userId)
            ->where('event_type', $eventType)
            ->where('is_enabled', true)
            ->pluck('notification_type')
            ->toArray();
    }

    /**
     * Check if user has enabled notification for specific type and event
     */
    public static function isEnabled(int $userId, string $notificationType, string $eventType): bool
    {
        return static::where('user_id', $userId)
            ->where('notification_type', $notificationType)
            ->where('event_type', $eventType)
            ->where('is_enabled', true)
            ->exists();
    }

    /**
     * Get all enabled notifications for user
     */
    public static function getEnabledForUser(int $userId): array
    {
        return static::where('user_id', $userId)
            ->where('is_enabled', true)
            ->get()
            ->groupBy('event_type')
            ->map(function ($settings) {
                return $settings->pluck('notification_type')->toArray();
            })
            ->toArray();
    }

    /**
     * Available notification types
     */
    public static function getNotificationTypes(): array
    {
        return [
            'email' => 'Email',
            'in_app' => 'In-App Notification',
            'whatsapp' => 'WhatsApp',
        ];
    }

    /**
     * Available event types
     */
    public static function getEventTypes(): array
    {
        return [
            'invoice_overdue' => 'Tagihan Jatuh Tempo',
            'payment_received' => 'Pembayaran Diterima',
            'low_stock' => 'Stok Rendah',
            'transaction_created' => 'Transaksi Dibuat',
            'budget_exceeded' => 'Anggaran Terlampaui',
            'monthly_report' => 'Laporan Bulanan',
            'system_backup' => 'Backup Sistem',
            'user_registered' => 'User Baru Terdaftar',
        ];
    }

    /**
     * Create default settings for new user
     */
    public static function createDefaultForUser(int $userId): void
    {
        $defaults = [
            ['notification_type' => 'email', 'event_type' => 'invoice_overdue', 'is_enabled' => true],
            ['notification_type' => 'in_app', 'event_type' => 'invoice_overdue', 'is_enabled' => true],
            ['notification_type' => 'whatsapp', 'event_type' => 'invoice_overdue', 'is_enabled' => false],
            ['notification_type' => 'email', 'event_type' => 'payment_received', 'is_enabled' => true],
            ['notification_type' => 'in_app', 'event_type' => 'payment_received', 'is_enabled' => true],
            ['notification_type' => 'email', 'event_type' => 'low_stock', 'is_enabled' => true],
            ['notification_type' => 'in_app', 'event_type' => 'low_stock', 'is_enabled' => true],
            ['notification_type' => 'email', 'event_type' => 'transaction_created', 'is_enabled' => false],
            ['notification_type' => 'in_app', 'event_type' => 'transaction_created', 'is_enabled' => true],
            ['notification_type' => 'email', 'event_type' => 'budget_exceeded', 'is_enabled' => true],
            ['notification_type' => 'in_app', 'event_type' => 'budget_exceeded', 'is_enabled' => true],
            ['notification_type' => 'email', 'event_type' => 'monthly_report', 'is_enabled' => true],
            ['notification_type' => 'email', 'event_type' => 'system_backup', 'is_enabled' => true],
        ];

        foreach ($defaults as $setting) {
            static::create(array_merge($setting, ['user_id' => $userId]));
        }
    }
}