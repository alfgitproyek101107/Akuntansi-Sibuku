@extends('layouts.app')

@section('title', 'Laporan Pergerakan Stok')
@section('page-title', 'Laporan Pergerakan Stok')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="17 8 21 12 17 16"></polyline>
                        <polyline points="7 8 3 12 7 16"></polyline>
                        <line x1="12" y1="2" x2="12" y2="22"></line>
                    </svg>
                    <span>Pergerakan</span>
                </div>
                <h1 class="page-title">Laporan Pergerakan Stok</h1>
                <p class="page-subtitle">Pantau alur masuk dan keluarnya stok produk</p>
            </div>
            <div class="header-right">
                <a href="{{ route('reports.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Laporan</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Filter Section -->
        <section class="filter-section">
            <div class="filter-container">
                <div class="filter-header">
                    <div class="filter-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                    </div>
                    <h2 class="filter-title">Opsi Filter</h2>
                </div>

                <div class="filter-body">
                    <form method="GET" class="filter-form">
                        <div class="filter-grid">
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Tanggal Mulai
                                </label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Tanggal Selesai
                                </label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="17 8 21 12 17 16"></polyline>
                                        <polyline points="7 8 3 12 7 16"></polyline>
                                        <line x1="12" y1="2" x2="12" y2="22"></line>
                                    </svg>
                                    Tipe Pergerakan
                                </label>
                                <select class="form-control" id="type" name="type">
                                    <option value="">Semua Tipe</option>
                                    <option value="in" {{ $type == 'in' ? 'selected' : '' }}>Stok Masuk</option>
                                    <option value="out" {{ $type == 'out' ? 'selected' : '' }}>Stok Keluar</option>
                                </select>
                            </div>
                            
                            <div class="form-group form-actions">
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    </svg>
                                    <span>Terapkan Filter</span>
                                </button>
                                <a href="{{ route('reports.stock-movements') }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    <span>Hapus</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        @if($startDate || $endDate || $type)
            <!-- Filter Info Banner -->
            <div class="filter-info-banner">
                <div class="banner-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                </div>
                <div class="banner-text">
                    Menampilkan
                    @if($type)
                        pergerakan <strong>{{ $type === 'in' ? 'stok masuk' : 'stok keluar' }}</strong>
                    @else
                        <strong>semua pergerakan</strong>
                    @endif
                    dari <strong>{{ $startDate ?: 'awal' }}</strong> hingga <strong>{{ $endDate ?: 'sekarang' }}</strong>
                </div>
            </div>
        @endif

        <!-- Stock Movements Table Section -->
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Pergerakan Stok</h2>
                    <p class="section-subtitle">Riwayat semua pergerakan stok produk</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="movementSearch" placeholder="Cari pergerakan..." onkeyup="filterMovements(this.value)">
                    </div>
                </div>
            </div>

            @if($movements->count() > 0)
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="stock-movements-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Referensi</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody id="movementsTableBody">
                                @foreach($movements as $movement)
                                    <tr class="movement-row">
                                        <td>
                                            <div class="movement-date">
                                                <div class="date-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                </div>
                                                <div class="date-details">
                                                    <div class="date-value">{{ $movement->date->format('d M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="movement-product">
                                                <a href="{{ route('products.show', $movement->product->id) }}" class="product-link">
                                                    {{ $movement->product->name }}
                                                </a>
                                                @if($movement->product->sku)
                                                    <div class="product-sku">{{ $movement->product->sku }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="movement-type">
                                                @if($movement->type === 'in')
                                                    <div class="type-badge type-in">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="17 8 21 12 17 16"></polyline>
                                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                                            <line x1="9" y1="12" x2="3" y2="12"></line>
                                                        </svg>
                                                        <span>Stok Masuk</span>
                                                    </div>
                                                @else
                                                    <div class="type-badge type-out">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="7 8 3 12 7 16"></polyline>
                                                            <line x1="3" y1="12" x2="15" y2="12"></line>
                                                            <line x1="15" y1="12" x2="21" y2="12"></line>
                                                        </svg>
                                                        <span>Stok Keluar</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="movement-quantity {{ $movement->type === 'in' ? 'quantity-in' : 'quantity-out' }}">
                                                {{ $movement->type === 'in' ? '+' : '-' }}{{ number_format($movement->quantity) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="movement-reference">{{ $movement->reference ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="movement-notes">{{ $movement->notes ?? 'N/A' }}</div>
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
                                <polyline points="17 8 21 12 17 16"></polyline>
                                <polyline points="7 8 3 12 7 16"></polyline>
                                <line x1="12" y1="2" x2="12" y2="22"></line>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada pergerakan stok</h3>
                        <p class="empty-description">Tidak ada pergerakan stok yang ditemukan dalam periode yang dipilih.</p>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>

<script>
// Filter movements function
function filterMovements(searchTerm) {
    const rows = document.querySelectorAll('#movementsTableBody .movement-row');
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
    background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.3);
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
    background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);
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

/* ===== FILTER SECTION ===== */
.filter-section {
    margin-bottom: 32px;
}

.filter-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.filter-header {
    background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.filter-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.filter-title {
    font-size: 20px;
    font-weight: 700;
    color: #312E81;
    margin: 0;
}

.filter-body {
    padding: 32px;
}

.filter-form {
    margin: 0;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto auto;
    gap: 24px;
    align-items: end;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.form-actions {
    flex-direction: row;
    gap: 12px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.form-control:focus {
    outline: none;
    border-color: #6366F1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.form-control::placeholder {
    color: #9CA3AF;
}

/* ===== FILTER INFO BANNER ===== */
.filter-info-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: linear-gradient(135deg, #E0E7FF 0%, #C7D2FE 100%);
    border: 1px solid #A5B4FC;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 32px;
}

.banner-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(99, 102, 241, 0.1);
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.banner-text {
    font-size: 14px;
    color: #312E81;
}

.banner-text strong {
    font-weight: 700;
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
    border-color: #6366F1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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

.stock-movements-table {
    width: 100%;
    border-collapse: collapse;
}

.stock-movements-table thead {
    background: #F9FAFB;
}

.stock-movements-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stock-movements-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.stock-movements-table tbody tr:hover {
    background: #F9FAFB;
}

.stock-movements-table td {
    padding: 16px;
    font-size: 14px;
}

/* ===== MOVEMENT CELLS ===== */
.movement-date {
    display: flex;
    align-items: center;
    gap: 12px;
}

.date-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(99, 102, 241, 0.1);
    color: #6366F1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.date-value {
    font-weight: 600;
    color: #111827;
}

.movement-product {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.product-link {
    color: #6366F1;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.product-link:hover {
    color: #4F46E5;
    text-decoration: underline;
}

.product-sku {
    font-family: 'Courier New', monospace;
    background: #F9FAFB;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    color: #6B7280;
}

.movement-type {
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

.type-in {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.type-out {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.movement-quantity {
    font-weight: 700;
    font-size: 16px;
}

.quantity-in {
    color: #22C55E;
}

.quantity-out {
    color: #EF4444;
}

.movement-reference, .movement-notes {
    color: #6B7280;
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
    background: rgba(99, 102, 241, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6366F1;
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
    
    .filter-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-group.form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
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
    
    .stock-movements-table {
        font-size: 12px;
    }
    
    .stock-movements-table th,
    .stock-movements-table td {
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
    
    .filter-body {
        padding: 24px 16px;
    }
    
    .filter-info-banner {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection