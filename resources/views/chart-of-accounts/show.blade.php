@extends('layouts.app')

@section('title', 'Detail Akun - ' . $account->name)
@section('page-title', 'Detail Akun')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <span>Detail Akun</span>
                </div>
                <h1 class="page-title">Detail Akun</h1>
                <p class="page-subtitle">{{ $account->name }} ({{ $account->code }}) - Saldo: Rp{{ number_format($account->balance, 0, ',', '.') }}</p>
            </div>
            <div class="header-right">
                <div class="date-indicator">
                    <div class="date-display">
                        <span class="date-day">{{ now()->format('d') }}</span>
                        <div class="date-details">
                            <span class="date-month">{{ now()->format('M') }}</span>
                            <span class="date-year">{{ now()->format('y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Account Details Section -->
            <section class="details-section">
                <div class="details-card">
                    <div class="details-header">
                        <div class="details-title-wrapper">
                            <h2 class="details-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                Informasi Akun
                            </h2>
                            <div class="details-status">
                                <div class="status-dot {{ $account->is_active ? 'active' : 'inactive' }}"></div>
                                <span>{{ $account->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="details-body">
                        <div class="details-grid">
                            <div class="detail-item">
                                <label class="detail-label">Kode Akun</label>
                                <div class="detail-value">{{ $account->code }}</div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label">Nama Akun</label>
                                <div class="detail-value">{{ $account->name }}</div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label">Tipe Akun</label>
                                <div class="detail-value">
                                    <span class="account-type-badge {{ $account->type }}">
                                        {{ ucfirst($account->type) }}
                                    </span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label">Kategori</label>
                                <div class="detail-value">{{ ucwords(str_replace('_', ' ', $account->category)) }}</div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label">Saldo Normal</label>
                                <div class="detail-value">{{ ucfirst($account->normal_balance) }}</div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label">Saldo Saat Ini</label>
                                <div class="detail-value balance-value {{ $account->balance >= 0 ? 'positive' : 'negative' }}">
                                    Rp{{ number_format(abs($account->balance), 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label">Level</label>
                                <div class="detail-value">{{ $account->level }}</div>
                            </div>
                            @if($account->parent)
                            <div class="detail-item">
                                <label class="detail-label">Akun Induk</label>
                                <div class="detail-value">
                                    <a href="{{ route('chart-of-accounts.show', $account->parent) }}" class="parent-link">
                                        {{ $account->parent->code }} - {{ $account->parent->name }}
                                    </a>
                                </div>
                            </div>
                            @endif
                            @if($account->description)
                            <div class="detail-item full-width">
                                <label class="detail-label">Deskripsi</label>
                                <div class="detail-value">{{ $account->description }}</div>
                            </div>
                            @endif
                            <div class="detail-item">
                                <label class="detail-label">Dibuat Pada</label>
                                <div class="detail-value">{{ $account->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <div class="detail-item">
                                <label class="detail-label">Terakhir Diupdate</label>
                                <div class="detail-value">{{ $account->updated_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Actions Section -->
            <section class="actions-section">
                <div class="actions-card">
                    <div class="actions-header">
                        <h2 class="actions-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                            Aksi
                        </h2>
                    </div>
                    <div class="actions-body">
                        <div class="actions-grid">
                            <a href="{{ route('chart-of-accounts.edit', $account) }}" class="action-btn action-btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Edit Akun
                            </a>
                            <a href="{{ route('chart-of-accounts.index') }}" class="action-btn action-btn-secondary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Kembali ke Daftar
                            </a>
                            @if($account->children()->count() === 0 && $account->journalLines()->count() === 0)
                            <form method="POST" action="{{ route('chart-of-accounts.destroy', $account) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')" class="action-btn-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                    Hapus Akun
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sub-Accounts Section -->
            @if($account->children->count() > 0)
            <section class="sub-accounts-section">
                <div class="section-header">
                    <div class="section-title-group">
                        <h2 class="section-title">Sub-Akun</h2>
                        <p class="section-subtitle">Akun turunan dari {{ $account->name }}</p>
                    </div>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="accounts-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Akun</th>
                                    <th>Tipe</th>
                                    <th>Kategori</th>
                                    <th>Saldo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($account->children as $child)
                                <tr>
                                    <td>{{ $child->code }}</td>
                                    <td>
                                        <strong>{{ $child->name }}</strong>
                                        @if($child->description)
                                        <div class="account-description">{{ $child->description }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="account-type {{ $child->type }}">
                                            {{ ucfirst($child->type) }}
                                        </span>
                                    </td>
                                    <td>{{ ucwords(str_replace('_', ' ', $child->category)) }}</td>
                                    <td>
                                        <div class="account-balance {{ $child->balance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                                            Rp{{ number_format(abs($child->balance), 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $child->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $child->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('chart-of-accounts.show', $child) }}" class="action-btn view-btn" title="Lihat Detail">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('chart-of-accounts.edit', $child) }}" class="action-btn edit-btn" title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            @endif

            <!-- Recent Journal Entries Section -->
            @if($recentEntries->count() > 0)
            <section class="journal-entries-section">
                <div class="section-header">
                    <div class="section-title-group">
                        <h2 class="section-title">Entri Jurnal Terbaru</h2>
                        <p class="section-subtitle">Aktivitas jurnal terkait akun ini</p>
                    </div>
                    {{-- Journal entries management not implemented yet --}}
                    {{-- <a href="#" class="view-all-link">
                        Lihat Semua <i class="fas fa-arrow-right"></i>
                    </a> --}}
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="accounts-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nomor Jurnal</th>
                                    <th>Deskripsi</th>
                                    <th>Debit</th>
                                    <th>Kredit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentEntries as $entry)
                                <tr>
                                    <td>{{ $entry->journalEntry->date->format('d M Y') }}</td>
                                    <td>{{ $entry->journalEntry->reference_number ?? 'N/A' }}</td>
                                    <td>{{ $entry->journalEntry->description }}</td>
                                    <td>
                                        @if($entry->debit > 0)
                                        <span class="debit-amount">Rp{{ number_format($entry->debit, 0, ',', '.') }}</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        @if($entry->credit > 0)
                                        <span class="credit-amount">Rp{{ number_format($entry->credit, 0, ',', '.') }}</span>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Journal entry detail view not implemented yet --}}
                                        <span class="action-btn view-btn disabled" title="Detail Jurnal Belum Tersedia">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
            @endif
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

.date-indicator {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #E5E7EB;
}

.date-display {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
}

.date-day {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    line-height: 1;
}

.date-details {
    display: flex;
    flex-direction: column;
}

.date-month {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.date-year {
    font-size: 12px;
    color: #9CA3AF;
}

/* ===== MAIN CONTENT ===== */
.dashboard-main {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

/* ===== DETAILS SECTION ===== */
.details-section {
    margin-bottom: 24px;
}

.details-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.details-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.details-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
}

.details-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
}

.details-title svg {
    color: #22C55E;
    margin-right: 8px;
}

.details-status {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #22C55E;
    font-weight: 600;
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #22C55E;
}

.status-dot.inactive {
    background: #EF4444;
}

.details-body {
    padding: 28px;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.detail-value {
    font-size: 16px;
    color: #111827;
    font-weight: 500;
}

.balance-value.positive {
    color: #22C55E;
}

.balance-value.negative {
    color: #EF4444;
}

.account-type-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.account-type-badge.asset {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.account-type-badge.liability {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.account-type-badge.equity {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.account-type-badge.revenue {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
}

.account-type-badge.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.parent-link {
    color: #4F46E5;
    text-decoration: none;
    font-weight: 500;
}

.parent-link:hover {
    text-decoration: underline;
}

/* ===== ACTIONS SECTION ===== */
.actions-section {
    margin-bottom: 24px;
}

.actions-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.actions-header {
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.actions-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
}

.actions-title svg {
    color: #22C55E;
    margin-right: 8px;
}

.actions-body {
    padding: 28px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    border: none;
}

.action-btn-primary {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.action-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(34, 197, 94, 0.4);
}

.action-btn-secondary {
    background: #FFFFFF;
    color: #6B7280;
    border: 2px solid #E5E7EB;
}

.action-btn-secondary:hover {
    background: #F9FAFB;
    color: #374151;
}

.action-btn-danger {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    width: 100%;
}

.action-btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
}

.action-btn-form {
    margin: 0;
}

/* ===== TABLE SECTIONS ===== */
.sub-accounts-section, .journal-entries-section {
    margin-bottom: 24px;
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

.view-all-link {
    color: #4F46E5;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
}

.view-all-link:hover {
    text-decoration: underline;
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

.accounts-table {
    width: 100%;
    border-collapse: collapse;
}

.accounts-table thead {
    background: #F9FAFB;
}

.accounts-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.accounts-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.accounts-table tbody tr:hover {
    background: #F9FAFB;
}

.accounts-table td {
    padding: 16px;
    font-size: 14px;
}

.account-description {
    font-size: 12px;
    color: #6B7280;
    margin-top: 4px;
}

.account-type {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.account-type.asset {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.account-type.liability {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.account-type.equity {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.account-type.revenue {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
}

.account-type.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.account-balance {
    font-weight: 600;
    font-size: 16px;
}

.balance-positive {
    color: #22C55E;
}

.balance-negative {
    color: #EF4444;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
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

.action-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.view-btn:hover {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.edit-btn:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.debit-amount {
    color: #22C55E;
    font-weight: 600;
}

.credit-amount {
    color: #EF4444;
    font-weight: 600;
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

    .details-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .accounts-table {
        font-size: 12px;
    }

    .accounts-table th,
    .accounts-table td {
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

    .details-header,
    .details-body,
    .actions-header,
    .actions-body {
        padding: 20px;
    }

    .accounts-table th,
    .accounts-table td {
        padding: 8px 4px;
    }
}
</style>
@endsection