@extends('layouts.app')

@section('title', 'Detail Layanan: ' . $service->name)

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
                    <span>Detail Layanan</span>
                </div>
                <h1 class="page-title">{{ $service->name }}</h1>
                <p class="page-subtitle">{{ $service->description ?? 'Layanan tanpa deskripsi' }}</p>
            </div>
            <div class="header-right">
                <div class="header-stats">
                    <div class="stat-item">
                        <div class="stat-label">Harga</div>
                        <div class="stat-value">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                    </div>
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="9" r="2"></circle>
                            <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Service Details Section -->
            <div class="details-section">
                <!-- Service Information Card -->
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                            </svg>
                        </div>
                        <h2 class="form-title">Informasi Layanan</h2>
                        <p class="form-subtitle">Detail lengkap mengenai layanan ini</p>
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
                                    {{ $service->productCategory->name ?? 'Tidak ada kategori' }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Harga
                                </label>
                                <div class="detail-value price-display">
                                    Rp{{ number_format($service->price, 0, ',', '.') }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Tanggal Dibuat
                                </label>
                                <div class="detail-value">
                                    {{ $service->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Terakhir Diupdate
                                </label>
                                <div class="detail-value">
                                    {{ $service->updated_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Description Card -->
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <h2 class="form-title">Deskripsi Layanan</h2>
                        <p class="form-subtitle">Informasi detail mengenai layanan ini</p>
                    </div>

                    <div class="form-body">
                        @if($service->description)
                            <div class="service-description">
                                {{ $service->description }}
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <h3 class="empty-title">Tidak ada deskripsi</h3>
                                <p class="empty-description">Layanan ini belum memiliki deskripsi</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="sidebar-section">
                <!-- Service Stats Card -->
                <div class="info-container">
                    <div class="info-header service-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                            </svg>
                            Informasi Layanan
                        </h3>
                    </div>
                    <div class="info-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Harga</div>
                                <div class="info-value price-value">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kategori</div>
                                <div class="info-value">{{ $service->productCategory->name ?? 'Tidak ada kategori' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span class="status-badge active">Aktif</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">ID Layanan</div>
                                <div class="info-value">#{{ $service->id }}</div>
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
                        <a href="{{ route('services.edit', $service) }}" class="action-card">
                            <div class="action-icon edit-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Edit Layanan</h4>
                                <p class="action-description">Ubah informasi layanan</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('services.create', ['category' => $service->product_category_id]) }}" class="action-card">
                            <div class="action-icon add-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Layanan Lain</h4>
                                <p class="action-description">Buat layanan baru</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('services.index') }}" class="action-card">
                            <div class="action-icon list-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Kembali ke Daftar</h4>
                                <p class="action-description">Lihat semua layanan</p>
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
                                <h4 class="action-title">Cetak Detail</h4>
                                <p class="action-description">Cetak informasi layanan</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Service Information Card -->
                <div class="info-container">
                    <div class="info-header info-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            Informasi Tambahan
                        </h3>
                    </div>
                    <div class="info-body">
                        <div class="info-list">
                            <div class="info-item-modern">
                                <div class="info-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-title">ID Layanan</div>
                                    <div class="info-value">#{{ $service->id }}</div>
                                </div>
                            </div>
                            <div class="info-item-modern">
                                <div class="info-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 12 12"></polyline>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-title">Dibuat</div>
                                    <div class="info-value">{{ $service->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="info-item-modern">
                                <div class="info-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-title">Terakhir Update</div>
                                    <div class="info-value">{{ $service->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
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

.header-stats {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-label {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.8);
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: white;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
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

.price-display {
    color: #10B981;
    font-size: 18px;
}

.service-description {
    line-height: 1.6;
    color: #4B5563;
    font-size: 16px;
    padding: 20px;
    background: #F8FAFC;
    border-radius: 12px;
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
    margin: 0 auto;
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

.service-header {
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

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
}

.info-label {
    font-size: 14px;
    color: #6B7280;
}

.info-value {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
}

.price-value {
    color: #10B981;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-badge.active {
    background: rgba(16, 185, 129, 0.1);
    color: #065F46;
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
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.action-card:nth-child(2) {
    --color-start: #10B981;
    --color-end: #059669;
}

.action-card:nth-child(3) {
    --color-start: #3B82F6;
    --color-end: #2563EB;
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

.edit-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.add-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
}

.list-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
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

/* Info List */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item-modern {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-title {
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.info-value {
    font-size: 14px;
    font-weight: 500;
    color: #1F2937;
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
    
    .page-title {
        font-size: 28px;
    }
    
    .form-body {
        padding: 24px;
    }
    
    .product-details-grid {
        grid-template-columns: 1fr;
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
    
    .info-body, .actions-body {
        padding: 16px;
    }
    
    .info-header, .actions-header {
        padding: 20px;
    }
    
    .action-card {
        padding: 12px;
    }
    
    .info-item-modern {
        padding: 10px;
    }
}
</style>
@endsection