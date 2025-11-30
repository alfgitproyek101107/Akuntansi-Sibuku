@extends('layouts.app')

@section('title', 'Laporan Transfer')
@section('page-title', 'Laporan Transfer')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                    <span>Transfer</span>
                </div>
                <h1 class="page-title">Laporan Transfer</h1>
                <p class="page-subtitle">Pantau semua aktivitas transfer antar rekening Anda</p>
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
        @if($transfers->count() > 0)
            <!-- Enhanced Key Metrics -->
            <section class="metrics-section">
                <div class="metrics-grid">
                    <!-- Total Transfers -->
                    <div class="metric-card metric-primary">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="17 1 21 5 17 9"></polyline>
                                    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                    <polyline points="7 23 3 19 7 15"></polyline>
                                    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                </svg>
                            </div>
                            <div class="metric-badge">
                                <span>Total Transfer</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $transfers->count() }}</div>
                            <div class="metric-details">
                                <span class="metric-label">Semua transfer</span>
                                <div class="metric-trend positive">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    </svg>
                                    <span>+12.5%</span>
                                </div>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="totalTransfersSparkline"></canvas>
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="metric-card metric-success">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <div class="metric-badge">
                                <span>Total Dana</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">Rp{{ number_format($totalTransferred, 0, ',', '.') }}</div>
                            <div class="metric-details">
                                <span class="metric-label">Total yang ditransfer</span>
                                <div class="metric-trend positive">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    </svg>
                                    <span>+18.2%</span>
                                </div>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="totalAmountSparkline"></canvas>
                        </div>
                    </div>

                    <!-- Average Amount -->
                    <div class="metric-card metric-info">
                        <div class="metric-header">
                            <div class="metric-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="metric-badge">
                                <span>Rata-rata</span>
                            </div>
                        </div>
                        <div class="metric-content">
                            <div class="metric-value">Rp{{ number_format($totalTransferred / max($transfers->count(), 1), 0, ',', '.') }}</div>
                            <div class="metric-details">
                                <span class="metric-label">Rata-rata per transfer</span>
                                <div class="metric-trend negative">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    </svg>
                                    <span>-5.4%</span>
                                </div>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="averageAmountSparkline"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Transfers Table Section -->
            <section class="table-section">
                <div class="section-header">
                    <div class="section-title-group">
                        <h2 class="section-title">Riwayat Transfer</h2>
                        <p class="section-subtitle">Detail semua transaksi transfer antar rekening</p>
                    </div>
                    <div class="section-actions">
                        <div class="search-input">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <input type="text" id="transferSearch" placeholder="Cari transfer..." onkeyup="filterTransfers(this.value)">
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="transfers-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Dari Rekening</th>
                                    <th>Ke Rekening</th>
                                    <th>Jumlah</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="transfersTableBody">
                                @foreach($transfers as $transfer)
                                    <tr class="transfer-row">
                                        <td>
                                            <div class="transfer-date">
                                                <div class="date-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                </div>
                                                <div class="date-details">
                                                    <div class="date-value">{{ $transfer->date->format('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transfer-account">
                                                <div class="account-icon from-account">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                </div>
                                                <div class="account-details">
                                                    <div class="account-name">{{ $transfer->fromAccount->name }}</div>
                                                    <div class="account-type">{{ $transfer->fromAccount->type }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transfer-account">
                                                <div class="account-icon to-account">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                </div>
                                                <div class="account-details">
                                                    <div class="account-name">{{ $transfer->toAccount->name }}</div>
                                                    <div class="account-type">{{ $transfer->toAccount->type }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transfer-amount">
                                                <div class="amount-badge">
                                                    Rp{{ number_format($transfer->amount, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transfer-description">
                                                {{ $transfer->description ?: '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('transfers.show', $transfer) }}" class="action-btn view-btn" title="Lihat Detail">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
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
                            <polyline points="17 1 21 5 17 9"></polyline>
                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                            <polyline points="7 23 3 19 7 15"></polyline>
                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                        </svg>
                    </div>
                    <h3 class="empty-title">Belum ada transfer</h3>
                    <p class="empty-description">Anda belum melakukan transfer antar rekening. Mulai dengan membuat transfer pertama Anda.</p>
                    <div class="empty-action">
                        <a href="{{ route('transfers.create') }}" class="btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="17 1 21 5 17 9"></polyline>
                                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                <polyline points="7 23 3 19 7 15"></polyline>
                                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                            </svg>
                            <span>Buat Transfer</span>
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

    // Total Transfers Sparkline
    const totalTransfersCtx = document.getElementById('totalTransfersSparkline')?.getContext('2d');
    if (totalTransfersCtx) {
        const gradient = totalTransfersCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
        
        new Chart(totalTransfersCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [5, 7, 6, 8, 7, 9],
                    borderColor: '#6366F1',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Total Amount Sparkline
    const totalAmountCtx = document.getElementById('totalAmountSparkline')?.getContext('2d');
    if (totalAmountCtx) {
        new Chart(totalAmountCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [3, 5, 4, 6, 5, 7],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Average Amount Sparkline
    const averageAmountCtx = document.getElementById('averageAmountSparkline')?.getContext('2d');
    if (averageAmountCtx) {
        new Chart(averageAmountCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [4, 3, 5, 4, 3, 3],
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }
});

// Filter transfers function
function filterTransfers(searchTerm) {
    const rows = document.querySelectorAll('#transfersTableBody .transfer-row');
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
    background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
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
    background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
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
    --color-start: #6366F1;
    --color-end: #4F46E5;
}

.metric-success {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.metric-info {
    --color-start: #3B82F6;
    --color-end: #2563EB;
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
    background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
}

.metric-success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-info .metric-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
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
    border-color: #6366F1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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

.transfers-table {
    width: 100%;
    border-collapse: collapse;
}

.transfers-table thead {
    background: #F9FAFB;
}

.transfers-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.transfers-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.transfers-table tbody tr:hover {
    background: #F9FAFB;
}

.transfers-table td {
    padding: 16px;
    font-size: 14px;
}

.transfer-date {
    display: flex;
    align-items: center;
    gap: 12px;
}

.date-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(99, 102, 241, 0.1);
    color: #6366F1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.date-value {
    font-weight: 600;
    color: #111827;
}

.transfer-account {
    display: flex;
    align-items: center;
    gap: 12px;
}

.account-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.from-account {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.to-account {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
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

.transfer-amount {
    display: flex;
    align-items: center;
}

.amount-badge {
    background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
    color: #4F46E5;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    border: 1px solid #C7D2FE;
}

.transfer-description {
    color: #6B7280;
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

.view-btn:hover {
    background: rgba(99, 102, 241, 0.1);
    color: #6366F1;
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
    background: rgba(99, 102, 241, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6366F1;
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
    
    .transfers-table {
        font-size: 12px;
    }
    
    .transfers-table th,
    .transfers-table td {
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
    
    .transfer-account {
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