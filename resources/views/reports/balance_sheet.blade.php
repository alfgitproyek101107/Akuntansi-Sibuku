@extends('layouts.app')

@section('title', 'Neraca - ' . $balanceSheet['as_of_date'])
@section('page-title', 'Neraca')

@section('content')
<div class="dashboard-layout">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Neraca</span>
                </div>
                <h1 class="page-title">Laporan Neraca</h1>
                <p class="page-subtitle">Posisi keuangan per {{ \Carbon\Carbon::parse($balanceSheet['as_of_date'])->format('d M Y') }}</p>
            </div>
            <div class="header-right">
                <a href="{{ route('reports.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali ke Laporan
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="balance-sheet-container">
            <!-- Assets Section -->
            <div class="balance-sheet-section assets-section">
                <h3 class="section-title">AKTIVA</h3>
                <div class="balance-sheet-table">
                    <!-- Current Assets -->
                    <div class="subsection">
                        <h4>Aktiva Lancar</h4>
                        @foreach($balanceSheet['assets']['current_assets'] as $asset)
                        <div class="balance-row">
                            <span class="account-name">{{ $asset['name'] }}</span>
                            <span class="account-balance">Rp{{ number_format($asset['balance'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="balance-row total-row">
                            <span class="account-name"><strong>Total Aktiva Lancar</strong></span>
                            <span class="account-balance"><strong>Rp{{ number_format(collect($balanceSheet['assets']['current_assets'])->sum('balance'), 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    <!-- Fixed Assets -->
                    <div class="subsection">
                        <h4>Aktiva Tetap</h4>
                        @foreach($balanceSheet['assets']['fixed_assets'] as $asset)
                        <div class="balance-row">
                            <span class="account-name">{{ $asset['name'] }}</span>
                            <span class="account-balance">Rp{{ number_format($asset['balance'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="balance-row total-row">
                            <span class="account-name"><strong>Total Aktiva Tetap</strong></span>
                            <span class="account-balance"><strong>Rp{{ number_format(collect($balanceSheet['assets']['fixed_assets'])->sum('balance'), 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    <!-- Total Assets -->
                    <div class="balance-row grand-total">
                        <span class="account-name"><strong>TOTAL AKTIVA</strong></span>
                        <span class="account-balance"><strong>Rp{{ number_format($balanceSheet['assets']['total'], 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Liabilities & Equity Section -->
            <div class="balance-sheet-section liabilities-equity-section">
                <h3 class="section-title">KEWAJIBAN & MODAL</h3>
                <div class="balance-sheet-table">
                    <!-- Current Liabilities -->
                    <div class="subsection">
                        <h4>Kewajiban Lancar</h4>
                        @foreach($balanceSheet['liabilities']['current_liabilities'] as $liability)
                        <div class="balance-row">
                            <span class="account-name">{{ $liability['name'] }}</span>
                            <span class="account-balance">Rp{{ number_format($liability['balance'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="balance-row total-row">
                            <span class="account-name"><strong>Total Kewajiban Lancar</strong></span>
                            <span class="account-balance"><strong>Rp{{ number_format(collect($balanceSheet['liabilities']['current_liabilities'])->sum('balance'), 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    <!-- Long-term Liabilities -->
                    <div class="subsection">
                        <h4>Kewajiban Jangka Panjang</h4>
                        @foreach($balanceSheet['liabilities']['long_term_liabilities'] as $liability)
                        <div class="balance-row">
                            <span class="account-name">{{ $liability['name'] }}</span>
                            <span class="account-balance">Rp{{ number_format($liability['balance'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="balance-row total-row">
                            <span class="account-name"><strong>Total Kewajiban Jangka Panjang</strong></span>
                            <span class="account-balance"><strong>Rp{{ number_format(collect($balanceSheet['liabilities']['long_term_liabilities'])->sum('balance'), 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    <!-- Total Liabilities -->
                    <div class="balance-row total-row">
                        <span class="account-name"><strong>TOTAL KEWAJIBAN</strong></span>
                        <span class="account-balance"><strong>Rp{{ number_format($balanceSheet['liabilities']['total'], 0, ',', '.') }}</strong></span>
                    </div>

                    <!-- Equity -->
                    <div class="subsection">
                        <h4>Modal</h4>
                        @foreach($balanceSheet['equity']['capital'] as $equity)
                        <div class="balance-row">
                            <span class="account-name">{{ $equity['name'] }}</span>
                            <span class="account-balance">Rp{{ number_format($equity['balance'], 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                        <div class="balance-row">
                            <span class="account-name">Laba Ditahan</span>
                            <span class="account-balance">Rp{{ number_format($balanceSheet['equity']['retained_earnings'], 0, ',', '.') }}</span>
                        </div>
                        <div class="balance-row total-row">
                            <span class="account-name"><strong>Total Modal</strong></span>
                            <span class="account-balance"><strong>Rp{{ number_format($balanceSheet['equity']['total'], 0, ',', '.') }}</strong></span>
                        </div>
                    </div>

                    <!-- Total Liabilities & Equity -->
                    <div class="balance-row grand-total">
                        <span class="account-name"><strong>TOTAL KEWAJIBAN & MODAL</strong></span>
                        <span class="account-balance"><strong>Rp{{ number_format($balanceSheet['liabilities']['total'] + $balanceSheet['equity']['total'], 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Check -->
        @if($balanceSheet['totals']['balance_check'] != 0)
        <div class="balance-check warning">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>Peringatan: Neraca tidak balance. Selisih: Rp{{ number_format($balanceSheet['totals']['balance_check'], 0, ',', '.') }}</span>
        </div>
        @endif
    </main>
</div>

<style>
.balance-sheet-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 40px;
}

.balance-sheet-section {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.section-title {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    padding: 20px 28px;
    margin: 0;
    font-size: 18px;
    font-weight: 700;
}

.balance-sheet-table {
    padding: 28px;
}

.subsection {
    margin-bottom: 24px;
}

.subsection h4 {
    color: #374151;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 12px;
    border-bottom: 2px solid #E5E7EB;
    padding-bottom: 8px;
}

.balance-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #F3F4F6;
}

.balance-row:last-child {
    border-bottom: none;
}

.account-name {
    font-size: 14px;
    color: #374151;
}

.account-balance {
    font-size: 14px;
    color: #111827;
    font-weight: 500;
}

.total-row {
    background: #F9FAFB;
    padding: 12px 0;
    margin: 8px 0;
    border-radius: 8px;
}

.total-row .account-name,
.total-row .account-balance {
    font-weight: 600;
}

.grand-total {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    padding: 16px 0;
    margin-top: 16px;
    border-radius: 12px;
}

.grand-total .account-name,
.grand-total .account-balance {
    color: white;
    font-size: 16px;
    font-weight: 700;
}

.balance-check {
    display: flex;
    align-items: center;
    gap: 12px;
    background: #FEF3C7;
    border: 1px solid #F59E0B;
    border-radius: 12px;
    padding: 16px 20px;
    color: #92400E;
    font-weight: 500;
}

@media (max-width: 1024px) {
    .balance-sheet-container {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}
</style>
@endsection