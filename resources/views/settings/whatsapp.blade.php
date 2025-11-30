@extends('layouts.app')

@section('title', 'Pengaturan WhatsApp')
@section('page-title', 'Pengaturan WhatsApp')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="dashboard-layout">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>Pengaturan WhatsApp</span>
                </div>
                <h1 class="page-title">Pengaturan WhatsApp</h1>
                <p class="page-subtitle">Konfigurasi pengiriman laporan otomatis dan manual via WhatsApp</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Settings Form -->
            <div class="settings-section">
                <div class="settings-container">
                    <div class="settings-header">
                        <h3 class="settings-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            Konfigurasi WhatsApp
                        </h3>
                    </div>

                    <div class="settings-content">
                        <form id="whatsappSettingsForm">
                            <div class="form-section">
                                <h4 class="form-section-title">Konfigurasi API</h4>

                                <div class="form-group">
                                    <label class="form-label">Nomor WhatsApp Pemilik</label>
                                    <input type="text" name="whatsapp_owner_number" class="form-control"
                                           value="{{ $settings['whatsapp_owner_number'] }}"
                                           placeholder="6281234567890" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Token API Fonnte</label>
                                    <input type="password" name="whatsapp_api_key" class="form-control"
                                           value="{{ $settings['whatsapp_api_key'] }}"
                                           placeholder="Masukkan token API Fonnte" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Kode Negara</label>
                                    <input type="text" name="whatsapp_country_code" class="form-control"
                                           value="{{ $settings['whatsapp_country_code'] }}"
                                           placeholder="62" required maxlength="3">
                                </div>
                            </div>

                            <div class="form-section">
                                <h4 class="form-section-title">Pengaturan Laporan Otomatis</h4>

                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="whatsapp_reports_enabled"
                                               {{ $settings['whatsapp_reports_enabled'] ? 'checked' : '' }}>
                                        <span>Aktifkan pengiriman laporan otomatis via WhatsApp</span>
                                    </label>
                                </div>

                                <div class="schedule-settings" id="scheduleSettings" style="{{ $settings['whatsapp_reports_enabled'] ? '' : 'display: none;' }}">
                                    <div class="form-group">
                                        <label class="form-label">Waktu Laporan Harian</label>
                                        <input type="time" name="whatsapp_daily_time" class="form-control"
                                               value="{{ $settings['whatsapp_daily_time'] }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Hari Laporan Mingguan</label>
                                        <select name="whatsapp_weekly_day" class="form-control">
                                            <option value="1" {{ $settings['whatsapp_weekly_day'] == '1' ? 'selected' : '' }}>Senin</option>
                                            <option value="2" {{ $settings['whatsapp_weekly_day'] == '2' ? 'selected' : '' }}>Selasa</option>
                                            <option value="3" {{ $settings['whatsapp_weekly_day'] == '3' ? 'selected' : '' }}>Rabu</option>
                                            <option value="4" {{ $settings['whatsapp_weekly_day'] == '4' ? 'selected' : '' }}>Kamis</option>
                                            <option value="5" {{ $settings['whatsapp_weekly_day'] == '5' ? 'selected' : '' }}>Jumat</option>
                                            <option value="6" {{ $settings['whatsapp_weekly_day'] == '6' ? 'selected' : '' }}>Sabtu</option>
                                            <option value="0" {{ $settings['whatsapp_weekly_day'] == '0' ? 'selected' : '' }}>Minggu</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Waktu Laporan Mingguan</label>
                                        <input type="time" name="whatsapp_weekly_time" class="form-control"
                                               value="{{ $settings['whatsapp_weekly_time'] }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Tanggal Laporan Bulanan</label>
                                        <select name="whatsapp_monthly_day" class="form-control">
                                            @for($i = 1; $i <= 31; $i++)
                                                <option value="{{ $i }}" {{ $settings['whatsapp_monthly_day'] == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Waktu Laporan Bulanan</label>
                                        <input type="time" name="whatsapp_monthly_time" class="form-control"
                                               value="{{ $settings['whatsapp_monthly_time'] }}">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Format Laporan</label>
                                        <select name="whatsapp_report_format" class="form-control">
                                            <option value="simple" {{ $settings['whatsapp_report_format'] == 'simple' ? 'selected' : '' }}>Sederhana</option>
                                            <option value="detailed" {{ $settings['whatsapp_report_format'] == 'detailed' ? 'selected' : '' }}>Detail</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                        <polyline points="17 21 17 13 7 13 7 21"/>
                                        <polyline points="7 3 7 8 15 8"/>
                                    </svg>
                                    Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Test Connection & Manual Send -->
            <div class="test-section">
                <div class="test-container">
                    <div class="test-header">
                        <h3 class="test-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            </svg>
                            Test & Kirim Manual
                        </h3>
                    </div>

                    <div class="test-content">
                        <!-- Test Connection -->
                        <div class="test-card">
                            <h4>Test Koneksi WhatsApp</h4>
                            <form id="testConnectionForm" onsubmit="testConnection(event)">
                                <div class="form-group">
                                    <label class="form-label">Nomor WhatsApp Test</label>
                                    <input type="text" name="test_number" class="form-control" placeholder="6281234567890" required>
                                </div>
                                <button type="submit" class="btn-secondary full-width">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 2L11 13"/>
                                        <path d="M22 2L15 22 11 13 2 9 22 2Z"/>
                                    </svg>
                                    Kirim Pesan Test
                                </button>
                            </form>
                        </div>

                        <!-- Manual Send -->
                        <div class="test-card">
                            <h4>Kirim Laporan Manual</h4>
                            <form id="manualSendForm" onsubmit="sendManualReport(event)">
                                <div class="form-group">
                                    <label class="form-label">Tipe Laporan</label>
                                    <select name="report_type" class="form-control" required>
                                        <option value="daily">Harian</option>
                                        <option value="weekly">Mingguan</option>
                                        <option value="monthly">Bulanan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <input type="text" name="phone_number" class="form-control" placeholder="6281234567890" required>
                                </div>
                                <button type="submit" class="btn-primary full-width">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 2L11 13"/>
                                        <path d="M22 2L15 22 11 13 2 9 22 2Z"/>
                                    </svg>
                                    Kirim Laporan
                                </button>
                            </form>
                        </div>

                        <!-- Recent Logs -->
                        <div class="test-card">
                            <h4>Log Pengiriman Terbaru</h4>
                            <div id="recentLogsContainer">
                                @if($recentLogs->count() > 0)
                                    @foreach($recentLogs->take(5) as $log)
                                        <div class="log-item {{ $log->status }}">
                                            <div class="log-info">
                                                <span class="log-type">{{ ucfirst($log->report_type) }}</span>
                                                <span class="log-time">{{ $log->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="log-status">
                                                <span class="status-badge {{ $log->status }}">
                                                    {{ $log->status === 'success' ? 'Berhasil' : 'Gagal' }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="no-logs">Belum ada log pengiriman</p>
                                @endif
                            </div>
                            <a href="{{ route('reports.whatsapp.index') }}" class="btn-secondary full-width" style="margin-top: 16px;">
                                Lihat Semua Log
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Toggle schedule settings visibility
document.querySelector('input[name="whatsapp_reports_enabled"]').addEventListener('change', function() {
    const scheduleSettings = document.getElementById('scheduleSettings');
    scheduleSettings.style.display = this.checked ? 'block' : 'none';
});

// Save settings
document.getElementById('whatsappSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Menyimpan...';

    fetch('/settings/whatsapp', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Pengaturan berhasil disimpan!', 'success');
        } else {
            showNotification('Error: ' + (data.message || 'Terjadi kesalahan'), 'error');
            if (data.errors) {
                displayValidationErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan jaringan', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Test connection
function testConnection(event) {
    event.preventDefault();
    const form = document.getElementById('testConnectionForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Mengirim...';

    fetch('/settings/whatsapp/test-connection', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showNotification(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            form.reset();
            refreshLogs();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan jaringan', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Send manual report
function sendManualReport(event) {
    event.preventDefault();
    const form = document.getElementById('manualSendForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Mengirim...';

    fetch('/settings/whatsapp/send-manual', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        showNotification(data.message, data.success ? 'success' : 'error');
        if (data.success) {
            form.reset();
            refreshLogs();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan jaringan', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
}

// Refresh recent logs
function refreshLogs() {
    fetch('/settings/whatsapp/logs?limit=5')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateLogsDisplay(data.logs);
            }
        })
        .catch(error => console.error('Error refreshing logs:', error));
}

// Update logs display
function updateLogsDisplay(logs) {
    const container = document.getElementById('recentLogsContainer');
    if (logs.length === 0) {
        container.innerHTML = '<p class="no-logs">Belum ada log pengiriman</p>';
        return;
    }

    container.innerHTML = logs.map(log => `
        <div class="log-item ${log.status}">
            <div class="log-info">
                <span class="log-type">${log.report_type.charAt(0).toUpperCase() + log.report_type.slice(1)}</span>
                <span class="log-time">${new Date(log.created_at).toLocaleString('id-ID')}</span>
            </div>
            <div class="log-status">
                <span class="status-badge ${log.status}">
                    ${log.status === 'success' ? 'Berhasil' : 'Gagal'}
                </span>
            </div>
        </div>
    `).join('');
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span>${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// Display validation errors
function displayValidationErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.field-error').forEach(el => el.remove());

    for (const [field, messages] of Object.entries(errors)) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.textContent = messages[0];
            input.parentElement.appendChild(errorDiv);
        }
    }
}
</script>

<style>
.content-grid { display: grid; grid-template-columns: 1fr 400px; gap: 32px; }
.settings-section, .test-section { margin-bottom: 48px; }
.settings-container, .test-container {
    background: #FFFFFF; border-radius: 20px; border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06); overflow: hidden;
}
.settings-header, .test-header {
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    padding: 24px; border-bottom: 1px solid #E5E7EB;
}
.settings-title, .test-title {
    font-size: 20px; font-weight: 700; color: #0C4A6E; margin: 0;
    display: flex; align-items: center; gap: 10px;
}
.settings-content, .test-content { padding: 24px; }
.form-section { margin-bottom: 32px; }
.form-section-title {
    font-size: 16px; font-weight: 600; color: #374151; margin: 0 0 20px 0;
}
.form-group { margin-bottom: 20px; }
.form-label {
    display: block; font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 8px;
}
.form-control {
    width: 100%; padding: 12px 16px; border: 1px solid #E5E7EB; border-radius: 10px;
    font-size: 14px; transition: all 0.2s ease; background: #FFFFFF;
}
.form-control:focus {
    outline: none; border-color: #22C55E; box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}
.checkbox-group { margin-bottom: 20px; }
.checkbox-label {
    display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px;
}
.checkbox-label input[type="checkbox"] { width: 16px; height: 16px; }
.schedule-settings {
    margin-top: 20px; padding: 20px; background: #F8FAFC; border-radius: 12px;
    border: 1px solid #E5E7EB;
}
.form-actions { margin-top: 32px; }
.btn-primary, .btn-secondary {
    display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px;
    border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer;
    text-decoration: none; transition: all 0.3s ease;
}
.btn-primary {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%); color: white;
}
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4); }
.btn-secondary {
    background: #FFFFFF; color: #6B7280; border: 1px solid #E5E7EB;
}
.btn-secondary:hover { background: #F3F4F6; }
.test-card {
    margin-bottom: 24px; padding: 20px; background: #F8FAFC; border-radius: 12px;
    border: 1px solid #E5E7EB;
}
.test-card h4 {
    font-size: 16px; font-weight: 600; color: #111827; margin: 0 0 16px 0;
}
.full-width { width: 100%; }

/* Log Items */
.log-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 12px 16px; margin-bottom: 8px; border-radius: 8px; background: white;
    border: 1px solid #E5E7EB;
}
.log-item.success { border-color: #22C55E; background: rgba(34, 197, 94, 0.05); }
.log-item.failed { border-color: #EF4444; background: rgba(239, 68, 68, 0.05); }
.log-info { display: flex; flex-direction: column; gap: 4px; }
.log-type { font-weight: 600; color: #111827; }
.log-time { font-size: 12px; color: #6B7280; }
.status-badge {
    padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;
}
.status-badge.success { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
.status-badge.failed { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
.no-logs { text-align: center; color: #6B7280; padding: 20px; }

/* Notifications */
.notification {
    position: fixed; top: 20px; right: 20px; z-index: 1000;
    min-width: 300px; max-width: 500px;
}
.notification-success { background: #22C55E; color: white; }
.notification-error { background: #EF4444; color: white; }
.notification-content {
    padding: 16px 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex; justify-content: space-between; align-items: center;
}
.notification-close {
    background: none; border: none; color: inherit; font-size: 20px;
    cursor: pointer; opacity: 0.8;
}
.notification-close:hover { opacity: 1; }

/* Field Errors */
.field-error {
    color: #EF4444; font-size: 12px; margin-top: 4px;
    display: block;
}

@media (max-width: 1024px) { .content-grid { grid-template-columns: 1fr; } }
</style>
@endsection