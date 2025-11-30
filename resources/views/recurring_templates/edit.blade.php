@extends('layouts.app')

@section('title', 'Edit Template Berulang')
@section('page-title', 'Edit Template Berulang')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit Template</span>
                </div>
                <h1 class="page-title">Edit Template Berulang</h1>
                <p class="page-subtitle">Perbarui template transaksi berulang Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('recurring-templates.index') }}" class="btn-secondary">
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
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Template Status -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Status</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $recurringTemplate->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Template saat ini</span>
                            <div class="metric-trend {{ $recurringTemplate->is_active ? 'positive' : 'negative' }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    @if($recurringTemplate->is_active)
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    @else
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    @endif
                                </svg>
                                <span>{{ $recurringTemplate->is_active ? 'Berjalan' : 'Dihentikan' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="statusSparkline"></canvas>
                    </div>
                </div>

                <!-- Transaction Type -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                @if($recurringTemplate->type === 'expense')
                                    <line x1="6" y1="12" x2="18" y2="12"></line>
                                @endif
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Tipe</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ ucfirst($recurringTemplate->type) }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Jenis transaksi</span>
                            <div class="metric-trend {{ $recurringTemplate->type === 'income' ? 'positive' : 'negative' }}">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    @if($recurringTemplate->type === 'income')
                                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    @else
                                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    @endif
                                </svg>
                                <span>{{ $recurringTemplate->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="typeSparkline"></canvas>
                    </div>
                </div>

                <!-- Frequency -->
                <div class="metric-card metric-warning">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Frekuensi</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ ucfirst($recurringTemplate->frequency) }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Perulangan</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>Terjadwal</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="frequencySparkline"></canvas>
                    </div>
                </div>

                <!-- Next Date -->
                <div class="metric-card metric-info">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Tanggal Berikutnya</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $recurringTemplate->next_date->format('d M') }}</div>
                        <div class="metric-details">
                            <span class="metric-label">{{ $recurringTemplate->next_date->format('Y') }}</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>{{ $recurringTemplate->next_date->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="dateSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <div class="content-grid">
            <!-- Form Section -->
            <div class="form-section">
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </div>
                        <h2 class="form-title">Form Edit Template</h2>
                        <p class="form-subtitle">Perbarui informasi template transaksi berulang</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('recurring-templates.update', $recurringTemplate) }}" id="templateForm">
                            @csrf
                            @method('PUT')

                            <!-- Transaction Type -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="17 1 21 5 17 9"></polyline>
                                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                        <polyline points="7 23 3 19 7 15"></polyline>
                                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                    </svg>
                                    Tipe Transaksi <span class="required">*</span>
                                </label>
                                <div class="type-selector">
                                    <div class="type-card {{ old('type', $recurringTemplate->type) == 'income' ? 'selected' : '' }}"
                                         onclick="selectType('income', this)">
                                        <div class="type-icon income-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="19" x2="12" y2="5"></line>
                                                <polyline points="5 12 12 5 19 12"></polyline>
                                            </svg>
                                        </div>
                                        <div class="type-details">
                                            <div class="type-name">Pemasukan</div>
                                            <div class="type-desc">Uang masuk ke rekening</div>
                                        </div>
                                        <input type="radio" name="type" value="income"
                                               {{ old('type', $recurringTemplate->type) == 'income' ? 'checked' : '' }}
                                               style="display: none;" required>
                                    </div>
                                    <div class="type-card {{ old('type', $recurringTemplate->type) == 'expense' ? 'selected' : '' }}"
                                         onclick="selectType('expense', this)">
                                        <div class="type-icon expense-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <polyline points="19 12 12 19 5 12"></polyline>
                                            </svg>
                                        </div>
                                        <div class="type-details">
                                            <div class="type-name">Pengeluaran</div>
                                            <div class="type-desc">Uang keluar dari rekening</div>
                                        </div>
                                        <input type="radio" name="type" value="expense"
                                               {{ old('type', $recurringTemplate->type) == 'expense' ? 'checked' : '' }}
                                               style="display: none;" required>
                                    </div>
                                </div>
                                @error('type')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Template Name -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    Nama Template <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name', $recurringTemplate->name) }}"
                                       placeholder="Contoh: Gaji Bulanan, Tagihan Listrik, Langganan Netflix">
                                @error('name')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Account Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    Rekening Terkait <span class="required">*</span>
                                </label>
                                <div class="account-grid">
                                    @foreach($accounts as $account)
                                        <div class="account-card {{ old('account_id', $recurringTemplate->account_id) == $account->id ? 'selected' : '' }}"
                                             onclick="selectAccount({{ $account->id }}, this)">
                                            <div class="account-icon">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                </svg>
                                            </div>
                                            <div class="account-details">
                                                <div class="account-name">{{ $account->name }}</div>
                                                <div class="account-balance">Rp{{ number_format($account->balance, 0, ',', '.') }}</div>
                                            </div>
                                            <input type="radio" name="account_id" value="{{ $account->id }}"
                                                   {{ old('account_id', $recurringTemplate->account_id) == $account->id ? 'checked' : '' }}
                                                   style="display: none;" required>
                                        </div>
                                    @endforeach
                                </div>
                                @error('account_id')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Category Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                    </svg>
                                    Kategori Transaksi <span class="required">*</span>
                                </label>
                                <div class="category-grid">
                                    @foreach($categories as $category)
                                        <div class="category-tag {{ old('category_id', $recurringTemplate->category_id) == $category->id ? 'selected' : '' }}"
                                             onclick="selectCategory({{ $category->id }}, this)">
                                            {{ $category->name }}
                                            <input type="radio" name="category_id" value="{{ $category->id }}"
                                                   {{ old('category_id', $recurringTemplate->category_id) == $category->id ? 'checked' : '' }}
                                                   style="display: none;" required>
                                        </div>
                                    @endforeach
                                </div>
                                @error('category_id')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Amount Input -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Jumlah Transaksi <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-prefix">Rp</div>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                           name="amount" value="{{ old('amount', $recurringTemplate->amount) }}" placeholder="0"
                                           min="1" step="0.01" required oninput="updateAmountDisplay()">
                                </div>
                                @error('amount')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                                
                                <div class="amount-display" id="amountDisplay" style="display: none;">
                                    <div class="amount-label">Jumlah yang akan dicatat setiap periode:</div>
                                    <div class="amount-value">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        <span id="formattedAmount">Rp{{ number_format($recurringTemplate->amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Frequency Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                    Frekuensi Perulangan <span class="required">*</span>
                                </label>
                                <div class="frequency-grid">
                                    <div class="frequency-card {{ old('frequency', $recurringTemplate->frequency) == 'daily' ? 'selected' : '' }}"
                                         onclick="selectFrequency('daily', this)">
                                        <div class="frequency-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div class="frequency-name">Harian</div>
                                        <input type="radio" name="frequency" value="daily"
                                               {{ old('frequency', $recurringTemplate->frequency) == 'daily' ? 'checked' : '' }}
                                               style="display: none;" required>
                                    </div>
                                    <div class="frequency-card {{ old('frequency', $recurringTemplate->frequency) == 'weekly' ? 'selected' : '' }}"
                                         onclick="selectFrequency('weekly', this)">
                                        <div class="frequency-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div class="frequency-name">Mingguan</div>
                                        <input type="radio" name="frequency" value="weekly"
                                               {{ old('frequency', $recurringTemplate->frequency) == 'weekly' ? 'checked' : '' }}
                                               style="display: none;" required>
                                    </div>
                                    <div class="frequency-card {{ old('frequency', $recurringTemplate->frequency) == 'monthly' ? 'selected' : '' }}"
                                         onclick="selectFrequency('monthly', this)">
                                        <div class="frequency-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div class="frequency-name">Bulanan</div>
                                        <input type="radio" name="frequency" value="monthly"
                                               {{ old('frequency', $recurringTemplate->frequency) == 'monthly' ? 'checked' : '' }}
                                               style="display: none;" required>
                                    </div>
                                    <div class="frequency-card {{ old('frequency', $recurringTemplate->frequency) == 'yearly' ? 'selected' : '' }}"
                                         onclick="selectFrequency('yearly', this)">
                                        <div class="frequency-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div class="frequency-name">Tahunan</div>
                                        <input type="radio" name="frequency" value="yearly"
                                               {{ old('frequency', $recurringTemplate->frequency) == 'yearly' ? 'checked' : '' }}
                                               style="display: none;" required>
                                    </div>
                                </div>
                                @error('frequency')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Next Date Input -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Tanggal Mulai <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control @error('next_date') is-invalid @enderror"
                                       name="next_date" value="{{ old('next_date', $recurringTemplate->next_date->format('Y-m-d')) }}" required>
                                @error('next_date')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Status Toggle -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                    </svg>
                                    Status Template
                                </label>
                                <div class="status-toggle">
                                    <div class="toggle-switch">
                                        <input class="toggle-input" type="checkbox" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $recurringTemplate->is_active) ? 'checked' : '' }}>
                                        <label class="toggle-label" for="is_active">
                                            <span class="toggle-slider"></span>
                                            <span class="status-text">{{ old('is_active', $recurringTemplate->is_active) ? 'Aktif' : 'Nonaktif' }}</span>
                                        </label>
                                    </div>
                                    <small class="form-text">
                                        Template yang tidak aktif tidak akan membuat transaksi otomatis
                                    </small>
                                </div>
                            </div>

                            <!-- Description Input -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Keterangan (Opsional)
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          name="description" rows="3"
                                          placeholder="Tambahkan keterangan untuk template ini">{{ old('description', $recurringTemplate->description) }}</textarea>
                                @error('description')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="{{ route('recurring-templates.show', $recurringTemplate) }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                    Batal
                                </a>
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    Perbarui Template
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tips Sidebar -->
            <div class="tips-section">
                <div class="tips-container">
                    <div class="tips-header">
                        <h3 class="tips-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Tips Edit Template
                        </h3>
                    </div>

                    <div class="tips-content">
                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 11l3 3L22 4"></path>
                                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Nama yang Deskriptif</h4>
                                <p>Beri nama yang jelas dan mudah diingat untuk setiap template agar mudah dikenali di masa depan.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Pilih Frekuensi yang Tepat</h4>
                                <p>Sesuaikan frekuensi dengan siklus pembayaran atau penerimaan yang sebenarnya terjadi.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Periksa Tanggal Mulai</h4>
                                <p>Pastikan tanggal mulai tepat dengan saat transaksi pertama kali akan terjadi.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Tambahkan Keterangan</h4>
                                <p>Keterangan detail akan membantu Anda mengingat detail transaksi di masa depan.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Perhatikan Perubahan</h4>
                                <p>Mengubah template tidak akan memengaruhi transaksi yang sudah dibuat sebelumnya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Chart.js for sparklines -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function selectType(type, element) {
    // Remove selected class from all type cards
    document.querySelectorAll('.type-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    element.classList.add('selected');

    // Check radio button
    element.querySelector('input[type="radio"]').checked = true;
}

function selectAccount(accountId, element) {
    // Remove selected class from all account cards
    document.querySelectorAll('.account-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    element.classList.add('selected');

    // Check radio button
    element.querySelector('input[type="radio"]').checked = true;
}

function selectCategory(categoryId, element) {
    // Remove selected class from all category tags
    document.querySelectorAll('.category-tag').forEach(tag => {
        tag.classList.remove('selected');
    });

    // Add selected class to clicked tag
    element.classList.add('selected');

    // Check radio button
    element.querySelector('input[type="radio"]').checked = true;
}

function selectFrequency(frequency, element) {
    // Remove selected class from all frequency cards
    document.querySelectorAll('.frequency-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    element.classList.add('selected');

    // Check radio button
    element.querySelector('input[type="radio"]').checked = true;
}

function updateAmountDisplay() {
    const amountInput = document.querySelector('input[name="amount"]');
    const amountDisplay = document.getElementById('amountDisplay');
    const formattedAmount = document.getElementById('formattedAmount');

    if (amountInput.value) {
        const amount = parseFloat(amountInput.value);
        if (!isNaN(amount) && amount > 0) {
            formattedAmount.textContent = 'Rp' + amount.toLocaleString('id-ID');
            amountDisplay.style.display = 'block';
        } else {
            amountDisplay.style.display = 'none';
        }
    } else {
        amountDisplay.style.display = 'none';
    }
}

// Initialize on page load
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

    // Status Sparkline
    const statusCtx = document.getElementById('statusSparkline')?.getContext('2d');
    if (statusCtx) {
        const gradient = statusCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(statusCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [1, 1, 0, 1, 1, {{ $recurringTemplate->is_active ? '1' : '0' }}],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Type Sparkline
    const typeCtx = document.getElementById('typeSparkline')?.getContext('2d');
    if (typeCtx) {
        new Chart(typeCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [{{ $recurringTemplate->type === 'income' ? '1' : '0' }}, 0, 0, 0, 0, 1],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Frequency Sparkline
    const frequencyCtx = document.getElementById('frequencySparkline')?.getContext('2d');
    if (frequencyCtx) {
        const frequencyData = {
            'daily': [5, 5, 5, 5, 5, 5],
            'weekly': [0, 5, 0, 0, 0, 5],
            'monthly': [0, 0, 5, 0, 0, 0],
            'yearly': [0, 0, 0, 0, 0, 5]
        };
        
        new Chart(frequencyCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: frequencyData['{{ $recurringTemplate->frequency }}'],
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Date Sparkline
    const dateCtx = document.getElementById('dateSparkline')?.getContext('2d');
    if (dateCtx) {
        new Chart(dateCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [3, 5, 4, 6, 5, 7],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Status toggle functionality
    const statusToggle = document.getElementById('is_active');
    const statusText = document.querySelector('.status-text');

    function updateStatusText() {
        statusText.textContent = statusToggle.checked ? 'Aktif' : 'Nonaktif';
    }

    statusToggle.addEventListener('change', updateStatusText);
    updateStatusText(); // Initialize

    // Auto-select type if pre-selected
    const selectedType = document.querySelector('input[name="type"]:checked');
    if (selectedType) {
        selectedType.closest('.type-card').classList.add('selected');
    }

    // Auto-select account if pre-selected
    const selectedAccount = document.querySelector('input[name="account_id"]:checked');
    if (selectedAccount) {
        selectedAccount.closest('.account-card').classList.add('selected');
    }

    // Auto-select category if pre-selected
    const selectedCategory = document.querySelector('input[name="category_id"]:checked');
    if (selectedCategory) {
        selectedCategory.closest('.category-tag').classList.add('selected');
    }

    // Auto-select frequency if pre-selected
    const selectedFrequency = document.querySelector('input[name="frequency"]:checked');
    if (selectedFrequency) {
        selectedFrequency.closest('.frequency-card').classList.add('selected');
    }

    // Initialize amount display
    updateAmountDisplay();
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
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
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
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
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

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
}

/* ===== FORM SECTION ===== */
.form-section {
    margin-bottom: 48px;
}

.form-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.form-header {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    padding: 32px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.form-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #1E40AF;
    margin-bottom: 8px;
}

.form-subtitle {
    font-size: 14px;
    color: #3B82F6;
    margin: 0;
}

.form-body {
    padding: 32px;
}

.form-group {
    margin-bottom: 28px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
}

.required {
    color: #EF4444;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix {
    position: absolute;
    left: 16px;
    font-weight: 600;
    color: #6B7280;
    z-index: 1;
}

.form-control {
    width: 100%;
    padding: 14px 16px 14px 40px;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.form-control:focus {
    outline: none;
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control::placeholder {
    color: #9CA3AF;
}

.form-control.is-invalid {
    border-color: #EF4444;
}

.form-error {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
    font-size: 13px;
    color: #EF4444;
}

.form-error svg {
    flex-shrink: 0;
}

/* ===== TYPE SELECTION ===== */
.type-selector {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
}

.type-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.type-card:hover {
    border-color: #3B82F6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.type-card.selected {
    border-color: #3B82F6;
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.type-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.income-icon {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.expense-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.type-details {
    flex-grow: 1;
}

.type-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.type-desc {
    font-size: 12px;
    color: #6B7280;
}

/* ===== ACCOUNT SELECTION ===== */
.account-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.account-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.account-card:hover {
    border-color: #3B82F6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.account-card.selected {
    border-color: #3B82F6;
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.account-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.account-details {
    flex-grow: 1;
}

.account-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.account-balance {
    font-size: 12px;
    color: #6B7280;
}

/* ===== CATEGORY SELECTION ===== */
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}

.category-tag {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #FFFFFF;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}

.category-tag:hover {
    border-color: #3B82F6;
    background: #EFF6FF;
}

.category-tag.selected {
    border-color: #3B82F6;
    background: #DBEAFE;
    color: #1E40AF;
}

/* ===== FREQUENCY SELECTION ===== */
.frequency-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 16px;
}

.frequency-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 16px 12px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.frequency-card:hover {
    border-color: #3B82F6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.frequency-card.selected {
    border-color: #3B82F6;
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

.frequency-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.frequency-name {
    font-weight: 600;
    color: #111827;
    font-size: 14px;
}

/* ===== AMOUNT DISPLAY ===== */
.amount-display {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    border: 2px solid #3B82F6;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-top: 16px;
}

.amount-label {
    font-size: 14px;
    color: #1E40AF;
    font-weight: 600;
    margin-bottom: 8px;
}

.amount-value {
    font-size: 24px;
    font-weight: 700;
    color: #1E40AF;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.amount-value svg {
    color: #3B82F6;
}

/* ===== STATUS TOGGLE ===== */
.status-toggle {
    margin-top: 8px;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 30px;
}

.toggle-input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-label {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #E5E7EB;
    transition: 0.4s;
    border-radius: 34px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 10px;
}

.toggle-slider {
    position: absolute;
    height: 22px;
    width: 22px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-input:checked + .toggle-label {
    background-color: #22C55E;
}

.toggle-input:checked + .toggle-label .toggle-slider {
    transform: translateX(30px);
}

.status-text {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-left: 70px;
}

.form-text {
    font-size: 13px;
    color: #6B7280;
    margin-top: 8px;
    display: block;
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 24px;
    border-top: 1px solid #E5E7EB;
    margin-top: 32px;
}

/* ===== TIPS SECTION ===== */
.tips-section {
    margin-bottom: 48px;
}

.tips-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    position: sticky;
    top: 32px;
}

.tips-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.tips-title {
    font-size: 18px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.tips-content {
    padding: 24px;
}

.tip-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
}

.tip-item:last-child {
    margin-bottom: 0;
}

.tip-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tip-text h4 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.tip-text p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
    line-height: 1.5;
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
    
    .tips-container {
        position: static;
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
    
    .form-body {
        padding: 24px;
    }
    
    .type-selector {
        grid-template-columns: 1fr;
    }
    
    .frequency-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .account-grid {
        grid-template-columns: 1fr;
    }
    
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .form-actions {
        flex-direction: column;
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
    
    .metric-value {
        font-size: 28px;
    }
    
    .form-body {
        padding: 16px;
    }
    
    .form-header {
        padding: 24px;
    }
    
    .tips-content {
        padding: 16px;
    }
    
    .tip-item {
        flex-direction: column;
        gap: 12px;
    }
    
    .frequency-grid {
        grid-template-columns: 1fr;
    }
    
    .category-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection