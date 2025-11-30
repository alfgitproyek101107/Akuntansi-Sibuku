<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\WhatsAppLog;
use App\Services\WhatsAppReportGenerator;

class WhatsAppService
{
    protected $config;
    protected $provider;
    protected WhatsAppReportGenerator $reportGenerator;

    public function __construct()
    {
        $this->config = config('whatsapp');
        $this->provider = $this->config['provider'];
        $this->reportGenerator = new WhatsAppReportGenerator();
    }

    /**
     * Validate phone number format
     */
    public function validatePhoneNumber(string $phone): bool
    {
        try {
            $normalized = $this->normalizeNumber($phone);
            // Indonesian phone number validation (62xxxxxxxxx)
            return preg_match('/^62[0-9]{9,12}$/', $normalized);
        } catch (\Exception $e) {
            $this->log('error', 'Phone number validation failed', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage(string $phoneNumber, string $message): array
    {
        try {
            // Check demo mode
            if ($this->isDemoMode()) {
                return $this->handleDemoMode($phoneNumber, $message);
            }

            // Validate phone number
            if (!$this->validatePhoneNumber($phoneNumber)) {
                return [
                    'success' => false,
                    'message' => 'Format nomor WhatsApp tidak valid',
                    'error' => 'INVALID_PHONE'
                ];
            }

            // Check connection first
            if (!$this->checkConnection()) {
                return [
                    'success' => false,
                    'message' => 'Tidak dapat terhubung ke WhatsApp API',
                    'error' => 'CONNECTION_FAILED'
                ];
            }

            $normalizedPhone = $this->normalizeNumber($phoneNumber);

            $result = match ($this->provider) {
                'fonnte' => $this->sendViaFonnte($normalizedPhone, $message),
                'ultramsg' => $this->sendViaUltramsg($normalizedPhone, $message),
                'cloud_api' => $this->sendViaCloudAPI($normalizedPhone, $message),
                default => throw new \Exception("Provider WhatsApp tidak didukung: {$this->provider}")
            };

            $this->log('info', 'WhatsApp message sent', [
                'provider' => $this->provider,
                'phone' => $normalizedPhone,
                'success' => $result['success'],
                'message_id' => $result['message_id'] ?? null,
                'error' => $result['error'] ?? null,
            ]);

            return $result;

        } catch (\Exception $e) {
            $this->log('error', 'WhatsApp send failed', [
                'provider' => $this->provider,
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan WhatsApp',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send PDF file via WhatsApp
     */
    public function sendPDF(string $phoneNumber, string $filePath, string $caption = ''): array
    {
        try {
            // Check demo mode
            if ($this->isDemoMode()) {
                return $this->handleDemoMode($phoneNumber, "PDF Report: {$caption}", $filePath);
            }

            // Validate phone number
            if (!$this->validatePhoneNumber($phoneNumber)) {
                return [
                    'success' => false,
                    'message' => 'Format nomor WhatsApp tidak valid',
                    'error' => 'INVALID_PHONE'
                ];
            }

            // Check if file exists
            if (!Storage::exists($filePath)) {
                return [
                    'success' => false,
                    'message' => 'File PDF tidak ditemukan',
                    'error' => 'FILE_NOT_FOUND'
                ];
            }

            $normalizedPhone = $this->normalizeNumber($phoneNumber);
            $fileUrl = Storage::url($filePath);

            $result = match ($this->provider) {
                'fonnte' => $this->sendViaFonnte($normalizedPhone, $caption, $fileUrl),
                'ultramsg' => $this->sendViaUltramsg($normalizedPhone, $caption, $fileUrl),
                'cloud_api' => $this->sendViaCloudAPI($normalizedPhone, $caption, $fileUrl),
                default => throw new \Exception("Provider WhatsApp tidak didukung: {$this->provider}")
            };

            $this->log('info', 'WhatsApp PDF sent', [
                'provider' => $this->provider,
                'phone' => $normalizedPhone,
                'file' => $filePath,
                'success' => $result['success'],
                'error' => $result['error'] ?? null,
            ]);

            return $result;

        } catch (\Exception $e) {
            $this->log('error', 'WhatsApp PDF send failed', [
                'provider' => $this->provider,
                'phone' => $phoneNumber,
                'file' => $filePath,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal mengirim PDF WhatsApp',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check WhatsApp API connection
     */
    public function checkConnection(): bool
    {
        try {
            return match ($this->provider) {
                'fonnte' => $this->checkFonnteConnection(),
                'ultramsg' => $this->checkUltramsgConnection(),
                'cloud_api' => $this->checkCloudAPIConnection(),
                default => false
            };
        } catch (\Exception $e) {
            $this->log('error', 'Connection check failed', [
                'provider' => $this->provider,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Handle provider-specific errors
     */
    public function handleProviderError($response): array
    {
        try {
            $statusCode = $response->status();
            $body = $response->body();

            $error = match ($statusCode) {
                400 => 'Bad Request - Periksa format data',
                401 => 'Unauthorized - Token API tidak valid',
                403 => 'Forbidden - Akses ditolak',
                404 => 'Not Found - Endpoint tidak ditemukan',
                429 => 'Too Many Requests - Rate limit tercapai',
                500 => 'Internal Server Error - Server WhatsApp bermasalah',
                default => 'Unknown Error'
            };

            $this->log('error', 'WhatsApp API error', [
                'status' => $statusCode,
                'response' => $body,
                'error' => $error
            ]);

            return [
                'success' => false,
                'message' => $error,
                'error' => $body
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error parsing response',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Normalize phone number to international format
     */
    public function normalizeNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // Handle Indonesian number formats
        if (strlen($phone) === 11 && str_starts_with($phone, '8')) {
            // 81234567890 -> 6281234567890
            $phone = '62' . $phone;
        } elseif (strlen($phone) === 12 && str_starts_with($phone, '08')) {
            // 081234567890 -> 6281234567890
            $phone = '62' . substr($phone, 1);
        } elseif (strlen($phone) === 10 && str_starts_with($phone, '8')) {
            // 8123456789 -> 628123456789
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Check if demo mode is enabled
     */
    protected function isDemoMode(): bool
    {
        return $this->config['demo']['enabled'] ?? false;
    }

    /**
     * Handle demo mode responses
     */
    protected function handleDemoMode(string $phoneNumber, string $message, ?string $filePath = null): array
    {
        $demoNumber = $this->config['demo']['test_number'];

        $this->log('info', 'Demo mode WhatsApp message', [
            'original_phone' => $phoneNumber,
            'demo_phone' => $demoNumber,
            'message' => $message,
            'file' => $filePath
        ]);

        // Simulate successful sending for demo
        return [
            'success' => true,
            'message' => 'Pesan demo berhasil dikirim (mode demo aktif)',
            'message_id' => 'demo_' . time(),
            'status' => 'sent',
            'demo' => true
        ];
    }

    /**
     * Send via Fonnte API
     */
    protected function sendViaFonnte(string $phoneNumber, string $message, ?string $fileUrl = null): array
    {
        $apiKey = $this->config['providers']['fonnte']['api_key'] ?? null;
        if (!$apiKey) {
            throw new \Exception('Fonnte API key tidak dikonfigurasi');
        }

        $url = $this->config['providers']['fonnte']['url'] . '/send';

        $payload = [
            'target' => $phoneNumber,
            'message' => $message,
        ];

        if ($fileUrl) {
            $payload['url'] = $fileUrl;
        }

        $response = Http::timeout($this->config['timeout'])
            ->withHeaders(['Authorization' => $apiKey])
            ->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message_id' => $data['id'] ?? null,
                'status' => $data['status'] ?? 'sent'
            ];
        }

        return $this->handleProviderError($response);
    }

    /**
     * Send via Ultramsg API
     */
    protected function sendViaUltramsg(string $phoneNumber, string $message, ?string $fileUrl = null): array
    {
        $instanceId = $this->config['providers']['ultramsg']['instance_id'] ?? null;
        $token = $this->config['providers']['ultramsg']['token'] ?? null;

        if (!$instanceId || !$token) {
            throw new \Exception('Ultramsg credentials tidak dikonfigurasi');
        }

        $url = $this->config['providers']['ultramsg']['url'] . "/{$instanceId}/messages/chat";

        $payload = [
            'token' => $token,
            'to' => $phoneNumber,
            'body' => $message,
        ];

        if ($fileUrl) {
            $payload['filename'] = basename($fileUrl);
            $url = $this->config['providers']['ultramsg']['url'] . "/{$instanceId}/messages/document";
        }

        $response = Http::timeout($this->config['timeout'])->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message_id' => $data['id'] ?? null,
                'status' => $data['status'] ?? 'sent'
            ];
        }

        return $this->handleProviderError($response);
    }

    /**
     * Send via WhatsApp Cloud API
     */
    protected function sendViaCloudAPI(string $phoneNumber, string $message, ?string $fileUrl = null): array
    {
        $accessToken = $this->config['providers']['cloud_api']['access_token'] ?? null;
        $phoneNumberId = $this->config['providers']['cloud_api']['phone_number_id'] ?? null;

        if (!$accessToken || !$phoneNumberId) {
            throw new \Exception('WhatsApp Cloud API credentials tidak dikonfigurasi');
        }

        $url = $this->config['providers']['cloud_api']['url'] . "/{$phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $phoneNumber,
            'type' => $fileUrl ? 'document' : 'text',
        ];

        if ($fileUrl) {
            $payload['document'] = [
                'link' => $fileUrl,
                'filename' => basename($fileUrl)
            ];
        } else {
            $payload['text'] = ['body' => $message];
        }

        $response = Http::timeout($this->config['timeout'])
            ->withToken($accessToken)
            ->post($url, $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'success' => true,
                'message_id' => $data['messages'][0]['id'] ?? null,
                'status' => 'sent'
            ];
        }

        return $this->handleProviderError($response);
    }

    /**
     * Check Fonnte connection
     */
    protected function checkFonnteConnection(): bool
    {
        $apiKey = $this->config['providers']['fonnte']['api_key'] ?? null;
        if (!$apiKey) return false;

        try {
            $response = Http::timeout(10)
                ->withHeaders(['Authorization' => $apiKey])
                ->get($this->config['providers']['fonnte']['url'] . '/device/status');

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check Ultramsg connection
     */
    protected function checkUltramsgConnection(): bool
    {
        $instanceId = $this->config['providers']['ultramsg']['instance_id'] ?? null;
        $token = $this->config['providers']['ultramsg']['token'] ?? null;

        if (!$instanceId || !$token) return false;

        try {
            $response = Http::timeout(10)->post(
                $this->config['providers']['ultramsg']['url'] . "/{$instanceId}/instance/status",
                ['token' => $token]
            );

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check Cloud API connection
     */
    protected function checkCloudAPIConnection(): bool
    {
        $accessToken = $this->config['providers']['cloud_api']['access_token'] ?? null;
        $phoneNumberId = $this->config['providers']['cloud_api']['phone_number_id'] ?? null;

        if (!$accessToken || !$phoneNumberId) return false;

        try {
            $response = Http::timeout(10)
                ->withToken($accessToken)
                ->get($this->config['providers']['cloud_api']['url'] . "/{$phoneNumberId}");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Log messages to dedicated channel
     */
    protected function log(string $level, string $message, array $context = []): void
    {
        if ($this->config['logging']['enabled']) {
            Log::channel($this->config['logging']['channel'])->$level($message, $context);
        }
    }

    /**
     * Send daily report
     */
    public function sendDailyReport(string $phoneNumber, ?int $branchId = null, ?int $userId = null): array
    {
        try {
            $message = $this->reportGenerator->generateDaily($branchId);

            $result = $this->sendMessage($phoneNumber, $message);

            $this->logWhatsAppActivity('daily', $phoneNumber, $result, $userId, $branchId, $message);

            return $result;
        } catch (\Exception $e) {
            $this->logWhatsAppActivity('daily', $phoneNumber, [
                'success' => false,
                'message' => $e->getMessage()
            ], $userId, $branchId);

            return [
                'success' => false,
                'message' => 'Gagal mengirim laporan harian',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send weekly report
     */
    public function sendWeeklyReport(string $phoneNumber, ?int $branchId = null, ?int $userId = null): array
    {
        try {
            $message = $this->reportGenerator->generateWeekly($branchId);

            $result = $this->sendMessage($phoneNumber, $message);

            $this->logWhatsAppActivity('weekly', $phoneNumber, $result, $userId, $branchId, $message);

            return $result;
        } catch (\Exception $e) {
            $this->logWhatsAppActivity('weekly', $phoneNumber, [
                'success' => false,
                'message' => $e->getMessage()
            ], $userId, $branchId);

            return [
                'success' => false,
                'message' => 'Gagal mengirim laporan mingguan',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send monthly report
     */
    public function sendMonthlyReport(string $phoneNumber, ?int $branchId = null, ?int $userId = null): array
    {
        try {
            $message = $this->reportGenerator->generateMonthly($branchId);

            $result = $this->sendMessage($phoneNumber, $message);

            $this->logWhatsAppActivity('monthly', $phoneNumber, $result, $userId, $branchId, $message);

            return $result;
        } catch (\Exception $e) {
            $this->logWhatsAppActivity('monthly', $phoneNumber, [
                'success' => false,
                'message' => $e->getMessage()
            ], $userId, $branchId);

            return [
                'success' => false,
                'message' => 'Gagal mengirim laporan bulanan',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send manual report
     */
    public function sendManualReport(string $phoneNumber, string $reportType, ?int $branchId = null, ?int $userId = null): array
    {
        try {
            $message = match ($reportType) {
                'daily' => $this->reportGenerator->generateDaily($branchId),
                'weekly' => $this->reportGenerator->generateWeekly($branchId),
                'monthly' => $this->reportGenerator->generateMonthly($branchId),
                default => throw new \Exception("Tipe laporan tidak valid: {$reportType}")
            };

            $result = $this->sendMessage($phoneNumber, $message);

            $this->logWhatsAppActivity('manual', $phoneNumber, $result, $userId, $branchId, $message);

            return $result;
        } catch (\Exception $e) {
            $this->logWhatsAppActivity('manual', $phoneNumber, [
                'success' => false,
                'message' => $e->getMessage()
            ], $userId, $branchId);

            return [
                'success' => false,
                'message' => 'Gagal mengirim laporan manual',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Log WhatsApp activity
     */
    protected function logWhatsAppActivity(string $reportType, string $phoneNumber, array $result, ?int $userId = null, ?int $branchId = null, ?string $message = null): void
    {
        WhatsAppLog::create([
            'report_type' => $reportType,
            'phone_number' => $phoneNumber,
            'status' => $result['success'] ? 'success' : 'failed',
            'message' => $message,
            'response_data' => $result,
            'error_message' => $result['error'] ?? $result['message'] ?? null,
            'sent_at' => $result['success'] ? now() : null,
            'user_id' => $userId,
            'branch_id' => $branchId,
        ]);
    }

    /**
     * Get available providers
     */
    public static function getAvailableProviders(): array
    {
        return ['fonnte', 'ultramsg', 'cloud_api'];
    }
}