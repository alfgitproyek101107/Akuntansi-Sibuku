@extends('layouts.app')

@section('title', 'Detail Pemasukan')
@section('page-title', 'Detail Pemasukan')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>Detail Pemasukan</span>
                </div>
                <h1 class="page-title">Detail Pemasukan</h1>
                <p class="page-subtitle">Lihat informasi lengkap transaksi pemasukan</p>
            </div>
            <div class="header-right">
                <a href="{{ route('incomes.edit', $transaction) }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit Pemasukan</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Transaction Details Section -->
            <section class="details-section">
                <div class="details-card">
                    <div class="card-header">
                        <div class="card-title-section">
                            <h2 class="card-title">Informasi Transaksi</h2>
                            <div class="card-status">
                                <div class="status-dot active"></div>
                                <span>Selesai</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Amount Display -->
                        <div class="amount-display">
                            <div class="amount-label">Jumlah Pemasukan</div>
                            <div class="amount-value">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Transaction Information -->
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Tanggal</div>
                                    <div class="info-value">{{ $transaction->date->format('d F Y') }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Rekening</div>
                                    <div class="info-value">{{ $transaction->account->name }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Kategori</div>
                                    <div class="info-value">{{ $transaction->category->name }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Keterangan</div>
                                    <div class="info-value">{{ $transaction->description ?: 'Tidak ada keterangan' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Income Items (if multi-item transaction) -->
                @if($transaction->incomeItems->count() > 0)
                <div class="items-card">
                    <div class="card-header">
                        <div class="card-title-section">
                            <h2 class="card-title">Detail Produk</h2>
                            <div class="card-status">
                                <div class="status-dot active"></div>
                                <span>{{ $transaction->incomeItems->count() }} item</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="items-table-container">
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->incomeItems as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </section>

            <!-- Actions Sidebar -->
            <section class="actions-section">
                <div class="actions-card">
                    <div class="card-header">
                        <div class="card-title-section">
                            <h2 class="card-title">Aksi</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="action-buttons">
                            <a href="{{ route('incomes.edit', $transaction) }}" class="action-button edit">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <span>Edit Pemasukan</span>
                            </a>

                            <form method="POST" action="{{ route('incomes.destroy', $transaction) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemasukan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-button delete">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                    <span>Hapus Pemasukan</span>
                                </button>
                            </form>
                        </div>

                        <div class="action-note">
                            <div class="note-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </div>
                            <div class="note-text">
                                <h4>Perhatian</h4>
                                <p>Menghapus pemasukan akan mengurangi saldo rekening terkait dan tidak dapat dibatalkan.</p>
                            </div>
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
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
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
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
}

/* ===== DETAILS SECTION ===== */
.details-section {
    margin-bottom: 48px;
}

.details-card, .items-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-bottom: 24px;
}

.card-header {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.card-title-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 20px;
    font-weight: 700;
    color: #065F46;
    margin: 0;
}

.card-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #047857;
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

.card-body {
    padding: 32px;
}

/* ===== AMOUNT DISPLAY ===== */
.amount-display {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    border: 2px solid #22C55E;
    border-radius: 16px;
    padding: 32px;
    text-align: center;
    margin-bottom: 32px;
}

.amount-label {
    font-size: 16px;
    color: #065F46;
    font-weight: 600;
    margin-bottom: 16px;
}

.amount-value {
    font-size: 36px;
    font-weight: 800;
    color: #047857;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.amount-value svg {
    color: #22C55E;
}

/* ===== INFO GRID ===== */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 20px;
    background: #F8FAFC;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    transition: all 0.2s ease;
}

.info-item:hover {
    background: #F1F5F9;
    border-color: #CBD5E1;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-content {
    flex-grow: 1;
}

.info-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
    margin-bottom: 4px;
}

.info-value {
    font-size: 16px;
    color: #111827;
    font-weight: 600;
}

/* ===== ITEMS TABLE ===== */
.items-table-container {
    background: #F8FAFC;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #E2E8F0;
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
    background: #F1F5F9;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.items-table td {
    font-size: 14px;
    color: #6B7280;
}

.items-table tbody tr:last-child td {
    border-bottom: none;
}

/* ===== ACTIONS SECTION ===== */
.actions-section {
    margin-bottom: 48px;
}

.actions-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    position: sticky;
    top: 32px;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 24px;
}

.action-button {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    width: 100%;
    justify-content: center;
}

.action-button.edit {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.action-button.edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(245, 158, 11, 0.4);
}

.action-button.delete {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.action-button.delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.action-note {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    background: #FEF3C7;
    border-radius: 12px;
    padding: 16px;
}

.note-icon {
    width: 40px;
    height: 40px;
    background: #F59E0B;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}

.note-text h4 {
    font-size: 16px;
    font-weight: 700;
    color: #92400E;
    margin: 0 0 8px 0;
}

.note-text p {
    font-size: 14px;
    color: #78350F;
    margin: 0;
    line-height: 1.5;
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
    
    .actions-card {
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
    
    .card-body {
        padding: 24px;
    }
    
    .amount-display {
        padding: 24px;
    }
    
    .amount-value {
        font-size: 28px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .card-body {
        padding: 16px;
    }
    
    .card-header {
        padding: 16px;
    }
    
    .amount-display {
        padding: 20px;
    }
    
    .amount-value {
        font-size: 24px;
        flex-direction: column;
        gap: 8px;
    }
    
    .info-item {
        padding: 16px;
    }
    
    .action-note {
        flex-direction: column;
        gap: 12px;
    }
}
</style>
@endsection