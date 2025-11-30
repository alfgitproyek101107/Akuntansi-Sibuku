@extends('layouts.app')

@section('title', 'Mutasi Rekening - ' . $account->name)

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>Mutasi Rekening</span>
                </div>
                <h1 class="page-title">Mutasi Rekening</h1>
                <p class="page-subtitle">{{ $account->name }} - Saldo: Rp{{ number_format($account->balance, 0, ',', '.') }}</p>
            </div>
            <div class="header-right">
                <div class="date-indicator">
                    <div class="date-display">
                        <span class="date-day">{{ now()->format('d') }}</span>
                        <div class="date-details">
                            <span class="date-month">{{ now()->format('M') }}</span>
                            <span class="date-year">{{ now()->format('Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Filters Section -->
            <section class="form-section">
                <div class="form-card">
                    <div class="form-header">
                        <div class="form-title-wrapper">
                            <h2 class="form-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 12H3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7"/>
                                    <path d="M7 12H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7"/>
                                </svg>
                                Filter & Pencarian
                            </h2>
                            <div class="form-status">
                                <div class="status-dot active"></div>
                                <span>Mode Filter</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <form method="GET" action="{{ route('accounts.ledger', $account) }}" id="filterForm">
                            <!-- Date Range -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    Rentang Tanggal
                                </label>
                                <div class="date-range">
                                    <div class="date-input-group">
                                        <label class="date-input-label">Dari</label>
                                        <input type="date" name="from_date" value="{{ $fromDate }}" class="form-control">
                                    </div>
                                    <div class="date-input-group">
                                        <label class="date-input-label">Sampai</label>
                                        <input type="date" name="to_date" value="{{ $toDate }}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Transaction Type -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                    Tipe Transaksi
                                </label>
                                <div class="filter-chips">
                                    <button class="chip-btn {{ $type === 'all' ? 'active' : '' }}" onclick="setTransactionType('all')">
                                        Semua
                                    </button>
                                    <button class="chip-btn {{ $type === 'income' ? 'active' : '' }}" onclick="setTransactionType('income')">
                                        Pemasukan
                                    </button>
                                    <button class="chip-btn {{ $type === 'expense' ? 'active' : '' }}" onclick="setTransactionType('expense')">
                                        Pengeluaran
                                    </button>
                                    <button class="chip-btn {{ $type === 'transfer' ? 'active' : '' }}" onclick="setTransactionType('transfer')">
                                        Transfer
                                    </button>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.74-1.51-2.75-1.5-4.5A5.5 5.5 0 0 0 7.5 8c1.76 0 3-.5 4.5-2 1.74 1.51 2.75 1.5 4.5A5.5 5.5 0 0 0 12 19"/>
                                    </svg>
                                    Kategori
                                </label>
                                <div class="filter-chips">
                                    <button class="chip-btn {{ !$categoryId ? 'active' : '' }}" onclick="setCategory('')">
                                        Semua
                                    </button>
                                    @foreach($categories as $category)
                                        <button class="chip-btn {{ $categoryId == $category->id ? 'active' : '' }}" onclick="setCategory('{{ $category->id }}')">
                                            {{ $category->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Ledger Table -->
            <section class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title-group">
                            <h2 class="table-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 5H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7"/>
                                    <path d="M21 12H3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7"/>
                                </svg>
                                Buku Besar
                            </h2>
                            <div class="table-status">
                                <div class="status-dot active"></div>
                                <span>{{ $ledgerEntries->count() }} Transaksi</span>
                            </div>
                        </div>
                        <div class="table-actions">
                            <div class="export-dropdown">
                                <button class="export-btn" onclick="toggleExportMenu()">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H7l-4 4H5a2 2 0 0 1-2 2h12l4 4h1a2 2 0 0 1 2 2v4z"/>
                                    </svg>
                                    Export
                                </button>
                                <div class="export-menu" id="exportMenu">
                                    <a href="{{ route('accounts.ledger', $account) }}?format=excel" class="export-option">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8l4 4H6a2 2 0 0 0-2 2v-16z"/>
                                        </svg>
                                        Excel
                                    </a>
                                    <a href="{{ route('accounts.ledger', $account) }}?format=pdf" class="export-option">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h8l4 4H6a2 2 0 0 0-2 2v-16z"/>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-body">
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Deskripsi</th>
                                        <th>Kategori</th>
                                        <th>Debit</th>
                                        <th>Kredit</th>
                                        <th>Saldo</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ledgerEntries->take(50) as $entry)
                                        <tr class="table-row">
                                            <td>
                                                <div class="date-info">
                                                    <div class="date-day">{{ $entry['date']->format('d') }}</div>
                                                    <div class="date-month">{{ $entry['date']->format('M') }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="description">
                                                    {{ $entry['description'] ?: '-' }}
                                                    @if($entry['transaction'])
                                                        <br><small class="text-muted">Ref: {{ $entry['transaction']->reference }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($entry['category'])
                                                    <span class="category-badge" style="background-color: {{ $entry['category']->color ?? '#6b7280' }}">
                                                        {{ $entry['category']->name }}
                                                    </span>
                                                @else
                                                    <span class="category-badge category-transfer">Transfer</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($entry['debit'] > 0)
                                                    <span class="amount-text amount-expense">Rp{{ number_format($entry['debit'], 0, ',', '.') }}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($entry['credit'] > 0)
                                                    <span class="amount-text amount-income">Rp{{ number_format($entry['credit'], 0, ',', '.') }}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="balance-text {{ $entry['balance'] < 0 ? 'balance-negative' : 'balance-positive' }}">
                                                    Rp{{ number_format($entry['balance'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    @if($entry['transaction'])
                                                        @if($entry['transaction']->type == 'income')
                                                            <a href="{{ route('incomes.show', $entry['transaction']) }}" class="action-btn action-btn-primary" title="Lihat Detail">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M1 12s4-4 4-4 4 4-4 4 4-4 4-4h4a2 2 0 0 1 2 0v0a2 2 0 0 1 2 0v0h-2z"/>
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('incomes.edit', $entry['transaction']) }}" class="action-btn action-btn-warning" title="Edit">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7z"/>
                                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                                </svg>
                                                            </a>
                                                        @elseif($entry['transaction']->type == 'expense')
                                                            <a href="{{ route('expenses.show', $entry['transaction']) }}" class="action-btn action-btn-danger" title="Lihat Detail">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M1 12s4-4 4-4 4 4 4 4 4-4 4-4h4a2 2 0 0 1 2 0v0a2 2 0 0 1 2 0v0h-2z"/>
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('expenses.edit', $entry['transaction']) }}" class="action-btn action-btn-warning" title="Edit">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7z"/>
                                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                                </svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('transfers.show', $entry['transaction']) }}" class="action-btn action-btn-warning" title="Lihat Detail">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M16 8l-4 4-4 4-4-4 4-4 4-4-4h4a2 2 0 0 1 2 0v0a2 2 0 0 1 2 0v0h-2z"/>
                                                                    <path d="M21 12H9a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2 2v-7z"/>
                                                                </svg>
                                                            </a>
                                                            <a href="{{ route('transfers.edit', $entry['transaction']) }}" class="action-btn action-btn-warning" title="Edit">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7z"/>
                                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Summary Cards -->
            <section class="stats-section">
                <div class="stats-grid">
                    <!-- Total Debit -->
                    <div class="stat-card stat-card-danger">
                        <div class="stat-content">
                            <div class="stat-header">
                                <div class="stat-info">
                                    <h3 class="stat-label">Total Debit</h3>
                                    <p class="stat-value">Rp{{ number_format(collect($ledgerEntries)->sum('debit'), 0, ',', '.') }}</p>
                                </div>
                                <div class="stat-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 12H9a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2 2v-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Credit -->
                    <div class="stat-card stat-card-success">
                        <div class="stat-content">
                            <div class="stat-header">
                                <div class="stat-info">
                                    <h3 class="stat-label">Total Kredit</h3>
                                    <p class="stat-value">Rp{{ number_format(collect($ledgerEntries)->sum('credit'), 0, ',', '.') }}</p>
                                </div>
                                <div class="stat-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Balance -->
                    <div class="stat-card stat-card-primary">
                        <div class="stat-content">
                            <div class="stat-header">
                                <div class="stat-info">
                                    <h3 class="stat-label">Saldo Netto</h3>
                                    <p class="stat-value">Rp{{ number_format(collect($ledgerEntries)->sum('credit') - collect($ledgerEntries)->sum('debit'), 0, ',', '.') }}</p>
                                </div>
                                <div class="stat-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2-2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2 2v-7z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
// Filter functionality
function setTransactionType(type) {
    // Update URL without page reload
    const url = new URL(window.location);
    url.searchParams.set('type', type);
    window.history.replaceState({}, '', url);
    
    // Update active button
    document.querySelectorAll('.chip-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.chip-btn[onclick*="setTransactionType('${type}')"]`).classList.add('active');
}

function setCategory(categoryId) {
    // Update URL without page reload
    const url = new URL(window.location);
    url.searchParams.set('category', categoryId);
    window.history.replaceState({}, '', url);
    
    // Update active button
    document.querySelectorAll('.chip-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.chip-btn[onclick*="setCategory('${categoryId}')"]`).classList.add('active');
}

function toggleExportMenu() {
    const menu = document.getElementById('exportMenu');
    menu.classList.toggle('active');
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
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
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

.date-indicator {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #E5E7EB;
}

.date-display {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
}

.date-day {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    line-height: 1;
}

.date-details {
    display: flex;
    flex-direction: column;
}

.date-month {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.date-year {
    font-size: 12px;
    color: #9CA3AF;
}

/* ===== MAIN CONTENT ===== */
.dashboard-main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

/* ===== FORM SECTION ===== */
.form-section {
    margin-bottom: 24px;
}

.form-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.form-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
}

.form-title svg {
    color: #4F46E5;
    margin-right: 8px;
}

.form-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #22C55E;
    font-weight: 600;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22C55E;
}

.status-dot.active {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.form-body {
    padding: 28px;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-label svg {
    color: #4F46E5;
    width: 20px;
    height: 20px;
}

.required {
    color: #EF4444;
    margin-left: 4px;
}

.date-range {
    display: flex;
    gap: 16px;
}

.date-input-group {
    flex: 1;
}

.date-input-label {
    font-size: 12px;
    color: #6B7280;
    margin-bottom: 4px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.form-control:focus {
    outline: none;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.filter-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.chip-btn {
    padding: 8px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 20px;
    background: #FFFFFF;
    color: #6B7280;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.chip-btn:hover {
    background: #F3F4F6;
}

.chip-btn.active {
    background: #4F46E5;
    color: #FFFFFF;
    border-color: #4F46E5;
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
    align-items: flex-start;
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.table-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
}

.table-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
}

.table-title svg {
    color: #4F46E5;
    margin-right: 8px;
}

.table-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #22C55E;
    font-weight: 600;
}

.table-actions {
    position: relative;
}

.export-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    background: #FFFFFF;
    color: #6B7280;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.export-btn:hover {
    background: #F3F4F6;
}

.export-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 10;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
    min-width: 150px;
}

.export-menu.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.export-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s ease;
}

.export-option:hover {
    background: #F9FAFB;
}

.export-option svg {
    color: #6B7280;
}

.table-body {
    padding: 0;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #F9FAFB;
}

.data-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.data-table tbody tr:hover {
    background: #F9FAFB;
}

.data-table td {
    padding: 16px;
    font-size: 14px;
    vertical-align: middle;
}

.date-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.date-day {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
}

.date-month {
    font-size: 12px;
    color: #6B7280;
}

.description {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.category-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.category-transfer {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.amount-text {
    font-weight: 700;
    font-size: 16px;
}

.amount-income {
    color: #22C55E;
}

.amount-expense {
    color: #EF4444;
}

.balance-positive {
    color: #22C55E;
}

.balance-negative {
    color: #EF4444;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
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
}

.action-btn-primary {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.action-btn-warning {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

/* ===== STATS SECTION ===== */
.stats-section {
    margin-bottom: 24px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.stat-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.stat-content {
    padding: 24px;
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 24px;
    font-weight: 7 700;
    color: #111827;
    margin-bottom: 8px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.stat-card-danger .stat-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
}

.stat-card-success .stat-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.stat-card-primary .stat-icon {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
}

/* ===== RESPONSIVE DESIGN ===== */
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
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .table-card {
        margin-bottom: 16px;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .form-body {
        padding: 20px;
    }
    
    .date-range {
        flex-direction: column;
        gap: 12px;
    }
    
    .table-header {
        flex-direction: column;
        gap: 16px;
    }
    
    .table-actions {
        width: 100%;
        margin-top: 16px;
    }
    
    .export-menu {
        position: relative;
        top: 100%;
        left: 0;
        right: 0;
        min-width: 200px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .form-header {
        padding: 20px;
    }
    
    .form-body {
        padding: 16px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-value {
        font-size: 20px;
    }
}
</style>
@endsection