@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span>Detail Produk</span>
                </div>
                <h1 class="page-title">{{ $product->name }}</h1>
                <p class="page-subtitle">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
            </div>
            <div class="header-right">
                <div class="stock-indicator">
                    <div class="stock-label">Stok Tersedia</div>
                    <div class="stock-value {{ $product->stock_quantity > 0 ? 'positive' : ($product->stock_quantity <= 0 ? 'negative' : 'neutral') }}">
                        {{ $product->stock_quantity }}
                    </div>
                </div>
                <a href="{{ route('products.edit', $product) }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit Produk</span>
                </a>
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Product Details Section -->
            <div class="details-section">
                <!-- Product Information Card -->
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h2 class="form-title">Informasi Produk</h2>
                        <p class="form-subtitle">Detail lengkap mengenai produk ini</p>
                    </div>

                    <div class="form-body">
                        <div class="product-details-grid">
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    Kategori
                                </label>
                                <div class="detail-value">
                                    {{ $product->productCategory->name ?? 'Tidak ada kategori' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    SKU
                                </label>
                                <div class="detail-value sku-code">
                                    {{ $product->sku ?? 'Tidak ada SKU' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    Satuan
                                </label>
                                <div class="detail-value">
                                    {{ $product->unit ?? 'Tidak ada satuan' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Harga Jual
                                </label>
                                <div class="detail-value price-display">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Harga Beli
                                </label>
                                <div class="detail-value cost-display">
                                    Rp{{ number_format($product->cost_price, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                    Profit Margin
                                </label>
                                <div class="detail-value">
                                    @php
                                        $margin = $product->price > 0 ? (($product->price - $product->cost_price) / $product->price) * 100 : 0;
                                    @endphp
                                    <span class="margin-badge {{ $margin > 0 ? 'positive' : ($margin < 0 ? 'negative' : 'neutral') }}">
                                        {{ number_format($margin, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Movements Card -->
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="17 1 21 5 17 9"></polyline>
                                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                <polyline points="7 23 3 19 7 15"></polyline>
                                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                            </svg>
                        </div>
                        <h2 class="form-title">Pergerakan Stok</h2>
                        <p class="form-subtitle">Riwayat pergerakan stok untuk produk ini</p>
                    </div>

                    <div class="form-body">
                        @if($product->stockMovements()->count() > 0)
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                                </svg>
                                                Tanggal
                                            </th>
                                            <th>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                                </svg>
                                                Tipe
                                            </th>
                                            <th>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                                </svg>
                                                Jumlah
                                            </th>
                                            <th>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                </svg>
                                                Referensi
                                            </th>
                                            <th>
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                </svg>
                                                Catatan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->stockMovements()->latest()->limit(10)->get() as $movement)
                                            <tr>
                                                <td>{{ $movement->date->format('d M Y') }}</td>
                                                <td>
                                                    <span class="movement-badge {{ $movement->type }}">
                                                        {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">{{ $movement->quantity }}</td>
                                                <td>{{ $movement->reference ?? '-' }}</td>
                                                <td>{{ $movement->notes ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($product->stockMovements()->count() > 10)
                                <div class="text-center mt-3">
                                    <a href="{{ route('stock-movements.index', ['product_id' => $product->id]) }}" class="btn-secondary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                        Lihat Semua Pergerakan
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                </div>
                                <h3 class="empty-title">Belum ada pergerakan stok</h3>
                                <p class="empty-description">Belum ada pergerakan stok yang dicatat untuk produk ini.</p>
                                <div class="empty-action">
                                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id]) }}" class="btn-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Tambah Pergerakan Pertama</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="sidebar-section">
                <!-- Stock Information Card -->
                <div class="info-container">
                    <div class="info-header stock-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            </svg>
                            Informasi Stok
                        </h3>
                    </div>
                    <div class="info-body">
                        <div class="stock-display">
                            <div class="stock-quantity {{ $product->stock_quantity > 0 ? 'positive' : ($product->stock_quantity <= 0 ? 'negative' : 'neutral') }}">
                                {{ $product->stock_quantity }}
                            </div>
                            <div class="stock-label">Stok Saat Ini</div>
                        </div>
                        
                        <div class="stock-stats">
                            <div class="stat-item">
                                <div class="stat-label">Total Pergerakan</div>
                                <div class="stat-value">{{ $product->stockMovements()->count() }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Stok Masuk</div>
                                <div class="stat-value positive">{{ $product->stockMovements()->where('type', 'in')->sum('quantity') }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Stok Keluar</div>
                                <div class="stat-value negative">{{ $product->stockMovements()->where('type', 'out')->sum('quantity') }}</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-label">Dibuat</div>
                                <div class="stat-value">{{ $product->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary Card -->
                <div class="info-container">
                    <div class="info-header finance-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            Ringkasan Finansial
                        </h3>
                    </div>
                    <div class="info-body">
                        <div class="finance-stats">
                            <div class="finance-item">
                                <div class="finance-label">Nilai Stok</div>
                                <div class="finance-value">
                                    Rp{{ number_format($product->stock_quantity * $product->cost_price, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="finance-item">
                                <div class="finance-label">Potensi Revenue</div>
                                <div class="finance-value">
                                    Rp{{ number_format($product->stock_quantity * $product->price, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="finance-item">
                                <div class="finance-label">Potensi Profit</div>
                                <div class="finance-value profit-value">
                                    @php
                                        $profit = $product->stock_quantity * ($product->price - $product->cost_price);
                                    @endphp
                                    Rp{{ number_format($profit, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
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
                        <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'in']) }}" class="action-card">
                            <div class="action-icon add-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Stok</h4>
                                <p class="action-description">Catat stok masuk</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'out']) }}" class="action-card">
                            <div class="action-icon remove-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Kurangi Stok</h4>
                                <p class="action-description">Catat stok keluar</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('products.edit', $product) }}" class="action-card">
                            <div class="action-icon edit-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Edit Produk</h4>
                                <p class="action-description">Ubah informasi produk</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <button class="action-card" onclick="window.print()">
                            <div class="action-icon print-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                    <rect x="6" y="14" width="12" height="8"></rect>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Cetak Laporan</h4>
                                <p class="action-description">Cetak informasi produk</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
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

.header-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.stock-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.stock-label {
    font-size: 12px;
    color: #6B7280;
    font-weight: 500;
}

.stock-value {
    font-size: 24px;
    font-weight: 700;
}

.stock-value.positive {
    color: #10B981;
}

.stock-value.negative {
    color: #EF4444;
}

.stock-value.neutral {
    color: #F59E0B;
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

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
}

/* ===== FORM SECTION ===== */
.details-section {
    margin-bottom: 48px;
}

.form-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-bottom: 24px;
}

.form-header {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    padding: 32px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.form-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #1E40AF;
    margin-bottom: 8px;
}

.form-subtitle {
    font-size: 14px;
    color: #3B82F6;
    margin: 0;
}

.form-body {
    padding: 32px;
}

.product-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.detail-item {
    padding: 16px;
    background: #F8FAFC;
    border-radius: 12px;
    border-left: 3px solid #3B82F6;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #1F2937;
}

.sku-code {
    font-family: monospace;
    background: #E5E7EB;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 14px;
}

.price-display {
    color: #10B981;
    font-size: 18px;
}

.cost-display {
    color: #6B7280;
}

.margin-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

.margin-badge.positive {
    background: rgba(16, 185, 129, 0.1);
    color: #065F46;
}

.margin-badge.negative {
    background: rgba(239, 68, 68, 0.1);
    color: #991B1B;
}

.margin-badge.neutral {
    background: rgba(107, 114, 128, 0.1);
    color: #4B5563;
}

/* ===== TABLE STYLES ===== */
.table-responsive {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table th {
    padding: 12px 16px;
    text-align: left;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6B7280;
    border-bottom: 1px solid #E5E7EB;
    background: #F9FAFB;
}

.modern-table td {
    padding: 12px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #F3F4F6;
}

.modern-table tbody tr:hover {
    background-color: #F9FAFB;
}

.movement-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
}

.movement-badge.in {
    background: rgba(16, 185, 129, 0.1);
    color: #065F46;
}

.movement-badge.out {
    background: rgba(239, 68, 68, 0.1);
    color: #991B1B;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 40px 20px;
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

/* ===== SIDEBAR SECTION ===== */
.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ===== INFO CONTAINER ===== */
.info-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.info-header {
    padding: 24px;
    color: white;
}

.stock-header {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
}

.finance-header {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-body {
    padding: 24px;
}

/* ===== STOCK DISPLAY ===== */
.stock-display {
    text-align: center;
    margin-bottom: 24px;
}

.stock-quantity {
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 8px;
}

.stock-quantity.positive {
    color: #10B981;
}

.stock-quantity.negative {
    color: #EF4444;
}

.stock-quantity.neutral {
    color: #F59E0B;
}

.stock-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
}

.stock-stats {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
}

.stat-label {
    font-size: 14px;
    color: #6B7280;
}

.stat-value {
    font-size: 16px;
    font-weight: 600;
    color: #1F2937;
}

.stat-value.positive {
    color: #10B981;
}

.stat-value.negative {
    color: #EF4444;
}

/* ===== FINANCE STATS ===== */
.finance-stats {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.finance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
}

.finance-label {
    font-size: 14px;
    color: #6B7280;
}

.finance-value {
    font-size: 16px;
    font-weight: 600;
    color: #1F2937;
}

.profit-value {
    color: #10B981;
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
    cursor: pointer;
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
    --color-start: #10B981;
    --color-end: #059669;
}

.action-card:nth-child(2) {
    --color-start: #EF4444;
    --color-end: #DC2626;
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
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
}

.remove-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.edit-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.print-icon {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .product-details-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
    
    .header-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .page-title {
        font-size: 28px;
    }
    
    .form-body {
        padding: 24px;
    }
    
    .product-details-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-table th,
    .modern-table td {
        padding: 10px 12px;
        font-size: 14px;
    }
    
    .action-card {
        padding: 16px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .form-body {
        padding: 16px;
    }
    
    .form-header {
        padding: 24px;
    }
    
    .info-header, .actions-header {
        padding: 20px;
    }
    
    .info-body, .actions-body {
        padding: 16px;
    }
    
    .action-card {
        padding: 12px;
    }
}
</style>
@endsection