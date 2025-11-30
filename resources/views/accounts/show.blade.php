@extends('layouts.app')

@section('title', 'Detail Rekening - ' . $account->name)
@section('page-title', 'Detail Rekening')

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
                    <span>Detail Rekening</span>
                </div>
                <h1 class="page-title">{{ $account->name }}</h1>
                <p class="page-subtitle">Informasi lengkap dan status rekening Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('accounts.index') }}" class="btn-secondary">
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
        <!-- Account Profile Card -->
        <section class="profile-section">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-name">{{ $account->name }}</h2>
                        <div class="profile-badges">
                            <span class="account-type-badge {{ $account->type }}">
                                {{ $account->type === 'savings' ? 'Tabungan' : ($account->type === 'checking' ? 'Giro' : 'Kredit') }}
                            </span>
                            <span class="status-badge {{ $account->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $account->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="profile-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Nomor Rekening</div>
                            <div class="detail-value">{{ $account->number ?? 'Tidak ada' }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Saldo Saat Ini</div>
                            <div class="detail-value balance-value {{ $account->balance < 0 ? 'balance-negative' : 'balance-positive' }}">
                                Rp{{ number_format($account->balance, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Dibuat Pada</div>
                            <div class="detail-value">{{ $account->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Terakhir Diubah</div>
                            <div class="detail-value">{{ $account->updated_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('accounts.edit', $account) }}" class="btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit Rekening</span>
                    </a>
                    <a href="{{ route('accounts.ledger', $account) }}" class="btn-secondary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        <span>Buku Besar</span>
                    </a>
                    <form method="POST" action="{{ route('accounts.destroy', $account) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening ini?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <span>Hapus Rekening</span>
                        </button>
                    </form>
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
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
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

.btn-primary, .btn-secondary, .btn-danger {
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

.btn-danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
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
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    border-bottom: 1px solid #E5E7EB;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 16px;
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 8px 24px rgba(34, 197, 94, 0.3);
}

.profile-info {
    flex-grow: 1;
}

.profile-name {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 12px;
}

.profile-badges {
    display: flex;
    align-items: center;
    gap: 12px;
}

.account-type-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.account-type-badge.savings {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.account-type-badge.checking {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.account-type-badge.credit {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.status-badge.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
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

.balance-value.balance-positive {
    color: #22C55E;
}

.balance-value.balance-negative {
    color: #EF4444;
}

.profile-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding: 24px 32px;
    border-top: 1px solid #E5E7EB;
}

.inline {
    display: inline;
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
        width: 60px;
        height: 60px;
    }
    
    .profile-name {
        font-size: 24px;
    }
    
    .detail-item {
        padding: 16px 24px;
    }
    
    .profile-actions {
        flex-direction: column;
        padding: 16px 24px;
    }
    
    .btn-primary, .btn-secondary, .btn-danger {
        width: 100%;
        justify-content: center;
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
        width: 50px;
        height: 50px;
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