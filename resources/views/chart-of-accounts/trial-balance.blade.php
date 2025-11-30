@extends('layouts.app')

@section('title', 'Neraca Saldo')
@section('page-title', 'Neraca Saldo')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 14l6-6m-5.5.5h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span>Neraca Saldo</span>
                </div>
                <h1 class="page-title">Laporan Neraca Saldo</h1>
                <p class="page-subtitle">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
            </div>
            <div class="header-right">
                <a href="{{ route('chart-of-accounts.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Kembali ke Daftar Akun
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Filters Section -->
            <section class="filters-section">
                <div class="filters-card">
                    <div class="filters-header">
                        <h2 class="filters-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter Periode
                        </h2>
                    </div>
                    <div class="filters-body">
                        <form method="GET" action="{{ route('trial-balance') }}" class="filters-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="M21 21l-4.35-4.35"></path>
                                        </svg>
                                        Terapkan Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Trial Balance Table Section -->
            <section class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title-group">
                            <h2 class="table-title">Data Neraca Saldo</h2>
                            <p class="table-subtitle">Ringkasan debit dan kredit per akun</p>
                        </div>
                    </div>
                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="trial-balance-table">
                                <thead>
                                    <tr>
                                        <th>Kode Akun</th>
                                        <th>Nama Akun</th>
                                        <th class="text-right">Debit</th>
                                        <th class="text-right">Kredit</th>
                                        <th class="text-right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalDebit = 0;
                                        $totalCredit = 0;
                                        $totalBalance = 0;
                                    @endphp

                                    @forelse($trialBalance as $account)
                                        <tr>
                                            <td>{{ $account['account_code'] }}</td>
                                            <td>{{ $account['account_name'] }}</td>
                                            <td class="text-right {{ $account['debit'] > 0 ? 'debit-amount' : '' }}">
                                                {{ $account['debit'] > 0 ? 'Rp' . number_format($account['debit'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right {{ $account['credit'] > 0 ? 'credit-amount' : '' }}">
                                                {{ $account['credit'] > 0 ? 'Rp' . number_format($account['credit'], 0, ',', '.') : '-' }}
                                            </td>
                                            <td class="text-right {{ $account['balance'] != 0 ? ($account['balance'] > 0 ? 'balance-positive' : 'balance-negative') : '' }}">
                                                {{ $account['balance'] != 0 ? 'Rp' . number_format(abs($account['balance']), 0, ',', '.') : '-' }}
                                            </td>
                                        </tr>
                                        @php
                                            $totalDebit += $account['debit'];
                                            $totalCredit += $account['credit'];
                                            $totalBalance += $account['balance'];
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center no-data">
                                                <div class="no-data-content">
                                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"></path>
                                                        <path d="M9 5a2 2 0 012-2h2a2 2 0 012 2v6a2 2 0 01-2 2H9z"></path>
                                                        <path d="M21 5a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2V7a2 2 0 012-2z"></path>
                                                        <path d="M2 17l10 5 10-5"></path>
                                                        <path d="M2 12l10 5 10-5"></path>
                                                    </svg>
                                                    <p>Tidak ada data neraca saldo untuk periode yang dipilih</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    @if(count($trialBalance) > 0)
                                        <tr class="total-row">
                                            <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                            <td class="text-right debit-amount"><strong>Rp{{ number_format($totalDebit, 0, ',', '.') }}</strong></td>
                                            <td class="text-right credit-amount"><strong>Rp{{ number_format($totalCredit, 0, ',', '.') }}</strong></td>
                                            <td class="text-right {{ $totalBalance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                                                <strong>Rp{{ number_format(abs($totalBalance), 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

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
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
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

/* ===== MAIN CONTENT ===== */
.dashboard-main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

/* ===== FILTERS SECTION ===== */
.filters-section {
    margin-bottom: 24px;
}

.filters-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.filters-header {
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.filters-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
}

.filters-title svg {
    color: #10B981;
    margin-right: 8px;
}

.filters-body {
    padding: 28px;
}

.filters-form {
    max-width: 800px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 16px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.form-control {
    padding: 12px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.form-control:focus {
    outline: none;
    border-color: #10B981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    border: none;
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    border: 2px solid #E5E7EB;
    background: #FFFFFF;
    color: #6B7280;
}

.btn-secondary:hover {
    background: #F9FAFB;
    color: #374151;
}

/* ===== TABLE SECTION ===== */
.table-section {
    margin-bottom: 24px;
}

.table-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.table-title-group {
    flex: 1;
}

.table-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.table-subtitle {
    font-size: 14px;
    color: #6B7280;
}

.table-container {
    overflow-x: auto;
}

.table-responsive {
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

.trial-balance-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.trial-balance-table tbody tr:hover {
    background: #F9FAFB;
}

.trial-balance-table td {
    padding: 16px;
    font-size: 14px;
    border-bottom: 1px solid #F3F4F6;
}

.trial-balance-table td.text-right {
    text-align: right;
}

.trial-balance-table .total-row {
    background: #F3F4F6;
    font-weight: 600;
}

.trial-balance-table .total-row td {
    border-top: 2px solid #E5E7EB;
    border-bottom: none;
}

.debit-amount {
    color: #22C55E;
    font-weight: 600;
}

.credit-amount {
    color: #EF4444;
    font-weight: 600;
}

.balance-positive {
    color: #22C55E;
    font-weight: 600;
}

.balance-negative {
    color: #EF4444;
    font-weight: 600;
}

.no-data {
    text-align: center;
    padding: 48px 16px;
}

.no-data-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.no-data-content svg {
    color: #9CA3AF;
}

.no-data-content p {
    color: #6B7280;
    font-size: 16px;
    margin: 0;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
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

    .filters-body {
        padding: 20px;
    }

    .table-header {
        padding: 20px;
    }

    .trial-balance-table th,
    .trial-balance-table td {
        padding: 12px 8px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }

    .page-title {
        font-size: 24px;
    }

    .filters-header,
    .filters-body,
    .table-header {
        padding: 16px;
    }

    .trial-balance-table th,
    .trial-balance-table td {
        padding: 8px 4px;
    }

    .form-row {
        gap: 8px;
    }
}
</style>
@endsection