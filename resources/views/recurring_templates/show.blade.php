@extends('layouts.app')

@section('title', 'Detail Template Berulang')
@section('page-title', 'Detail Template Berulang')

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
                    <span>Detail Template</span>
                </div>
                <h1 class="page-title">{{ $recurringTemplate->name }}</h1>
                <p class="page-subtitle">Informasi lengkap tentang template transaksi berulang</p>
            </div>
            <div class="header-right">
                <div class="breadcrumb-nav">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                    <a href="{{ route('recurring-templates.index') }}" class="breadcrumb-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        <span>Template</span>
                    </a>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                    <span class="breadcrumb-current">Detail</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Template Status -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Status</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $recurringTemplate->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Template saat ini</span>
                            <div class="metric-trend {{ $recurringTemplate->is_active ? 'positive' : 'negative' }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    @if($recurringTemplate->is_active)
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    @else
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    @endif
                                </svg>
                                <span>{{ $recurringTemplate->is_active ? 'Berjalan' : 'Dihentikan' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="statusSparkline"></canvas>
                    </div>
                </div>

                <!-- Transaction Type -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                @if($recurringTemplate->type === 'expense')
                                    <line x1="6" y1="12" x2="18" y2="12"></line>
                                @endif
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Tipe</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ ucfirst($recurringTemplate->type) }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Jenis transaksi</span>
                            <div class="metric-trend {{ $recurringTemplate->type === 'income' ? 'positive' : 'negative' }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    @if($recurringTemplate->type === 'income')
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    @else
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    @endif
                                </svg>
                                <span>{{ $recurringTemplate->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="typeSparkline"></canvas>
                    </div>
                </div>

                <!-- Frequency -->
                <div class="metric-card metric-warning">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Frekuensi</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ ucfirst($recurringTemplate->frequency) }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Perulangan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>Terjadwal</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="frequencySparkline"></canvas>
                    </div>
                </div>

                <!-- Next Date -->
                <div class="metric-card metric-info">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Tanggal Berikutnya</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $recurringTemplate->next_date->format('d M') }}</div>
                        <div class="metric-details">
                            <span class="metric-label">{{ $recurringTemplate->next_date->format('Y') }}</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>{{ $recurringTemplate->next_date->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="dateSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <div class="content-grid">
            <!-- Template Details Section -->
            <div class="details-section">
                <div class="details-container">
                    <div class="details-header">
                        <h2 class="details-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            Informasi Template
                        </h2>
                        <p class="details-subtitle">Detail lengkap template transaksi berulang</p>
                    </div>

                    <div class="details-body">
                        <!-- Template Name -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                Nama Template
                            </div>
                            <div class="detail-value">{{ $recurringTemplate->name }}</div>
                        </div>

                        <!-- Transaction Type -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="17 1 21 5 17 9"></polyline>
                                    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                    <polyline points="7 23 3 19 7 15"></polyline>
                                    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                </svg>
                                Tipe Transaksi
                            </div>
                            <div class="detail-value">
                                <div class="type-badge {{ $recurringTemplate->type === 'income' ? 'type-income' : 'type-expense' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        @if($recurringTemplate->type === 'income')
                                            <line x1="12" y1="19" x2="12" y2="5"></line>
                                            <polyline points="5 12 12 5 19 12"></polyline>
                                        @else
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <polyline points="19 12 12 19 5 12"></polyline>
                                        @endif
                                    </svg>
                                    <span>{{ ucfirst($recurringTemplate->type) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Account -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                                Rekening
                            </div>
                            <div class="detail-value">{{ $recurringTemplate->account->name }}</div>
                        </div>

                        <!-- Category -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                </svg>
                                Kategori
                            </div>
                            <div class="detail-value">{{ $recurringTemplate->category->name }}</div>
                        </div>

                        <!-- Amount -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                Jumlah
                            </div>
                            <div class="detail-value amount-value {{ $recurringTemplate->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                                Rp{{ number_format($recurringTemplate->amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Frequency -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                                Frekuensi
                            </div>
                            <div class="detail-value">
                                <div class="frequency-badge">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                    <span>{{ ucfirst($recurringTemplate->frequency) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Next Date -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                Tanggal Berikutnya
                            </div>
                            <div class="detail-value">{{ $recurringTemplate->next_date->format('d F Y') }}</div>
                        </div>

                        <!-- Status -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                                Status
                            </div>
                            <div class="detail-value">
                                <div class="status-badge {{ $recurringTemplate->is_active ? 'status-active' : 'status-inactive' }}">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        @if($recurringTemplate->is_active)
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        @else
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        @endif
                                    </svg>
                                    <span>{{ $recurringTemplate->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($recurringTemplate->description)
                            <div class="detail-item">
                                <div class="detail-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Keterangan
                                </div>
                                <div class="detail-value">{{ $recurringTemplate->description }}</div>
                            </div>
                        @endif

                        <!-- Created and Updated Dates -->
                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                Dibuat Pada
                            </div>
                            <div class="detail-value">{{ $recurringTemplate->created_at->format('d F Y H:i') }}</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-label">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Terakhir Diubah
                            </div>
                            <div class="detail-value">{{ $recurringTemplate->updated_at->format('d F Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions & Statistics Section -->
            <div class="sidebar-section">
                <!-- Actions Card -->
                <div class="actions-container">
                    <div class="actions-header">
                        <h3 class="actions-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                            </svg>
                            Aksi
                        </h3>
                    </div>

                    <div class="actions-body">
                        <a href="{{ route('recurring-templates.edit', $recurringTemplate) }}" class="action-card">
                            <div class="action-icon edit-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Edit Template</h4>
                                <p class="action-description">Perbarui informasi template</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <form method="POST" action="{{ route('recurring-templates.destroy', $recurringTemplate) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-card delete-action">
                                <div class="action-icon delete-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </div>
                                <div class="action-content">
                                    <h4 class="action-title">Hapus Template</h4>
                                    <p class="action-description">Hapus template secara permanen</p>
                                </div>
                                <div class="action-arrow">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </div>
                            </button>
                        </form>

                        <a href="{{ route('recurring-templates.create') }}" class="action-card">
                            <div class="action-icon add-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Buat Template Baru</h4>
                                <p class="action-description">Buat template transaksi baru</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('recurring-templates.index') }}" class="action-card">
                            <div class="action-icon back-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Kembali ke Daftar</h4>
                                <p class="action-description">Lihat semua template</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="stats-container">
                    <div class="stats-header">
                        <h3 class="stats-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            Statistik Template
                        </h3>
                    </div>

                    <div class="stats-body">
                        <div class="stat-item">
                            <div class="stat-value">-</div>
                            <div class="stat-label">Transaksi Dibuat</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">Rp0</div>
                            <div class="stat-label">Total Nilai</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">Rp0</div>
                            <div class="stat-label">Rata-rata Nilai</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">-</div>
                            <div class="stat-label">Transaksi Terakhir</div>
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

    // Status Sparkline
    const statusCtx = document.getElementById('statusSparkline')?.getContext('2d');
    if (statusCtx) {
        const gradient = statusCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(statusCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [1, 1, 0, 1, 1, {{ $recurringTemplate->is_active ? '1' : '0' }}],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Type Sparkline
    const typeCtx = document.getElementById('typeSparkline')?.getContext('2d');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [{{ $recurringTemplate->type === 'income' ? '1' : '0' }}, 0, 0, 0, 0, 1],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Frequency Sparkline
    const frequencyCtx = document.getElementById('frequencySparkline')?.getContext('2d');
    if (frequencyCtx) {
        const frequencyData = {
            'daily': [5, 5, 5, 5, 5, 5],
            'weekly': [0, 5, 0, 0, 0, 5],
            'monthly': [0, 0, 5, 0, 0, 0],
            'yearly': [0, 0, 0, 0, 0, 5]
        };
        
        new Chart(frequencyCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: frequencyData['{{ $recurringTemplate->frequency }}'],
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Date Sparkline
    const dateCtx = document.getElementById('dateSparkline')?.getContext('2d');
    if (dateCtx) {
        new Chart(dateCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [3, 5, 4, 6, 5, 7],
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

.breadcrumb-nav {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.breadcrumb-link {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #6B7280;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: #3B82F6;
}

.breadcrumb-current {
    color: #3B82F6;
    font-weight: 600;
    font-size: 14px;
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

/* ===== DETAILS SECTION ===== */
.details-section {
    margin-bottom: 48px;
}

.details-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.details-header {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    padding: 32px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.details-title {
    font-size: 24px;
    font-weight: 700;
    color: #1E40AF;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.details-subtitle {
    font-size: 16px;
    color: #3B82F6;
    margin: 0;
}

.details-body {
    padding: 32px;
}

.detail-item {
    display: flex;
    margin-bottom: 24px;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-label {
    width: 160px;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.detail-value {
    flex-grow: 1;
    color: #111827;
    font-size: 16px;
    font-weight: 500;
}

.type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
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

.amount-income {
    color: #22C55E;
    font-weight: 700;
}

.amount-expense {
    color: #EF4444;
    font-weight: 700;
}

.frequency-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
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
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border-radius: 12px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: inherit;
    border: 1px solid transparent;
}

.action-card:hover {
    background: #F9FAFB;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.action-card.delete-action:hover {
    background: rgba(239, 68, 68, 0.05);
    border-color: rgba(239, 68, 68, 0.2);
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

.edit-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.delete-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.add-icon {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.back-icon {
    background: rgba(107, 114, 128, 0.1);
    color: #6B7280;
}

.action-content {
    flex-grow: 1;
}

.action-title {
    font-size: 16px;
    font-weight: 600;
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
    grid-template-columns: 1fr 1fr;
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
    
    .stats-body {
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
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .details-body {
        padding: 24px;
    }
    
    .detail-item {
        flex-direction: column;
        gap: 8px;
    }
    
    .detail-label {
        width: 100%;
    }
    
    .actions-body {
        padding: 20px;
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
    
    .details-body {
        padding: 16px;
    }
    
    .details-header {
        padding: 24px;
    }
    
    .actions-body {
        padding: 16px;
    }
    
    .stats-body {
        padding: 16px;
    }
    
    .action-card {
        padding: 12px;
    }
    
    .action-icon {
        width: 40px;
        height: 40px;
    }
    
    .breadcrumb-nav {
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
    }
}
</style>
@endsection