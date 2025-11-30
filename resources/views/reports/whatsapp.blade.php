@extends('layouts.app')

@section('title', 'Laporan WhatsApp')
@section('page-title', 'Laporan WhatsApp')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="dashboard-layout">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"/>
                    </svg>
                    <span>Laporan WhatsApp</span>
                </div>
                <h1 class="page-title">Monitoring Laporan WhatsApp</h1>
                <p class="page-subtitle">Pantau pengiriman laporan otomatis dan manual via WhatsApp</p>
            </div>
            <div class="header-right">
                <a href="{{ route('settings.whatsapp.index') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                    Pengaturan WhatsApp
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon success">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['total_sent']) }}</div>
                    <div class="stat-label">Total Terkirim</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon error">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['total_failed']) }}</div>
                    <div class="stat-label">Total Gagal</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ number_format($stats['today_sent']) }}</div>
                    <div class="stat-label">Terkirim Hari Ini</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $recentLogs->where('status', 'failed')->count() }}</div>
                    <div class="stat-label">Error Hari Ini</div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="filters-section">
            <div class="filters-container">
                <div class="filter-group">
                    <label class="filter-label">Status:</label>
                    <select id="statusFilter" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="success">Berhasil</option>
                        <option value="failed">Gagal</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Tipe:</label>
                    <select id="typeFilter" class="filter-select">
                        <option value="">Semua Tipe</option>
                        <option value="daily">Harian</option>
                        <option value="weekly">Mingguan</option>
                        <option value="monthly">Bulanan</option>
                        <option value="manual">Manual</option>
                        <option value="test">Test</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Tanggal Dari:</label>
                    <input type="date" id="dateFromFilter" class="filter-input">
                </div>

                <div class="filter-group">
                    <label class="filter-label">Tanggal Sampai:</label>
                    <input type="date" id="dateToFilter" class="filter-input">
                </div>

                <button id="applyFilters" class="btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="M21 21l-4.35-4.35"></path>
                    </svg>
                    Terapkan Filter
                </button>

                <button id="clearFilters" class="btn-secondary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Hapus Filter
                </button>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="logs-section">
            <div class="logs-container">
                <div class="logs-header">
                    <h3 class="logs-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Log Pengiriman WhatsApp
                    </h3>
                    <div class="logs-actions">
                        <button id="refreshLogs" class="btn-secondary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                            </svg>
                            Refresh
                        </button>
                        <button id="exportLogs" class="btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>

                <div class="logs-content">
                    <div id="logsTableContainer">
                        @include('reports.whatsapp._logs_table', ['logs' => $recentLogs])
                    </div>

                    <div id="loadingIndicator" class="loading-indicator" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Log Detail Modal -->
<div id="logDetailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Detail Log Pengiriman</h2>
            <button class="modal-close" onclick="closeLogDetailModal()">&times;</button>
        </div>
        <div class="modal-body" id="logDetailContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
// Global variables
let currentPage = 1;
let currentFilters = {};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();
    setupActions();
});

// Setup filters
function setupFilters() {
    document.getElementById('applyFilters').addEventListener('click', applyFilters);
    document.getElementById('clearFilters').addEventListener('click', clearFilters);

    // Auto-apply filters on change
    document.querySelectorAll('.filter-select, .filter-input').forEach(element => {
        element.addEventListener('change', applyFilters);
    });
}

// Setup action buttons
function setupActions() {
    document.getElementById('refreshLogs').addEventListener('click', refreshLogs);
    document.getElementById('exportLogs').addEventListener('click', exportLogs);
}

// Apply filters
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const reportType = document.getElementById('typeFilter').value;
    const dateFrom = document.getElementById('dateFromFilter').value;
    const dateTo = document.getElementById('dateToFilter').value;

    currentFilters = {
        status: status || undefined,
        report_type: reportType || undefined,
        date_from: dateFrom || undefined,
        date_to: dateTo || undefined
    };

    currentPage = 1;
    loadLogs(currentFilters, currentPage);
}

// Clear filters
function clearFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('dateFromFilter').value = '';
    document.getElementById('dateToFilter').value = '';

    currentFilters = {};
    currentPage = 1;
    loadLogs({}, currentPage);
}

// Load logs with filters
function loadLogs(filters = {}, page = 1) {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const logsContainer = document.getElementById('logsTableContainer');

    loadingIndicator.style.display = 'flex';
    logsContainer.style.opacity = '0.5';

    // Build query string
    const params = new URLSearchParams({
        page: page,
        ...filters
    });

    fetch(`/settings/whatsapp/logs?${params}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateLogsTable(data.logs);
        } else {
            showNotification('Gagal memuat data logs', 'error');
        }
    })
    .catch(error => {
        console.error('Error loading logs:', error);
        showNotification('Terjadi kesalahan jaringan', 'error');
    })
    .finally(() => {
        loadingIndicator.style.display = 'none';
        logsContainer.style.opacity = '1';
    });
}

// Update logs table
function updateLogsTable(logsData) {
    fetch('/settings/whatsapp/logs-table', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'text/html',
        },
        body: JSON.stringify({ logs: logsData.data })
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('logsTableContainer').innerHTML = html;
        setupLogDetailButtons();
    })
    .catch(error => {
        console.error('Error updating table:', error);
    });
}

// Setup log detail buttons
function setupLogDetailButtons() {
    document.querySelectorAll('.log-detail-btn').forEach(button => {
        button.addEventListener('click', function() {
            const logId = this.dataset.logId;
            showLogDetail(logId);
        });
    });
}

// Show log detail
function showLogDetail(logId) {
    fetch(`/settings/whatsapp/logs/${logId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayLogDetail(data.log);
        } else {
            showNotification('Gagal memuat detail log', 'error');
        }
    })
    .catch(error => {
        console.error('Error loading log detail:', error);
        showNotification('Terjadi kesalahan jaringan', 'error');
    });
}

// Display log detail in modal
function displayLogDetail(log) {
    const modal = document.getElementById('logDetailModal');
    const content = document.getElementById('logDetailContent');

    content.innerHTML = `
        <div class="log-detail-grid">
            <div class="detail-section">
                <h4>Informasi Dasar</h4>
                <div class="detail-row">
                    <span class="detail-label">ID:</span>
                    <span class="detail-value">${log.id}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tipe Laporan:</span>
                    <span class="detail-value">${log.report_type.charAt(0).toUpperCase() + log.report_type.slice(1)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value status-${log.status}">${log.status === 'success' ? 'Berhasil' : 'Gagal'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nomor WhatsApp:</span>
                    <span class="detail-value">${log.phone_number}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu Pengiriman:</span>
                    <span class="detail-value">${new Date(log.created_at).toLocaleString('id-ID')}</span>
                </div>
                ${log.sent_at ? `
                <div class="detail-row">
                    <span class="detail-label">Waktu Terkirim:</span>
                    <span class="detail-value">${new Date(log.sent_at).toLocaleString('id-ID')}</span>
                </div>
                ` : ''}
            </div>

            ${log.message ? `
            <div class="detail-section">
                <h4>Pesan</h4>
                <div class="message-content">${log.message.replace(/\n/g, '<br>')}</div>
            </div>
            ` : ''}

            ${log.error_message ? `
            <div class="detail-section">
                <h4>Error Message</h4>
                <div class="error-content">${log.error_message}</div>
            </div>
            ` : ''}

            ${log.response_data ? `
            <div class="detail-section">
                <h4>Response Data</h4>
                <pre class="json-content">${JSON.stringify(log.response_data, null, 2)}</pre>
            </div>
            ` : ''}
        </div>
    `;

    modal.style.display = 'block';
}

// Close log detail modal
function closeLogDetailModal() {
    document.getElementById('logDetailModal').style.display = 'none';
}

// Refresh logs
function refreshLogs() {
    loadLogs(currentFilters, currentPage);
}

// Export logs
function exportLogs() {
    const params = new URLSearchParams(currentFilters);
    window.open(`/settings/whatsapp/logs/export?${params}`, '_blank');
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

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('logDetailModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}
</script>

<style>
/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-icon.success { background: rgba(34, 197, 94, 0.1); color: #22C55E; }
.stat-icon.error { background: rgba(239, 68, 68, 0.1); color: #EF4444; }
.stat-icon.info { background: rgba(59, 130, 246, 0.1); color: #3B82F6; }
.stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #F59E0B; }

.stat-content { flex: 1; }
.stat-value {
    font-size: 28px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 4px;
}
.stat-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
}

/* Filters */
.filters-section {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.filters-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.filter-select, .filter-input {
    padding: 10px 12px;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 14px;
    background: #FFFFFF;
}

.filter-select:focus, .filter-input:focus {
    outline: none;
    border-color: #22C55E;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

/* Logs Section */
.logs-section { margin-bottom: 48px; }
.logs-container {
    background: #FFFFFF;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.logs-header {
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logs-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.logs-actions {
    display: flex;
    gap: 12px;
}

.logs-content {
    position: relative;
}

/* Loading Indicator */
.loading-indicator {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 16px;
    z-index: 10;
    border-radius: 16px;
}

.loading-indicator svg {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    padding: 20px;
    box-sizing: border-box;
}

.modal-content {
    background: #FFFFFF;
    border-radius: 16px;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    margin: auto;
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #111827;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6B7280;
    padding: 4px;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: #F3F4F6;
    color: #374151;
}

.modal-body {
    padding: 24px;
}

/* Log Detail Styles */
.log-detail-grid {
    display: grid;
    gap: 24px;
}

.detail-section {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #E5E7EB;
}

.detail-section h4 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 16px 0;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #E5E7EB;
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #374151;
}

.detail-value {
    color: #111827;
    font-family: monospace;
}

.detail-value.status-success { color: #22C55E; }
.detail-value.status-failed { color: #EF4444; }

.message-content {
    background: white;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid #E5E7EB;
    white-space: pre-wrap;
    font-family: monospace;
    font-size: 14px;
}

.error-content {
    background: rgba(239, 68, 68, 0.05);
    color: #EF4444;
    padding: 16px;
    border-radius: 8px;
    border: 1px solid rgba(239, 68, 68, 0.2);
    font-family: monospace;
    font-size: 14px;
}

.json-content {
    background: #1F2937;
    color: #F9FAFB;
    padding: 16px;
    border-radius: 8px;
    overflow-x: auto;
    font-size: 12px;
    line-height: 1.5;
}

/* Notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    min-width: 300px;
    max-width: 500px;
}

.notification-success { background: #22C55E; color: white; }
.notification-error { background: #EF4444; color: white; }

.notification-content {
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-close {
    background: none;
    border: none;
    color: inherit;
    font-size: 20px;
    cursor: pointer;
    opacity: 0.8;
}

.notification-close:hover { opacity: 1; }

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .filters-container { grid-template-columns: 1fr; }
    .logs-header { flex-direction: column; gap: 16px; align-items: flex-start; }
}

@media (max-width: 768px) {
    .stats-grid { grid-template-columns: 1fr; }
    .logs-actions { flex-direction: column; width: 100%; }
    .logs-actions button { width: 100%; }
}
</style>
@endsection