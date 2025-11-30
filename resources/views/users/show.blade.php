@extends('layouts.app')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                    <span>Detail Pengguna</span>
                </div>
                <h1 class="page-title">{{ $user->name }}</h1>
                <p class="page-subtitle">Lihat informasi lengkap dan aktivitas pengguna</p>
            </div>
            <div class="header-right">
                <a href="{{ route('users.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Daftar</span>
                </a>
                @if(auth()->user()->userRole?->name === 'super_admin' || auth()->user()->userRole?->name === 'admin' || auth()->id() === $user->id)
                    <a href="{{ route('users.edit', $user) }}" class="btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0-2 2H4a2 2 0 0 0-2 2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit Pengguna</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- User Profile Card -->
        <section class="profile-section">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <div class="avatar-initials">{{ substr($user->name, 0, 1) }}</div>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name">{{ $user->name }}</h2>
                        <p class="profile-email">{{ $user->email }}</p>
                        <div class="profile-badges">
                            <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                            <span class="user-role {{ $user->userRole?->name ?? 'user' }}">
                                @switch($user->userRole?->name)
                                    @case('super_admin')
                                        'Super Admin'
                                    @case('admin')
                                        'Administrator'
                                    @case('user')
                                        'Pengguna'
                                    @default
                                        'Pengguna'
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                <div class="profile-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 12l-6.91-6.26L12 2z"></path>
                                <path d="M12 22l6.91-6.26L12 22z"></path>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Hak Akses</div>
                            <div class="detail-value">
                                @switch($user->userRole?->name)
                                    @case('super_admin')
                                        'Super Admin'
                                    @case('admin')
                                        'Administrator'
                                    @case('user')
                                        'Pengguna'
                                    @default
                                        'Tidak Diketahui'
                                @endswitch
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Cabang</div>
                            <div class="detail-value">{{ $user->branch?->name ?? 'Semua Cabang' }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Total Transaksi</div>
                            <div class="detail-value">{{ $user->transactions->count() }} Transaksi</div>
                        </div>
                    </div>
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
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
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

/* ===== PROFILE SECTION ===== */
.profile-section {
    margin-bottom: 48px;
}

.profile-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 24px;
    padding: 32px;
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    border-bottom: 1px solid #E5E7EB;
}

.profile-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    font-weight: 700;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
}

.avatar-initials {
    text-transform: uppercase;
}

.profile-info {
    flex-grow: 1;
}

.profile-name {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.profile-email {
    font-size: 16px;
    color: #6B7280;
    margin-bottom: 16px;
}

.profile-badges {
    display: flex;
    align-items: center;
    gap: 12px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.user-role {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.user-role.super_admin {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
}

.user-role.admin {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.user-role.user {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.profile-details {
    padding: 8px 0;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 32px;
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item:hover {
    background: #F9FAFB;
}

.detail-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.detail-content {
    flex-grow: 1;
}

.detail-label {
    font-size: 13px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
}

.detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
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
    
    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 24px;
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        font-size: 32px;
    }
    
    .profile-name {
        font-size: 24px;
    }
    
    .detail-item {
        padding: 16px 24px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .profile-header {
        padding: 20px;
    }
    
    .profile-avatar {
        width: 70px;
        height: 70px;
        font-size: 28px;
    }
    
    .profile-name {
        font-size: 20px;
    }
    
    .detail-item {
        padding: 12px 16px;
    }
    
    .detail-icon {
        width: 36px;
        height: 36px;
    }
}
</style>
@endsection