@extends('layouts.app')

@section('title', 'Laporan Saldo Rekening')
@section('page-title', 'Laporan Saldo Rekening')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <span>Saldo Rekening</span>
                </div>
                <h1 class="page-title">Laporan Saldo Rekening</h1>
                <p class="page-subtitle">Pantau saldo dan riwayat transaksi semua rekening Anda</p>
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
            <div class="accounts-grid">
                @foreach($accounts as $accountData)
                    <div class="account-card">
                        <div class="account-header">
                            <div class="account-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                            </div>
                            <div class="account-info">
                                <h3 class="account-name">{{ $accountData['account']->name }}</h3>
                                <p class="account-type">{{ $accountData['account']->type }}</p>
                            </div>
                        </div>
                        
                        <div class="account-balance">
                            <div class="balance-value">Rp{{ number_format($accountData['current_balance'], 0, ',', '.') }}</div>
                            <div class="balance-label">Saldo Saat Ini</div>
                        </div>

                        @if(count($accountData['balance_history']) > 0)
                            <div class="account-chart">
                                <div class="chart-header">
                                    <h4 class="chart-title">Riwayat Saldo</h4>
                                    <span class="chart-subtitle">{{ count($accountData['balance_history']) }} transaksi</span>
                                </div>
                                <div class="chart-container">
                                    <canvas id="chart-{{ $accountData['account']->id }}"></canvas>
                                </div>
                            </div>
                        @else
                            <div class="account-empty">
                                <div class="empty-icon">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                </div>
                                <p class="empty-text">Belum ada transaksi</p>
                            </div>
                        @endif

                        <div class="account-actions">
                            <a href="{{ route('accounts.show', $accountData['account']->id) }}" class="action-btn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <span>Lihat Detail</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
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
                    <p class="empty-description">Anda belum memiliki rekening apapun. Mulai dengan menambahkan rekening pertama Anda.</p>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced chart configuration
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                titleColor: '#111827',
                bodyColor: '#6B7280',
                borderColor: '#E5E7EB',
                borderWidth: 1,
                padding: 12,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return 'Saldo: Rp' + context.parsed.y.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: {
                    color: '#9CA3AF',
                    font: { size: 11 }
                }
            },
            y: {
                beginAtZero: false,
                grid: {
                    color: 'rgba(229, 231, 235, 0.5)',
                    drawBorder: false
                },
                ticks: {
                    color: '#9CA3AF',
                    font: { size: 11 },
                    callback: function(value) {
                        return 'Rp' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        elements: {
            line: {
                borderWidth: 3,
                tension: 0.4
            },
            point: {
                radius: 0,
                hoverRadius: 6,
                hoverBorderWidth: 2,
                hoverBackgroundColor: '#FFFFFF',
                hoverBorderColor: '#3B82F6'
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    };

    @foreach($accounts as $accountData)
        @if(count($accountData['balance_history']) > 0)
        const ctx{{ $accountData['account']->id }} = document.getElementById('chart-{{ $accountData['account']->id }}');
        if (ctx{{ $accountData['account']->id }}) {
            const balanceData = @json($accountData['balance_history']);
            
            // Create gradient
            const gradient = ctx{{ $accountData['account']->id }}.getContext('2d').createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(ctx{{ $accountData['account']->id }}, {
                type: 'line',
                data: {
                    labels: balanceData.map(item => item.date),
                    datasets: [{
                        label: 'Saldo',
                        data: balanceData.map(item => item.balance),
                        borderColor: '#3B82F6',
                        backgroundColor: gradient,
                        fill: true
                    }]
                },
                options: chartOptions
            });
        }
        @endif
    @endforeach
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
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
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
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
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

/* ===== ACCOUNTS GRID ===== */
.accounts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 24px;
    margin-bottom: 48px;
}

/* ===== ACCOUNT CARD ===== */
.account-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.account-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.account-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #8B5CF6 0%, #7C3AED 100%);
}

.account-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 24px;
    border-bottom: 1px solid #F3F4F6;
}

.account-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

.account-info {
    flex-grow: 1;
}

.account-name {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.account-type {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.account-balance {
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #F3F4F6;
}

.balance-value {
    font-size: 28px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 4px;
}

.balance-label {
    font-size: 14px;
    color: #6B7280;
}

.account-chart {
    padding: 24px;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.chart-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.chart-subtitle {
    font-size: 12px;
    color: #6B7280;
}

.chart-container {
    height: 200px;
    position: relative;
}

.account-empty {
    padding: 40px 24px;
    text-align: center;
}

.empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    background: rgba(139, 92, 246, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8B5CF6;
}

.empty-text {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.account-actions {
    padding: 16px 24px;
    border-top: 1px solid #F3F4F6;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: transparent;
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    color: #6B7280;
    text-decoration: none;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background: #F9FAFB;
    border-color: #8B5CF6;
    color: #8B5CF6;
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
    background: rgba(139, 92, 246, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8B5CF6;
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
    
    .accounts-grid {
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
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
    
    .accounts-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .account-header {
        padding: 20px;
    }
    
    .account-balance {
        padding: 20px;
    }
    
    .account-chart {
        padding: 20px;
    }
    
    .chart-container {
        height: 180px;
    }
    
    .account-actions {
        padding: 16px 20px;
    }
    
    .action-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection