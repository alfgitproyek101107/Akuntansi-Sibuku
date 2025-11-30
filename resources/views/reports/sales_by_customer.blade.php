@extends('layouts.app')

@section('title', 'Laporan Penjualan Pelanggan')
@section('page-title', 'Laporan Penjualan Pelanggan')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Pelanggan</span>
                </div>
                <h1 class="page-title">Laporan Penjualan Pelanggan</h1>
                <p class="page-subtitle">Analisis penjualan berdasarkan pelanggan untuk pemahaman yang lebih baik</p>
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
                            
                            <div class="form-group form-actions">
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    </svg>
                                    <span>Terapkan Filter</span>
                                </button>
                                <a href="{{ route('reports.sales-by-customer') }}" class="btn-secondary">
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
                    Menampilkan data penjualan pelanggan dari <strong>{{ $startDate ?: 'awal' }}</strong> hingga <strong>{{ $endDate ?: 'sekarang' }}</strong>
                </div>
            </div>
        @endif

        <!-- Warning Banner -->
        <div class="warning-banner">
            <div class="banner-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
            </div>
            <div class="banner-text">
                <strong>Catatan:</strong> Laporan ini menampilkan transaksi penjualan yang terkait dengan pelanggan. Saat ini, transaksi penjualan tidak langsung terhubung dengan pelanggan tertentu dalam sistem.
            </div>
        </div>

        <!-- Customer Sales Table Section -->
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Penjualan Berdasarkan Pelanggan</h2>
                    <p class="section-subtitle">Daftar pelanggan dan aktivitas penjualan mereka</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="customerSearch" placeholder="Cari pelanggan..." onkeyup="filterCustomers(this.value)">
                    </div>
                </div>
            </div>

            @if($customers->count() > 0)
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="customer-sales-table">
                            <thead>
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <th>Email</th>
                                    <th>Telepon</th>
                                    <th>Total Penjualan</th>
                                    <th>Jumlah Transaksi</th>
                                    <th>Rata-rata Transaksi</th>
                                </tr>
                            </thead>
                            <tbody id="customersTableBody">
                                @foreach($customers as $customer)
                                    <tr class="customer-row">
                                        <td>
                                            <div class="customer-name">
                                                <div class="customer-avatar">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="12" cy="7" r="4"></circle>
                                                    </svg>
                                                </div>
                                                <a href="{{ route('customers.show', $customer['customer']->id) }}" class="customer-link">
                                                    {{ $customer['customer']->name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="customer-email">
                                                {{ $customer['customer']->email ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="customer-phone">
                                                {{ $customer['customer']->phone ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sales-data unavailable">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                                <span>Data tidak tersedia</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sales-data unavailable">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                                <span>Data tidak tersedia</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="sales-data unavailable">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                                </svg>
                                                <span>Data tidak tersedia</span>
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
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada data penjualan pelanggan</h3>
                        <p class="empty-description">Tidak ada transaksi penjualan yang terkait dengan pelanggan dalam periode yang dipilih.</p>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>

<script>
// Filter customers function
function filterCustomers(searchTerm) {
    const rows = document.querySelectorAll('#customersTableBody .customer-row');
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
    background: linear-gradient(135deg, #14B8A6 0%, #0D9488 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(20, 184, 166, 0.3);
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
    background: linear-gradient(135deg, #14B8A6 0%, #0D9488 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(20, 184, 166, 0.4);
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
    background: linear-gradient(135deg, #F0FDFA 0%, #CCFBF1 100%);
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.filter-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #14B8A6 0%, #0D9488 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}

.filter-title {
    font-size: 20px;
    font-weight: 700;
    color: #134E4A;
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
    grid-template-columns: 1fr 1fr auto;
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
    border-color: #14B8A6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
}

.form-control::placeholder {
    color: #9CA3AF;
}

/* ===== FILTER INFO BANNER ===== */
.filter-info-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: linear-gradient(135deg, #E0F2FE 0%, #BAE6FD 100%);
    border: 1px solid #7DD3FC;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 32px;
}

.banner-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(20, 184, 166, 0.1);
    color: #0D9488;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.banner-text {
    font-size: 14px;
    color: #134E4A;
}

.banner-text strong {
    font-weight: 700;
}

/* ===== WARNING BANNER ===== */
.warning-banner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    border: 1px solid #FCD34D;
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 32px;
}

.warning-banner .banner-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #D97706;
}

.warning-banner .banner-text {
    color: #92400E;
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
    border-color: #14B8A6;
    box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
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

.customer-sales-table {
    width: 100%;
    border-collapse: collapse;
}

.customer-sales-table thead {
    background: #F9FAFB;
}

.customer-sales-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.customer-sales-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.customer-sales-table tbody tr:hover {
    background: #F9FAFB;
}

.customer-sales-table td {
    padding: 16px;
    font-size: 14px;
}

/* ===== CUSTOMER CELLS ===== */
.customer-name {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(20, 184, 166, 0.1);
    color: #14B8A6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.customer-link {
    color: #14B8A6;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.customer-link:hover {
    color: #0D9488;
    text-decoration: underline;
}

.customer-email, .customer-phone {
    color: #6B7280;
}

.sales-data {
    display: flex;
    align-items: center;
    gap: 8px;
}

.sales-data.unavailable {
    color: #9CA3AF;
    font-style: italic;
}

.sales-data svg {
    flex-shrink: 0;
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
    background: rgba(20, 184, 166, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #14B8A6;
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
    
    .customer-sales-table {
        font-size: 12px;
    }
    
    .customer-sales-table th,
    .customer-sales-table td {
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
    
    .filter-info-banner,
    .warning-banner {
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