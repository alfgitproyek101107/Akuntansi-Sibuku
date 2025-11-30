@extends('layouts.app')

@section('title', 'Rekonsiliasi Rekening')
@section('page-title', 'Rekonsiliasi Rekening')

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
                <h1 class="page-title">Rekonsiliasi {{ $account->name }}</h1>
                <p class="page-subtitle">Pencocokan transaksi dengan rekening bank Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('accounts.show', $account) }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Rekening</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Account Information Section -->
            <div class="info-section">
                <div class="info-container">
                    <div class="info-header">
                        <div class="info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <h2 class="info-title">Informasi Rekening</h2>
                    </div>

                    <div class="info-body">
                        <div class="info-item">
                            <div class="info-label">Nama Rekening</div>
                            <div class="info-value">{{ $account->name }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Tipe Rekening</div>
                            <div class="info-value">
                                <span class="type-badge">{{ ucfirst($account->type) }}</span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-label">Saldo Saat Ini</div>
                            <div class="info-value balance {{ $account->balance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                                Rp{{ number_format($account->balance, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reconciliation Status Section -->
            <div class="status-section">
                <div class="status-container">
                    <div class="status-header">
                        <div class="status-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                            </svg>
                        </div>
                        <h2 class="status-title">Status Rekonsiliasi</h2>
                    </div>

                    <div class="status-body">
                        <p class="status-description">
                            Centang transaksi yang sesuai dengan rekening bank Anda untuk menandainya sebagai telah direkonsiliasi.
                        </p>
                        
                        <div class="status-progress">
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ ($transactions->where('reconciled', true)->count() / max($transactions->count(), 1)) * 100 }}%"></div>
                                </div>
                                <div class="progress-text">
                                    {{ $transactions->where('reconciled', true)->count() }} / {{ $transactions->count() }}
                                </div>
                            </div>
                            <div class="progress-label">
                                Transaksi telah direkonsiliasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table Section -->
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Transaksi</h2>
                    <p class="section-subtitle">Pilih transaksi yang cocok dengan rekening bank Anda</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="transactionSearch" placeholder="Cari transaksi..." onkeyup="filterTransactions(this.value)">
                    </div>
                </div>
            </div>

            @if($transactions->count() > 0)
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="reconcile-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Jumlah</th>
                                    <th>Direkonsiliasi</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsTableBody">
                                @foreach($transactions as $transaction)
                                    <tr class="transaction-row">
                                        <td>
                                            <div class="transaction-date">
                                                <div class="date-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                </div>
                                                <div class="date-details">
                                                    <div class="date-value">{{ $transaction->date->format('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-type">
                                                <div class="type-badge 
                                                    @if($transaction->type == 'income') type-income
                                                    @elseif($transaction->type == 'expense') type-expense
                                                    @else type-transfer
                                                    @endif">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        @if($transaction->type == 'income')
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        @elseif($transaction->type == 'expense')
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        @else
                                                            <polyline points="17 1 21 5 17 9"></polyline>
                                                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                                            <polyline points="7 23 3 19 7 15"></polyline>
                                                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                                        @endif
                                                    </svg>
                                                    <span>{{ ucfirst($transaction->type) }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-category">
                                                @if($transaction->category)
                                                    <div class="category-icon">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="category-name">{{ $transaction->category->name }}</div>
                                                @else
                                                    <div class="category-name">-</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-description">
                                                {{ $transaction->description ?: '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-amount 
                                                @if($transaction->type == 'income') amount-income
                                                @elseif($transaction->type == 'expense') amount-expense
                                                @else amount-transfer
                                                @endif">
                                                Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="reconcile-checkbox-container">
                                                <label class="custom-checkbox">
                                                    <input type="checkbox"
                                                           class="reconcile-checkbox"
                                                           value="{{ $transaction->id }}"
                                                           {{ $transaction->reconciled ? 'checked' : '' }}
                                                           onchange="toggleReconcile({{ $transaction->id }})">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <section class="empty-section">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 11l3 3L22 4"></path>
                                <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada transaksi</h3>
                        <p class="empty-description">Tidak ada transaksi yang ditemukan untuk rekening ini.</p>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>

<script>
function toggleReconcile(transactionId) {
    // Show loading state
    const checkbox = event.target;
    const originalChecked = checkbox.checked;
    checkbox.disabled = true;
    
    fetch(`{{ route('accounts.toggle-reconcile', $account) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            transaction_id: transactionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update progress bar
            updateProgressBar();
        } else {
            // Revert checkbox on error
            checkbox.checked = !originalChecked;
        }
        checkbox.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        // Revert checkbox on error
        checkbox.checked = !originalChecked;
        checkbox.disabled = false;
    });
}

function updateProgressBar() {
    // Count checked checkboxes
    const totalCheckboxes = document.querySelectorAll('.reconcile-checkbox').length;
    const checkedCheckboxes = document.querySelectorAll('.reconcile-checkbox:checked').length;
    const percentage = (checkedCheckboxes / totalCheckboxes) * 100;
    
    // Update progress bar
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.querySelector('.progress-text');
    
    if (progressFill && progressText) {
        progressFill.style.width = percentage + '%';
        progressText.textContent = `${checkedCheckboxes} / ${totalCheckboxes}`;
    }
}

// Filter transactions function
function filterTransactions(searchTerm) {
    const rows = document.querySelectorAll('#transactionsTableBody .transaction-row');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateProgressBar();
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

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    margin-bottom: 48px;
}

/* ===== INFO SECTION ===== */
.info-section {
    margin-bottom: 48px;
}

.info-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.info-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.info-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
}

.info-body {
    padding: 24px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #F3F4F6;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
}

.info-value {
    font-size: 16px;
    color: #111827;
    font-weight: 600;
}

.type-badge {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.balance-positive {
    color: #22C55E;
}

.balance-negative {
    color: #EF4444;
}

/* ===== STATUS SECTION ===== */
.status-section {
    margin-bottom: 48px;
}

.status-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.status-header {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.status-title {
    font-size: 20px;
    font-weight: 700;
    color: #065F46;
    margin: 0;
}

.status-body {
    padding: 24px;
}

.status-description {
    font-size: 14px;
    color: #6B7280;
    margin-bottom: 24px;
    line-height: 1.6;
}

.status-progress {
    margin-top: 16px;
}

.progress-container {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 8px;
}

.progress-bar {
    flex-grow: 1;
    height: 8px;
    background: #F3F4F6;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #22C55E 0%, #10B981 100%);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    min-width: 60px;
    text-align: right;
}

.progress-label {
    font-size: 12px;
    color: #6B7280;
    text-align: center;
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

.reconcile-table {
    width: 100%;
    border-collapse: collapse;
}

.reconcile-table thead {
    background: #F9FAFB;
}

.reconcile-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.reconcile-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.reconcile-table tbody tr:hover {
    background: #F9FAFB;
}

.reconcile-table td {
    padding: 16px;
    font-size: 14px;
}

.transaction-date {
    display: flex;
    align-items: center;
    gap: 12px;
}

.date-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.date-value {
    font-weight: 600;
    color: #111827;
}

.transaction-type {
    display: flex;
    align-items: center;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.type-income {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.type-expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.type-transfer {
    background: rgba(99, 102, 241, 0.1);
    color: #6366F1;
}

.transaction-category {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-name {
    font-weight: 600;
    color: #111827;
}

.transaction-description {
    color: #6B7280;
}

.transaction-amount {
    font-weight: 700;
    font-size: 16px;
}

.amount-income {
    color: #22C55E;
}

.amount-expense {
    color: #EF4444;
}

.amount-transfer {
    color: #6366F1;
}

/* ===== CUSTOM CHECKBOX ===== */
.reconcile-checkbox-container {
    display: flex;
    justify-content: center;
}

.custom-checkbox {
    position: relative;
    display: inline-block;
    width: 24px;
    height: 24px;
    cursor: pointer;
}

.custom-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 24px;
    width: 24px;
    background-color: #F3F4F6;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.custom-checkbox:hover input ~ .checkmark {
    background-color: #E5E7EB;
}

.custom-checkbox input:checked ~ .checkmark {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
    left: 8px;
    top: 4px;
    width: 6px;
    height: 12px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.custom-checkbox input:checked ~ .checkmark:after {
    display: block;
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
    margin: 0 auto;
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
    
    .reconcile-table {
        font-size: 12px;
    }
    
    .reconcile-table th,
    .reconcile-table td {
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
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .progress-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .progress-text {
        min-width: auto;
        text-align: left;
    }
    
    .transaction-date,
    .transaction-category {
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