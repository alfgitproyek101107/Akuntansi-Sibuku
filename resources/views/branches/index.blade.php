@extends('layouts.app')

@section('title', 'Manajemen Cabang')
@section('page-title', 'Manajemen Cabang')

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
                    <span>Cabang</span>
                </div>
                <h1 class="page-title">Manajemen Cabang</h1>
                <p class="page-subtitle">Kelola semua lokasi cabang bisnis Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('branches.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Cabang Baru</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Branches -->
                <div class="metric-card metric-slate">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Cabang</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $branches->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua cabang terdaftar</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+2</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalBranchesSparkline"></canvas>
                    </div>
                </div>

                <!-- Active Branches -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Cabang Aktif</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $branches->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Sedang beroperasi</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>100%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="activeBranchesSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Branches Table Section -->
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Daftar Cabang</h2>
                    <p class="section-subtitle">Kelola informasi dan lokasi setiap cabang</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="branchSearch" placeholder="Cari cabang..." onkeyup="filterBranches(this.value)">
                    </div>
                </div>
            </div>

            @if($branches->count() > 0)
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="branches-table">
                            <thead>
                                <tr>
                                    <th>Nama Cabang</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="branchesTableBody">
                                @foreach($branches as $branch)
                                    <tr class="branch-row">
                                        <td>
                                            <div class="branch-name">
                                                <div class="branch-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                </div>
                                                <div class="branch-details">
                                                    <div class="name">{{ $branch->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="branch-address">{{ $branch->address ?: '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="branch-phone">{{ $branch->phone ?: '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="branch-email">{{ $branch->email ?: '-' }}</div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('branches.show', $branch) }}" class="action-btn view-btn" title="Lihat Detail">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('branches.edit', $branch) }}" class="action-btn edit-btn" title="Edit">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('branches.destroy', $branch) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus cabang ini?')" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete-btn" title="Hapus">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    {{ $branches->links() }}
                </div>
            @else
                <!-- Empty State -->
                <section class="empty-section">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </div>
                        <h3 class="empty-title">Belum ada cabang</h3>
                        <p class="empty-description">Anda belum menambahkan cabang apapun. Mulai dengan membuat cabang pertama Anda.</p>
                        <div class="empty-action">
                            <a href="{{ route('branches.create') }}" class="btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Buat Cabang Pertama</span>
                            </a>
                        </div>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>

<!-- Chart.js for sparklines -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced sparkline configuration
    const sparklineOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        },
        scales: {
            x: { display: false },
            y: { display: false }
        },
        elements: {
            line: {
                borderWidth: 2,
                tension: 0.4
            },
            point: {
                radius: 0,
                hoverRadius: 0
            }
        }
    };

    // Total Branches Sparkline
    const totalBranchesCtx = document.getElementById('totalBranchesSparkline')?.getContext('2d');
    if (totalBranchesCtx) {
        const gradient = totalBranchesCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(100, 116, 139, 0.3)');
        gradient.addColorStop(1, 'rgba(100, 116, 139, 0)');
        
        new Chart(totalBranchesCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [4, 4, 5, 5, 6, 6],
                    borderColor: '#64748B',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Active Branches Sparkline
    const activeBranchesCtx = document.getElementById('activeBranchesSparkline')?.getContext('2d');
    if (activeBranchesCtx) {
        new Chart(activeBranchesCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [4, 4, 5, 5, 6, 6],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }
});

// Filter branches function
function filterBranches(searchTerm) {
    const rows = document.querySelectorAll('#branchesTableBody .branch-row');
    const term = searchTerm.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}
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

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 24px;
}

.metric-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.metric-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.metric-slate {
    --color-start: #64748B;
    --color-end: #475569;
}

.metric-success {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.metric-slate .metric-icon {
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
}

.metric-success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-content {
    margin-bottom: 24px;
}

.metric-value {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.metric-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.metric-label {
    font-size: 14px;
    color: #6B7280;
}

.metric-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #22C55E;
}

.metric-trend.negative {
    color: #EF4444;
}

.metric-sparkline {
    height: 50px;
}

/* ===== TABLE SECTION ===== */
.table-section {
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

.section-actions {
    display: flex;
    gap: 12px;
}

.search-input {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input svg {
    position: absolute;
    left: 12px;
    color: #9CA3AF;
}

.search-input input {
    padding: 10px 16px 10px 40px;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    width: 250px;
    transition: all 0.2s ease;
}

.search-input input:focus {
    outline: none;
    border-color: #64748B;
    box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
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

.branches-table {
    width: 100%;
    border-collapse: collapse;
}

.branches-table thead {
    background: #F9FAFB;
}

.branches-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.branches-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.branches-table tbody tr:hover {
    background: #F9FAFB;
}

.branches-table td {
    padding: 16px;
    font-size: 14px;
}

.branch-name {
    display: flex;
    align-items: center;
    gap: 12px;
}

.branch-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.name {
    font-weight: 600;
    color: #111827;
}

.branch-address, .branch-phone, .branch-email {
    color: #6B7280;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: #F3F4F6;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn:hover {
    background: #E5E7EB;
    color: #374151;
}

.view-btn:hover {
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
}

.edit-btn:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.delete-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

/* ===== PAGINATION ===== */
.pagination-container {
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 1px solid #F3F4F6;
}

/* Simple styling for Laravel pagination links */
.pagination-container span {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 4px;
    border-radius: 6px;
    background: #F3F4F6;
    color: #6B7280;
    font-size: 14px;
    font-weight: 500;
}

.pagination-container a {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 4px;
    border-radius: 6px;
    background: #FFFFFF;
    color: #64748B;
    border: 1px solid #E5E7EB;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.pagination-container a:hover {
    background: #F9FAFB;
    border-color: #64748B;
}

.pagination-container .current {
    background: #64748B;
    color: #FFFFFF;
    border-color: #64748B;
}

/* ===== EMPTY STATE ===== */
.empty-section {
    margin-bottom: 48px;
}

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
    background: rgba(100, 116, 139, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748B;
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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }
    
    .metrics-grid {
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
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .section-actions {
        width: 100%;
    }
    
    .search-input input {
        width: 100%;
    }
    
    .branches-table {
        font-size: 12px;
    }
    
    .branches-table th,
    .branches-table td {
        padding: 12px 8px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .metric-value {
        font-size: 28px;
    }
    
    .branch-name {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection