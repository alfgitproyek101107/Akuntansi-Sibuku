@extends('layouts.app')

@section('title', 'Laporan Total Penjualan')
@section('page-title', 'Laporan Total Penjualan')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Penjualan</span>
                </div>
                <h1 class="page-title">Laporan Total Penjualan</h1>
                <p class="page-subtitle">Analisis performa penjualan dalam periode tertentu</p>
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
        <!-- Filter Section -->
        <section class="filter-section">
            <div class="filter-container">
                <div class="filter-header">
                    <div class="filter-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </div>
                    <h2 class="filter-title">Opsi Filter</h2>
                </div>

                <div class="filter-body">
                    <form method="GET" class="filter-form">
                        <div class="filter-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Tanggal Mulai
                                </label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Tanggal Selesai
                                </label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>
                            
                            <div class="form-group form-actions">
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    </svg>
                                    <span>Terapkan Filter</span>
                                </button>
                                <a href="{{ route('reports.total-sales') }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    <span>Hapus</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        @if($startDate || $endDate)
            <!-- Filter Info Banner -->
            <div class="filter-info-banner">
                <div class="banner-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </div>
                <div class="banner-text">
                    Menampilkan data penjualan dari <strong>{{ $startDate ?: 'awal' }}</strong> hingga <strong>{{ $endDate ?: 'sekarang' }}</strong>
                </div>
            </div>
        @endif

        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Sales -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Penjualan</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">Rp{{ number_format($totalSales, 0, ',', '.') }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Jumlah total penjualan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+25.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalSalesSparkline"></canvas>
                    </div>
                </div>

                <!-- Number of Sales -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Jumlah Transaksi</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ number_format($salesCount) }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Total transaksi penjualan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+18.7%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="salesCountSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>
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

    // Total Sales Sparkline
    const totalSalesCtx = document.getElementById('totalSalesSparkline')?.getContext('2d');
    if (totalSalesCtx) {
        const gradient = totalSalesCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(34, 197, 94, 0.3)');
        gradient.addColorStop(1, 'rgba(34, 197, 94, 0)');
        
        new Chart(totalSalesCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [30, 45, 35, 50, 40, 60],
                    borderColor: '#22C55E',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Sales Count Sparkline
    const salesCountCtx = document.getElementById('salesCountSparkline')?.getContext('2d');
    if (salesCountCtx) {
        new Chart(salesCountCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [10, 15, 12, 18, 14, 20],
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: '#4F46E5',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }
});
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
    background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
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
    background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(6, 182, 212, 0.4);
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

/* ===== FILTER SECTION ===== */
.filter-section {
    margin-bottom: 32px;
}

.filter-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.filter-header {
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.filter-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
}

.filter-title {
    font-size: 20px;
    font-weight: 700;
    color: #0C4A6E;
    margin: 0;
}

.filter-body {
    padding: 32px;
}

.filter-form {
    margin: 0;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 24px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.form-actions {
    flex-direction: row;
    gap: 12px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.form-control:focus {
    outline: none;
    border-color: #06B6D4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}

.form-control::placeholder {
    color: #9CA3AF;
}

/* ===== FILTER INFO BANNER ===== */
.filter-info-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: linear-gradient(135deg, #E0F2FE 0%, #BAE6FD 100%);
    border: 1px solid #7DD3FC;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 32px;
}

.banner-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(6, 182, 212, 0.1);
    color: #0891B2;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.banner-text {
    font-size: 14px;
    color: #0C4A6E;
}

.banner-text strong {
    font-weight: 700;
}

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
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

.metric-success {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.metric-primary {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
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

.metric-success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-primary .metric-icon {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }
    
    .metrics-grid {
        grid-template-columns: 1fr;
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
    
    .filter-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-group.form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .filter-body {
        padding: 24px 16px;
    }
    
    .filter-info-banner {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .metric-value {
        font-size: 28px;
    }
}
</style>
@endsection