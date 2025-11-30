@extends('layouts.app')

@section('title', 'Neraca Saldo - ' . $trialBalance['as_of_date'])
@section('page-title', 'Neraca Saldo')

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
                    <span>Neraca Saldo</span>
                </div>
                <h1 class="page-title">Laporan Neraca Saldo</h1>
                <p class="page-subtitle">Posisi semua akun per {{ \Carbon\Carbon::parse($trialBalance['as_of_date'])->format('d M Y') }}</p>
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
        <div class="trial-balance-container">
            <div class="trial-balance-table-container">
                <table class="trial-balance-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Akun</th>
                            <th>Tipe</th>
                            <th class="text-right">Debit</th>
                            <th class="text-right">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trialBalance['accounts'] as $account)
                        <tr>
                            <td class="account-code">{{ $account['code'] }}</td>
                            <td class="account-name">{{ $account['name'] }}</td>
                            <td class="account-type">
                                <span class="type-badge {{ $account['type'] }}">
                                    {{ ucfirst($account['type']) }}
                                </span>
                            </td>
                            <td class="text-right debit-amount">
                                @if($account['debit'] > 0)
                                    Rp{{ number_format($account['debit'], 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="text-right credit-amount">
                                @if($account['credit'] > 0)
                                    Rp{{ number_format($account['credit'], 0, ',', '.') }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="totals-row">
                            <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                            <td class="text-right total-debit">
                                <strong>Rp{{ number_format($trialBalance['totals']['total_debit'], 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-right total-credit">
                                <strong>Rp{{ number_format($trialBalance['totals']['total_credit'], 0, ',', '.') }}</strong>
                            </td>
                        </tr>
                        <tr class="difference-row">
                            <td colspan="3" class="text-right">
                                <strong>Selisih</strong>
                            </td>
                            <td colspan="2" class="text-center difference-amount {{ $trialBalance['totals']['difference'] == 0 ? 'balanced' : 'unbalanced' }}">
                                <strong>
                                    @if($trialBalance['totals']['difference'] == 0)
                                        Seimbang
                                    @else
                                        Rp{{ number_format(abs($trialBalance['totals']['difference']), 0, ',', '.') }}
                                        <span class="difference-direction">
                                            ({{ $trialBalance['totals']['difference'] > 0 ? 'Debit > Kredit' : 'Kredit > Debit' }})
                                        </span>
                                    @endif
                                </strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Summary Cards -->
            <div class="trial-balance-summary">
                <div class="summary-card">
                    <div class="summary-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="summary-content">
                        <h4>Total Debit</h4>
                        <p class="summary-value">Rp{{ number_format($trialBalance['totals']['total_debit'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <div class="summary-content">
                        <h4>Total Kredit</h4>
                        <p class="summary-value">Rp{{ number_format($trialBalance['totals']['total_credit'], 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="summary-card status-card {{ $trialBalance['totals']['difference'] == 0 ? 'balanced' : 'unbalanced' }}">
                    <div class="summary-icon">
                        @if($trialBalance['totals']['difference'] == 0)
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22 4 12 14.01 9 11.01"/>
                            </svg>
                        @else
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>
                        @endif
                    </div>
                    <div class="summary-content">
                        <h4>Status</h4>
                        <p class="summary-value">
                            {{ $trialBalance['totals']['difference'] == 0 ? 'Seimbang' : 'Tidak Seimbang' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
.trial-balance-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.trial-balance-table-container {
    overflow-x: auto;
}

.trial-balance-table {
    width: 100%;
    border-collapse: collapse;
}

.trial-balance-table thead {
    background: #F9FAFB;
}

.trial-balance-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #E5E7EB;
}

.trial-balance-table th.text-right {
    text-align: right;
}

.trial-balance-table td {
    padding: 12px 16px;
    border-bottom: 1px solid #F3F4F6;
    font-size: 14px;
}

.account-code {
    font-family: 'Monaco', 'Menlo', monospace;
    font-weight: 600;
    color: #4F46E5;
}

.account-name {
    font-weight: 500;
    color: #111827;
}

.type-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.type-badge.asset {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.type-badge.liability {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.type-badge.equity {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.type-badge.revenue {
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
}

.type-badge.expense {
    background: rgba(139, 69, 19, 0.1);
    color: #92400E;
}

.debit-amount {
    color: #22C55E;
    font-weight: 500;
}

.credit-amount {
    color: #EF4444;
    font-weight: 500;
}

.totals-row {
    background: #F9FAFB;
    border-top: 2px solid #E5E7EB;
}

.totals-row td {
    font-weight: 700;
    color: #111827;
}

.difference-row {
    background: #FEF3C7;
    border-top: 1px solid #F59E0B;
}

.difference-amount.balanced {
    color: #22C55E;
}

.difference-amount.unbalanced {
    color: #EF4444;
}

.difference-direction {
    font-size: 12px;
    font-weight: 400;
}

.trial-balance-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    padding: 28px;
    border-top: 1px solid #E5E7EB;
}

.summary-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: #F9FAFB;
    border-radius: 12px;
    border: 1px solid #E5E7EB;
}

.summary-card.status-card.balanced {
    background: rgba(34, 197, 94, 0.05);
    border-color: #22C55E;
}

.summary-card.status-card.unbalanced {
    background: rgba(239, 68, 68, 0.05);
    border-color: #EF4444;
}

.summary-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
}

.summary-card.status-card.balanced .summary-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.summary-card.status-card.unbalanced .summary-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
}

.summary-content h4 {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
    margin-bottom: 4px;
}

.summary-value {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

@media (max-width: 768px) {
    .trial-balance-table-container {
        font-size: 12px;
    }

    .trial-balance-table th,
    .trial-balance-table td {
        padding: 8px;
    }

    .trial-balance-summary {
        grid-template-columns: 1fr;
        padding: 20px;
    }
}
</style>
@endsection