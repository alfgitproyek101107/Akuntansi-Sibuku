@extends('layouts.app')

@section('title', 'Layanan')
@section('page-title', 'Layanan')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="9" r="2"></circle>
                        <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                    </svg>
                    <span>Layanan</span>
                </div>
                <h1 class="page-title">Manajemen Layanan</h1>
                <p class="page-subtitle">Kelola layanan dan jasa yang Anda tawarkan</p>
            </div>
            <div class="header-right">
                <a href="{{ route('services.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Layanan Baru</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Services -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Layanan</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $services->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua layanan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+12.1%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalServicesSparkline"></canvas>
                    </div>
                </div>

                <!-- Average Price -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Rata-rata Harga</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">Rp{{ number_format($services->avg('price'), 0, ',', '.') }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Harga rata-rata</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+8.7%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="averagePriceSparkline"></canvas>
                    </div>
                </div>

                <!-- Highest Price -->
                <div class="metric-card metric-warning">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Harga Tertinggi</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">Rp{{ number_format($services->max('price'), 0, ',', '.') }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Layanan termahal</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+15.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="highestPriceSparkline"></canvas>
                    </div>
                </div>

                <!-- Categories -->
                <div class="metric-card metric-info">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Kategori</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $services->pluck('product_category_id')->unique()->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Kategori unik</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+5.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="categoriesSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Data Table -->
        <section class="table-section">
            <div class="table-container">
                <div class="table-header">
                    <h2 class="table-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="9" r="2"></circle>
                            <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                        </svg>
                        Daftar Layanan
                    </h2>
                    <div class="search-filter-section">
                        <div class="search-input">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <input type="text" id="searchInput" placeholder="Cari layanan...">
                        </div>
                        <div class="filter-dropdown">
                            <button class="filter-btn" id="filterBtn">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3z"></polygon>
                                </svg>
                                Filter
                            </button>
                            <div class="filter-options" id="filterOptions">
                                <button class="filter-option active" data-filter="all">Semua</button>
                                <button class="filter-option" data-filter="high-price">Harga Tinggi</button>
                                <button class="filter-option" data-filter="low-price">Harga Rendah</button>
                            </div>
                        </div>
                    </div>
                </div>

                @if($services->count() > 0)
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Nama Layanan</th>
                                    <th>Kategori</th>
                                    <th>Cabang</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                    <tr data-price="{{ $service->price }}">
                                        <td>
                                            <div class="service-info">
                                                <div class="service-icon">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                        <circle cx="9" cy="9" r="2"></circle>
                                                        <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                                    </svg>
                                                </div>
                                                <div class="service-details">
                                                    <div class="service-name">{{ $service->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="category-badge">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                                </svg>
                                                {{ $service->productCategory->name ?? 'Tidak ada kategori' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="branch-badge">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"></path>
                                                    <path d="M8 5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2H8V5z"></path>
                                                </svg>
                                                {{ $service->branch->name ?? 'Cabang Tidak Ditemukan' }}
                                                @if($service->branch && $service->branch->is_demo)
                                                    <span class="demo-badge">(Demo)</span>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <span class="price-value">Rp{{ number_format($service->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="service-description">{{ $service->description ? Str::limit($service->description, 50) : 'Tidak ada deskripsi' }}</span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('services.show', $service) }}" class="action-btn view-btn" title="Lihat Detail">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('services.edit', $service) }}" class="action-btn edit-btn" title="Edit">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('services.destroy', $service) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete-btn" title="Hapus">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada layanan ditemukan</h3>
                        <p class="empty-description">Mulai dengan menambahkan layanan pertama Anda ke inventori</p>
                        <div class="empty-action">
                            <a href="{{ route('services.create') }}" class="btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Tambah Layanan Pertama</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
</div>

<!-- Chart.js for sparklines -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality
    const filterBtn = document.getElementById('filterBtn');
    const filterOptions = document.getElementById('filterOptions');

    if (filterBtn && filterOptions) {
        filterBtn.addEventListener('click', function() {
            filterOptions.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!filterBtn.contains(e.target) && !filterOptions.contains(e.target)) {
                filterOptions.classList.remove('active');
            }
        });
    }

    // Filter options
    const filterOptionBtns = document.querySelectorAll('.filter-option');
    filterOptionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterOptionBtns.forEach(b => b.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Filter the services
            const filterType = this.getAttribute('data-filter');
            filterServices(filterType);

            // Close dropdown
            filterOptions.classList.remove('active');
        });
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.data-table tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Filter services function
    function filterServices(filterType) {
        const rows = document.querySelectorAll('.data-table tbody tr');

        rows.forEach(row => {
            if (filterType === 'all') {
                row.style.display = '';
            } else if (filterType === 'high-price') {
                const price = parseInt(row.dataset.price);
                const avgPrice = {{ $services->avg('price') }};
                row.style.display = price > avgPrice ? '' : 'none';
            } else if (filterType === 'low-price') {
                const price = parseInt(row.dataset.price);
                const avgPrice = {{ $services->avg('price') }};
                row.style.display = price <= avgPrice ? '' : 'none';
            }
        });
    }

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

    // Total Services Sparkline
    const totalServicesCtx = document.getElementById('totalServicesSparkline')?.getContext('2d');
    if (totalServicesCtx) {
        new Chart(totalServicesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [20, 25, 22, 30, 28, {{ $services->count() }}],
                    backgroundColor: 'rgba(79, 70, 229, 0.2)',
                    borderColor: '#4F46E5',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Average Price Sparkline
    const averagePriceCtx = document.getElementById('averagePriceSparkline')?.getContext('2d');
    if (averagePriceCtx) {
        new Chart(averagePriceCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [200000, 250000, 220000, 300000, 280000, {{ $services->avg('price') }}],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Highest Price Sparkline
    const highestPriceCtx = document.getElementById('highestPriceSparkline')?.getContext('2d');
    if (highestPriceCtx) {
        new Chart(highestPriceCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [300000, 350000, 320000, 400000, 380000, {{ $services->max('price') }}],
                    borderColor: '#F59E0B',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Categories Sparkline
    const categoriesCtx = document.getElementById('categoriesSparkline')?.getContext('2d');
    if (categoriesCtx) {
        new Chart(categoriesCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [3, 5, 4, 6, 5, {{ $services->pluck('product_category_id')->unique()->count() }}],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }
});
</script>

<style>
/* ===== ENHANCED BASE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
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
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
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

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

.metric-primary {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.metric-success {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.metric-warning {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.metric-info {
    --color-start: #3B82F6;
    --color-end: #2563EB;
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

.metric-primary .metric-icon {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
}

.metric-success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-warning .metric-icon {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.metric-info .metric-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
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

.table-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-title {
    font-size: 20px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.search-filter-section {
    display: flex;
    gap: 16px;
}

.search-input {
    position: relative;
    display: flex;
    align-items: center;
    flex-grow: 1;
    max-width: 400px;
}

.search-input svg {
    position: absolute;
    left: 16px;
    color: #9CA3AF;
}

.search-input input {
    padding: 12px 16px 12px 44px;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    width: 100%;
    transition: all 0.2s ease;
}

.search-input input:focus {
    outline: none;
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-dropdown {
    position: relative;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 12px 16px;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn:hover {
    border-color: #3B82F6;
    color: #3B82F6;
}

.filter-options {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
    z-index: 10;
    min-width: 150px;
}

.filter-options.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.filter-option {
    display: block;
    width: 100%;
    padding: 10px 16px;
    background: transparent;
    border: none;
    text-align: left;
    font-size: 14px;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-option:hover {
    background: #F3F4F6;
}

.filter-option.active {
    background: #F3F4F6;
    color: #3B82F6;
    font-weight: 600;
}

/* ===== DATA TABLE ===== */
.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead th {
    background: #F9FAFB;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    border-bottom: 1px solid #E5E7EB;
}

.data-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: all 0.2s ease;
}

.data-table tbody tr:hover {
    background: #F9FAFB;
}

.data-table tbody td {
    padding: 16px;
    font-size: 14px;
    color: #374151;
}

.service-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.service-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-name {
    font-weight: 600;
    color: #111827;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 500;
    color: #374151;
    background: #F3F4F6;
    padding: 4px 8px;
    border-radius: 6px;
}

.branch-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 500;
    color: #374151;
    background: #F3F4F6;
    padding: 4px 8px;
    border-radius: 6px;
}

.price-value {
    font-weight: 600;
    color: #111827;
}

.service-description {
    font-size: 13px;
    color: #6B7280;
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
    color: #374151;
}

.view-btn:hover {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.edit-btn:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.delete-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 60px 40px;
    color: #6B7280;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: rgba(59, 130, 246, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #3B82F6;
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
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }

    .metrics-grid {
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }

    .search-filter-section {
        flex-direction: column;
        gap: 12px;
    }

    .search-input {
        max-width: 100%;
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

    .metrics-grid {
        grid-template-columns: 1fr;
    }

    .table-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .data-table thead th,
    .data-table tbody td {
        padding: 12px 8px;
        font-size: 12px;
    }

    .service-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 4px;
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

    .table-header {
        padding: 16px;
    }

    .data-table thead th,
    .data-table tbody td {
        padding: 8px 4px;
        font-size: 11px;
    }

    .action-buttons {
        flex-direction: row;
        gap: 4px;
    }
}
</style>
@endsection