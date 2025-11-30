@extends('layouts.app')

@section('title', 'Chart of Accounts')
@section('page-title', 'Chart of Accounts')

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
                    <span>Chart of Accounts</span>
                </div>
                <h1 class="page-title">Chart of Accounts</h1>
                <p class="page-subtitle">Kelola struktur akun untuk sistem akuntansi double entry</p>
            </div>
            <div class="header-right">
                <a href="{{ route('chart-of-accounts.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Akun Baru</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Accounts -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Akun</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ \App\Models\ChartOfAccount::active()->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua akun aktif</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+12.5%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalAccountsSparkline"></canvas>
                    </div>
                </div>

                <!-- Asset Accounts -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Akun Asset</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ \App\Models\ChartOfAccount::active()->where('type', 'asset')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Akun aktiva</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+8.2%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="assetAccountsSparkline"></canvas>
                    </div>
                </div>

                <!-- Liability Accounts -->
                <div class="metric-card metric-warning">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Akun Kewajiban</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ \App\Models\ChartOfAccount::active()->where('type', 'liability')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Akun kewajiban</span>
                            <div class="metric-trend negative">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                </svg>
                                <span>-3.1%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="liabilityAccountsSparkline"></canvas>
                    </div>
                </div>

                <!-- Equity Accounts -->
                <div class="metric-card metric-info">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                                <path d="M2 17l10 5 10-5"></path>
                                <path d="M2 12l10 5 10-5"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Akun Ekuitas</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ \App\Models\ChartOfAccount::active()->where('type', 'equity')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Akun modal</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+5.4%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="equityAccountsSparkline"></canvas>
                    </div>
                </div>

                <!-- Revenue Accounts -->
                <div class="metric-card metric-purple">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Akun Pendapatan</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ \App\Models\ChartOfAccount::active()->where('type', 'revenue')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Akun pendapatan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+15.7%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="revenueAccountsSparkline"></canvas>
                    </div>
                </div>

                <!-- Expense Accounts -->
                <div class="metric-card metric-danger">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Akun Beban</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ \App\Models\ChartOfAccount::active()->where('type', 'expense')->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Akun beban</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+7.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="expenseAccountsSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chart of Accounts by Type -->
        @if($accounts->count() > 0)
            @foreach($accounts as $groupCode => $groupAccounts)
                @php
                    $groupName = match(substr($groupCode, 0, 1)) {
                        '1' => 'AKTIVA',
                        '2' => 'KEWAJIBAN',
                        '3' => 'MODAL',
                        '4' => 'PENDAPATAN',
                        '5' => 'BEBAN',
                        default => 'LAINNYA'
                    };
                @endphp

                <section class="table-section">
                    <div class="section-header">
                        <div class="section-title-group">
                            <h2 class="section-title">{{ $groupName }}</h2>
                            <p class="section-subtitle">Daftar akun dalam kategori {{ strtolower($groupName) }}</p>
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
                                        <th>Saldo Normal</th>
                                        <th>Saldo</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($groupAccounts as $account)
                                        <tr class="account-row {{ $account->level > 1 ? 'account-sub' : '' }}">
                                            <td>
                                                <div class="account-code">
                                                    @for($i = 1; $i < $account->level; $i++)
                                                        <span class="account-indent">—</span>
                                                    @endfor
                                                    {{ $account->code }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="account-name">
                                                    @for($i = 1; $i < $account->level; $i++)
                                                        <span class="account-indent">—</span>
                                                    @endfor
                                                    <strong>{{ $account->name }}</strong>
                                                    @if($account->description)
                                                        <div class="account-description">{{ $account->description }}</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="account-type {{ $account->type }}">
                                                    {{ ucfirst($account->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="account-category">
                                                    {{ ucwords(str_replace('_', ' ', $account->category)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="account-normal-balance">
                                                    {{ ucfirst($account->normal_balance) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="account-balance {{ $account->balance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                                                    Rp{{ number_format(abs($account->balance), 0, ',', '.') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $account->is_active ? 'status-active' : 'status-inactive' }}">
                                                    {{ $account->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('chart-of-accounts.show', $account) }}" class="action-btn view-btn" title="Lihat Detail">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('chart-of-accounts.edit', $account) }}" class="action-btn edit-btn" title="Edit">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                        </svg>
                                                    </a>
                                                    <form method="POST" action="{{ route('chart-of-accounts.toggle-active', $account) }}" class="inline" onsubmit="return confirm('{{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                                                        @csrf
                                                        @method('POST')
                                                        <button type="submit" class="action-btn {{ $account->is_active ? 'deactivate-btn' : 'activate-btn' }}" title="{{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                @if($account->is_active)
                                                                    <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                                                                    <line x1="12" y1="2" x2="12" y2="12"></line>
                                                                @else
                                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                                    <path d="M20 6L9 17 4 12"></path>
                                                                @endif
                                                            </svg>
                                                        </button>
                                                    </form>
                                                    @if($account->children()->count() === 0 && $account->journalLines()->count() === 0)
                                                        <form method="POST" action="{{ route('chart-of-accounts.destroy', $account) }}" class="inline" onsubmit="return confirm('Hapus akun ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action-btn delete-btn" title="Hapus">
                                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
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
                    </div>
                </section>
            @endforeach
        @else
            <!-- Empty State -->
            <section class="empty-section">
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <h3 class="empty-title">Belum ada Chart of Accounts</h3>
                    <p class="empty-description">Mulai buat struktur akun untuk sistem akuntansi double entry Anda</p>
                    <div class="empty-action">
                        <a href="{{ route('chart-of-accounts.create') }}" class="btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Tambah Akun Pertama</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <!-- Quick Actions -->
        <section class="actions-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Aksi Cepat</h2>
                    <p class="section-subtitle">Kelola Chart of Accounts dengan mudah</p>
                </div>
            </div>
            <div class="actions-grid">
                <a href="{{ route('chart-of-accounts.create') }}" class="action-card">
                    <div class="action-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tambah Akun</h3>
                        <p class="action-description">Buat akun baru untuk struktur akuntansi</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="action-card">
                    <div class="action-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 20V10"></path>
                            <path d="M12 20V4"></path>
                            <path d="M6 20v-6"></path>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Laporan Keuangan</h3>
                        <p class="action-description">Lihat laporan keuangan perusahaan</p>
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
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Dashboard</h3>
                        <p class="action-description">Kembali ke dashboard utama</p>
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

    // Total Accounts Sparkline
    const totalAccountsCtx = document.getElementById('totalAccountsSparkline')?.getContext('2d');
    if (totalAccountsCtx) {
        const gradient = totalAccountsCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(totalAccountsCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [20, 25, 22, 28, 26, 30],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Asset Accounts Sparkline
    const assetAccountsCtx = document.getElementById('assetAccountsSparkline')?.getContext('2d');
    if (assetAccountsCtx) {
        new Chart(assetAccountsCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [10, 12, 11, 13, 12, 14],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Liability Accounts Sparkline
    const liabilityAccountsCtx = document.getElementById('liabilityAccountsSparkline')?.getContext('2d');
    if (liabilityAccountsCtx) {
        new Chart(liabilityAccountsCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [8, 9, 8, 10, 9, 8],
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Equity Accounts Sparkline
    const equityAccountsCtx = document.getElementById('equityAccountsSparkline')?.getContext('2d');
    if (equityAccountsCtx) {
        new Chart(equityAccountsCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [4, 5, 4, 6, 5, 6],
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Revenue Accounts Sparkline
    const revenueAccountsCtx = document.getElementById('revenueAccountsSparkline')?.getContext('2d');
    if (revenueAccountsCtx) {
        new Chart(revenueAccountsCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [6, 8, 7, 9, 8, 10],
                    backgroundColor: 'rgba(139, 92, 246, 0.2)',
                    borderColor: '#8B5CF6',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Expense Accounts Sparkline
    const expenseAccountsCtx = document.getElementById('expenseAccountsSparkline')?.getContext('2d');
    if (expenseAccountsCtx) {
        new Chart(expenseAccountsCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [7, 8, 7, 9, 8, 9],
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderColor: '#EF4444',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
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

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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

.metric-primary {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.metric-success {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.metric-warning {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.metric-info {
    --color-start: #3B82F6;
    --color-end: #2563EB;
}

.metric-purple {
    --color-start: #8B5CF6;
    --color-end: #7C3AED;
}

.metric-danger {
    --color-start: #EF4444;
    --color-end: #DC2626;
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

.metric-primary .metric-icon {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
}

.metric-success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-warning .metric-icon {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.metric-info .metric-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
}

.metric-purple .metric-icon {
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
}

.metric-danger .metric-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
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

.account-code {
    display: flex;
    align-items: center;
}

.account-indent {
    color: #9CA3AF;
    margin-right: 8px;
}

.account-name {
    display: flex;
    flex-direction: column;
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

.account-category {
    font-size: 14px;
    color: #6B7280;
}

.account-normal-balance {
    font-size: 14px;
    color: #6B7280;
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

.view-btn:hover {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.edit-btn:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.activate-btn:hover {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.deactivate-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.delete-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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
    background: rgba(34, 197, 94, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #22C55E;
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

.action-card:nth-child(1) {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.action-card:nth-child(2) {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.action-card:nth-child(3) {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.action-card:nth-child(4) {
    --color-start: #3B82F6;
    --color-end: #2563EB;
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

.action-icon.success {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
    color: #22C55E;
}

.action-icon.primary {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
    color: #4F46E5;
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
}

.action-arrow {
    color: #9CA3AF;
    transition: all 0.2s ease;
}

.action-card:hover .action-arrow {
    color: #22C55E;
    transform: translateX(4px);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }
    
    .metrics-grid {
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
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .accounts-table {
        font-size: 12px;
    }
    
    .accounts-table th,
    .accounts-table td {
        padding: 12px 8px;
    }
    
    .actions-grid {
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
    
    .metric-value {
        font-size: 28px;
    }
    
    .accounts-table th,
    .accounts-table td {
        padding: 8px 4px;
    }
    
    .action-card {
        padding: 16px;
    }
}
</style>
@endsection