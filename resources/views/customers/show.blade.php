@extends('layouts.app')

@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="9" r="2"></circle>
                        <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                    </svg>
                    <span>Detail Pelanggan</span>
                </div>
                <h1 class="page-title">{{ $customer->name }}</h1>
                <p class="page-subtitle">Lihat informasi lengkap pelanggan</p>
            </div>
            <div class="header-right">
                <a href="{{ route('customers.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Customer Details Section -->
            <div class="details-section">
                <div class="details-container">
                    <div class="details-header">
                        <h2 class="details-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                            </svg>
                            Informasi Pelanggan
                        </h2>
                    </div>

                    <div class="details-body">
                        <!-- Customer Profile Card -->
                        <div class="profile-card">
                            <div class="profile-header">
                                <div class="customer-avatar">
                                    <span>{{ substr($customer->name, 0, 1) }}</span>
                                </div>
                                <div class="profile-info">
                                    <h3 class="profile-name">{{ $customer->name }}</h3>
                                    <div class="profile-meta">
                                        <div class="meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="11" width="18" height="10" rx="2" ry="2"></rect>
                                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                            </svg>
                                            <span>ID: {{ $customer->id }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 12 12"></polyline>
                                            </svg>
                                            <span>{{ $customer->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-status">
                                    <span class="status-badge {{ $customer->status ?? 'active' }}">
                                        {{ $customer->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="info-grid">
                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Email</h4>
                                    <p class="info-value">{{ $customer->email ?? 'Tidak ada email' }}</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Telepon</h4>
                                    <p class="info-value">{{ $customer->phone ?? 'Tidak ada telepon' }}</p>
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
                                    <h4 class="info-title">Tanggal Registrasi</h4>
                                    <p class="info-value">{{ $customer->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <h4 class="info-title">Terakhir Update</h4>
                                    <p class="info-value">{{ $customer->updated_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        @if($customer->address)
                            <div class="address-section">
                                <h3 class="section-title">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    Alamat Lengkap
                                </h3>
                                <div class="address-card">
                                    <p>{{ $customer->address }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Quick Actions -->
                        <div class="actions-section">
                            <h3 class="section-title">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                                </svg>
                                Aksi Cepat
                            </h3>
                            <div class="actions-grid">
                                <a href="{{ route('customers.edit', $customer) }}" class="action-card">
                                    <div class="action-icon primary">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </div>
                                    <div class="action-content">
                                        <h4 class="action-title">Edit Pelanggan</h4>
                                        <p class="action-description">Perbarui informasi pelanggan</p>
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
                                        <h4 class="action-title">Buat Transaksi</h4>
                                        <p class="action-description">Tambah transaksi baru</p>
                                    </div>
                                    <div class="action-arrow">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </div>
                                </a>

                                <button class="action-card" onclick="window.print()">
                                    <div class="action-icon warning">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                            <rect x="6" y="14" width="12" height="8"></rect>
                                        </svg>
                                    </div>
                                    <div class="action-content">
                                        <h4 class="action-title">Cetak Detail</h4>
                                        <p class="action-description">Cetak informasi pelanggan</p>
                                    </div>
                                    <div class="action-arrow">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="sidebar-section">
                <!-- Customer Stats -->
                <div class="stats-container">
                    <div class="stats-header">
                        <h3 class="stats-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                            Statistik Pelanggan
                        </h3>
                    </div>
                    <div class="stats-body">
                        <div class="stat-item">
                            <div class="stat-value">{{ $customer->id }}</div>
                            <div class="stat-label">ID Pelanggan</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $customer->created_at->diffForHumans() }}</div>
                            <div class="stat-label">Bergabung</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">
                                <span class="status-badge {{ $customer->status ?? 'active' }}">
                                    {{ $customer->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                            <div class="stat-label">Status</div>
                        </div>
                    </div>
                </div>

                <!-- Customer Contact Info -->
                <div class="contact-container">
                    <div class="contact-header">
                        <h3 class="contact-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            Informasi Kontak
                        </h3>
                    </div>
                    <div class="contact-body">
                        @if($customer->email)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Email</div>
                                    <div class="contact-value">{{ $customer->email }}</div>
                                </div>
                            </div>
                        @endif

                        @if($customer->phone)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Telepon</div>
                                    <div class="contact-value">{{ $customer->phone }}</div>
                                </div>
                            </div>
                        @endif

                        @if($customer->address)
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="contact-details">
                                    <div class="contact-label">Alamat</div>
                                    <div class="contact-value">{{ Str::limit($customer->address, 50) }}</div>
                                </div>
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

/* ===== PROFILE CARD ===== */
.profile-card {
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
    border: 1px solid #DBEAFE;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 20px;
}

.customer-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.profile-info {
    flex-grow: 1;
}

.profile-name {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.profile-meta {
    display: flex;
    gap: 16px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #6B7280;
}

.profile-status {
    flex-shrink: 0;
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
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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

/* ===== ADDRESS SECTION ===== */
.address-section {
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

.address-card {
    background: #F8FAFC;
    border-radius: 16px;
    padding: 20px;
    border-left: 4px solid #4F46E5;
}

.address-card p {
    margin: 0;
    font-size: 16px;
    color: #374151;
    line-height: 1.6;
}

/* ===== ACTIONS SECTION ===== */
.actions-section {
    margin-bottom: 32px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
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
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

/* ===== SIDEBAR SECTION ===== */
.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
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
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.stats-title {
    font-size: 18px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.stats-body {
    padding: 20px;
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
    font-size: 20px;
    font-weight: 700;
    color: #4F46E5;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 14px;
    color: #6B7280;
}

/* ===== CONTACT CONTAINER ===== */
.contact-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.contact-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.contact-title {
    font-size: 18px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.contact-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px;
    background: #F8FAFC;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: #FFFFFF;
    transform: translateX(5px);
}

.contact-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.contact-details {
    flex-grow: 1;
}

.contact-label {
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    margin-bottom: 4px;
}

.contact-value {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
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
    
    .stats-container, .contact-container {
        position: static;
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
    
    .profile-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 16px;
    }
    
    .profile-meta {
        justify-content: center;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
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
    
    .details-body {
        padding: 16px;
    }
    
    .profile-card {
        padding: 16px;
    }
    
    .customer-avatar {
        width: 60px;
        height: 60px;
        font-size: 24px;
    }
    
    .profile-name {
        font-size: 20px;
    }
    
    .stats-body, .contact-body {
        padding: 16px;
    }
    
    .action-card {
        padding: 12px;
    }
    
    .stat-item {
        padding: 12px;
    }
    
    .contact-item {
        padding: 12px;
    }
}
</style>
@endsection