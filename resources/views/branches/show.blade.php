@extends('layouts.app')

@section('title', 'Detail Cabang')
@section('page-title', 'Detail Cabang')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    <span>Detail Cabang</span>
                </div>
                <h1 class="page-title">{{ $branch->name }}</h1>
                <p class="page-subtitle">Informasi lengkap tentang cabang ini</p>
            </div>
            <div class="header-right">
                <a href="{{ route('branches.edit', $branch) }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit Cabang</span>
                </a>
                <a href="{{ route('branches.index') }}" class="btn-secondary">
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
            <!-- Branch Information Section -->
            <div class="info-section">
                <div class="info-container">
                    <div class="info-header">
                        <div class="info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <h2 class="info-title">Informasi Cabang</h2>
                    </div>

                    <div class="info-body">
                        <div class="info-group">
                            <h3 class="info-group-title">Detail Dasar</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Nama Cabang</div>
                                    <div class="info-value">{{ $branch->name }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">ID Cabang</div>
                                    <div class="info-value">#{{ $branch->id }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="info-group">
                            <h3 class="info-group-title">Informasi Kontak</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Alamat</div>
                                    <div class="info-value">{{ $branch->address ?: 'Tidak ada alamat' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Telepon</div>
                                    <div class="info-value">{{ $branch->phone ?: 'Tidak ada telepon' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $branch->email ?: 'Tidak ada email' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="info-group">
                            <h3 class="info-group-title">Waktu</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Dibuat</div>
                                    <div class="info-value">{{ $branch->created_at->format('d M Y, H:i') }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Terakhir Diubah</div>
                                    <div class="info-value">{{ $branch->updated_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="actions-section">
                <div class="actions-container">
                    <div class="actions-header">
                        <div class="actions-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 8.96l4.24 4.24M20.46 8.96l-4.24 4.24"></path>
                            </svg>
                        </div>
                        <h2 class="actions-title">Aksi</h2>
                    </div>

                    <div class="actions-body">
                        <a href="{{ route('branches.edit', $branch) }}" class="action-card action-edit">
                            <div class="action-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h3 class="action-title">Edit Cabang</h3>
                                <p class="action-description">Perbarui informasi cabang ini</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <form method="POST" action="{{ route('branches.destroy', $branch) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-card action-delete">
                                <div class="action-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </div>
                                <div class="action-content">
                                    <h3 class="action-title">Hapus Cabang</h3>
                                    <p class="action-description">Hapus cabang ini secara permanen</p>
                                </div>
                                <div class="action-arrow">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </div>
                            </button>
                        </form>

                        @if(auth()->user()->userRole->name === 'super_admin' || (auth()->user()->branch && auth()->user()->branch->id === $branch->id))
                            <form method="POST" action="{{ route('branches.switch', $branch) }}">
                                @csrf
                                <button type="submit" class="action-card action-switch">
                                    <div class="action-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="17 8 21 12 17 16"></polyline>
                                            <polyline points="7 8 3 12 7 16"></polyline>
                                            <line x1="12" y1="2" x2="12" y2="22"></line>
                                        </svg>
                                    </div>
                                    <div class="action-content">
                                        <h3 class="action-title">Beralih ke Cabang Ini</h3>
                                        <p class="action-description">Gunakan cabang ini sebagai cabang aktif Anda</p>
                                    </div>
                                    <div class="action-arrow">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="9 18 15 12 9 6"></polyline>
                                        </svg>
                                    </div>
                                </button>
                            </form>
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
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
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
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(100, 116, 139, 0.4);
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

/* ===== INFO SECTION ===== */
.info-section {
    margin-bottom: 48px;
}

.info-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.info-header {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    padding: 24px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.info-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #1E293B;
    margin: 0;
}

.info-body {
    padding: 32px;
}

.info-group {
    margin-bottom: 32px;
}

.info-group:last-child {
    margin-bottom: 0;
}

.info-group-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid #F3F4F6;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
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

/* ===== ACTIONS SECTION ===== */
.actions-section {
    margin-bottom: 48px;
}

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
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.actions-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.actions-title {
    font-size: 20px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
}

.actions-body {
    padding: 24px;
}

.action-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    background: #F9FAFB;
    cursor: pointer;
    width: 100%;
}

.action-card:last-child {
    margin-bottom: 0;
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.action-edit:hover {
    background: rgba(245, 158, 11, 0.05);
    border: 1px solid rgba(245, 158, 11, 0.2);
}

.action-delete:hover {
    background: rgba(239, 68, 68, 0.05);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.action-switch:hover {
    background: rgba(34, 197, 94, 0.05);
    border: 1px solid rgba(34, 197, 94, 0.2);
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

.action-edit .action-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.action-delete .action-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.action-switch .action-icon {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
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
    color: #64748B;
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
    
    .info-grid {
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
    
    .info-body {
        padding: 24px 16px;
    }
    
    .actions-body {
        padding: 24px 16px;
    }
    
    .action-card {
        padding: 16px;
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .action-arrow {
        transform: rotate(90deg);
    }
}
</style>
@endsection