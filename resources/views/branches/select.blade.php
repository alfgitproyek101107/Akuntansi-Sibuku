@extends('layouts.app')

@section('title', 'Pilih Cabang')
@section('page-title', 'Pilih Cabang')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5m-4 0h4"></path>
                    </svg>
                    <span>Cabang</span>
                </div>
                <h1 class="page-title">Pilih Cabang</h1>
                <p class="page-subtitle">Silakan pilih cabang yang ingin Anda kelola</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        @if($branches->count() > 0)
            <div class="branch-selection-container">
                <form method="POST" action="{{ route('branch.set') }}" id="branchForm">
                    @csrf

                    <div class="branches-grid">
                        @foreach($branches as $branch)
                            <div class="branch-card {{ $loop->first ? 'selected' : '' }}" onclick="selectBranch({{ $branch->id }}, this)">
                                <div class="branch-header">
                                    <div class="branch-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                        </svg>
                                    </div>
                                    <div class="branch-status">
                                        @if($branch->is_head_office)
                                            <span class="status-badge status-head-office">Kantor Pusat</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="branch-info">
                                    <h3 class="branch-name">{{ $branch->name }}</h3>
                                    <div class="branch-code">{{ $branch->code }}</div>
                                    <div class="branch-address">{{ $branch->address ?? 'Alamat belum diatur' }}</div>
                                </div>
                                <div class="branch-check">
                                    <div class="check-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 11l3 3L22 4"></path>
                                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                                        </svg>
                                    </div>
                                </div>
                                <input type="radio" name="branch_id" value="{{ $branch->id }}" {{ $loop->first ? 'checked' : '' }} style="display: none;">
                            </div>
                        @endforeach
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            <span>Masuk ke Sistem</span>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <section class="empty-section">
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                    <h3 class="empty-title">Tidak ada cabang tersedia</h3>
                    <p class="empty-description">Anda belum memiliki akses ke cabang manapun. Silakan hubungi administrator sistem.</p>
                    <div class="empty-action">
                        <a href="{{ route('logout') }}" class="btn-secondary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Keluar</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif
    </main>
</div>

<script>
function selectBranch(branchId, element) {
    // Remove selected class from all branch cards
    document.querySelectorAll('.branch-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    element.classList.add('selected');

    // Check radio button
    element.querySelector('input[type="radio"]').checked = true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select first branch if available
    const selectedBranch = document.querySelector('input[name="branch_id"]:checked');
    if (selectedBranch) {
        selectedBranch.closest('.branch-card').classList.add('selected');
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
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px;
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

/* ===== ENHANCED HEADER ===== */
.dashboard-header {
    margin-bottom: 40px;
}

.header-container {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
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

/* ===== MAIN CONTENT ===== */
.dashboard-main {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* ===== BRANCH SELECTION ===== */
.branch-selection-container {
    max-width: 800px;
    margin: 0 auto;
    width: 100%;
}

.branches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.branch-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 2px solid #E5E7EB;
    padding: 24px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.branch-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    border-color: #22C55E;
}

.branch-card.selected {
    border-color: #22C55E;
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    box-shadow: 0 8px 24px rgba(34, 197, 94, 0.15);
}

.branch-card.selected::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #22C55E 0%, #10B981 100%);
}

.branch-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.branch-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.branch-info {
    margin-bottom: 16px;
}

.branch-name {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.branch-code {
    font-size: 14px;
    font-weight: 600;
    color: #22C55E;
    margin-bottom: 8px;
}

.branch-address {
    font-size: 14px;
    color: #6B7280;
    line-height: 1.5;
}

.branch-check {
    position: absolute;
    top: 24px;
    right: 24px;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
}

.branch-card.selected .branch-check {
    opacity: 1;
    transform: scale(1);
}

.check-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #22C55E;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-head-office {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    justify-content: center;
    margin-top: 32px;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 24px;
    border-radius: 12px;
    font-size: 16px;
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

/* ===== EMPTY STATE ===== */
.empty-section {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-grow: 1;
}

.empty-state {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    padding: 60px 40px;
    text-align: center;
    max-width: 500px;
    width: 100%;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: rgba(245, 158, 11, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #F59E0B;
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
    margin-bottom: 32px;
    line-height: 1.5;
}

.empty-action {
    margin-top: 8px;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .dashboard-layout {
        padding: 16px;
    }
    
    .page-title {
        font-size: 28px;
    }
    
    .branches-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .branch-card {
        padding: 20px;
    }
    
    .branch-name {
        font-size: 18px;
    }
    
    .form-actions {
        margin-top: 24px;
    }
    
    .btn-primary, .btn-secondary {
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
    
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection