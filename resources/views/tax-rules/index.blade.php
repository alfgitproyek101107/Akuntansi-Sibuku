@extends('layouts.app')

@section('title', 'Kelola Aturan Pajak')
@section('page-title', 'Kelola Aturan Pajak')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                    </svg>
                    <span>Kelola Aturan Pajak</span>
                </div>
                <h1 class="page-title">Daftar Aturan Pajak</h1>
                <p class="page-subtitle">Kelola aturan pajak untuk produk dan layanan</p>
            </div>
            <div class="header-right">
                <a href="{{ route('tax-rules.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Aturan Pajak</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Filters Section -->
            <section class="filters-section">
                <div class="filters-card">
                    <form method="GET" action="{{ route('tax-rules.index') }}" id="filtersForm">
                        <div class="filters-grid">
                            <!-- Search -->
                            <div class="filter-group">
                                <label class="filter-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                    Cari
                                </label>
                                <input type="text" class="filter-input" name="search" value="{{ request('search') }}" placeholder="Nama, kode, atau deskripsi...">
                            </div>

                            <!-- Type Filter -->
                            <div class="filter-group">
                                <label class="filter-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.74-1.51-2.75-1.5-4.5A5.5 5.5 0 0 0 7.5 8c1.76 0 3-.5 4.5-2 1.74 1.51 2.75 1.5 4.5A5.5 5.5 0 0 0 12 19"></path>
                                    </svg>
                                    Tipe
                                </label>
                                <select class="filter-select" name="type">
                                    <option value="">Semua Tipe</option>
                                    <option value="input" {{ request('type') == 'input' ? 'selected' : '' }}>Pajak Masukan</option>
                                    <option value="output" {{ request('type') == 'output' ? 'selected' : '' }}>Pajak Keluaran</option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div class="filter-group">
                                <label class="filter-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    Status
                                </label>
                                <select class="filter-select" name="is_active">
                                    <option value="">Semua Status</option>
                                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="filter-actions">
                                <button type="submit" class="btn-filter">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.35-4.35"></path>
                                    </svg>
                                    Filter
                                </button>
                                <a href="{{ route('tax-rules.index') }}" class="btn-reset">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Table Section -->
            <section class="table-section">
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-info">
                            <h2 class="table-title">Daftar Aturan Pajak</h2>
                            <p class="table-subtitle">{{ $taxRules->total() }} aturan pajak ditemukan</p>
                        </div>
                    </div>

                    @if($taxRules->count() > 0)
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Persentase</th>
                                        <th>Kode</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($taxRules as $taxRule)
                                        <tr>
                                            <td>
                                                <div class="tax-rule-name">
                                                    <strong>{{ $taxRule->name }}</strong>
                                                    @if($taxRule->description)
                                                        <div class="tax-rule-description">{{ Str::limit($taxRule->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="type-badge {{ $taxRule->type }}">
                                                    {{ $taxRule->type == 'input' ? 'Masukan' : 'Keluaran' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="percentage-value">{{ number_format($taxRule->percentage, 2) }}%</span>
                                            </td>
                                            <td>
                                                <span class="code-value">{{ $taxRule->code ?: '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $taxRule->is_active ? 'active' : 'inactive' }}">
                                                    {{ $taxRule->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('tax-rules.show', $taxRule) }}" class="btn-action btn-view" title="Lihat Detail">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('tax-rules.edit', $taxRule) }}" class="btn-action btn-edit" title="Edit">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </a>
                                                    <form method="POST" action="{{ route('tax-rules.toggle-status', $taxRule) }}" class="inline-form" onsubmit="return confirm('{{ $taxRule->is_active ? 'Nonaktifkan' : 'Aktifkan' }} aturan pajak ini?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn-action {{ $taxRule->is_active ? 'btn-deactivate' : 'btn-activate' }}" title="{{ $taxRule->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                @if($taxRule->is_active)
                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                                                @else
                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                    <path d="M9 12l2 2 4-4"></path>
                                                                @endif
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    @if($taxRule->products->count() == 0)
                                                        <form method="POST" action="{{ route('tax-rules.destroy', $taxRule) }}" class="inline-form" onsubmit="return confirm('Hapus aturan pajak ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-action btn-delete" title="Hapus">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M3 6h18"></path>
                                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pagination-container">
                            {{ $taxRules->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                            </div>
                            <h3 class="empty-title">Tidak ada aturan pajak</h3>
                            <p class="empty-description">
                                Belum ada aturan pajak yang dibuat. Mulai dengan membuat aturan pajak baru.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('tax-rules.create') }}" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    <span>Tambah Aturan Pajak</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </section>
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

/* ===== MAIN CONTENT ===== */
.dashboard-main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

/* ===== FILTERS SECTION ===== */
.filters-section {
    margin-bottom: 24px;
}

.filters-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    padding: 28px;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.filter-label svg {
    color: #4F46E5;
    width: 16px;
    height: 16px;
}

.filter-input, .filter-select {
    padding: 12px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.filter-actions {
    display: flex;
    gap: 12px;
}

.btn-filter, .btn-reset {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
}

.btn-filter {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
}

.btn-reset {
    background: #FFFFFF;
    color: #6B7280;
    border: 2px solid #E5E7EB;
}

.btn-reset:hover {
    background: #F9FAFB;
    color: #374151;
}

/* ===== TABLE SECTION ===== */
.table-section {
    margin-bottom: 24px;
}

.table-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.table-info h2 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.table-info p {
    font-size: 14px;
    color: #6B7280;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #F9FAFB;
}

.data-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.data-table tbody tr:hover {
    background: #F9FAFB;
}

.data-table td {
    padding: 16px;
    font-size: 14px;
}

/* ===== TAX RULE STYLES ===== */
.tax-rule-name strong {
    color: #111827;
    font-weight: 600;
}

.tax-rule-description {
    color: #6B7280;
    font-size: 12px;
    margin-top: 2px;
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

.percentage-value {
    font-weight: 700;
    color: #4F46E5;
}

.code-value {
    font-family: 'Monaco', 'Menlo', monospace;
    background: #F3F4F6;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    color: #374151;
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

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 8px;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-view {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.btn-view:hover {
    background: rgba(59, 130, 246, 0.2);
}

.btn-edit {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.btn-edit:hover {
    background: rgba(245, 158, 11, 0.2);
}

.btn-activate {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.btn-activate:hover {
    background: rgba(34, 197, 94, 0.2);
}

.btn-deactivate {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.btn-deactivate:hover {
    background: rgba(239, 68, 68, 0.2);
}

.btn-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.btn-delete:hover {
    background: rgba(239, 68, 68, 0.2);
}

.inline-form {
    display: inline;
}

/* ===== PAGINATION ===== */
.pagination-container {
    padding: 20px 28px;
    border-top: 1px solid #F3F4F6;
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

.empty-action {
    margin-top: 8px;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }

    .filters-grid {
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

    .table-responsive {
        font-size: 12px;
    }

    .data-table th,
    .data-table td {
        padding: 12px 8px;
    }

    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }

    .btn-action {
        width: 28px;
        height: 28px;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }

    .page-title {
        font-size: 24px;
    }

    .filters-card {
        padding: 20px;
    }

    .table-card {
        border-radius: 12px;
    }

    .table-header {
        padding: 20px;
    }

    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection