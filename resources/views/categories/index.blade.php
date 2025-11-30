@extends('layouts.app')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Kategori')

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
                    <span>Kategori</span>
                </div>
                <h1 class="page-title">Manajemen Kategori</h1>
                <p class="page-subtitle">Kelola kategori pemasukan dan pengeluaran Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('categories.create') }}" class="btn-primary">
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
                                <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Kategori</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $categories->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua tipe kategori</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+15.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalCategoriesSparkline"></canvas>
                    </div>
                </div>

                <!-- Income Categories -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Kategori Pemasukan</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $categories->where('type', 'income')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Untuk mengelompokkan pemasukan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+8.7%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="incomeCategoriesSparkline"></canvas>
                    </div>
                </div>

                <!-- Expense Categories -->
                <div class="metric-card metric-danger">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Kategori Pengeluaran</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $categories->where('type', 'expense')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Untuk mengelompokkan pengeluaran</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+12.1%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="expenseCategoriesSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Table Section -->
        @if($categories->count() > 0)
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Daftar Kategori</h2>
                    <p class="section-subtitle">Kelola semua kategori transaksi Anda</p>
                </div>
                <div class="section-actions">
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
                            <button class="filter-option" data-filter="income">Pemasukan</button>
                            <button class="filter-option" data-filter="expense">Pengeluaran</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="categories-table">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Tipe</th>
                                <th>Jumlah Transaksi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="categoriesTableBody">
                            @foreach($categories as $category)
                                <tr class="category-row" data-type="{{ $category->type }}">
                                    <td>
                                        <div class="category-info">
                                            <div class="category-icon {{ $category->type }}">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                                </svg>
                                            </div>
                                            <div class="category-details">
                                                <div class="category-name">{{ $category->name }}</div>
                                                <div class="category-description">{{ $category->type === 'income' ? 'Kategori pemasukan' : 'Kategori pengeluaran' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="category-type-badge {{ $category->type }}">
                                            {{ $category->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="category-transactions">
                                            {{ $category->transactions_count ?? 0 }} transaksi
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('categories.show', $category) }}" class="action-btn view-btn" title="Lihat Detail">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}" class="action-btn edit-btn" title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" class="inline">
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
            </div>
        </section>
        @else
        <!-- Empty State -->
        <section class="empty-section">
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                    </svg>
                </div>
                <h3 class="empty-title">Belum ada kategori</h3>
                <p class="empty-description">Anda belum menambahkan kategori apapun. Kategori membantu mengorganisir transaksi Anda.</p>
                <div class="empty-action">
                    <a href="{{ route('categories.create') }}" class="btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Buat Kategori Pertama</span>
                    </a>
                </div>
            </div>
        </section>
        @endif

        <!-- Quick Actions -->
        <section class="actions-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Aksi Cepat</h2>
                    <p class="section-subtitle">Tugas umum dan pintasan</p>
                </div>
            </div>
            <div class="actions-grid">
                <a href="{{ route('categories.create') }}" class="action-card">
                    <div class="action-icon primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tambah Kategori</h3>
                        <p class="action-description">Buat kategori baru</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('incomes.create') }}" class="action-card">
                    <div class="action-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tambah Pemasukan</h3>
                        <p class="action-description">Catat transaksi pemasukan</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('expenses.create') }}" class="action-card">
                    <div class="action-icon danger">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tambah Pengeluaran</h3>
                        <p class="action-description">Catat transaksi pengeluaran</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="action-card">
                    <div class="action-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 19v-6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2zm0 0V9a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v10m-6 0a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2m0 0V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2z"></path>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Lihat Laporan</h3>
                        <p class="action-description">Analisis keuangan Anda</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
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
                    data: [8, 10, 9, 12, 11, 14],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Income Categories Sparkline
    const incomeCategoriesCtx = document.getElementById('incomeCategoriesSparkline')?.getContext('2d');
    if (incomeCategoriesCtx) {
        new Chart(incomeCategoriesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [4, 5, 4, 6, 5, 7],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Expense Categories Sparkline
    const expenseCategoriesCtx = document.getElementById('expenseCategoriesSparkline')?.getContext('2d');
    if (expenseCategoriesCtx) {
        new Chart(expenseCategoriesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [4, 5, 5, 6, 6, 7],
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderColor: '#EF4444',
                    borderWidth: 1,
                    borderRadius: 4
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

            // Filter the table
            const filterType = this.getAttribute('data-filter');
            filterCategoriesByType(filterType);

            // Close dropdown
            filterOptions.classList.remove('active');
        });
    });
});

// Filter categories function
function filterCategories(searchTerm) {
    const rows = document.querySelectorAll('#categoriesTableBody .category-row');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

// Filter categories by type function
function filterCategoriesByType(type) {
    const rows = document.querySelectorAll('#categoriesTableBody .category-row');

    rows.forEach(row => {
        if (type === 'all' || row.dataset.type === type) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
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

.metric-danger {
    --color-start: #EF4444;
    --color-end: #DC2626;
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

.metric-danger .metric-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
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
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.filter-dropdown {
    position: relative;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
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
    border-color: #4F46E5;
    color: #4F46E5;
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
    color: #4F46E5;
    font-weight: 600;
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

.categories-table {
    width: 100%;
    border-collapse: collapse;
}

.categories-table thead {
    background: #F9FAFB;
}

.categories-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.categories-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.categories-table tbody tr:hover {
    background: #F9FAFB;
}

.categories-table td {
    padding: 16px;
    font-size: 14px;
}

.category-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.category-icon.income {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.category-icon.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.category-details {
    display: flex;
    flex-direction: column;
}

.category-name {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.category-description {
    font-size: 12px;
    color: #9CA3AF;
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

.category-transactions {
    font-weight: 600;
    color: #6B7280;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 36px;
    height: 36px;
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

/* ===== ACTIONS SECTION ===== */
.actions-section {
    margin-bottom: 48px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.action-card {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 20px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.action-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
}

.action-card:nth-child(1) {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.action-card:nth-child(2) {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.action-card:nth-child(3) {
    --color-start: #EF4444;
    --color-end: #DC2626;
}

.action-card:nth-child(4) {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.action-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-icon.primary {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
    color: #4F46E5;
}

.action-icon.success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
    color: #22C55E;
}

.action-icon.danger {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    color: #EF4444;
}

.action-icon.warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
    color: #F59E0B;
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
    font-size: 13px;
    color: #6B7280;
}

.action-arrow {
    color: #9CA3AF;
    transition: all 0.2s ease;
}

.action-card:hover .action-arrow {
    color: #4F46E5;
    transform: translateX(4px);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }

    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
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

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .section-actions {
        width: 100%;
        flex-direction: column;
        gap: 12px;
    }

    .search-input input {
        width: 100%;
    }

    .categories-table {
        font-size: 12px;
    }

    .categories-table th,
    .categories-table td {
        padding: 12px 8px;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .action-card {
        padding: 20px;
    }
}

@media (max-width: 480</style> 
@endsection 
