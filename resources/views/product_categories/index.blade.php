@extends('layouts.app')

@section('title', 'Product Categories')

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
                    <span>Kategori Produk</span>
                </div>
                <h1 class="page-title">Kategori Produk</h1>
                <p class="page-subtitle">Kelola kategori produk dan layanan Anda dengan mudah</p>
            </div>
            <div class="header-right">
                <a href="{{ route('product-categories.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Kategori Baru</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Categories -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Kategori</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $productCategories->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua kategori</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+5.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalCategoriesSparkline"></canvas>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Produk</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $productCategories->sum(function($cat) { return $cat->products()->count(); }) }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua produk</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+12.1%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalProductsSparkline"></canvas>
                    </div>
                </div>

                <!-- Total Services -->
                <div class="metric-card metric-warning">
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
                        <div class="metric-value">{{ $productCategories->sum(function($cat) { return $cat->services()->count(); }) }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua layanan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+8.7%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalServicesSparkline"></canvas>
                    </div>
                </div>

                <!-- Average Products/Category -->
                <div class="metric-card metric-info">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Rata-rata Produk/Kategori</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $productCategories->count() > 0 ? round($productCategories->sum(function($cat) { return $cat->products()->count(); }) / $productCategories->count(), 1) : 0 }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Rata-rata</span>
                            <div class="metric-trend negative">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                </svg>
                                <span>-3.2%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="averageProductsSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <div class="content-grid">
            <!-- Categories List Section -->
            <div class="categories-section">
                <div class="categories-container">
                    <div class="categories-header">
                        <h2 class="categories-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                            Daftar Kategori Produk
                        </h2>
                        <p class="categories-subtitle">Kelola semua kategori produk dan layanan Anda</p>
                    </div>

                    <div class="categories-body">
                        <!-- Search and Filter Section -->
                        <div class="search-filter-section">
                            <div class="search-input">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                                <input type="text" id="categorySearch" placeholder="Cari kategori..." onkeyup="filterCategories(this.value)">
                            </div>
                            <div class="filter-dropdown">
                                <button class="filter-btn" id="filterBtn">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                    </svg>
                                    Filter
                                </button>
                                <div class="filter-options" id="filterOptions">
                                    <button class="filter-option active" data-filter="all">Semua</button>
                                    <button class="filter-option" data-filter="with-products">Dengan Produk</button>
                                    <button class="filter-option" data-filter="with-services">Dengan Layanan</button>
                                    <button class="filter-option" data-filter="empty">Kosong</button>
                                </div>
                            </div>
                        </div>

                        <!-- Categories Grid -->
                        @if($productCategories->count() > 0)
                            <div class="categories-grid" id="categoriesGrid">
                                @foreach($productCategories as $category)
                                    <div class="category-card" 
                                         data-products="{{ $category->products()->count() > 0 ? 'yes' : 'no' }}" 
                                         data-services="{{ $category->services()->count() > 0 ? 'yes' : 'no' }}">
                                        <div class="category-header">
                                            <div class="category-icon">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                                </svg>
                                            </div>
                                            <div class="category-title">{{ $category->name }}</div>
                                            <div class="category-actions">
                                                <a href="{{ route('product-categories.show', $category) }}" class="action-btn-small view-btn" title="Lihat Detail">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('product-categories.edit', $category) }}" class="action-btn-small edit-btn" title="Edit">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('product-categories.destroy', $category) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn-small delete-btn" title="Hapus">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="category-body">
                                            <div class="category-description">
                                                {{ $category->description ?? 'Tidak ada deskripsi' }}
                                            </div>
                                            <div class="category-stats">
                                                <div class="stat-item">
                                                    <div class="stat-icon products-icon">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                                        </svg>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">{{ $category->products()->count() }}</div>
                                                        <div class="stat-label">Produk</div>
                                                    </div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-icon services-icon">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="9" cy="9" r="2"></circle>
                                                            <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="stat-content">
                                                        <div class="stat-value">{{ $category->services()->count() }}</div>
                                                        <div class="stat-label">Layanan</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                </div>
                                <h3 class="empty-title">Tidak ada kategori produk ditemukan</h3>
                                <p class="empty-description">Mulai dengan membuat kategori produk pertama Anda</p>
                                <div class="empty-action">
                                    <a href="{{ route('product-categories.create') }}" class="btn-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Tambah Kategori Pertama</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="sidebar-section">
                <!-- Quick Actions -->
                <div class="actions-container">
                    <div class="actions-header">
                        <h3 class="actions-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                            </svg>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="actions-body">
                        <a href="{{ route('product-categories.create') }}" class="action-card">
                            <div class="action-icon add-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Kategori</h4>
                                <p class="action-description">Buat kategori produk baru</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('products.create') }}" class="action-card">
                            <div class="action-icon products-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Produk</h4>
                                <p class="action-description">Buat produk baru</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('services.create') }}" class="action-card">
                            <div class="action-icon services-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Layanan</h4>
                                <p class="action-description">Buat layanan baru</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('reports.index') }}" class="action-card">
                            <div class="action-icon reports-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Lihat Laporan</h4>
                                <p class="action-description">Analisis produk dan layanan</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Categories Statistics -->
                <div class="stats-container">
                    <div class="stats-header">
                        <h3 class="stats-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            Statistik Kategori
                        </h3>
                    </div>
                    <div class="stats-body">
                        <div class="stat-item">
                            <div class="stat-value">{{ $productCategories->count() }}</div>
                            <div class="stat-label">Total Kategori</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $productCategories->sum(function($cat) { return $cat->products()->count(); }) }}</div>
                            <div class="stat-label">Total Produk</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $productCategories->sum(function($cat) { return $cat->services()->count(); }) }}</div>
                            <div class="stat-label">Total Layanan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $productCategories->count() > 0 ? round($productCategories->sum(function($cat) { return $cat->products()->count(); }) / $productCategories->count(), 1) : 0 }}</div>
                            <div class="stat-label">Rata-rata Produk/Kategori</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    // Total Categories Sparkline
    const totalCategoriesCtx = document.getElementById('totalCategoriesSparkline')?.getContext('2d');
    if (totalCategoriesCtx) {
        const gradient = totalCategoriesCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(totalCategoriesCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [5, 8, 7, 10, 9, {{ $productCategories->count() }}],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Total Products Sparkline
    const totalProductsCtx = document.getElementById('totalProductsSparkline')?.getContext('2d');
    if (totalProductsCtx) {
        new Chart(totalProductsCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [20, 25, 22, 30, 28, {{ $productCategories->sum(function($cat) { return $cat->products()->count(); }) }}],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Total Services Sparkline
    const totalServicesCtx = document.getElementById('totalServicesSparkline')?.getContext('2d');
    if (totalServicesCtx) {
        new Chart(totalServicesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [10, 12, 11, 15, 14, {{ $productCategories->sum(function($cat) { return $cat->services()->count(); }) }}],
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Average Products Sparkline
    const averageProductsCtx = document.getElementById('averageProductsSparkline')?.getContext('2d');
    if (averageProductsCtx) {
        new Chart(averageProductsCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [3, 5, 4, 6, 5, {{ $productCategories->count() > 0 ? round($productCategories->sum(function($cat) { return $cat->products()->count(); }) / $productCategories->count(), 1) : 0 }}],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

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
            
            // Filter the categories
            const filterType = this.getAttribute('data-filter');
            filterCategoriesByType(filterType);
            
            // Close dropdown
            filterOptions.classList.remove('active');
        });
    });
});

// Filter categories function
function filterCategories(searchTerm) {
    const cards = document.querySelectorAll('.category-card');
    const term = searchTerm.toLowerCase();

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(term) ? '' : 'none';
    });
}

// Filter categories by type function
function filterCategoriesByType(type) {
    const cards = document.querySelectorAll('.category-card');
    
    cards.forEach(card => {
        if (type === 'all') {
            card.style.display = '';
        } else if (type === 'with-products') {
            const hasProducts = card.getAttribute('data-products') === 'yes';
            card.style.display = hasProducts ? '' : 'none';
        } else if (type === 'with-services') {
            const hasServices = card.getAttribute('data-services') === 'yes';
            card.style.display = hasServices ? '' : 'none';
        } else if (type === 'empty') {
            const hasProducts = card.getAttribute('data-products') === 'yes';
            const hasServices = card.getAttribute('data-services') === 'yes';
            card.style.display = (!hasProducts && !hasServices) ? '' : 'none';
        }
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

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
}

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
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

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
}

/* ===== CATEGORIES SECTION ===== */
.categories-section {
    margin-bottom: 48px;
}

.categories-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.categories-header {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    padding: 32px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.categories-title {
    font-size: 24px;
    font-weight: 700;
    color: #1E40AF;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.categories-subtitle {
    font-size: 16px;
    color: #3B82F6;
    margin: 0;
}

.categories-body {
    padding: 32px;
}

.search-filter-section {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
}

.search-input {
    position: relative;
    display: flex;
    align-items: center;
    flex-grow: 1;
}

.search-input svg {
    position: absolute;
    left: 16px;
    color: #9CA3AF;
}

.search-input input {
    padding: 12px 16px 12px 40px;
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

/* ===== CATEGORIES GRID ===== */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

.category-card {
    background: #FFFFFF;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: all 0.3s ease;
}

.category-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    border-bottom: 1px solid #F3F4F6;
}

.category-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    flex-grow: 1;
}

.category-actions {
    display: flex;
    gap: 8px;
}

.action-btn-small {
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

.action-btn-small:hover {
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

.category-body {
    padding: 16px;
}

.category-description {
    font-size: 14px;
    color: #6B7280;
    margin-bottom: 16px;
    line-height: 1.5;
}

.category-stats {
    display: flex;
    gap: 16px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-grow: 1;
}

.stat-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.products-icon {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.services-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    line-height: 1.2;
}

.stat-label {
    font-size: 12px;
    color: #6B7280;
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

/* ===== SIDEBAR SECTION ===== */
.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ===== ACTIONS CONTAINER ===== */
.actions-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.actions-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.actions-title {
    font-size: 20px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.actions-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.action-card {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.action-card:nth-child(1) {
    --color-start: #3B82F6;
    --color-end: #2563EB;
}

.action-card:nth-child(2) {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.action-card:nth-child(3) {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.action-card:nth-child(4) {
    --color-start: #8B5CF6;
    --color-end: #7C3AED;
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.add-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.products-icon {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.services-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.reports-icon {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
}

.action-content {
    flex-grow: 1;
}

.action-title {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.action-description {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.action-arrow {
    color: #9CA3AF;
    transition: all 0.2s ease;
}

.action-card:hover .action-arrow {
    color: #3B82F6;
    transform: translateX(4px);
}

/* ===== STATS CONTAINER ===== */
.stats-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.stats-header {
    background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.stats-title {
    font-size: 20px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.stats-body {
    padding: 24px;
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.stat-item {
    text-align: center;
    padding: 16px;
    background: #F9FAFB;
    border-radius: 12px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #3B82F6;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: #6B7280;
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
    
    .categories-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
    
    .categories-body {
        padding: 20px;
    }
    
    .search-filter-section {
        flex-direction: column;
        gap: 12px;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .category-stats {
        flex-direction: column;
        gap: 12px;
    }
    
    .action-card {
        padding: 16px;
    }
    
    .stats-body {
        padding: 20px;
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
    
    .categories-body {
        padding: 16px;
    }
    
    .categories-header {
        padding: 24px;
    }
    
    .action-card {
        padding: 12px;
    }
    
    .stats-body {
        padding: 16px;
    }
}
</style>
@endsection