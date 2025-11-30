@extends('layouts.app')

@section('title', 'Laporan Rekonsiliasi')
@section('page-title', 'Laporan Rekonsiliasi')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 11l3 3L22 4"></path>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                    </svg>
                    <span>Rekonsiliasi</span>
                </div>
                <h1 class="page-title">Laporan Rekonsiliasi</h1>
                <p class="page-subtitle">Pantau status pencocokan transaksi untuk semua rekening Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('reports.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Laporan</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        @if($accounts->count() > 0)
            <!-- Enhanced Key Metrics -->
            <section class="metrics-section">
                <div class="metrics-grid">
                    <!-- Total Accounts -->
                    <div class="metric-card metric-primary">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <div class="metric-badge">
                                <span>Total Rekening</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $accounts->count() }}</div>
                            <div class="metric-details">
                                <span class="metric-label">Perlu direkonsiliasi</span>
                                <div class="metric-trend positive">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    </svg>
                                    <span>Aktif</span>
                                </div>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="totalAccountsSparkline"></canvas>
                        </div>
                    </div>

                    <!-- Fully Reconciled -->
                    <div class="metric-card metric-success">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                            </div>
                            <div class="metric-badge">
                                <span>Tuntas Direkonsiliasi</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $accounts->where('reconciliation_percentage', 100)->count() }}</div>
                            <div class="metric-details">
                                <span class="metric-label">Status 100%</span>
                                <div class="metric-trend positive">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    </svg>
                                    <span>Selesai</span>
                                </div>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="reconciledAccountsSparkline"></canvas>
                        </div>
                    </div>

                    <!-- Average Percentage -->
                    <div class="metric-card metric-warning">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="metric-badge">
                                <span>Rata-rata Progress</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ number_format($accounts->avg('reconciliation_percentage'), 1) }}%</div>
                            <div class="metric-details">
                                <span class="metric-label">Keseluruhan rekening</span>
                                <div class="metric-trend {{ $accounts->avg('reconciliation_percentage') >= 75 ? 'positive' : 'negative' }}">
                                    @if($accounts->avg('reconciliation_percentage') >= 75)
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                        </svg>
                                        <span>Baik</span>
                                    @else
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                        </svg>
                                        <span>Perlu Perhatian</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="averageProgressSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Reconciliation Table Section -->
            <section class="table-section">
                <div class="section-header">
                    <div class="section-title-group">
                        <h2 class="section-title">Status Rekonsiliasi Rekening</h2>
                        <p class="section-subtitle">Detail progress rekonsiliasi untuk setiap rekening</p>
                    </div>
                    <div class="section-actions">
                        <div class="search-input">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <input type="text" id="reconciliationSearch" placeholder="Cari rekening..." onkeyup="filterReconciliations(this.value)">
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="reconciliation-table">
                            <thead>
                                <tr>
                                    <th>Rekening</th>
                                    <th>Total Transaksi</th>
                                    <th>Direkonsiliasi</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="reconciliationTableBody">
                                @foreach($accounts as $accountData)
                                    <tr class="reconciliation-row">
                                        <td>
                                            <div class="reconciliation-account">
                                                <div class="account-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                    </svg>
                                                </div>
                                                <div class="account-details">
                                                    <div class="account-name">{{ $accountData['account']->name }}</div>
                                                    <div class="account-type">{{ $accountData['account']->type }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-count">
                                                <div class="count-value">{{ $accountData['total_transactions'] }}</div>
                                                <div class="count-label">transaksi</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="reconciled-count">
                                                <div class="count-value">{{ $accountData['reconciled_transactions'] }}</div>
                                                <div class="count-label">telah dicocokkan</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="reconciliation-progress">
                                                <div class="progress-bar-container">
                                                    <div class="progress-bar-fill 
                                                        @if($accountData['reconciliation_percentage'] == 100) progress-complete
                                                        @elseif($accountData['reconciliation_percentage'] >= 75) progress-good
                                                        @else progress-warning
                                                        @endif" 
                                                        style="width: {{ $accountData['reconciliation_percentage'] }}%">
                                                    </div>
                                                </div>
                                                <div class="progress-text">{{ $accountData['reconciliation_percentage'] }}%</div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($accountData['reconciliation_percentage'] == 100)
                                                <div class="status-badge status-complete">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                    </svg>
                                                    <span>Lengkap</span>
                                                </div>
                                            @elseif($accountData['reconciliation_percentage'] >= 75)
                                                <div class="status-badge status-pending">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <polyline points="12 6 12 12 16 14"></polyline>
                                                    </svg>
                                                    <span>Hampir Selesai</span>
                                                </div>
                                            @else
                                                <div class="status-badge status-attention">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                    </svg>
                                                    <span>Perlu Perhatian</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('accounts.reconcile', $accountData['account']) }}" class="action-btn reconcile-btn" title="Rekonsiliasi">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M9 11l3 3L22 4"></path>
                                                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @else
            <!-- Empty State -->
            <section class="empty-section">
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <h3 class="empty-title">Belum ada rekening</h3>
                    <p class="empty-description">Anda belum memiliki rekening untuk direkonsiliasi. Mulai dengan menambahkan rekening pertama Anda.</p>
                    <div class="empty-action">
                        <a href="{{ route('accounts.create') }}" class="btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Tambah Rekening</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif
    </main>
</div>

<!-- Chart.js for sparklines -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced sparkline configuration
    const sparklineOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        },
        scales: {
            x: { display: false },
            y: { display: false }
        },
        elements: {
            line: {
                borderWidth: 2,
                tension: 0.4
            },
            point: {
                radius: 0,
                hoverRadius: 0
            }
        }
    };

    // Total Accounts Sparkline
    const totalAccountsCtx = document.getElementById('totalAccountsSparkline')?.getContext('2d');
    if (totalAccountsCtx) {
        const gradient = totalAccountsCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(245, 158, 11, 0.3)');
        gradient.addColorStop(1, 'rgba(245, 158, 11, 0)');
        
        new Chart(totalAccountsCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [4, 5, 5, 6, 6, 7],
                    borderColor: '#F59E0B',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Reconciled Accounts Sparkline
    const reconciledAccountsCtx = document.getElementById('reconciledAccountsSparkline')?.getContext('2d');
    if (reconciledAccountsCtx) {
        new Chart(reconciledAccountsCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [2, 3, 3, 4, 4, 5],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Average Progress Sparkline
    const averageProgressCtx = document.getElementById('averageProgressSparkline')?.getContext('2d');
    if (averageProgressCtx) {
        new Chart(averageProgressCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [65, 70, 68, 75, 72, 78],
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }
});

// Filter reconciliations function
function filterReconciliations(searchTerm) {
    const rows = document.querySelectorAll('#reconciliationTableBody .reconciliation-row');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}
</script>

<style>
/* ===== ENHANCED BASE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: #1F2937;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    line-height: 1.6;
    min-height: 100vh;
}

/* ===== ENHANCED DASHBOARD LAYOUT ===== */
.dashboard-layout {
    max-width: 1440px;
    margin: 0 auto;
    padding: 32px;
    position: relative;
}

/* ===== ENHANCED HEADER ===== */
.dashboard-header {
    margin-bottom: 40px;
}

.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.welcome-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

.page-title {
    font-size: 36px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #111827 0%, #374151 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    font-size: 16px;
    color: #6B7280;
    font-weight: 400;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
}

.btn-secondary {
    background: #FFFFFF;
    color: #6B7280;
    border: 1px solid #E5E7EB;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #D1D5DB;
}

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 24px;
}

.metric-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.metric-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.metric-primary {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.metric-success {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.metric-warning {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.metric-primary .metric-icon {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.metric-success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-warning .metric-icon {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.metric-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-content {
    margin-bottom: 24px;
}

.metric-value {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.metric-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.metric-label {
    font-size: 14px;
    color: #6B7280;
}

.metric-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #22C55E;
}

.metric-trend.negative {
    color: #EF4444;
}

.metric-sparkline {
    height: 50px;
}

/* ===== TABLE SECTION ===== */
.table-section {
    margin-bottom: 48px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-title-group {
    flex: 1;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.section-subtitle {
    font-size: 14px;
    color: #6B7280;
}

.section-actions {
    display: flex;
    gap: 12px;
}

.search-input {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input svg {
    position: absolute;
    left: 12px;
    color: #9CA3AF;
}

.search-input input {
    padding: 10px 16px 10px 40px;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    width: 250px;
    transition: all 0.2s ease;
}

.search-input input:focus {
    outline: none;
    border-color: #F59E0B;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.table-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.reconciliation-table {
    width: 100%;
    border-collapse: collapse;
}

.reconciliation-table thead {
    background: #F9FAFB;
}

.reconciliation-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.reconciliation-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.reconciliation-table tbody tr:hover {
    background: #F9FAFB;
}

.reconciliation-table td {
    padding: 16px;
    font-size: 14px;
}

.reconciliation-account {
    display: flex;
    align-items: center;
    gap: 12px;
}

.account-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.account-details {
    flex-grow: 1;
}

.account-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.account-type {
    font-size: 12px;
    color: #6B7280;
}

.transaction-count, .reconciled-count {
    text-align: center;
}

.count-value {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.count-label {
    font-size: 12px;
    color: #6B7280;
}

.reconciliation-progress {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress-bar-container {
    flex-grow: 1;
    height: 8px;
    background: #F3F4F6;
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-complete {
    background: linear-gradient(90deg, #22C55E 0%, #10B981 100%);
}

.progress-good {
    background: linear-gradient(90deg, #F59E0B 0%, #D97706 100%);
}

.progress-warning {
    background: linear-gradient(90deg, #EF4444 0%, #DC2626 100%);
}

.progress-text {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    min-width: 40px;
    text-align: right;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-complete {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.status-pending {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.status-attention {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: #F3F4F6;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn:hover {
    background: #E5E7EB;
    color: #374151;
}

.reconcile-btn:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

/* ===== EMPTY STATE ===== */
.empty-section {
    margin-bottom: 48px;
}

.empty-state {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    padding: 60px 40px;
    text-align: center;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: rgba(245, 158, 11, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F59E0B;
}

.empty-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.empty-description {
    font-size: 16px;
    color: #6B7280;
    max-width: 500px;
    margin: 0 auto 32px;
}

.empty-action {
    margin-top: 8px;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }
    
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-layout {
        padding: 16px;
    }
    
    .header-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }
    
    .page-title {
        font-size: 28px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .section-actions {
        width: 100%;
    }
    
    .search-input input {
        width: 100%;
    }
    
    .reconciliation-table {
        font-size: 12px;
    }
    
    .reconciliation-table th,
    .reconciliation-table td {
        padding: 12px 8px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .metric-value {
        font-size: 28px;
    }
    
    .reconciliation-account {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection