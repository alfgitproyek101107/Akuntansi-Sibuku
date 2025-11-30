@extends('layouts.app')

@section('title', 'Manajemen Pajak')
@section('page-title', 'Pajak')

@section('content')
<div class="dashboard-layout">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>Manajemen Pajak</span>
                </div>
                <h1 class="page-title">Dashboard Pajak</h1>
                <p class="page-subtitle">Kelola faktur pajak dan integrasi CoreTax</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Statistics Cards -->
            <section class="stats-section">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ number_format($stats['total_invoices']) }}</div>
                            <div class="stat-label">Total Faktur</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">Rp {{ number_format($stats['total_tax_amount']) }}</div>
                            <div class="stat-label">Total Pajak</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ number_format($stats['approved_invoices']) }}</div>
                            <div class="stat-label">Faktur Disetujui</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <div class="stat-value">{{ number_format($stats['failed_logs']) }}</div>
                            <div class="stat-label">Log Error</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Quick Actions -->
            <section class="actions-section">
                <div class="actions-grid">
                    <a href="{{ route('tax.settings') }}" class="action-card">
                        <div class="action-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Pengaturan Pajak</div>
                            <div class="action-description">Konfigurasi pengaturan pajak dan CoreTax</div>
                        </div>
                    </a>

                    <a href="{{ route('tax.invoices') }}" class="action-card">
                        <div class="action-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Faktur Pajak</div>
                            <div class="action-description">Kelola faktur pajak dan kirim ke CoreTax</div>
                        </div>
                    </a>

                    <a href="{{ route('tax.logs') }}" class="action-card">
                        <div class="action-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="action-content">
                            <div class="action-title">Log Aktivitas</div>
                            <div class="action-description">Pantau aktivitas integrasi CoreTax</div>
                        </div>
                    </a>
                </div>
            </section>

            <!-- Recent Invoices -->
            <section class="recent-section">
                <div class="section-header">
                    <h3 class="section-title">Faktur Terbaru</h3>
                    <a href="{{ route('tax.invoices') }}" class="section-link">Lihat Semua</a>
                </div>
                <div class="recent-list">
                    @forelse($recentInvoices as $invoice)
                        <div class="recent-item">
                            <div class="recent-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="recent-content">
                                <div class="recent-title">{{ $invoice->invoice_number }}</div>
                                <div class="recent-meta">
                                    {{ $invoice->customer_name }} • {{ $invoice->invoice_date->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="recent-status">
                                <span class="status-badge status-{{ $invoice->status }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="empty-title">Belum ada faktur pajak</div>
                            <div class="empty-description">Faktur pajak akan muncul di sini setelah transaksi dengan pajak dibuat</div>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
</div>

<style>
/* Dashboard Layout */
.dashboard-layout {
    max-width: 1440px;
    margin: 0 auto;
    padding: 32px;
}

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
}

.page-title {
    font-size: 36px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 16px;
    color: #6B7280;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 32px;
}

/* Statistics */
.stats-section {
    margin-bottom: 32px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-content {
    flex-grow: 1;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: #6B7280;
}

/* Actions */
.actions-section {
    margin-bottom: 32px;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.action-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    border-color: #64748B;
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.action-content {
    flex-grow: 1;
}

.action-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.action-description {
    font-size: 14px;
    color: #6B7280;
}

/* Recent Section */
.recent-section {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.section-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

.section-link {
    font-size: 14px;
    color: #64748B;
    text-decoration: none;
}

.section-link:hover {
    color: #475569;
}

.recent-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.recent-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
}

.recent-icon {
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.recent-content {
    flex-grow: 1;
}

.recent-title {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 2px;
}

.recent-meta {
    font-size: 12px;
    color: #6B7280;
}

.recent-status {
    flex-shrink: 0;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-draft { background: #FEF3C7; color: #92400E; }
.status-generated { background: #DBEAFE; color: #1E40AF; }
.status-sent { background: #FEF3C7; color: #92400E; }
.status-approved { background: #D1FAE5; color: #065F46; }
.status-rejected { background: #FEE2E2; color: #991B1B; }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 24px;
}

.empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    border-radius: 16px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.empty-description {
    font-size: 14px;
    color: #6B7280;
    max-width: 400px;
    margin: 0 auto;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-layout {
        padding: 16px;
    }

    .page-title {
        font-size: 28px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: 1fr;
    }

    .stat-card,
    .action-card {
        padding: 16px;
    }

    .recent-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .recent-status {
        align-self: flex-end;
    }
}
</style>
@endsection