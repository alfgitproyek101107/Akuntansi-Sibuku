@extends('layouts.app')

@section('title', 'Detail Kategori')
@section('page-title', 'Detail Kategori')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                    </svg>
                    <span>Detail Kategori</span>
                </div>
                <h1 class="page-title">{{ $category->name }}</h1>
                <p class="page-subtitle">
                    {{ $category->type == 'income' ? 'Kategori pemasukan' : 'Kategori pengeluaran' }}
                </p>
            </div>
            <div class="header-right">
                <a href="{{ route('categories.edit', $category) }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit Kategori</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Category Info Section -->
        <section class="info-section">
            <div class="info-grid">
                <!-- Category Information Card -->
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon {{ $category->type }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                            </svg>
                        </div>
                        <h2 class="info-title">Informasi Kategori</h2>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <div class="info-label">Nama Kategori</div>
                            <div class="info-value">{{ $category->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tipe Kategori</div>
                            <div class="info-value">
                                <span class="category-type-badge {{ $category->type }}">
                                    {{ $category->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Jumlah Transaksi</div>
                            <div class="info-value">{{ $transactions->count() }} transaksi</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon {{ $category->type == 'income' ? 'success' : 'danger' }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </div>
                        <h2 class="info-title">Aksi Cepat</h2>
                    </div>
                    <div class="info-content">
                        @if($category->type == 'income')
                            <a href="{{ route('incomes.create') }}?category={{ $category->id }}" class="action-button success">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Tambah Pemasukan</span>
                            </a>
                        @else
                            <a href="{{ route('expenses.create') }}?category={{ $category->id }}" class="action-button danger">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Tambah Pengeluaran</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Transactions Section -->
        <section class="transactions-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Riwayat Transaksi</h2>
                    <p class="section-subtitle">Semua transaksi untuk kategori ini</p>
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

            <div class="table-container">
                @if($transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="transactions-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Tipe</th>
                                    <th>Rekening</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsTableBody">
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="transaction-date">
                                                {{ $transaction->date->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="transaction-type-badge {{ $transaction->type }}">
                                                {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="transaction-account">
                                                {{ $transaction->account->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-description">
                                                {{ $transaction->description ?: '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="transaction-amount {{ $transaction->type }}">
                                                Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada transaksi</h3>
                        <p class="empty-description">
                            Belum ada transaksi untuk kategori ini.
                            @if($category->type == 'income')
                                Mulai dengan menambahkan pemasukan baru.
                            @else
                                Mulai dengan menambahkan pengeluaran baru.
                            @endif
                        </p>
                        <div class="empty-action">
                            @if($category->type == 'income')
                                <a href="{{ route('incomes.create') }}?category={{ $category->id }}" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span>Tambah Pemasukan</span>
                                </a>
                            @else
                                <a href="{{ route('expenses.create') }}?category={{ $category->id }}" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span>Tambah Pengeluaran</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
</div>

<script>
// Filter transactions function
function filterTransactions(searchTerm) {
    const rows = document.querySelectorAll('#transactionsTableBody tr');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
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

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
}

/* ===== INFO SECTION ===== */
.info-section {
    margin-bottom: 48px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 24px;
}

.info-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.info-card:nth-child(1) {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.info-card:nth-child(2) {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.info-icon.income {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.info-icon.expense {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
}

.info-icon.success {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.info-icon.danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
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

.category-type-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.category-type-badge.income {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.category-type-badge.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.action-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    width: 100%;
    justify-content: center;
}

.action-button.success {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.action-button.danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.action-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
}

/* ===== TRANSACTIONS SECTION ===== */
.transactions-section {
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
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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

.transactions-table {
    width: 100%;
    border-collapse: collapse;
}

.transactions-table thead {
    background: #F9FAFB;
}

.transactions-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.transactions-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.transactions-table tbody tr:hover {
    background: #F9FAFB;
}

.transactions-table td {
    padding: 16px;
    font-size: 14px;
}

.transaction-date {
    font-weight: 600;
    color: #111827;
}

.transaction-type-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.transaction-type-badge.income {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.transaction-type-badge.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.transaction-account {
    font-weight: 500;
    color: #374151;
}

.transaction-description {
    color: #6B7280;
}

.transaction-amount {
    font-weight: 700;
    font-size: 16px;
}

.transaction-amount.income {
    color: #22C55E;
}

.transaction-amount.expense {
    color: #EF4444;
}

/* ===== EMPTY STATE ===== */
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
    background: rgba(79, 70, 229, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4F46E5;
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
    
    .info-grid {
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
    
    .transactions-table {
        font-size: 12px;
    }
    
    .transactions-table th,
    .transactions-table td {
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
    
    .info-card {
        padding: 20px;
    }
    
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection