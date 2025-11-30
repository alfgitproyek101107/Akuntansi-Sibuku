@extends('layouts.app')

@section('title', 'Laporan Produk Teratas')
@section('page-title', 'Laporan Produk Teratas')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                    <span>Produk</span>
                </div>
                <h1 class="page-title">Laporan Produk Teratas</h1>
                <p class="page-subtitle">Identifikasi produk dengan performa penjualan terbaik</p>
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
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                    Top N
                                </label>
                                <input type="number" class="form-control" id="limit" name="limit" value="{{ $limit }}" min="1" max="100">
                            </div>
                            
                            <div class="form-group form-actions">
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    </svg>
                                    <span>Terapkan Filter</span>
                                </button>
                                <a href="{{ route('reports.top-products') }}" class="btn-secondary">
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

        @if($startDate || $endDate)
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
                    Menampilkan produk teratas dari <strong>{{ $startDate ?: 'awal' }}</strong> hingga <strong>{{ $endDate ?: 'sekarang' }}</strong>
                </div>
            </div>
        @endif

        <!-- Top Products Table Section -->
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Top {{ $limit }} Produk Berdasarkan Penjualan</h2>
                    <p class="section-subtitle">Daftar produk dengan penjualan tertinggi dalam periode yang dipilih</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="productSearch" placeholder="Cari produk..." onkeyup="filterProducts(this.value)">
                    </div>
                </div>
            </div>

            @if($topProducts->count() > 0)
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="top-products-table">
                            <thead>
                                <tr>
                                    <th>Peringkat</th>
                                    <th>Nama Produk</th>
                                    <th>SKU</th>
                                    <th>Kategori</th>
                                    <th>Total Penjualan</th>
                                    <th>Jumlah Penjualan</th>
                                    <th>Rata-rata Penjualan</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                @foreach($topProducts as $index => $item)
                                    <tr class="product-row">
                                        <td>
                                            <div class="rank-badge rank-{{ $index < 3 ? 'top' : 'standard' }}">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-name">
                                                <a href="{{ route('products.show', $item['product']->id) }}" class="product-link">
                                                    {{ $item['product']->name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-sku">{{ $item['product']->sku ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="product-category">
                                                <div class="category-icon">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                                    </svg>
                                                </div>
                                                <span>{{ $item['product']->productCategory->name ?? 'Tidak Berkategori' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sales-value">
                                                Rp{{ number_format($item['total_sales'], 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sales-count">{{ number_format($item['sales_count']) }}</div>
                                        </td>
                                        <td>
                                            <div class="sales-average">
                                                Rp{{ number_format($item['total_sales'] / $item['sales_count'], 0, ',', '.') }}
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
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada data penjualan</h3>
                        <p class="empty-description">Tidak ada penjualan produk yang ditemukan dalam periode yang dipilih.</p>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>

<script>
// Filter products function
function filterProducts(searchTerm) {
    const rows = document.querySelectorAll('#productsTableBody .product-row');
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
    background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(244, 63, 94, 0.3);
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
    background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(244, 63, 94, 0.4);
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
    background: linear-gradient(135deg, #FDF2F8 0%, #FCE7F3 100%);
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.filter-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);
}

.filter-title {
    font-size: 20px;
    font-weight: 700;
    color: #9F1239;
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
    grid-template-columns: 1fr 1fr 1fr auto;
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
    border-color: #F43F5E;
    box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.1);
}

.form-control::placeholder {
    color: #9CA3AF;
}

/* ===== FILTER INFO BANNER ===== */
.filter-info-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: linear-gradient(135deg, #FCE7F3 0%, #FBCFE8 100%);
    border: 1px solid #F9A8D4;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 32px;
}

.banner-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(244, 63, 94, 0.1);
    color: #E11D48;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.banner-text {
    font-size: 14px;
    color: #9F1239;
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
    border-color: #F43F5E;
    box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.1);
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

.top-products-table {
    width: 100%;
    border-collapse: collapse;
}

.top-products-table thead {
    background: #F9FAFB;
}

.top-products-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.top-products-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.top-products-table tbody tr:hover {
    background: #F9FAFB;
}

.top-products-table td {
    padding: 16px;
    font-size: 14px;
}

/* ===== RANK BADGE ===== */
.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 14px;
    font-weight: 700;
    color: white;
}

.rank-top {
    background: linear-gradient(135deg, #F43F5E 0%, #E11D48 100%);
    box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);
}

.rank-standard {
    background: #F3F4F6;
    color: #6B7280;
}

/* ===== PRODUCT CELLS ===== */
.product-name {
    font-weight: 600;
}

.product-link {
    color: #F43F5E;
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-link:hover {
    color: #E11D48;
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

.product-category {
    display: flex;
    align-items: center;
    gap: 8px;
}

.category-icon {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: rgba(244, 63, 94, 0.1);
    color: #F43F5E;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sales-value {
    font-weight: 700;
    font-size: 16px;
    color: #22C55E;
}

.sales-count {
    font-weight: 600;
    color: #111827;
    text-align: center;
}

.sales-average {
    font-weight: 600;
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
    background: rgba(244, 63, 94, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F43F5E;
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
    
    .top-products-table {
        font-size: 12px;
    }
    
    .top-products-table th,
    .top-products-table td {
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