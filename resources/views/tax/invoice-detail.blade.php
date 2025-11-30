@extends('layouts.app')

@section('title', 'Detail Faktur Pajak - ' . $taxInvoice->invoice_number)
@section('page-title', 'Pajak')

@section('content')
<div class="dashboard-layout">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Detail Faktur</span>
                </div>
                <h1 class="page-title">{{ $taxInvoice->invoice_number }}</h1>
                <p class="page-subtitle">Detail lengkap faktur pajak</p>
            </div>

            <div class="header-actions">
                <div class="status-display">
                    <span class="status-badge status-{{ $taxInvoice->status }}">
                        {{ ucfirst($taxInvoice->status) }}
                    </span>
                    @if($taxInvoice->coretax_status)
                        <span class="coretax-status">CoreTax: {{ ucfirst($taxInvoice->coretax_status) }}</span>
                    @endif
                </div>

                <div class="action-buttons">
                    @if($taxInvoice->status === 'generated' || $taxInvoice->status === 'rejected')
                        <form method="POST" action="{{ route('tax.invoices.send', $taxInvoice) }}" class="inline-form" onsubmit="return confirm('Kirim faktur ini ke CoreTax?')">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn-send">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Kirim ke CoreTax
                            </button>
                        </form>
                    @endif

                    @if($taxInvoice->status === 'sent' || $taxInvoice->status === 'approved')
                        <form method="POST" action="{{ route('tax.invoices.check-status', $taxInvoice) }}" class="inline-form">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn-check">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Cek Status
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('tax.invoices.download', $taxInvoice) }}" class="btn-download" target="_blank">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </a>

                    <a href="{{ route('tax.invoices') }}" class="btn-back">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Invoice Overview -->
            <section class="overview-section">
                <div class="overview-grid">
                    <div class="overview-card">
                        <div class="overview-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="overview-content">
                            <div class="overview-value">{{ $taxInvoice->invoice_number }}</div>
                            <div class="overview-label">Nomor Faktur</div>
                        </div>
                    </div>

                    <div class="overview-card">
                        <div class="overview-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="overview-content">
                            <div class="overview-value">{{ $taxInvoice->invoice_date->format('d/m/Y') }}</div>
                            <div class="overview-label">Tanggal Faktur</div>
                        </div>
                    </div>

                    <div class="overview-card">
                        <div class="overview-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="overview-content">
                            <div class="overview-value">Rp {{ number_format($taxInvoice->total_amount, 0, ',', '.') }}</div>
                            <div class="overview-label">Total Faktur</div>
                        </div>
                    </div>

                    <div class="overview-card">
                        <div class="overview-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="overview-content">
                            <div class="overview-value">{{ $taxInvoice->getTaxTypeDisplayName() }}</div>
                            <div class="overview-label">Tipe Pajak</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Parties Information -->
            <section class="parties-section">
                <div class="parties-grid">
                    <div class="party-card">
                        <div class="party-header">
                            <div class="party-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <h3 class="party-title">Penjual</h3>
                        </div>
                        <div class="party-content">
                            <div class="party-name">{{ $taxInvoice->branch->name }}</div>
                            <div class="party-detail">
                                <span class="detail-label">NPWP:</span>
                                <span class="detail-value">{{ $taxInvoice->branch->npwp ?? 'N/A' }}</span>
                            </div>
                            <div class="party-address">{{ $taxInvoice->branch->address ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="party-card">
                        <div class="party-header">
                            <div class="party-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="party-title">Pembeli</h3>
                        </div>
                        <div class="party-content">
                            <div class="party-name">{{ $taxInvoice->customer_name ?: 'N/A' }}</div>
                            <div class="party-detail">
                                <span class="detail-label">NPWP:</span>
                                <span class="detail-value">{{ $taxInvoice->customer_npwp ?: 'N/A' }}</span>
                            </div>
                            <div class="party-address">{{ $taxInvoice->customer_address ?: 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Items Table -->
            <section class="items-section">
                <div class="section-header">
                    <h3 class="section-title">Detail Barang/Jasa</h3>
                </div>
                <div class="items-container">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Nama Barang/Jasa</th>
                                <th>Kuantitas</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($taxInvoice->items && is_array($taxInvoice->items))
                                @foreach($taxInvoice->items as $item)
                                    <tr>
                                        <td>{{ $item['name'] ?? 'N/A' }}</td>
                                        <td>{{ $item['quantity'] ?? 1 }}</td>
                                        <td>Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item['total'] ?? 0, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="no-items">Detail item tidak tersedia</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Summary -->
            <section class="summary-section">
                <div class="summary-card">
                    <div class="summary-header">
                        <h3 class="summary-title">Ringkasan Faktur</h3>
                    </div>
                    <div class="summary-content">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value">Rp {{ number_format($taxInvoice->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Pajak ({{ number_format($taxInvoice->tax_rate, 2) }}%):</span>
                            <span class="summary-value">Rp {{ number_format($taxInvoice->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row total-row">
                            <span class="summary-label">Total:</span>
                            <span class="summary-value">Rp {{ number_format($taxInvoice->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Transaction Reference -->
            @if($taxInvoice->transaction)
                <section class="reference-section">
                    <div class="section-header">
                        <h3 class="section-title">Referensi Transaksi</h3>
                    </div>
                    <div class="reference-card">
                        <div class="reference-content">
                            <div class="reference-detail">
                                <span class="reference-label">ID Transaksi:</span>
                                <span class="reference-value">{{ $taxInvoice->transaction->id }}</span>
                            </div>
                            <div class="reference-detail">
                                <span class="reference-label">Deskripsi:</span>
                                <span class="reference-value">{{ $taxInvoice->transaction->description ?: 'N/A' }}</span>
                            </div>
                            <div class="reference-detail">
                                <span class="reference-label">Tanggal:</span>
                                <span class="reference-value">{{ $taxInvoice->transaction->date->format('d/m/Y') }}</span>
                            </div>
                            <div class="reference-detail">
                                <span class="reference-label">Jumlah:</span>
                                <span class="reference-value">Rp {{ number_format($taxInvoice->transaction->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        @if($taxInvoice->transaction)
                            @php
                                $routeName = match($taxInvoice->transaction->type) {
                                    'income' => 'incomes.show',
                                    'expense' => 'expenses.show',
                                    'transfer' => 'transfers.show',
                                    default => null
                                };
                            @endphp
                            @if($routeName)
                                <a href="{{ route($routeName, $taxInvoice->transaction) }}" class="btn-view-transaction">
                                    Lihat Transaksi
                                </a>
                            @else
                                <span class="btn-view-transaction disabled" title="Tipe transaksi tidak didukung">
                                    Lihat Transaksi
                                </span>
                            @endif
                        @endif
                    </div>
                </section>
            @endif

            <!-- Activity Logs -->
            <section class="logs-section">
                <div class="section-header">
                    <h3 class="section-title">Log Aktivitas</h3>
                </div>
                <div class="logs-container">
                    @forelse($taxLogs as $log)
                        <div class="log-item">
                            <div class="log-icon">
                                @if($log->status === 'success')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @elseif($log->status === 'failed')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @else
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>
                            <div class="log-content">
                                <div class="log-action">{{ ucfirst($log->action) }}</div>
                                <div class="log-details">
                                    <span class="log-time">{{ $log->created_at->format('d/m/Y H:i:s') }}</span>
                                    <span class="log-status status-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
                                </div>
                                @if($log->response_message)
                                    <div class="log-message">{{ $log->response_message }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="no-logs">Belum ada aktivitas untuk faktur ini</div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>

<style>
/* Dashboard Layout */
.dashboard-layout {
    max-width: 1440px;
    margin: 0 auto;
    padding: 32px;
}

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
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
}

.page-title {
    font-size: 36px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 16px;
    color: #6B7280;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
}

/* Header Actions */
.header-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
}

.status-display {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 4px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-draft { background: #FEF3C7; color: #92400E; }
.status-generated { background: #DBEAFE; color: #1E40AF; }
.status-sent { background: #FEF3C7; color: #92400E; }
.status-approved { background: #D1FAE5; color: #065F46; }
.status-rejected { background: #FEE2E2; color: #991B1B; }

.coretax-status {
    font-size: 11px;
    color: #6B7280;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-send,
.btn-check,
.btn-download,
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-send { background: #FEF3C7; color: #92400E; }
.btn-send:hover { background: #FDE68A; }

.btn-check { background: #D1FAE5; color: #065F46; }
.btn-check:hover { background: #A7F3D0; }

.btn-download { background: #E0E7FF; color: #3730A3; }
.btn-download:hover { background: #C7D2FE; }

.btn-back { background: #F3F4F6; color: #374151; }
.btn-back:hover { background: #E5E7EB; }

.inline-form {
    display: inline;
}

/* Overview Section */
.overview-section {
    margin-bottom: 32px;
}

.overview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.overview-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
}

.overview-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.overview-content {
    flex-grow: 1;
}

.overview-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.overview-label {
    font-size: 14px;
    color: #6B7280;
}

/* Parties Section */
.parties-section {
    margin-bottom: 32px;
}

.parties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
}

.party-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.party-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.party-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.party-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.party-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.party-name {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
}

.party-detail {
    display: flex;
    gap: 8px;
    align-items: center;
}

.detail-label {
    font-weight: 600;
    color: #6B7280;
    min-width: 60px;
}

.detail-value {
    color: #111827;
}

.party-address {
    color: #6B7280;
    font-style: italic;
}

/* Items Section */
.items-section {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.items-container {
    overflow-x: auto;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table th,
.items-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #E5E7EB;
}

.items-table th {
    background: #F9FAFB;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.items-table td {
    font-size: 14px;
    color: #111827;
}

.no-items {
    text-align: center;
    color: #6B7280;
    font-style: italic;
    padding: 20px;
}

/* Summary Section */
.summary-section {
    margin-bottom: 32px;
}

.summary-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    max-width: 400px;
    margin-left: auto;
}

.summary-header {
    margin-bottom: 20px;
}

.summary-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.summary-content {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-label {
    font-weight: 500;
    color: #374151;
}

.summary-value {
    font-weight: 600;
    color: #111827;
}

.total-row {
    border-top: 2px solid #E5E7EB;
    margin-top: 12px;
    padding-top: 16px;
}

.total-row .summary-label,
.total-row .summary-value {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
}

/* Reference Section */
.reference-section {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.reference-card {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.reference-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.reference-detail {
    display: flex;
    gap: 12px;
    align-items: center;
}

.reference-label {
    font-weight: 600;
    color: #374151;
    min-width: 100px;
}

.reference-value {
    color: #111827;
}

.btn-view-transaction {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: #64748B;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    transition: background-color 0.2s ease;
    white-space: nowrap;
}

.btn-view-transaction:hover {
    background: #475569;
}

.btn-view-transaction.disabled {
    background: #9CA3AF;
    cursor: not-allowed;
    opacity: 0.6;
}

/* Logs Section */
.logs-section {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.logs-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.log-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
}

.log-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.log-icon svg {
    width: 16px;
    height: 16px;
}

.log-item.success .log-icon { background: #D1FAE5; color: #065F46; }
.log-item.failed .log-icon { background: #FEE2E2; color: #991B1B; }
.log-item .log-icon { background: #FEF3C7; color: #92400E; }

.log-content {
    flex: 1;
}

.log-action {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.log-details {
    display: flex;
    gap: 12px;
    align-items: center;
    margin-bottom: 4px;
}

.log-time {
    font-size: 12px;
    color: #6B7280;
}

.log-status {
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
}

.log-status.success { background: #D1FAE5; color: #065F46; }
.log-status.failed { background: #FEE2E2; color: #991B1B; }
.log-status.pending,
.log-status.retry { background: #FEF3C7; color: #92400E; }

.log-message {
    font-size: 13px;
    color: #6B7280;
    font-style: italic;
}

.no-logs {
    text-align: center;
    color: #6B7280;
    font-style: italic;
    padding: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-layout {
        padding: 16px;
    }

    .page-title {
        font-size: 28px;
    }

    .overview-grid {
        grid-template-columns: 1fr;
    }

    .parties-grid {
        grid-template-columns: 1fr;
    }

    .summary-card {
        margin-left: 0;
        max-width: none;
    }

    .reference-card {
        flex-direction: column;
        gap: 16px;
    }

    .action-buttons {
        flex-wrap: wrap;
    }

    .btn-send,
    .btn-check,
    .btn-download,
    .btn-back {
        flex: 1;
        min-width: 120px;
        justify-content: center;
    }
}
</style>
@endsection
