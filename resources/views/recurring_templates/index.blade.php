@extends('layouts.app')

@section('title', 'Template Berulang')
@section('page-title', 'Template Berulang')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                    <span>Template</span>
                </div>
                <h1 class="page-title">Template Berulang</h1>
                <p class="page-subtitle">Kelola semua template transaksi berulang Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('recurring-templates.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Template Baru</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Templates -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="23 4 23 10 17 10"></polyline>
                                <polyline points="1 20 1 14 7 14"></polyline>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Template</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $templates->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua template</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+5.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalTemplatesSparkline"></canvas>
                    </div>
                </div>

                <!-- Active Templates -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Aktif</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $templates->where('is_active', true)->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Template aktif</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+2.1%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="activeTemplatesSparkline"></canvas>
                    </div>
                </div>

                <!-- Income Templates -->
                <div class="metric-card metric-warning">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Template Pemasukan</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $templates->where('type', 'income')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Template pemasukan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+3.7%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="incomeTemplatesSparkline"></canvas>
                    </div>
                </div>

                <!-- Expense Templates -->
                <div class="metric-card metric-info">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                <line x1="6" y1="12" x2="18" y2="12"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Template Pengeluaran</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $templates->where('type', 'expense')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Template pengeluaran</span>
                            <div class="metric-trend negative">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                </svg>
                                <span>-1.2%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="expenseTemplatesSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Templates Table Section -->
        @if($templates->count() > 0)
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Daftar Template Berulang</h2>
                    <p class="section-subtitle">Kelola semua template transaksi berulang Anda</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="templateSearch" placeholder="Cari template..." onkeyup="filterTemplates(this.value)">
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
                            <button class="filter-option" data-filter="active">Aktif</button>
                            <button class="filter-option" data-filter="inactive">Nonaktif</button>
                            <button class="filter-option" data-filter="income">Pemasukan</button>
                            <button class="filter-option" data-filter="expense">Pengeluaran</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="templates-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Rekening</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Frekuensi</th>
                                <th>Tanggal Berikutnya</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="templatesTableBody">
                            @foreach($templates as $template)
                                <tr class="template-row">
                                    <td>
                                        <div class="template-name">
                                            <div class="name-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="23 4 23 10 17 10"></polyline>
                                                    <polyline points="1 20 1 14 7 14"></polyline>
                                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                                </svg>
                                            </div>
                                            <div class="name-details">
                                                <div class="name-value">{{ $template->name }}</div>
                                                @if($template->description)
                                                    <div class="name-description">{{ $template->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="template-type">
                                            <div class="type-badge {{ $template->type === 'income' ? 'type-income' : 'type-expense' }}">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    @if($template->type === 'income')
                                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                    @else
                                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                        <line x1="6" y1="12" x2="18" y2="12"></line>
                                                    @endif
                                                </svg>
                                                <span>{{ ucfirst($template->type) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="template-account">
                                            <div class="account-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                </svg>
                                            </div>
                                            <div class="account-name">{{ $template->account->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="template-category">
                                            <div class="category-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                                </svg>
                                            </div>
                                            <div class="category-name">{{ $template->category->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="template-amount {{ $template->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                                            Rp{{ number_format($template->amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="template-frequency">
                                            <div class="frequency-badge">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                                </svg>
                                                <span>{{ ucfirst($template->frequency) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="template-date">
                                            <div class="date-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                            </div>
                                            <div class="date-details">
                                                <div class="date-value">{{ $template->next_date->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="template-status">
                                            <div class="status-badge {{ $template->is_active ? 'status-active' : 'status-inactive' }}">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    @if($template->is_active)
                                                        <polyline points="20 6 9 17 4 12"></polyline>
                                                    @else
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                                    @endif
                                                </svg>
                                                <span>{{ $template->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if($template->is_active && $template->next_date->lte(now()))
                                                <form method="POST" action="{{ route('recurring-templates.execute', $template) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="action-btn execute-btn" title="Jalankan Sekarang" onclick="return confirm('Apakah Anda yakin ingin menjalankan template ini sekarang?')">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polygon points="23 12 16 12 18 8 23 12"></polygon>
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('recurring-templates.toggle', $template) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="action-btn toggle-btn {{ $template->is_active ? 'active' : 'inactive' }}" title="{{ $template->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        @if($template->is_active)
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                                        @else
                                                            <polyline points="20 6 9 17 4 12"></polyline>
                                                        @endif
                                                    </svg>
                                                </button>
                                            </form>
                                            <a href="{{ route('recurring-templates.show', $template) }}" class="action-btn view-btn" title="Lihat Detail">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('recurring-templates.edit', $template) }}" class="action-btn edit-btn" title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('recurring-templates.destroy', $template) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?')" class="inline">
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
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                </div>
                <h3 class="empty-title">Belum ada template berulang</h3>
                <p class="empty-description">Buat template untuk transaksi yang berulang secara otomatis</p>
                <div class="empty-action">
                    <a href="{{ route('recurring-templates.create') }}" class="btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>Buat Template Baru</span>
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
                    <p class="section-subtitle">Kelola template dan buat transaksi berulang</p>
                </div>
            </div>
            <div class="actions-grid">
                <a href="{{ route('recurring-templates.create') }}" class="action-card">
                    <div class="action-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Buat Template</h3>
                        <p class="action-description">Buat template transaksi berulang</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('categories.create') }}" class="action-card">
                    <div class="action-icon primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Buat Kategori</h3>
                        <p class="action-description">Kelompokkan transaksi Anda</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('accounts.create') }}" class="action-card">
                    <div class="action-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tambah Rekening</h3>
                        <p class="action-description">Kelola rekening bank Anda</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="action-card">
                    <div class="action-icon info">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
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

    // Total Templates Sparkline
    const totalTemplatesCtx = document.getElementById('totalTemplatesSparkline')?.getContext('2d');
    if (totalTemplatesCtx) {
        const gradient = totalTemplatesCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(totalTemplatesCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [8, 10, 9, 12, 11, 13],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Active Templates Sparkline
    const activeTemplatesCtx = document.getElementById('activeTemplatesSparkline')?.getContext('2d');
    if (activeTemplatesCtx) {
        new Chart(activeTemplatesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [4, 5, 5, 6, 6, 7],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Income Templates Sparkline
    const incomeTemplatesCtx = document.getElementById('incomeTemplatesSparkline')?.getContext('2d');
    if (incomeTemplatesCtx) {
        new Chart(incomeTemplatesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [2, 3, 3, 4, 4, 5],
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Expense Templates Sparkline
    const expenseTemplatesCtx = document.getElementById('expenseTemplatesSparkline')?.getContext('2d');
    if (expenseTemplatesCtx) {
        new Chart(expenseTemplatesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [6, 7, 6, 8, 7, 8],
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: '#3B82F6',
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
            filterTemplatesByType(filterType);
            
            // Close dropdown
            filterOptions.classList.remove('active');
        });
    });
});

// Filter templates function
function filterTemplates(searchTerm) {
    const rows = document.querySelectorAll('#templatesTableBody .template-row');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

// Filter templates by type function
function filterTemplatesByType(type) {
    const rows = document.querySelectorAll('#templatesTableBody .template-row');
    
    rows.forEach(row => {
        if (type === 'all') {
            row.style.display = '';
        } else if (type === 'active') {
            const isActive = row.querySelector('.status-active');
            row.style.display = isActive ? '' : 'none';
        } else if (type === 'inactive') {
            const isInactive = row.querySelector('.status-inactive');
            row.style.display = isInactive ? '' : 'none';
        } else if (type === 'income') {
            const isIncome = row.querySelector('.type-income');
            row.style.display = isIncome ? '' : 'none';
        } else if (type === 'expense') {
            const isExpense = row.querySelector('.type-expense');
            row.style.display = isExpense ? '' : 'none';
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

.templates-table {
    width: 100%;
    border-collapse: collapse;
}

.templates-table thead {
    background: #F9FAFB;
}

.templates-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.templates-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.templates-table tbody tr:hover {
    background: #F9FAFB;
}

.templates-table td {
    padding: 16px;
    font-size: 14px;
}

.template-name {
    display: flex;
    align-items: center;
    gap: 12px;
}

.name-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.name-value {
    font-weight: 600;
    color: #111827;
}

.name-description {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
}

.template-type {
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

.type-income {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.type-expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.template-account {
    display: flex;
    align-items: center;
    gap: 12px;
}

.account-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
}

.account-name {
    font-weight: 600;
    color: #111827;
}

.template-category {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.category-name {
    font-weight: 600;
    color: #111827;
}

.template-amount {
    font-weight: 700;
    font-size: 16px;
}

.amount-income {
    color: #22C55E;
}

.amount-expense {
    color: #EF4444;
}

.template-frequency {
    display: flex;
    align-items: center;
}

.frequency-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.template-date {
    display: flex;
    align-items: center;
    gap: 12px;
}

.date-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
    display: flex;
    align-items: center;
    justify-content: center;
}

.date-value {
    font-weight: 600;
    color: #111827;
}

.template-status {
    display: flex;
    align-items: center;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.status-inactive {
    background: rgba(107, 114, 128, 0.1);
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

.execute-btn {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.execute-btn:hover {
    background: rgba(34, 197, 94, 0.2);
    color: #16A34A;
}

.toggle-btn.active {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.toggle-btn.inactive {
    background: rgba(107, 114, 128, 0.1);
    color: #6B7280;
}

.toggle-btn.active:hover {
    background: rgba(34, 197, 94, 0.2);
    color: #16A34A;
}

.toggle-btn.inactive:hover {
    background: rgba(107, 114, 128, 0.2);
    color: #4B5563;
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
    --color-start: #3B82F6;
    --color-end: #2563EB;
}

.action-card:nth-child(2) {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.action-card:nth-child(3) {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.action-card:nth-child(4) {
    --color-start: #3B82F6;
    --color-end: #2563EB;
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

.action-icon.success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
    color: #22C55E;
}

.action-icon.primary {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
    color: #4F46E5;
}

.action-icon.warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
    color: #F59E0B;
}

.action-icon.info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: #3B82F6;
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
    color: #3B82F6;
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
    
    .templates-table {
        font-size: 12px;
    }
    
    .templates-table th,
    .templates-table td {
        padding: 12px 8px;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .action-card {
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
    
    .template-name,
    .template-account,
    .template-category,
    .template-date {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .action-card {
        padding: 16px;
    }
}
</style>
@endsection