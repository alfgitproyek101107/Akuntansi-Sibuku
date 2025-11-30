@extends('layouts.app')

@section('title', 'Faktur Pajak')
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
                    <span>Faktur Pajak</span>
                </div>
                <h1 class="page-title">Manajemen Faktur Pajak</h1>
                <p class="page-subtitle">Kelola faktur pajak dan integrasi dengan CoreTax</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Filters -->
        <div class="filters-section">
            <form method="GET" action="{{ route('tax.invoices') }}" class="filters-form">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label for="status" class="filter-label">Status</label>
                        <select name="status" id="status" class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="generated" {{ request('status') == 'generated' ? 'selected' : '' }}>Generated</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="tax_type" class="filter-label">Tipe Pajak</label>
                        <select name="tax_type" id="tax_type" class="filter-select">
                            <option value="">Semua Tipe</option>
                            <option value="ppn" {{ request('tax_type') == 'ppn' ? 'selected' : '' }}>PPN</option>
                            <option value="ppn_umkm" {{ request('tax_type') == 'ppn_umkm' ? 'selected' : '' }}>PPN UMKM</option>
                            <option value="pph_21" {{ request('tax_type') == 'pph_21' ? 'selected' : '' }}>PPh 21</option>
                            <option value="pph_22" {{ request('tax_type') == 'pph_22' ? 'selected' : '' }}>PPh 22</option>
                            <option value="pph_23" {{ request('tax_type') == 'pph_23' ? 'selected' : '' }}>PPh 23</option>
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
                        <a href="{{ route('tax.invoices') }}" class="btn-reset">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Invoices Table -->
        <div class="table-section">
            <div class="table-header">
                <h3 class="table-title">Daftar Faktur Pajak</h3>
                <div class="table-info">
                    Menampilkan {{ $taxInvoices->count() }} dari {{ $taxInvoices->total() }} faktur
                </div>
            </div>

            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nomor Faktur</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Tipe Pajak</th>
                            <th>Subtotal</th>
                            <th>Pajak</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taxInvoices as $invoice)
                            <tr>
                                <td>
                                    <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                                    @if($invoice->coretax_invoice_id)
                                        <div class="coretax-id">CoreTax: {{ $invoice->coretax_invoice_id }}</div>
                                    @endif
                                </td>
                                <td>{{ $invoice->customer_name ?: 'N/A' }}</td>
                                <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="tax-type-badge tax-type-{{ $invoice->tax_type }}">
                                        {{ $invoice->getTaxTypeDisplayName() }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong></td>
                                <td>
                                    <span class="status-badge status-{{ $invoice->status }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                    @if($invoice->coretax_status)
                                        <br>
                                        <small class="coretax-status">CoreTax: {{ ucfirst($invoice->coretax_status) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('tax.invoices.show', $invoice) }}" class="btn-action btn-view" title="Lihat Detail">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        @if($invoice->status === 'generated' || $invoice->status === 'rejected')
                                            <form method="POST" action="{{ route('tax.invoices.send', $invoice) }}" class="inline-form" onsubmit="return confirm('Kirim faktur ini ke CoreTax?')">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn-action btn-send" title="Kirim ke CoreTax">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if($invoice->status === 'sent' || $invoice->status === 'approved')
                                            <form method="POST" action="{{ route('tax.invoices.check-status', $invoice) }}" class="inline-form">
                                                @csrf
                                                @method('POST')
                                                <button type="submit" class="btn-action btn-check" title="Cek Status CoreTax">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('tax.invoices.download', $invoice) }}" class="btn-action btn-download" title="Download Faktur" target="_blank">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="empty-row">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="empty-title">Belum ada faktur pajak</div>
                                        <div class="empty-description">Faktur pajak akan muncul di sini setelah transaksi dengan pajak dibuat</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($taxInvoices->hasPages())
                <div class="pagination-container">
                    {{ $taxInvoices->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
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

.invoice-number {
    font-weight: 600;
    color: #111827;
}

.coretax-id {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
}

.tax-type-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.tax-type-ppn { background: #DBEAFE; color: #1E40AF; }
.tax-type-ppn_umkm { background: #DCFCE7; color: #166534; }
.tax-type-pph_21 { background: #FEF3C7; color: #92400E; }
.tax-type-pph_22 { background: #FEE2E2; color: #991B1B; }
.tax-type-pph_23 { background: #E0E7FF; color: #3730A3; }

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-draft { background: #FEF3C7; color: #92400E; }
.status-generated { background: #DBEAFE; color: #1E40AF; }
.status-sent { background: #FEF3C7; color: #92400E; }
.status-approved { background: #D1FAE5; color: #065F46; }
.status-rejected { background: #FEE2E2; color: #991B1B; }

.coretax-status {
    color: #6B7280;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-view { background: #DBEAFE; color: #1E40AF; }
.btn-view:hover { background: #BFDBFE; }

.btn-send { background: #FEF3C7; color: #92400E; }
.btn-send:hover { background: #FDE68A; }

.btn-check { background: #D1FAE5; color: #065F46; }
.btn-check:hover { background: #A7F3D0; }

.btn-download { background: #E0E7FF; color: #3730A3; }
.btn-download:hover { background: #C7D2FE; }

.inline-form {
    display: inline;
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

/* Responsive */
@media (max-width: 768px) {
    .filters-grid {
        grid-template-columns: 1fr;
    }

    .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .data-table {
        font-size: 12px;
    }

    .data-table th,
    .data-table td {
        padding: 12px 16px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
</style>
@endsection