@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Pusat Laporan')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <span>Pusat Laporan</span>
                </div>
                <h1 class="page-title">Pusat Laporan</h1>
                <p class="page-subtitle">Analisis mendalam tentang kinerja bisnis Anda</p>
            </div>
            <div class="header-right">
                <div class="period-selector">
                    <div class="period-label">Periode Aktif</div>
                    <div class="period-value">{{ now()->format('F Y') }}</div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Financial Reports Section -->
        <section class="reports-section">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    Laporan Keuangan
                </h2>
                <p class="section-subtitle">Ringkasan keuangan dan analisis mendalam</p>
            </div>
            <div class="reports-grid">
                <a href="{{ route('reports.monthly') }}" class="report-card">
                    <div class="report-icon primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Laporan Bulanan</h3>
                        <p class="report-description">Ringkasan keuangan bulanan</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.accounts') }}" class="report-card">
                    <div class="report-icon info">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Saldo Rekening</h3>
                        <p class="report-description">Status akun dan saldo</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.transfers') }}" class="report-card">
                    <div class="report-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 7h12m0 0l-4-4-4 4m0 6l4 4 4-4"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Transfer</h3>
                        <p class="report-description">Riwayat transfer dana</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.reconciliation') }}" class="report-card">
                    <div class="report-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                            <path d="M20 6l-2 2m-8 6l2 2m-2-2l-2-2m-2-2l-2-2m-2-2l-2-2"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Rekonsiliasi</h3>
                        <p class="report-description">Pencocokan data keuangan</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
        </section>

        <!-- Sales Reports Section -->
        <section class="reports-section">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    Laporan Penjualan
                </h2>
                <p class="section-subtitle">Analisis pendapatan dan performa penjualan</p>
            </div>
            <div class="reports-grid">
                <a href="{{ route('reports.total-sales') }}" class="report-card">
                    <div class="report-icon primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Total Penjualan</h3>
                        <p class="report-description">Analisis pendapatan keseluruhan</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.top-products') }}" class="report-card">
                    <div class="report-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 2.18 8.84L12 18.82l-7.18 3.16L2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Produk Terlaris</h3>
                        <p class="report-description">Produk dengan penjualan tertinggi</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.sales-by-customer') }}" class="report-card">
                    <div class="report-icon info">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="9" r="2"></circle>
                            <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Penjualan per Pelanggan</h3>
                        <p class="report-description">Analisis pembelian pelanggan</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
        </section>

        <!-- Inventory Reports Section -->
        <section class="reports-section">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 1 1.73z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    Laporan Inventori
                </h2>
                <p class="section-subtitle">Pantau stok dan pergerakan inventori</p>
            </div>
            <div class="reports-grid">
                <a href="{{ route('reports.stock-levels') }}" class="report-card">
                    <div class="report-icon primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 1 1.73z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Tingkat Stok</h3>
                        <p class="report-description">Status ketersediaan produk</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.stock-movements') }}" class="report-card">
                    <div class="report-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Pergerakan Stok</h3>
                        <p class="report-description">Riwayat masuk dan keluar</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.inventory-value') }}" class="report-card">
                    <div class="report-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div class="report-content">
                        <h3 class="report-title">Nilai Inventori</h3>
                        <p class="report-description">Nilai total aset inventori</p>
                    </div>
                    <div class="report-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
            </div>
        </section>

        <!-- Quick Actions -->
        <section class="actions-section">
            <div class="section-header">
                <h2 class="section-title">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                    </svg>
                    Aksi Cepat
                </h2>
                <p class="section-subtitle">Tugas umum dan pintasan</p>
            </div>
            <div class="actions-grid">
                <a href="{{ route('reports.monthly') }}" class="action-card">
                    <div class="action-icon primary">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Laporan Bulanan</h3>
                        <p class="action-description">Ringkasan keuangan bulanan</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.total-sales') }}" class="action-card">
                    <div class="action-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Total Penjualan</h3>
                        <p class="action-description">Analisis pendapatan keseluruhan</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.stock-levels') }}" class="action-card">
                    <div class="action-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 1 1.73z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tingkat Stok</h3>
                        <p class="action-description">Status ketersediaan produk</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('dashboard') }}" class="action-card">
                    <div class="action-icon info">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Dashboard</h3>
                        <p class="action-description">Kembali ke halaman utama</p>
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

.period-selector {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 16px 24px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    text-align: center;
    min-width: 200px;
}

.period-label {
    font-size: 14px;
    color: #6B7280;
    margin-bottom: 4px;
}

.period-value {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
}

/* ===== REPORTS SECTION ===== */
.reports-section {
    margin-bottom: 48px;
}

.section-header {
    margin-bottom: 24px;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.section-title svg {
    color: #4F46E5;
}

.section-subtitle {
    font-size: 16px;
    color: #6B7280;
}

.reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.report-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    padding: 32px;
    display: flex;
    align-items: center;
    gap: 24px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.report-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.report-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.report-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.report-icon.primary {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
    color: #4F46E5;
}

.report-icon.info {
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: #3B82F6;
}

.report-icon.warning {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(217, 119, 6, 0.1) 100%);
    color: #F59E0B;
}

.report-icon.success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
    color: #22C55E;
}

.report-content {
    flex-grow: 1;
}

.report-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.report-description {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.report-arrow {
    color: #9CA3AF;
    transition: all 0.2s ease;
}

.report-card:hover .report-arrow {
    color: #4F46E5;
    transform: translateX(4px);
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
    margin: 0;
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
    
    .reports-grid,
    .actions-grid {
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
    
    .reports-grid,
    .actions-grid {
        grid-template-columns: 1fr;
    }
    
    .report-card,
    .action-card {
        padding: 24px;
    }
    
    .report-icon,
    .action-icon {
        width: 48px;
        height: 48px;
    }
    
    .report-title,
    .action-title {
        font-size: 18px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .report-card,
    .action-card {
        padding: 20px;
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    
    .report-icon,
    .action-icon {
        width: 56px;
        height: 56px;
    }
    
    .report-title,
    .action-title {
        font-size: 16px;
    }
}
</style>
@endsection