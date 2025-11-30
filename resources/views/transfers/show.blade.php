@extends('layouts.app')

@section('title', 'Detail Transfer')
@section('page-title', 'Detail Transfer')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7a2 2 0 0 0 2 2h7a2 2 0 0 0 2 2z"></path>
                    </svg>
                    <span>Detail Transfer</span>
                </div>
                <h1 class="page-title">Detail Transfer</h1>
                <p class="page-subtitle">Informasi lengkap transfer yang telah dilakukan</p>
            </div>
            <div class="header-right">
                <div class="action-buttons">
                    <a href="{{ route('transfers.edit', $transfer) }}" class="btn-secondary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit</span>
                    </a>
                    <a href="{{ route('transfers.index') }}" class="btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Transfer Details Section -->
            <section class="transfer-details-section">
                <div class="transfer-card">
                    <div class="transfer-header">
                        <div class="transfer-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="transfer-info">
                            <h2 class="transfer-title">Transfer #{{ $transfer->id }}</h2>
                            <p class="transfer-date">{{ $transfer->date->format('d M Y') }}</p>
                        </div>
                        <div class="transfer-status">
                            <span class="status-badge status-completed">Selesai</span>
                        </div>
                    </div>

                    <div class="transfer-body">
                        <!-- Transfer Amount -->
                        <div class="transfer-amount">
                            <div class="amount-label">Jumlah Transfer</div>
                            <div class="amount-value">Rp{{ number_format($transfer->amount, 0, ',', '.') }}</div>
                        </div>

                        <!-- Transfer Flow -->
                        <div class="transfer-flow">
                            <div class="account-from">
                                <div class="account-header">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2 2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    <span>Dari Rekening</span>
                                </div>
                                <div class="account-name">{{ $transfer->fromAccount->name }}</div>
                                <div class="account-balance">Saldo: Rp{{ number_format($transfer->fromAccount->balance, 0, ',', '.') }}</div>
                            </div>

                            <div class="transfer-arrow">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="21"></line>
                                    <path d="M21 11l-7-7-7 7"></path>
                                    <path d="M3 21l7-7 7 7"></path>
                                </svg>
                            </div>

                            <div class="account-to">
                                <div class="account-header">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2 2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    <span>Ke Rekening</span>
                                </div>
                                <div class="account-name">{{ $transfer->toAccount->name }}</div>
                                <div class="account-balance">Saldo: Rp{{ number_format($transfer->toAccount->balance, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <!-- Transfer Details -->
                        <div class="transfer-details">
                            <div class="detail-row">
                                <span class="detail-label">Deskripsi:</span>
                                <span class="detail-value">{{ $transfer->description ?: 'Tidak ada deskripsi' }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Tanggal Dibuat:</span>
                                <span class="detail-value">{{ $transfer->created_at->format('d M Y H:i') }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Terakhir Diupdate:</span>
                                <span class="detail-value">{{ $transfer->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Transaction Records Section -->
            <section class="transaction-records-section">
                <div class="records-card">
                    <div class="records-header">
                        <h3 class="records-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                            <span>Rekaman Transaksi</span>
                        </h3>
                    </div>

                    <div class="records-body">
                        @if($transfer->transactions->count() > 0)
                            @foreach($transfer->transactions as $transaction)
                                <div class="transaction-record">
                                    <div class="record-icon">
                                        @if($transaction->account_id == $transfer->from_account_id)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="7 16 12 21 17 16"></polyline>
                                                <line x1="12" y1="21" x2="12" y2="3"></line>
                                            </svg>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="7 8 12 3 17 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="21"></line>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="record-details">
                                        <div class="record-account">{{ $transaction->account->name }}</div>
                                        <div class="record-type">Transfer - {{ $transaction->type }}</div>
                                        <div class="record-date">{{ $transaction->date->format('d M Y') }}</div>
                                    </div>
                                    <div class="record-amount">
                                        @if($transaction->account_id == $transfer->from_account_id)
                                            <span class="amount-negative">-Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="amount-positive">+Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="no-records">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                                <p>Tidak ada rekaman transaksi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
// No JavaScript needed for detail view
document.addEventListener('DOMContentLoaded', function() {
    // Any initialization code can go here
});
</script>

<style>
/* ===== BASE STYLES ===== */
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

/* ===== DASHBOARD LAYOUT ===== */
.dashboard-layout {
    max-width: 1440px;
    margin: 0 auto;
    padding: 32px;
    position: relative;
}

/* ===== HEADER ===== */
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

.action-buttons {
    display: flex;
    gap: 12px;
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
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
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

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 32px;
}

/* ===== TRANSFER DETAILS SECTION ===== */
.transfer-details-section {
    margin-bottom: 48px;
}

.transfer-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.transfer-header {
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #E5E7EB;
}

.transfer-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.transfer-info {
    flex-grow: 1;
    margin-left: 16px;
}

.transfer-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.transfer-date {
    font-size: 14px;
    color: #6B7280;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-completed {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
}

.transfer-body {
    padding: 32px;
}

/* ===== TRANSFER AMOUNT ===== */
.transfer-amount {
    text-align: center;
    margin-bottom: 32px;
    padding: 24px;
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    border-radius: 16px;
    border: 2px solid #10B981;
}

.amount-label {
    font-size: 16px;
    color: #374151;
    font-weight: 600;
    margin-bottom: 8px;
}

.amount-value {
    font-size: 32px;
    font-weight: 800;
    color: #10B981;
}

/* ===== TRANSFER FLOW ===== */
.transfer-flow {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    padding: 24px;
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    border-radius: 16px;
    border: 1px solid #F59E0B;
}

.account-from, .account-to {
    flex: 1;
    text-align: center;
}

.account-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #92400E;
    margin-bottom: 12px;
}

.account-name {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.account-balance {
    font-size: 14px;
    color: #6B7280;
}

.transfer-arrow {
    margin: 0 20px;
    color: #F59E0B;
    font-size: 24px;
}

/* ===== TRANSFER DETAILS ===== */
.transfer-details {
    background: #F9FAFB;
    border-radius: 12px;
    padding: 20px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
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
    color: #6B7280;
}

/* ===== TRANSACTION RECORDS SECTION ===== */
.transaction-records-section {
    margin-bottom: 48px;
}

.records-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    position: sticky;
    top: 32px;
}

.records-header {
    background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
    padding: 20px 24px;
    border-bottom: 1px solid #E5E7EB;
}

.records-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.records-body {
    padding: 24px;
}

/* ===== TRANSACTION RECORD ===== */
.transaction-record {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    margin-bottom: 12px;
    background: #FFFFFF;
    transition: all 0.2s ease;
}

.transaction-record:hover {
    border-color: #4F46E5;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.1);
}

.transaction-record:last-child {
    margin-bottom: 0;
}

.record-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.record-details {
    flex-grow: 1;
}

.record-account {
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
}

.record-type {
    font-size: 12px;
    color: #6B7280;
    margin-bottom: 2px;
}

.record-date {
    font-size: 12px;
    color: #9CA3AF;
}

.record-amount {
    font-weight: 700;
    font-size: 16px;
}

.amount-positive {
    color: #10B981;
}

.amount-negative {
    color: #EF4444;
}

/* ===== NO RECORDS ===== */
.no-records {
    text-align: center;
    padding: 40px 20px;
    color: #9CA3AF;
}

.no-records svg {
    margin-bottom: 16px;
    opacity: 0.5;
}

.no-records p {
    font-size: 14px;
    margin: 0;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }

    .content-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .records-card {
        position: static;
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

    .action-buttons {
        width: 100%;
        justify-content: space-between;
    }

    .transfer-flow {
        flex-direction: column;
        gap: 20px;
    }

    .transfer-arrow {
        transform: rotate(90deg);
        margin: 0;
    }

    .transfer-amount .amount-value {
        font-size: 28px;
    }

    .transaction-record {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .record-amount {
        align-self: flex-end;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }

    .page-title {
        font-size: 24px;
    }

    .transfer-header {
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .transfer-body {
        padding: 20px;
    }

    .transfer-amount {
        padding: 20px;
    }

    .transfer-amount .amount-value {
        font-size: 24px;
    }

    .transfer-flow {
        padding: 20px;
    }

    .account-name {
        font-size: 16px;
    }

    .records-body {
        padding: 16px;
    }
}
</style>
@endsection