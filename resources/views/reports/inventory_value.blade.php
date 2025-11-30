@extends('layouts.app')

@section('title', 'Laporan Nilai Inventaris')
@section('page-title', 'Laporan Nilai Inventaris')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <span>Nilai Inventaris</span>
                </div>
                <h1 class="page-title">Laporan Nilai Inventaris</h1>
                <p class="page-subtitle">Analisis nilai aset inventaris Anda berdasarkan kategori</p>
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
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Inventory Value -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Nilai Inventaris</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">Rp{{ number_format($totalValue, 0, ',', '.') }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Nilai semua stok</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+15.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="inventoryValueSparkline"></canvas>
                    </div>
                </div>

                <!-- Number of Categories -->
                <div class="metric-card metric-purple">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Jumlah Kategori</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $productsByCategory->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Kategori produk</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+2</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="categoriesCountSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Inventory Value by Category Section -->
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Nilai Inventaris per Kategori</h2>
                    <p class="section-subtitle">Ringkasan nilai dan distribusi aset berdasarkan kategori produk</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="categorySearch" placeholder="Cari kategori..." onkeyup="filterCategories(this.value)">
                    </div>
                </div>
            </div>

            @if($productsByCategory->count() > 0)
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="inventory-value-table">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Total Nilai</th>
                                    <th>Jumlah Produk</th>
                                    <th>Rata-rata Nilai/Produk</th>
                                    <th>Distribusi Nilai</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTableBody">
                                @foreach($productsByCategory as $category)
                                    <tr class="category-row" data-category="{{ $category['category'] }}">
                                        <td>
                                            <div class="category-name">
                                                <div class="category-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                                    </svg>
                                                </div>
                                                <span>{{ $category['category'] }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="total-value">Rp{{ number_format($category['total_value'], 0, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <div class="product-count">{{ $category['products']->count() }}</div>
                                        </td>
                                        <td>
                                            <div class="average-value">Rp{{ number_format($category['total_value'] / $category['products']->count(), 0, ',', '.') }}</div>
                                        </td>
                                        <td>
                                            <div class="value-distribution">
                                                <div class="distribution-bar">
                                                    <div class="distribution-fill" style="width: {{ $totalValue > 0 ? ($category['total_value'] / $totalValue) * 100 : 0 }}%"></div>
                                                </div>
                                                <div class="distribution-text">{{ number_format(($category['total_value'] / $totalValue) * 100, 1) }}%</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detailed Product Breakdown -->
                <div class="breakdown-section">
                    <div class="breakdown-header">
                        <h3 class="breakdown-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            Rincian Produk per Kategori
                        </h3>
                    </div>

                    <div class="breakdown-content">
                        @foreach($productsByCategory as $category)
                            <div class="category-details" id="details-{{ Str::slug($category['category']) }}">
                                <div class="details-header" onclick="toggleDetails('{{ Str::slug($category['category']) }}')">
                                    <div class="details-title">
                                        <h4>{{ $category['category'] }}</h4>
                                        <span class="details-subtitle">Rp{{ number_format($category['total_value'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="details-toggle">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="details-body">
                                    <div class="products-table-responsive">
                                        <table class="products-table">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Stok</th>
                                                    <th>Harga Pokok</th>
                                                    <th>Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($category['products'] as $product)
                                                    <tr>
                                                        <td class="product-name">{{ $product->name }}</td>
                                                        <td class="product-stock">{{ number_format($product->stock_quantity) }}</td>
                                                        <td class="product-cost">Rp{{ number_format($product->cost_price, 0, ',', '.') }}</td>
                                                        <td class="product-value">Rp{{ number_format($product->stock_quantity * $product->cost_price, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <section class="empty-section">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada data inventaris</h3>
                        <p class="empty-description">Tidak ada produk di inventaris Anda untuk menghitung nilainya.</p>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>

<!-- Chart.js for sparklines -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced sparkline configuration
    const sparklineOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        },
        scales: {
            x: { display: false },
            y: { display: false }
        },
        elements: {
            line: {
                borderWidth: 2,
                tension: 0.4
            },
            point: {
                radius: 0,
                hoverRadius: 0
            }
        }
    };

    // Inventory Value Sparkline
    const inventoryValueCtx = document.getElementById('inventoryValueSparkline')?.getContext('2d');
    if (inventoryValueCtx) {
        const gradient = inventoryValueCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
        
        new Chart(inventoryValueCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [40, 50, 45, 60, 55, 70],
                    borderColor: '#10B981',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Categories Count Sparkline
    const categoriesCountCtx = document.getElementById('categoriesCountSparkline')?.getContext('2d');
    if (categoriesCountCtx) {
        new Chart(categoriesCountCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [5, 6, 6, 7, 7, 8],
                    backgroundColor: 'rgba(139, 92, 246, 0.2)',
                    borderColor: '#8B5CF6',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }
});

// Toggle category details
function toggleDetails(categorySlug) {
    const detailsElement = document.getElementById(`details-${categorySlug}`);
    const allDetails = document.querySelectorAll('.category-details');
    
    // Close all other details
    allDetails.forEach(detail => {
        if (detail.id !== `details-${categorySlug}`) {
            detail.classList.remove('active');
        }
    });
    
    // Toggle current details
    detailsElement.classList.toggle('active');
}

// Filter categories function
function filterCategories(searchTerm) {
    const rows = document.querySelectorAll('#categoryTableBody .category-row');
    const detailsSections = document.querySelectorAll('.category-details');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });

    detailsSections.forEach(section => {
        const categoryName = section.querySelector('h4').textContent.toLowerCase();
        section.style.display = categoryName.includes(term) ? '' : 'none';
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
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
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

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.metric-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.metric-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.metric-success {
    --color-start: #10B981;
    --color-end: #059669;
}

.metric-purple {
    --color-start: #8B5CF6;
    --color-end: #7C3AED;
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.metric-success .metric-icon {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
}

.metric-purple .metric-icon {
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
}

.metric-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-content {
    margin-bottom: 24px;
}

.metric-value {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.metric-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.metric-label {
    font-size: 14px;
    color: #6B7280;
}

.metric-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #22C55E;
}

.metric-trend.negative {
    color: #EF4444;
}

.metric-sparkline {
    height: 50px;
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
    border-color: #10B981;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

.table-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-bottom: 32px;
}

.table-responsive {
    overflow-x: auto;
}

.inventory-value-table {
    width: 100%;
    border-collapse: collapse;
}

.inventory-value-table thead {
    background: #F9FAFB;
}

.inventory-value-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.inventory-value-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.inventory-value-table tbody tr:hover {
    background: #F9FAFB;
}

.inventory-value-table td {
    padding: 16px;
    font-size: 14px;
}

.category-name {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
    display: flex;
    align-items: center;
    justify-content: center;
}

.total-value {
    font-weight: 700;
    font-size: 16px;
    color: #10B981;
}

.product-count {
    font-weight: 600;
    color: #111827;
}

.average-value {
    font-weight: 600;
    color: #6B7280;
}

.value-distribution {
    display: flex;
    align-items: center;
    gap: 12px;
}

.distribution-bar {
    flex-grow: 1;
    height: 8px;
    background: #F3F4F6;
    border-radius: 4px;
    overflow: hidden;
}

.distribution-fill {
    height: 100%;
    background: linear-gradient(90deg, #10B981 0%, #059669 100%);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.distribution-text {
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    min-width: 40px;
    text-align: right;
}

/* ===== BREAKDOWN SECTION ===== */
.breakdown-section {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.breakdown-header {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    padding: 20px 24px;
    border-bottom: 1px solid #E5E7EB;
}

.breakdown-title {
    font-size: 18px;
    font-weight: 700;
    color: #065F46;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.breakdown-content {
    padding: 8px;
}

.category-details {
    margin-bottom: 8px;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.category-details:last-child {
    margin-bottom: 0;
}

.details-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: #F9FAFB;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.details-header:hover {
    background: #F3F4F6;
}

.details-title h4 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin: 0 0 4px 0;
}

.details-subtitle {
    font-size: 14px;
    color: #10B981;
    font-weight: 600;
}

.details-toggle {
    color: #6B7280;
    transition: transform 0.3s ease, color 0.2s ease;
}

.category-details.active .details-toggle {
    transform: rotate(180deg);
    color: #10B981;
}

.details-body {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

.category-details.active .details-body {
    max-height: 500px;
    transition: max-height 0.5s ease-in;
}

.products-table-responsive {
    overflow-x: auto;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table thead {
    background: #F9FAFB;
}

.products-table th {
    padding: 12px 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.products-table td {
    padding: 12px 16px;
    font-size: 13px;
    border-top: 1px solid #F3F4F6;
}

.product-name {
    font-weight: 600;
    color: #374151;
}

.product-stock {
    color: #6B7280;
}

.product-cost {
    color: #6B7280;
}

.product-value {
    font-weight: 600;
    color: #10B981;
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
    background: rgba(16, 185, 129, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #10B981;
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
    
    .metrics-grid {
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
    
    .inventory-value-table {
        font-size: 12px;
    }
    
    .inventory-value-table th,
    .inventory-value-table td {
        padding: 12px 8px;
    }
    
    .value-distribution {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .distribution-bar {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .metric-value {
        font-size: 28px;
    }
    
    .details-header {
        padding: 12px 16px;
    }
    
    .details-title h4 {
        font-size: 14px;
    }
    
    .details-subtitle {
        font-size: 12px;
    }
    
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection