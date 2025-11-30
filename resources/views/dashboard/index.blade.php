@extends('layouts.app')

@section('title', 'Dashboard - Akuntansi Sibuku')
@section('page-title', 'Dashboard')

@section('content')
<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay">
    <div class="loading-content">
        <div class="loading-spinner">
            <div class="spinner"></div>
        </div>
        <div class="loading-text">Memuat dashboard...</div>
    </div>
</div>

<div class="dashboard-container">
    <!-- Enhanced Header -->
    <header class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>Ringkasan</span>
                </div>
                <h1 class="dashboard-title">
                    @if(App\Models\AppSetting::getValue('company_name'))
                        {{ App\Models\AppSetting::getValue('company_name') }} - Dashboard Keuangan
                    @else
                        Dashboard Keuangan
                    @endif
                </h1>
                <div class="branch-info">
                    @if(session('active_branch'))
                        @php $activeBranch = \App\Models\Branch::find(session('active_branch')) @endphp
                        @if($activeBranch)
                            <div class="branch-badge">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                                <span>Cabang: {{ $activeBranch->name }}</span>
                            </div>
                        @endif
                    @elseif(Auth::user()->branch)
                        <div class="branch-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <span>Cabang: {{ Auth::user()->branch->name }}</span>
                        </div>
                    @else
                        <div class="branch-badge all-branches">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                            <span>Semua Cabang</span>
                        </div>
                    @endif
                </div>
                <p class="dashboard-subtitle">
                    @if(session('active_branch') || Auth::user()->branch)
                        Data keuangan untuk cabang yang dipilih
                    @else
                        Wawasan real-time tentang kinerja keuangan Anda di semua cabang
                    @endif
                </p>
            </div>
            <div class="header-right">
                <div class="date-card">
                    <div class="date-content">
                        <span class="date-day">{{ now()->format('d') }}</span>
                        <div class="date-details">
                            <span class="date-month">{{ now()->format('M') }}</span>
                            <span class="date-year">{{ now()->format('Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content with proper spacing -->
    <main class="dashboard-main">
        <!-- Key Metrics Section -->
        <section class="metrics-section">
            <div class="metrics-container">
                <!-- Total Balance Card -->
                <div class="metric-card primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <div class="metric-title">Total Saldo</div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">Rp{{ number_format($totalBalance, 0, ',', '.') }}</div>
                        <div class="metric-details">
                            <span class="metric-label">{{ $totalAccounts }} rekening</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+12.5%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-chart">
                        <canvas id="balanceSparkline"></canvas>
                    </div>
                </div>

                <!-- Monthly Income Card -->
                <div class="metric-card success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-title">Pemasukan Bulanan</div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">Rp{{ number_format($monthlyIncome, 0, ',', '.') }}</div>
                        <div class="metric-details">
                            @if($incomeChange != 0)
                                <div class="metric-change positive">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    </svg>
                                    <span>{{ abs($incomeChange) }}%</span>
                                </div>
                            @else
                                <span class="metric-label">Tidak ada perubahan</span>
                            @endif
                        </div>
                    </div>
                    <div class="metric-chart">
                        <canvas id="incomeSparkline"></canvas>
                    </div>
                </div>

                <!-- Monthly Expense Card -->
                <div class="metric-card danger">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <div class="metric-title">Pengeluaran Bulanan</div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">Rp{{ number_format($monthlyExpense, 0, ',', '.') }}</div>
                        <div class="metric-details">
                            @if($expenseChange != 0)
                                <div class="metric-change negative">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    </svg>
                                    <span>{{ abs($expenseChange) }}%</span>
                                </div>
                            @else
                                <span class="metric-label">Tidak ada perubahan</span>
                            @endif
                        </div>
                    </div>
                    <div class="metric-chart">
                        <canvas id="expenseSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Charts Section -->
        <section class="charts-section">
            <div class="charts-container">
                <!-- Cash Flow Chart -->
                <div class="chart-card">
                    <div class="card-header">
                        <div class="card-title-section">
                            <h2 class="card-title">Analisis Arus Kas</h2>
                            <div class="card-status">
                                <div class="status-dot active"></div>
                                <span>Langsung</span>
                            </div>
                        </div>
                        <div class="card-controls">
                            <div class="period-toggle">
                                <button class="toggle-btn active" data-period="6m" data-chart="cashFlow">6 Bln</button>
                                <button class="toggle-btn" data-period="1y" data-chart="cashFlow">1 Thn</button>
                                <button class="toggle-btn" data-period="all" data-chart="cashFlow">Semua</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <canvas id="cashFlowChart"></canvas>
                        </div>
                    </div>
                    <div class="card-footer">
                        @php
                            $avgIncome = collect($cashFlowData)->avg('income');
                            $avgExpense = collect($cashFlowData)->avg('expense');
                        @endphp
                        <div class="chart-stats">
                            <div class="stat-item">
                                <div class="stat-header">
                                    <span class="stat-label">Rata-rata Pemasukan</span>
                                    <div class="stat-indicator positive"></div>
                                </div>
                                <span class="stat-value positive">Rp{{ number_format($avgIncome, 0, ',', '.') }}</span>
                            </div>
                            <div class="stat-item">
                                <div class="stat-header">
                                    <span class="stat-label">Rata-rata Pengeluaran</span>
                                    <div class="stat-indicator negative"></div>
                                </div>
                                <span class="stat-value negative">Rp{{ number_format($avgExpense, 0, ',', '.') }}</span>
                            </div>
                            <div class="stat-item">
                                <div class="stat-header">
                                    <span class="stat-label">Rata-rata Netto</span>
                                    <div class="stat-indicator neutral"></div>
                                </div>
                                <span class="stat-value neutral">Rp{{ number_format($avgIncome - $avgExpense, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Distribution -->
                <div class="chart-card">
                    <div class="card-header">
                        <div class="card-title-section">
                            <h2 class="card-title">Kategori Pengeluaran</h2>
                            <div class="card-status">
                                <div class="status-dot active"></div>
                                <span>Diperbarui</span>
                            </div>
                        </div>
                        <div class="card-controls">
                            <div class="period-toggle">
                                <button class="toggle-btn active" data-period="current" data-chart="category">Sekarang</button>
                                <button class="toggle-btn" data-period="last" data-chart="category">Sebelumnya</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-wrapper">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="category-list">
                            @if($topExpenseCategories->count() > 0)
                                @foreach($topExpenseCategories->take(4) as $index => $category)
                                    <div class="category-item">
                                        <div class="category-dot" style="background-color: {{ ['#4F46E5', '#7C3AED', '#EC4899', '#F59E0B'][$index] ?? '#6B7280' }}"></div>
                                        <div class="category-info">
                                            <span class="category-name">{{ $category->category ? Str::limit($category->category->name, 12) : 'Lainnya' }}</span>
                                            <div class="category-progress">
                                                <div class="progress-bar" style="width: {{ $monthlyExpense > 0 ? ($category->total / $monthlyExpense) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                        <span class="category-percentage">{{ $monthlyExpense > 0 ? round(($category->total / $monthlyExpense) * 100) : 0 }}%</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 6v6l4 2"></path>
                                    </svg>
                                    <span>Tidak ada data tersedia</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Transactions Section -->
        @if($recentTransactions->count() > 0)
        <section class="transactions-section">
            <div class="section-header">
                <div class="section-title-container">
                    <h2 class="section-title">Transaksi Terbaru</h2>
                    <p class="section-subtitle">Aktivitas keuangan terkini</p>
                </div>
                <div class="section-actions">
                    <button class="filter-button" id="transactionFilterBtn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('reports.index') }}" class="view-all-link">
                        Lihat Semua
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Filter Dropdown -->
            <div class="filter-dropdown" id="transactionFilterDropdown">
                <div class="filter-header">
                    <h3>Filter Transaksi</h3>
                    <button class="close-filter" id="closeTransactionFilter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                <div class="filter-content">
                    <div class="filter-group">
                        <label for="transactionType">Jenis Transaksi</label>
                        <select id="transactionType" class="filter-select">
                            <option value="">Semua</option>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="transactionDateRange">Rentang Tanggal</label>
                        <div class="date-range-inputs">
                            <input type="date" id="startDate" class="filter-input">
                            <span>sampai</span>
                            <input type="date" id="endDate" class="filter-input">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label for="transactionAccount">Rekening</label>
                        <select id="transactionAccount" class="filter-select">
                            <option value="">Semua Rekening</option>
                            @foreach($accounts ?? [] as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button class="btn btn-secondary" id="resetFilter">Reset</button>
                        <button class="btn btn-primary" id="applyFilter">Terapkan</button>
                    </div>
                </div>
            </div>
            
            <div class="transactions-container" id="transactionsContainer">
                @foreach($recentTransactions->take(6) as $transaction)
                    <div class="transaction-card {{ $transaction->type }}" data-type="{{ $transaction->type }}" data-date="{{ $transaction->date->format('Y-m-d') }}" data-account="{{ $transaction->account_id }}">
                        <div class="transaction-icon {{ $transaction->type }}">
                            @if($transaction->type == 'income')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                            @elseif($transaction->type == 'expense')
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    <polyline points="17 18 23 18 23 12"></polyline>
                                </svg>
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                    <path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-title">{{ $transaction->description ?: 'Tidak ada keterangan' }}</div>
                            <div class="transaction-meta">
                                <span class="transaction-date">{{ $transaction->date->format('d M Y') }}</span>
                                <span class="transaction-account">{{ $transaction->account->name }}</span>
                            </div>
                        </div>
                        <div class="transaction-amount {{ $transaction->type }}">
                            Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                        </div>
                        <div class="transaction-actions">
                            @if($transaction->type == 'income')
                                <a href="{{ route('incomes.show', $transaction) }}" class="action-button view-button" title="Lihat Detail">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8 11 8 11 8 11-4 4-8 11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <a href="{{ route('incomes.edit', $transaction) }}" class="action-button edit-button" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                            @elseif($transaction->type == 'expense')
                                <a href="{{ route('expenses.show', $transaction) }}" class="action-button view-button" title="Lihat Detail">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8 11 8 11 8 11-4 4-8 11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                <a href="{{ route('expenses.edit', $transaction) }}" class="action-button edit-button" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </a>
                            @else
                                @if($transaction->transfer_id)
                                    <a href="{{ route('transfers.show', $transaction->transfer) }}" class="action-button view-button" title="Lihat Detail">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8 11 8 11 8 11-4 4-8 11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="{{ route('transfers.edit', $transaction->transfer) }}" class="action-button edit-button" title="Edit">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Quick Actions Section -->
        <section class="actions-section">
            <div class="section-header">
                <div class="section-title-container">
                    <h2 class="section-title">Aksi Cepat</h2>
                    <p class="section-subtitle">Tugas umum dan pintasan</p>
                </div>
            </div>
            <div class="actions-container">
                <a href="{{ route('incomes.create') }}" class="action-card">
                    <div class="action-icon income">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tambah Pemasukan</h3>
                        <p class="action-description">Catat transaksi pemasukan baru</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('expenses.create') }}" class="action-card">
                    <div class="action-icon expense">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Tambah Pengeluaran</h3>
                        <p class="action-description">Catat transaksi pengeluaran baru</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('transfers.create') }}" class="action-card">
                    <div class="action-icon transfer">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                            <path d="M20 12v7a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-7"></path>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Transfer Dana</h3>
                        <p class="action-description">Pindahkan uang antar rekening</p>
                    </div>
                    <div class="action-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>

                <a href="{{ route('reports.index') }}" class="action-card">
                    <div class="action-icon report">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                    <div class="action-content">
                        <h3 class="action-title">Lihat Laporan</h3>
                        <p class="action-description">Buat laporan keuangan</p>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sparkline configuration
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

    // Balance Sparkline
    const balanceCtx = document.getElementById('balanceSparkline')?.getContext('2d');
    if (balanceCtx) {
        const gradient = balanceCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(balanceCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [1200000, 1300000, 1250000, 1400000, 1350000, 1500000],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Income Sparkline
    const incomeCtx = document.getElementById('incomeSparkline')?.getContext('2d');
    if (incomeCtx) {
        new Chart(incomeCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [1200000, 1300000, 1250000, 1400000, 1350000, 1500000],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Expense Sparkline
    const expenseCtx = document.getElementById('expenseSparkline')?.getContext('2d');
    if (expenseCtx) {
        new Chart(expenseCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', ''],
                datasets: [{
                    data: [800000, 900000, 850000, 950000, 900000, 1000000],
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderColor: '#EF4444',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Cash Flow Chart
    const cashFlowCtx = document.getElementById('cashFlowChart').getContext('2d');
    const incomeGradient = cashFlowCtx.createLinearGradient(0, 0, 0, 400);
    incomeGradient.addColorStop(0, 'rgba(34, 197, 94, 0.3)');
    incomeGradient.addColorStop(1, 'rgba(34, 197, 94, 0.01)');

    const expenseGradient = cashFlowCtx.createLinearGradient(0, 0, 0, 400);
    expenseGradient.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
    expenseGradient.addColorStop(1, 'rgba(239, 68, 68, 0.01)');

    const cashFlowChart = new Chart(cashFlowCtx, {
        type: 'line',
        data: {
            labels: @json(collect($cashFlowData)->pluck('month')),
            datasets: [{
                label: 'Pemasukan',
                data: @json(collect($cashFlowData)->pluck('income')),
                borderColor: '#22C55E',
                backgroundColor: incomeGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#22C55E',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7
            }, {
                label: 'Pengeluaran',
                data: @json(collect($cashFlowData)->pluck('expense')),
                borderColor: '#EF4444',
                backgroundColor: expenseGradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#EF4444',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        boxWidth: 12,
                        usePointStyle: true,
                        padding: 20,
                        font: { size: 12, weight: '500' }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { 
                        color: '#6B7280',
                        font: { size: 11 }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { 
                        color: 'rgba(0, 0, 0, 0.03)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: { size: 11 },
                        callback: function(value) {
                            if (value >= 1000000) {
                                return 'Rp' + (value / 1000000).toFixed(1) + 'jt';
                            }
                            return 'Rp' + (value / 1000).toFixed(0) + 'rb';
                        }
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');

    @if($topExpenseCategories->count() > 0)
        const categoryLabels = @json($topExpenseCategories->map(function($cat) {
            return $cat->category ? $cat->category->name : 'Lainnya';
        }));
        const categoryData = @json($topExpenseCategories->pluck('total'));
    @else
        const categoryLabels = ['Tidak Ada Data'];
        const categoryData = [1];
    @endif

    const categoryChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: [
                    '#4F46E5',
                    '#7C3AED',
                    '#EC4899',
                    '#F59E0B',
                    '#10B981',
                    '#06B6D4'
                ],
                borderWidth: 0,
                hoverBorderWidth: 3,
                hoverBorderColor: '#ffffff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.9)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });

    // Period Toggle functionality
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const siblings = this.parentElement.querySelectorAll('.toggle-btn');
            siblings.forEach(sibling => sibling.classList.remove('active'));
            this.classList.add('active');
            
            // Update chart based on selected period
            const period = this.getAttribute('data-period');
            const chartType = this.getAttribute('data-chart');
            
            if (chartType === 'cashFlow') {
                updateCashFlowChart(period);
            } else if (chartType === 'category') {
                updateCategoryChart(period);
            }
        });
    });

    // Transaction Filter functionality
    const transactionFilterBtn = document.getElementById('transactionFilterBtn');
    const transactionFilterDropdown = document.getElementById('transactionFilterDropdown');
    const closeTransactionFilter = document.getElementById('closeTransactionFilter');
    const applyFilter = document.getElementById('applyFilter');
    const resetFilter = document.getElementById('resetFilter');
    
    if (transactionFilterBtn) {
        transactionFilterBtn.addEventListener('click', function() {
            transactionFilterDropdown.style.display = 'block';
        });
    }
    
    if (closeTransactionFilter) {
        closeTransactionFilter.addEventListener('click', function() {
            transactionFilterDropdown.style.display = 'none';
        });
    }
    
    if (resetFilter) {
        resetFilter.addEventListener('click', function() {
            document.getElementById('transactionType').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            document.getElementById('transactionAccount').value = '';
            applyTransactionFilter();
        });
    }
    
    if (applyFilter) {
        applyFilter.addEventListener('click', function() {
            applyTransactionFilter();
            transactionFilterDropdown.style.display = 'none';
        });
    }
    
    function applyTransactionFilter() {
        const type = document.getElementById('transactionType').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const account = document.getElementById('transactionAccount').value;
        
        const transactionCards = document.querySelectorAll('.transaction-card');
        
        transactionCards.forEach(card => {
            let show = true;
            
            // Filter by type
            if (type && card.getAttribute('data-type') !== type) {
                show = false;
            }
            
            // Filter by date range
            if (startDate && card.getAttribute('data-date') < startDate) {
                show = false;
            }
            
            if (endDate && card.getAttribute('data-date') > endDate) {
                show = false;
            }
            
            // Filter by account
            if (account && card.getAttribute('data-account') !== account) {
                show = false;
            }
            
            card.style.display = show ? 'flex' : 'none';
        });
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (transactionFilterDropdown && 
            !transactionFilterDropdown.contains(event.target) && 
            event.target !== transactionFilterBtn) {
            transactionFilterDropdown.style.display = 'none';
        }
    });

    // Update chart functions (mock implementations)
    function updateCashFlowChart(period) {
        // In a real implementation, you would fetch new data based on period
        // and update chart with new data
        console.log('Updating cash flow chart with period:', period);
        
        // Mock data for demonstration
        let labels, incomeData, expenseData;
        
        if (period === '6m') {
            labels = @json(collect($cashFlowData)->take(6)->pluck('month'));
            incomeData = @json(collect($cashFlowData)->take(6)->pluck('income'));
            expenseData = @json(collect($cashFlowData)->take(6)->pluck('expense'));
        } else if (period === '1y') {
            labels = @json(collect($cashFlowData)->take(12)->pluck('month'));
            incomeData = @json(collect($cashFlowData)->take(12)->pluck('income'));
            expenseData = @json(collect($cashFlowData)->take(12)->pluck('expense'));
        } else { // all
            labels = @json(collect($cashFlowData)->pluck('month'));
            incomeData = @json(collect($cashFlowData)->pluck('income'));
            expenseData = @json(collect($cashFlowData)->pluck('expense'));
        }
        
        cashFlowChart.data.labels = labels;
        cashFlowChart.data.datasets[0].data = incomeData;
        cashFlowChart.data.datasets[1].data = expenseData;
        cashFlowChart.update();
    }
    
    function updateCategoryChart(period) {
        // In a real implementation, you would fetch new data based on period
        // and update chart with new data
        console.log('Updating category chart with period:', period);
        
        // For now, just log the action
        // In a real app, you would make an AJAX call to get new data
    }

    // Handle window resize for responsive charts
    window.addEventListener('resize', function() {
        Chart.helpers.each(Chart.instances, function(instance) {
            instance.resize();
        });
    });

    // Hide loading overlay when page is fully loaded
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.style.opacity = '0';
                setTimeout(function() {
                    loadingOverlay.style.display = 'none';
                }, 300);
            }
        }, 500); // Small delay to ensure everything is rendered
    });
});
</script>

<style>
/* ===== BASE STYLES ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: #1F2937;
    background-color: #F9FAFB;
    line-height: 1.6;
}

/* ===== DASHBOARD CONTAINER ===== */
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
    position: relative;
}

/* ===== HEADER ===== */
.dashboard-header {
    margin-bottom: 60px; /* Increased margin to prevent overlap */
    position: relative;
    z-index: 100; /* Highest z-index */
    background: transparent; /* Ensure header background is transparent */
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    position: relative;
    z-index: 100;
}

.header-left {
    flex: 1;
    position: relative;
    z-index: 100;
}

.header-badge {
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
    position: relative;
    z-index: 100;
}

.dashboard-title {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
    position: relative;
    z-index: 100;
}

.dashboard-subtitle {
    font-size: 16px;
    color: #6B7280;
    position: relative;
    z-index: 100;
}

.branch-info {
    margin-top: 8px;
    position: relative;
    z-index: 100;
}

.branch-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    position: relative;
    z-index: 100;
}

.branch-badge.all-branches {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.date-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #E5E7EB;
    position: relative;
    z-index: 100;
}

.date-content {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
}

.date-day {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.date-details {
    display: flex;
    flex-direction: column;
}

.date-month {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.date-year {
    font-size: 12px;
    color: #9CA3AF;
}

/* ===== METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
}

.metrics-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.metric-card {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #E5E7EB;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
}

.metric-card.primary::before {
    background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
}

.metric-card.success::before {
    background: linear-gradient(90deg, #22C55E 0%, #10B981 100%);
}

.metric-card.danger::before {
    background: linear-gradient(90deg, #EF4444 0%, #DC2626 100%);
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
}

.metric-card.primary .metric-icon {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
}

.metric-card.success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-card.danger .metric-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
}

.metric-title {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
}

.metric-content {
    margin-bottom: 16px;
}

.metric-value {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
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

.metric-change {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 6px;
}

.metric-change.positive {
    color: #22C55E;
    background: rgba(34, 197, 94, 0.1);
}

.metric-change.negative {
    color: #EF4444;
    background: rgba(239, 68, 68, 0.1);
}

.metric-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #22C55E;
}

.metric-chart {
    height: 60px;
}

/* ===== CHARTS SECTION ===== */
.charts-section {
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
}

.charts-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.chart-card {
    background: #FFFFFF;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #E5E7EB;
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 24px;
    border-bottom: 1px solid #F3F4F6;
}

.card-title-section {
    flex: 1;
}

.card-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.card-status {
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

.status-dot.active {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.card-controls {
    display: flex;
    align-items: center;
}

.period-toggle {
    display: flex;
    background: #F3F4F6;
    border-radius: 8px;
    padding: 2px;
}

.toggle-btn {
    padding: 6px 12px;
    border: none;
    background: transparent;
    color: #6B7280;
    font-size: 12px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.toggle-btn.active {
    background: #FFFFFF;
    color: #111827;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-body {
    padding: 24px;
}

.chart-wrapper {
    height: 300px;
}

.card-footer {
    padding: 20px 24px;
    border-top: 1px solid #F3F4F6;
}

.chart-stats {
    display: flex;
    justify-content: space-between;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-bottom: 8px;
}

.stat-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.stat-indicator.positive {
    background: #22C55E;
}

.stat-indicator.negative {
    background: #EF4444;
}

.stat-indicator.neutral {
    background: #4F46E5;
}

.stat-label {
    font-size: 12px;
    color: #6B7280;
    font-weight: 500;
}

.stat-value {
    font-size: 16px;
    font-weight: 700;
}

.stat-value.positive {
    color: #22C55E;
}

.stat-value.negative {
    color: #EF4444;
}

.stat-value.neutral {
    color: #4F46E5;
}

/* ===== CATEGORY LIST ===== */
.category-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.category-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.category-info {
    flex-grow: 1;
}

.category-name {
    font-size: 13px;
    color: #374151;
    font-weight: 500;
    margin-bottom: 4px;
}

.category-progress {
    width: 100%;
    height: 4px;
    background: #F3F4F6;
    border-radius: 2px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
    border-radius: 2px;
    transition: width 0.3s ease;
}

.category-percentage {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    min-width: 40px;
    text-align: right;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 32px;
    color: #9CA3AF;
}

.empty-state svg {
    margin-bottom: 12px;
}

.empty-state span {
    font-size: 14px;
}

/* ===== TRANSACTIONS SECTION ===== */
.transactions-section {
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-title-container {
    flex: 1;
}

.section-title {
    font-size: 20px;
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

.filter-button {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #FFFFFF;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    color: #4B5563;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-button:hover {
    border-color: #4F46E5;
    color: #4F46E5;
    background: rgba(79, 70, 229, 0.05);
}

.view-all-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #4F46E5;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.view-all-link:hover {
    color: #4338CA;
}

.transactions-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 16px;
}

.transaction-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
    position: relative;
}

.transaction-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
}

.transaction-card.income::before {
    background: #22C55E;
}

.transaction-card.expense::before {
    background: #EF4444;
}

.transaction-card.transfer::before {
    background: #4F46E5;
}

.transaction-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.transaction-icon.income {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.transaction-icon.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.transaction-icon.transfer {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.transaction-details {
    flex-grow: 1;
}

.transaction-title {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.transaction-meta {
    display: flex;
    gap: 12px;
}

.transaction-date,
.transaction-account {
    font-size: 12px;
    color: #9CA3AF;
}

.transaction-amount {
    font-size: 16px;
    font-weight: 700;
    margin-right: 8px;
}

.transaction-amount.income {
    color: #22C55E;
}

.transaction-amount.expense {
    color: #EF4444;
}

.transaction-amount.transfer {
    color: #4F46E5;
}

.transaction-actions {
    display: flex;
    gap: 8px;
}

.action-button {
    width: 32px;
    height: 32px;
    border-radius: 6px;
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

.action-button:hover {
    background: #E5E7EB;
    color: #374151;
}

.action-button.view-button:hover {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.action-button.edit-button:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

/* ===== FILTER DROPDOWN ===== */
.filter-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    width: 320px;
    background: #FFFFFF;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid #E5E7EB;
    z-index: 1000; /* Very high z-index */
    margin-top: 8px;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #F3F4F6;
}

.filter-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.close-filter {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    background: #F3F4F6;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.close-filter:hover {
    background: #E5E7EB;
    color: #374151;
}

.filter-content {
    padding: 20px;
}

.filter-group {
    margin-bottom: 20px;
}

.filter-group:last-child {
    margin-bottom: 0;
}

.filter-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
}

.filter-select,
.filter-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #D1D5DB;
    border-radius: 6px;
    font-size: 14px;
    color: #374151;
    background: #FFFFFF;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.date-range-inputs {
    display: flex;
    align-items: center;
    gap: 8px;
}

.date-range-inputs span {
    font-size: 14px;
    color: #6B7280;
}

.filter-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-secondary {
    background: #F3F4F6;
    border: 1px solid #D1D5DB;
    color: #4B5563;
}

.btn-secondary:hover {
    background: #E5E7EB;
}

.btn-primary {
    background: #4F46E5;
    border: 1px solid #4F46E5;
    color: #FFFFFF;
}

.btn-primary:hover {
    background: #4338CA;
}

/* ===== ACTIONS SECTION ===== */
.actions-section {
    margin-bottom: 32px;
    position: relative;
    z-index: 1;
}

.actions-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
}

.action-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
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

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.action-card:nth-child(1) {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.action-card:nth-child(2) {
    --color-start: #EF4444;
    --color-end: #DC2626;
}

.action-card:nth-child(3) {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.action-card:nth-child(4) {
    --color-start: #F59E0B;
    --color-end: #D97706;
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

.action-icon.income {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.action-icon.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.action-icon.transfer {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.action-icon.report {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
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
    font-size: 13px;
    color: #6B7280;
}

.action-arrow {
    color: #9CA3AF;
    transition: all 0.2s ease;
}

.action-card:hover .action-arrow {
    color: #4F46E5;
    transform: translateX(4px);
}

/* ===== LOADING OVERLAY ===== */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(2px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
}

.loading-content {
    text-align: center;
    color: #374151;
}

.loading-spinner {
    margin-bottom: 16px;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #E5E7EB;
    border-top: 4px solid #4F46E5;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-text {
    font-size: 16px;
    font-weight: 500;
    color: #6B7280;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-container {
        padding: 20px;
    }
    
    .charts-container {
        grid-template-columns: 1fr;
    }
    
    .transactions-container {
        grid-template-columns: 1fr;
    }
    
    .filter-dropdown {
        width: 280px;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .dashboard-title {
        font-size: 28px;
    }
    
    .metrics-container {
        grid-template-columns: 1fr;
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .chart-stats {
        flex-direction: column;
        gap: 16px;
    }
    
    .actions-container {
        grid-template-columns: 1fr;
    }
    
    .transaction-card {
        flex-wrap: wrap;
    }
    
    .transaction-details {
        width: 100%;
        margin-bottom: 8px;
    }
    
    .filter-dropdown {
        width: 100%;
        left: 0;
        right: 0;
    }
}

@media (max-width: 480px) {
    .dashboard-container {
        padding: 12px;
    }
    
    .dashboard-title {
        font-size: 24px;
    }
    
    .metric-value {
        font-size: 24px;
    }
    
    .chart-wrapper {
        height: 250px;
    }
    
    .date-content {
        padding: 6px 12px;
    }
    
    .date-day {
        font-size: 24px;
    }
}
</style>
@endsection