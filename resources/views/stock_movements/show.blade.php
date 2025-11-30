@extends('layouts.app')

@section('title', 'Detail Pergerakan Stok')
@section('page-title', 'Detail Pergerakan Stok')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                    </svg>
                    <span>Detail Pergerakan Stok</span>
                </div>
                <h1 class="page-title">Detail Pergerakan Stok</h1>
                <p class="page-subtitle">
                    {{ $stockMovement->product->name ?? 'N/A' }} - 
                    <span class="movement-badge {{ $stockMovement->type === 'in' ? 'in' : 'out' }}">
                        {{ $stockMovement->type === 'in' ? 'Masuk' : 'Keluar' }} {{ $stockMovement->quantity }}
                    </span>
                </p>
            </div>
            <div class="header-right">
                <a href="{{ route('stock-movements.edit', $stockMovement) }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('stock-movements.index') }}" class="btn-secondary">
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
            <!-- Movement Details Section -->
            <div class="details-section">
                <div class="details-container">
                    <div class="details-header">
                        <h2 class="details-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                            </svg>
                            Detail Pergerakan
                        </h2>
                    </div>

                    <div class="details-body">
                        <!-- Movement Information -->
                        <div class="info-grid">
                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 1 1.73z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Produk</h4>
                                    <p class="info-value">{{ $stockMovement->product->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 5v14m-7-7h14"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Tipe</h4>
                                    <div class="movement-type-badge {{ $stockMovement->type }}">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 5v14m-7-7h14"></path>
                                        </svg>
                                        {{ $stockMovement->type === 'in' ? 'Stok Masuk' : 'Stok Keluar' }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Kuantitas</h4>
                                    <p class="info-value quantity-display {{ $stockMovement->type === 'in' ? 'positive' : 'negative' }}">
                                        {{ $stockMovement->type === 'in' ? '+' : '-' }}{{ $stockMovement->quantity }}
                                    </p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Tanggal</h4>
                                    <p class="info-value">{{ $stockMovement->date->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Referensi</h4>
                                    <p class="info-value">{{ $stockMovement->reference ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 12 12"></polyline>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Dibuat</h4>
                                    <p class="info-value">{{ $stockMovement->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($stockMovement->notes)
                            <div class="notes-section">
                                <h3 class="section-title">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Catatan
                                </h3>
                                <div class="notes-card">
                                    <p>{{ $stockMovement->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Product Information -->
                        <div class="product-section">
                            <h3 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 1 1.73z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                                Informasi Produk
                            </h3>
                            <div class="product-info-grid">
                                <div class="product-info-item">
                                    <h4 class="product-info-title">Stok Saat Ini</h4>
                                    <div class="product-info-value stock-display">
                                        {{ $stockMovement->product->stock_quantity ?? 0 }}
                                        <span class="stock-badge {{ $stockMovement->product->stock_quantity > 0 ? ($stockMovement->product->stock_quantity <= 10 ? 'low' : 'available') : 'empty' }}">
                                            {{ $stockMovement->product->stock_quantity > 0 ? ($stockMovement->product->stock_quantity <= 10 ? 'Rendah' : 'Tersedia') : 'Habis' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="product-info-item">
                                    <h4 class="product-info-title">SKU</h4>
                                    <div class="product-info-value sku-code">{{ $stockMovement->product->sku ?? 'N/A' }}</div>
                                </div>

                                <div class="product-info-item">
                                    <h4 class="product-info-title">Harga</h4>
                                    <div class="product-info-value price-display">
                                        Rp{{ number_format($stockMovement->product->price ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="product-info-item">
                                    <h4 class="product-info-title">Harga Pokok</h4>
                                    <div class="product-info-value cost-display">
                                        Rp{{ number_format($stockMovement->product->cost_price ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="product-info-item">
                                    <h4 class="product-info-title">Kategori</h4>
                                    <div class="product-info-value category-tag">
                                        <span class="category-dot"></span>
                                        {{ $stockMovement->product->productCategory->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="product-info-item">
                                    <h4 class="product-info-title">Satuan</h4>
                                    <div class="product-info-value">{{ $stockMovement->product->unit ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="product-action">
                                <a href="{{ route('products.show', $stockMovement->product) }}" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11 8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <span>Lihat Detail Produk</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="sidebar-section">
                <!-- Impact Summary -->
                <div class="impact-container">
                    <div class="impact-header">
                        <h3 class="impact-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            Ringkasan Dampak
                        </h3>
                    </div>
                    <div class="impact-body">
                        <div class="impact-summary">
                            <div class="impact-value {{ $stockMovement->type === 'in' ? 'positive' : 'negative' }}">
                                {{ $stockMovement->type === 'in' ? '+' : '-' }}{{ $stockMovement->quantity }}
                            </div>
                            <div class="impact-label">Perubahan Stok</div>
                        </div>
                        
                        <div class="impact-details">
                            <div class="impact-item">
                                <div class="impact-item-label">Sebelum Pergerakan</div>
                                <div class="impact-item-value">
                                    {{ ($stockMovement->product->stock_quantity ?? 0) - ($stockMovement->type === 'in' ? $stockMovement->quantity : -$stockMovement->quantity) }}
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-item-label">Setelah Pergerakan</div>
                                <div class="impact-item-value">{{ $stockMovement->product->stock_quantity ?? 0 }}</div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-item-label">Nilai Dampak</div>
                                <div class="impact-item-value value-impact">
                                    @php
                                        $value = ($stockMovement->product->cost_price ?? 0) * $stockMovement->quantity;
                                    @endphp
                                    Rp{{ number_format($value, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Movements -->
                <div class="recent-container">
                    <div class="recent-header">
                        <h3 class="recent-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pergerakan Terkini
                        </h3>
                    </div>
                    <div class="recent-body">
                        @if($stockMovement->product && $stockMovement->product->stockMovements()->count() > 1)
                            <div class="recent-movements">
                                @foreach($stockMovement->product->stockMovements()->where('id', '!=', $stockMovement->id)->latest()->limit(5)->get() as $movement)
                                    <div class="recent-movement-item">
                                        <div class="recent-movement-date">
                                            {{ $movement->date->format('d M') }}
                                        </div>
                                        <div class="recent-movement-type">
                                            <span class="movement-type-badge {{ $movement->type }}">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M12 5v14m-7-7h14"></path>
                                                </svg>
                                                {{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}
                                            </span>
                                        </div>
                                        <div class="recent-movement-quantity {{ $movement->type === 'in' ? 'positive' : 'negative' }}">
                                            {{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="recent-action">
                                <a href="{{ route('stock-movements.index', ['product_id' => $stockMovement->product_id]) }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                    <span>Lihat Semua</span>
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                                    </svg>
                                </div>
                                <p class="empty-description">Tidak ada pergerakan lain untuk produk ini.</p>
                            </div>
                        @endif
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
    display: flex;
    align-items: center;
    gap: 8px;
}

.movement-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.movement-badge.in {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.movement-badge.out {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
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
    background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.details-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.details-title svg {
    color: #4F46E5;
}

.details-body {
    padding: 32px;
}

/* ===== INFO GRID ===== */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.info-card {
    background: #F8FAFC;
    border-radius: 16px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    background: #FFFFFF;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-content {
    flex-grow: 1;
}

.info-title {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
    margin-bottom: 4px;
}

.info-value {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.quantity-display {
    font-size: 18px;
    font-weight: 700;
}

.quantity-display.positive {
    color: #22C55E;
}

.quantity-display.negative {
    color: #EF4444;
}

.movement-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.movement-type-badge.in {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.movement-type-badge.out {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

/* ===== NOTES SECTION ===== */
.notes-section {
    margin-bottom: 32px;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.notes-card {
    background: #F8FAFC;
    border-radius: 16px;
    padding: 20px;
    border-left: 4px solid #4F46E5;
}

.notes-card p {
    margin: 0;
    font-size: 16px;
    color: #374151;
    line-height: 1.6;
}

/* ===== PRODUCT SECTION ===== */
.product-section {
    margin-bottom: 32px;
}

.product-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.product-info-item {
    background: #F8FAFC;
    border-radius: 16px;
    padding: 20px;
}

.product-info-title {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
    margin-bottom: 8px;
}

.product-info-value {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
}

.stock-display {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stock-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.stock-badge.available {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.stock-badge.low {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.stock-badge.empty {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.sku-code {
    font-family: monospace;
    background: #F3F4F6;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 14px;
}

.price-display {
    font-weight: 700;
    color: #111827;
}

.cost-display {
    color: #6B7280;
}

.category-tag {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #F3F4F6;
    border-radius: 20px;
    font-size: 14px;
}

.category-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #4F46E5;
    margin-right: 8px;
}

.product-action {
    margin-top: 8px;
}

/* ===== SIDEBAR SECTION ===== */
.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ===== IMPACT CONTAINER ===== */
.impact-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.impact-header {
    background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.impact-title {
    font-size: 18px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.impact-body {
    padding: 20px;
}

.impact-summary {
    text-align: center;
    padding: 16px;
    background: #F8FAFC;
    border-radius: 12px;
    margin-bottom: 20px;
}

.impact-value {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 8px;
}

.impact-value.positive {
    color: #22C55E;
}

.impact-value.negative {
    color: #EF4444;
}

.impact-label {
    font-size: 14px;
    color: #6B7280;
}

.impact-details {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.impact-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #E5E7EB;
}

.impact-item:last-child {
    border-bottom: none;
}

.impact-item-label {
    font-size: 14px;
    color: #6B7280;
}

.impact-item-value {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.value-impact {
    color: #4F46E5;
    font-weight: 700;
}

/* ===== RECENT CONTAINER ===== */
.recent-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.recent-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.recent-title {
    font-size: 18px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.recent-body {
    padding: 20px;
}

.recent-movements {
    max-height: 250px;
    overflow-y: auto;
}

.recent-movement-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
}

.recent-movement-item:last-child {
    border-bottom: none;
}

.recent-movement-date {
    color: #6B7280;
    font-size: 14px;
    min-width: 60px;
}

.recent-movement-type {
    flex: 1;
    text-align: center;
}

.recent-movement-quantity {
    font-weight: 600;
    min-width: 50px;
    text-align: right;
}

.recent-movement-quantity.positive {
    color: #22C55E;
}

.recent-movement-quantity.negative {
    color: #EF4444;
}

.recent-action {
    margin-top: 16px;
    text-align: center;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 20px 0;
}

.empty-icon {
    font-size: 32px;
    margin-bottom: 12px;
    color: #4F46E5;
    opacity: 0.7;
}

.empty-description {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
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
    
    .details-body {
        padding: 24px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .product-info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .details-body {
        padding: 16px;
    }
    
    .info-card {
        padding: 16px;
    }
    
    .product-info-item {
        padding: 16px;
    }
    
    .impact-body, .recent-body {
        padding: 16px;
    }
}
</style>
@endsection