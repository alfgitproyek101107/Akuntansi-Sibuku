<?php

namespace App\Jobs;

use App\Models\ReportSchedule;
use App\Services\WhatsAppService;
use App\Services\ReportGeneratorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Throwable;

class SendWhatsAppReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    public $backoff = 30;
    public $timeout = 120;

    protected ReportSchedule $schedule;
    protected ?string $reportType;
    protected array $customData;
    protected bool $sendPdf;

    /**
     * Create a new job instance.
     */
    public function __construct(
        ReportSchedule $schedule,
        ?string $reportType = null,
        array $customData = [],
        bool $sendPdf = true
    ) {
        $this->schedule = $schedule;
        $this->reportType = $reportType ?: $schedule->report_type;
        $this->customData = $customData;
        $this->sendPdf = $sendPdf;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsAppService $whatsApp, ReportGeneratorService $reportGenerator): void
    {
        try {
            Log::info('Starting SendWhatsAppReport job', [
                'schedule_id' => $this->schedule->id,
                'report_type' => $this->reportType,
                'attempt' => $this->attempts()
            ]);

            // Check if schedule is still active
            if (!$this->schedule->is_active) {
                Log::info('Schedule is not active, skipping', ['schedule_id' => $this->schedule->id]);
                return;
            }

            // Generate report data
            $reportData = $reportGenerator->generate($this->reportType, $this->schedule->branch_id, $this->customData);

            if (empty($reportData)) {
                throw new \Exception('Failed to generate report data');
            }

            // Create text message
            $message = $this->createMessage($reportData);

            // Send text message first
            $textResult = $whatsApp->sendMessage($this->schedule->whatsapp_number, $message);

            if (!$textResult['success']) {
                throw new \Exception('Failed to send WhatsApp text message: ' . ($textResult['message'] ?? 'Unknown error'));
            }

            // Send PDF if requested and message is not too long
            if ($this->sendPdf && strlen($message) < 1000) {
                $pdfResult = $this->sendPdfReport($whatsApp, $reportData);

                if (!$pdfResult['success']) {
                    Log::warning('PDF sending failed, but text message was sent', [
                        'schedule_id' => $this->schedule->id,
                        'pdf_error' => $pdfResult['message']
                    ]);
                }
            }

            // Mark as sent
            $this->schedule->markAsSent();

            Log::info('WhatsApp report sent successfully', [
                'schedule_id' => $this->schedule->id,
                'report_type' => $this->reportType,
                'text_message_id' => $textResult['message_id'] ?? null,
                'pdf_sent' => $this->sendPdf && isset($pdfResult) ? $pdfResult['success'] : false
            ]);

        } catch (Throwable $e) {
            Log::error('SendWhatsAppReport job failed', [
                'schedule_id' => $this->schedule->id,
                'report_type' => $this->reportType,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
                'max_tries' => $this->tries
            ]);

            // If this is the last attempt, mark the schedule as having issues
            if ($this->attempts() >= $this->tries) {
                $this->schedule->update([
                    'last_error' => $e->getMessage(),
                    'error_count' => ($this->schedule->error_count ?? 0) + 1
                ]);
            }

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('SendWhatsAppReport job permanently failed', [
            'schedule_id' => $this->schedule->id,
            'report_type' => $this->reportType,
            'error' => $exception->getMessage(),
            'total_attempts' => $this->tries
        ]);

        // Update schedule with permanent failure
        $this->schedule->update([
            'last_error' => 'Permanent failure: ' . $exception->getMessage(),
            'error_count' => ($this->schedule->error_count ?? 0) + 1,
            'is_active' => false // Disable schedule after permanent failure
        ]);
    }

    /**
     * Send PDF report
     */
    protected function sendPdfReport(WhatsAppService $whatsApp, array $reportData): array
    {
        try {
            // Generate PDF
            $pdf = Pdf::loadView('reports.template', [
                'reportData' => $reportData,
                'reportType' => $this->reportType,
                'generatedAt' => now(),
                'isDemo' => config('whatsapp.demo.enabled', false)
            ]);

            // Save PDF temporarily
            $filename = 'report_' . $this->reportType . '_' . now()->format('Y-m-d_H-i-s') . '.pdf';
            $path = 'temp/' . $filename;

            Storage::put($path, $pdf->output());

            // Send PDF
            $caption = "📊 Laporan {$this->reportType} - " . now()->format('d M Y');
            $result = $whatsApp->sendPDF($this->schedule->whatsapp_number, $path, $caption);

            // Clean up temp file
            Storage::delete($path);

            return $result;

        } catch (Throwable $e) {
            Log::error('PDF generation/sending failed', [
                'schedule_id' => $this->schedule->id,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send PDF: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create WhatsApp message from report data
     */
    protected function createMessage(array $reportData): string
    {
        $type = strtoupper($this->reportType);
        $branch = $reportData['branch'] ?? 'Cabang Utama';
        $date = now()->format('d M Y');

        $message = "📊 *LAPORAN {$type} — {$date}*\n";
        $message .= "Cabang: {$branch}\n\n";

        if (isset($reportData['summary'])) {
            $summary = $reportData['summary'];

            if (isset($summary['income'])) {
                $message .= "📥 Pemasukan: Rp " . number_format($summary['income'], 0, ',', '.') . "\n";
            }

            if (isset($summary['expense'])) {
                $message .= "📤 Pengeluaran: Rp " . number_format($summary['expense'], 0, ',', '.') . "\n";
            }

            if (isset($summary['profit'])) {
                $profit = $summary['profit'];
                $emoji = $profit >= 0 ? "💵" : "⚠️";
                $message .= "{$emoji} " . ($profit >= 0 ? "Keuntungan" : "Kerugian") . ": Rp " . number_format(abs($profit), 0, ',', '.') . "\n";
            }

            if (isset($summary['transaction_count'])) {
                $message .= "🛒 Total transaksi: " . number_format($summary['transaction_count'], 0, ',', '.') . "\n";
            }

            if (isset($summary['top_product'])) {
                $message .= "🏷 Produk terlaris: {$summary['top_product']}\n";
            }
        }

        // Add demo watermark if in demo mode
        if (config('whatsapp.demo.enabled', false)) {
            $message .= "\n" . config('whatsapp.demo.watermark_text', 'DEMO MODE');
        }

        $message .= "\n🕒 Dikirim otomatis oleh sistem Akuntansi Sibuku";

        return $message;
    }
}