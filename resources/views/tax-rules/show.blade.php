@extends('layouts.app')

@section('title', 'Detail Aturan Pajak')
@section('page-title', 'Detail Aturan Pajak')

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
                    <span>Detail Aturan Pajak</span>
                </div>
                <h1 class="page-title">{{ $taxRule->name }}</h1>
                <p class="page-subtitle">
                    {{ $taxRule->type == 'input' ? 'Pajak Masukan' : 'Pajak Keluaran' }} • {{ number_format($taxRule->percentage, 2) }}%
                </p>
            </div>
            <div class="header-right">
                <a href="{{ route('tax-rules.edit', $taxRule) }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit Aturan Pajak</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Tax Rule Info Section -->
        <section class="info-section">
            <div class="info-grid">
                <!-- Tax Rule Information Card -->
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon {{ $taxRule->type }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                        </div>
                        <h2 class="info-title">Informasi Aturan Pajak</h2>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <div class="info-label">Nama Aturan Pajak</div>
                            <div class="info-value">{{ $taxRule->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Tipe Pajak</div>
                            <div class="info-value">
                                <span class="type-badge {{ $taxRule->type }}">
                                    {{ $taxRule->type == 'input' ? 'Pajak Masukan' : 'Pajak Keluaran' }}
                                </span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Persentase</div>
                            <div class="info-value">{{ number_format($taxRule->percentage, 2) }}%</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Kode</div>
                            <div class="info-value">{{ $taxRule->code ?: '-' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="status-badge {{ $taxRule->is_active ? 'active' : 'inactive' }}">
                                    {{ $taxRule->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                        </div>
                        @if($taxRule->description)
                            <div class="info-item">
                                <div class="info-label">Deskripsi</div>
                                <div class="info-value">{{ $taxRule->description }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics Card -->
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon stats">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </div>
                        <h2 class="info-title">Statistik</h2>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <div class="info-label">Produk yang Menggunakan</div>
                            <div class="info-value">{{ $taxRule->products->count() }} produk</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Dibuat Oleh</div>
                            <div class="info-value">{{ $taxRule->creator->name ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Dibuat Pada</div>
                            <div class="info-value">{{ $taxRule->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Terakhir Diubah</div>
                            <div class="info-value">{{ $taxRule->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Produk yang Menggunakan Aturan Pajak Ini</h2>
                    <p class="section-subtitle">Daftar produk yang menggunakan aturan pajak "{{ $taxRule->name }}"</p>
                </div>
            </div>

            <div class="table-container">
                @if($taxRule->products->count() > 0)
                    <div class="table-responsive">
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga Jual</th>
                                    <th>Pajak</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($taxRule->products as $product)
                                    <tr>
                                        <td>
                                            <div class="product-name">
                                                <strong>{{ $product->name }}</strong>
                                                @if($product->description)
                                                    <div class="product-description">{{ Str::limit($product->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-category">
                                                {{ $product->category->name ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-price">
                                                Rp{{ number_format($product->selling_price, 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-tax">
                                                Rp{{ number_format($taxRule->getTaxAmount($product->selling_price), 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-total">
                                                Rp{{ number_format($product->selling_price + $taxRule->getTaxAmount($product->selling_price), 0, ',', '.') }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.83z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </div>
                        <h3 class="empty-title">Tidak ada produk</h3>
                        <p class="empty-description">
                            Belum ada produk yang menggunakan aturan pajak ini.
                        </p>
                    </div>
                @endif
            </div>
        </section>

        <!-- Actions Section -->
        <section class="actions-section">
            <div class="actions-card">
                <h3 class="actions-title">Aksi</h3>
                <div class="actions-grid">
                    <a href="{{ route('tax-rules.edit', $taxRule) }}" class="action-button edit">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit Aturan Pajak</span>
                    </a>

                    <form method="POST" action="{{ route('tax-rules.toggle-status', $taxRule) }}" class="inline-form">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="action-button {{ $taxRule->is_active ? 'deactivate' : 'activate' }}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                @if($taxRule->is_active)
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                @else
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M9 12l2 2 4-4"></path>
                                @endif
                            </svg>
                            <span>{{ $taxRule->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Aturan Pajak</span>
                        </button>
                    </form>

                    @if($taxRule->products->count() == 0)
                        <form method="POST" action="{{ route('tax-rules.destroy', $taxRule) }}" class="inline-form" onsubmit="return confirm('Hapus aturan pajak ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-button delete">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18"></path>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                <span>Hapus Aturan Pajak</span>
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('tax-rules.index') }}" class="action-button back">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12,19 5,12 12,5"></polyline>
                        </svg>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>
            </div>
        </section>
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
}

/* ===== MAIN CONTENT ===== */
.dashboard-main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

/* ===== INFO SECTION ===== */
.info-section {
    margin-bottom: 48px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.info-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.info-card:nth-child(1) {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.info-card:nth-child(2) {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.info-icon.input {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.info-icon.output {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
}

.info-icon.stats {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
}

.info-value {
    font-size: 16px;
    color: #111827;
    font-weight: 600;
}

.type-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.type-badge.input {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.type-badge.output {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.status-badge.inactive {
    background: rgba(156, 163, 175, 0.1);
    color: #6B7280;
}

/* ===== PRODUCTS SECTION ===== */
.products-section {
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

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table thead {
    background: #F9FAFB;
}

.products-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.products-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.products-table tbody tr:hover {
    background: #F9FAFB;
}

.products-table td {
    padding: 16px;
    font-size: 14px;
}

.product-name strong {
    color: #111827;
    font-weight: 600;
}

.product-description {
    color: #6B7280;
    font-size: 12px;
    margin-top: 2px;
}

.product-category {
    font-weight: 500;
    color: #374151;
}

.product-price, .product-tax, .product-total {
    font-weight: 700;
    font-size: 16px;
}

.product-price {
    color: #111827;
}

.product-tax {
    color: #F59E0B;
}

.product-total {
    color: #22C55E;
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

/* ===== ACTIONS SECTION ===== */
.actions-section {
    margin-bottom: 48px;
}

.actions-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
}

.actions-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 24px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
}

.action-button {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
}

.action-button.edit {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.action-button.edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
}

.action-button.activate {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.action-button.activate:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

.action-button.deactivate {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.action-button.deactivate:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.action-button.delete {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.action-button.delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.action-button.back {
    background: #FFFFFF;
    color: #6B7280;
    border: 2px solid #E5E7EB;
}

.action-button.back:hover {
    background: #F9FAFB;
    color: #374151;
}

.inline-form {
    display: inline;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
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

    .products-table {
        font-size: 12px;
    }

    .products-table th,
    .products-table td {
        padding: 12px 8px;
    }

    .actions-grid {
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

    .info-card {
        padding: 20px;
    }

    .empty-state {
        padding: 40px 20px;
    }

    .actions-card {
        padding: 20px;
    }
}
</style>
@endsection