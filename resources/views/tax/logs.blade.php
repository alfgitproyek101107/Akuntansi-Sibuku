@extends('layouts.app')

@section('title', 'Log Aktivitas Pajak')
@section('page-title', 'Pajak')

@section('content')
<div class="dashboard-layout">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Log Aktivitas</span>
                </div>
                <h1 class="page-title">Log Aktivitas CoreTax</h1>
                <p class="page-subtitle">Pantau semua aktivitas integrasi dengan CoreTax</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('tax.logs') }}" class="filters-form">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="status" class="filter-label">Status</label>
                        <select name="status" id="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="retry" {{ request('status') == 'retry' ? 'selected' : '' }}>Retry</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="action" class="filter-label">Aksi</label>
                        <select name="action" id="action" class="filter-select">
                            <option value="">Semua Aksi</option>
                            <option value="validate_npwp" {{ request('action') == 'validate_npwp' ? 'selected' : '' }}>Validasi NPWP</option>
                            <option value="create_invoice" {{ request('action') == 'create_invoice' ? 'selected' : '' }}>Buat Faktur</option>
                            <option value="check_status" {{ request('action') == 'check_status' ? 'selected' : '' }}>Cek Status</option>
                            <option value="sync_data" {{ request('action') == 'sync_data' ? 'selected' : '' }}>Sinkronisasi Data</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="date_from" class="filter-label">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" class="filter-input"
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="filter-group">
                        <label for="date_to" class="filter-label">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" class="filter-input"
                               value="{{ request('date_to') }}">
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                        <a href="{{ route('tax.logs') }}" class="btn-reset">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $taxLogs->where('status', 'success')->count() }}</div>
                        <div class="stat-label">Berhasil</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon failed">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $taxLogs->where('status', 'failed')->count() }}</div>
                        <div class="stat-label">Gagal</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon pending">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $taxLogs->whereIn('status', ['pending', 'retry'])->count() }}</div>
                        <div class="stat-label">Pending/Retry</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon total">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-value">{{ $taxLogs->total() }}</div>
                        <div class="stat-label">Total Log</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="table-section">
            <div class="table-header">
                <h3 class="table-title">Daftar Log Aktivitas</h3>
                <div class="table-info">
                    Menampilkan {{ $taxLogs->count() }} dari {{ $taxLogs->total() }} log
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Aksi</th>
                            <th>Endpoint</th>
                            <th>Status</th>
                            <th>Pesan</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taxLogs as $log)
                            <tr class="log-row {{ $log->status }}">
                                <td>
                                    <div class="timestamp">{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="time">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td>
                                    <span class="action-badge">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                                </td>
                                <td>
                                    <div class="endpoint">{{ $log->endpoint }}</div>
                                    <div class="method">{{ strtoupper($log->method ?? 'POST') }}</div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $log->status }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="response-message">
                                        @if($log->response_message)
                                            {{ Str::limit($log->response_message, 50) }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button class="btn-detail" onclick="showLogDetail({{ $log->id }})">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-row">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div class="empty-title">Belum ada log aktivitas</div>
                                        <div class="empty-description">Log aktivitas akan muncul di sini setelah ada interaksi dengan CoreTax</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($taxLogs->hasPages())
                <div class="pagination-container">
                    {{ $taxLogs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Log Detail Modal -->
    <div id="logDetailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Log</h3>
                <button class="modal-close" onclick="closeLogDetailModal()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="logDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
/* Filters */
.filters-section {
    margin-bottom: 24px;
}

.filters-form {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.filter-select,
.filter-input {
    padding: 10px 12px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #64748B;
    box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.btn-filter,
.btn-reset {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-filter {
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
}

.btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.btn-reset {
    background: #FFFFFF;
    color: #6B7280;
    border: 1px solid #D1D5DB;
}

.btn-reset:hover {
    background: #F9FAFB;
    border-color: #9CA3AF;
}

/* Statistics */
.stats-section {
    margin-bottom: 24px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-icon.success { background: rgba(5, 150, 105, 0.1); color: #065F46; }
.stat-icon.failed { background: rgba(220, 38, 38, 0.1); color: #DC2626; }
.stat-icon.pending { background: rgba(245, 158, 11, 0.1); color: #D97706; }
.stat-icon.total { background: rgba(100, 116, 139, 0.1); color: #64748B; }

.stat-content {
    flex-grow: 1;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #6B7280;
}

/* Table */
.table-section {
    background: #FFFFFF;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    overflow: hidden;
}

.table-header {
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.table-info {
    font-size: 14px;
    color: #6B7280;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 16px 24px;
    text-align: left;
    border-bottom: 1px solid #E5E7EB;
}

.data-table th {
    background: #F9FAFB;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    white-space: nowrap;
}

.data-table td {
    font-size: 14px;
    color: #111827;
}

.log-row.success { background: rgba(5, 150, 105, 0.02); }
.log-row.failed { background: rgba(220, 38, 38, 0.02); }
.log-row.pending,
.log-row.retry { background: rgba(245, 158, 11, 0.02); }

.timestamp {
    font-weight: 600;
    color: #111827;
}

.time {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
}

.action-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    background: #E0E7FF;
    color: #3730A3;
}

.endpoint {
    font-family: monospace;
    font-size: 12px;
    color: #374151;
    word-break: break-all;
}

.method {
    font-size: 10px;
    color: #6B7280;
    font-weight: 600;
    margin-top: 2px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-success { background: #D1FAE5; color: #065F46; }
.status-failed { background: #FEE2E2; color: #991B1B; }
.status-pending,
.status-retry { background: #FEF3C7; color: #92400E; }

.response-message {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.btn-detail {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    background: #64748B;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-detail:hover {
    background: #475569;
}

/* Empty State */
.empty-row {
    text-align: center;
    padding: 48px 24px !important;
}

.empty-state {
    display: inline-block;
    text-align: center;
}

.empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    border-radius: 16px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.empty-description {
    font-size: 14px;
    color: #6B7280;
    max-width: 400px;
    margin: 0 auto;
}

/* Pagination */
.pagination-container {
    padding: 24px;
    border-top: 1px solid #E5E7EB;
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
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #FFFFFF;
    margin: 5% auto;
    padding: 0;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #6B7280;
    padding: 4px;
    border-radius: 6px;
    transition: background-color 0.2s ease;
}

.modal-close:hover {
    background: #F3F4F6;
}

.modal-body {
    padding: 24px;
    max-height: 60vh;
    overflow-y: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .filters-grid {
        grid-template-columns: 1fr;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .data-table {
        font-size: 12px;
    }

    .data-table th,
    .data-table td {
        padding: 12px 16px;
    }

    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
}
</style>

<script>
let logDetailModal = document.getElementById('logDetailModal');

function showLogDetail(logId) {
    // In a real application, you would fetch the log details via AJAX
    // For now, we'll show a placeholder
    const content = `
        <div class="log-detail">
            <div class="detail-section">
                <h4>Request Payload</h4>
                <pre><code>Log ID: ${logId}\nLoading detailed information...</code></pre>
            </div>
            <div class="detail-section">
                <h4>Response</h4>
                <pre><code>Response details would be displayed here...</code></pre>
            </div>
        </div>
    `;

    document.getElementById('logDetailContent').innerHTML = content;
    logDetailModal.style.display = 'block';
}

function closeLogDetailModal() {
    logDetailModal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == logDetailModal) {
        logDetailModal.style.display = 'none';
    }
}
</script>

<style>
/* Additional modal styles */
.log-detail {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.detail-section h4 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 12px;
}

.detail-section pre {
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 16px;
    overflow-x: auto;
    font-size: 12px;
    line-height: 1.5;
}

.detail-section code {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
}
</style>
@endsection