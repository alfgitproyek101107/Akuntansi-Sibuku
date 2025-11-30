@extends('layouts.app')

@section('title', 'Edit Pemasukan')
@section('page-title', 'Edit Pemasukan')

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
                    <span>Edit Pemasukan</span>
                </div>
                <h1 class="page-title">Edit Pemasukan</h1>
                <p class="page-subtitle">Perbarui informasi transaksi pemasukan</p>
            </div>
            <div class="header-right">
                <a href="{{ route('incomes.show', $transaction) }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Detail</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
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
                        <h2 class="form-title">Edit Pemasukan</h2>
                        <p class="form-subtitle">Perbarui detail transaksi pemasukan</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('incomes.update', $transaction) }}" onsubmit="return validateForm()">
                            @csrf
                            @method('PUT')

                            <!-- Account Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    Rekening Tujuan <span class="required">*</span>
                                </label>
                                <div class="account-grid">
                                    @foreach($accounts as $account)
                                        <div class="account-card {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}"
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
                                                   {{ old('account_id', $transaction->account_id) == $account->id ? 'checked' : '' }}
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
                                    Kategori Pemasukan <span class="required">*</span>
                                </label>
                                <div class="category-grid">
                                    @foreach($categories as $category)
                                        <div class="category-tag {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}"
                                             onclick="selectCategory({{ $category->id }}, this)">
                                            {{ $category->name }}
                                            <input type="radio" name="category_id" value="{{ $category->id }}"
                                                   {{ old('category_id', $transaction->category_id) == $category->id ? 'checked' : '' }}
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

                            <!-- Input Mode Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Mode Input <span class="required">*</span>
                                </label>
                                <div class="mode-toggle">
                                    <div class="mode-option {{ $inputMode === 'simple' ? 'active' : '' }}"
                                         onclick="selectInputMode('simple')">
                                        <input type="radio" name="input_mode" value="simple"
                                               {{ $inputMode === 'simple' ? 'checked' : '' }}
                                               style="display: none;">
                                        <div class="mode-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                        </div>
                                        <div class="mode-details">
                                            <div class="mode-title">Nominal Saja</div>
                                            <div class="mode-description">Input manual untuk jasa atau transaksi sederhana</div>
                                        </div>
                                    </div>
                                    <div class="mode-option {{ $inputMode === 'product' ? 'active' : '' }}"
                                         onclick="selectInputMode('product')">
                                        <input type="radio" name="input_mode" value="product"
                                               {{ $inputMode === 'product' ? 'checked' : '' }}
                                               style="display: none;">
                                        <div class="mode-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                                <line x1="12" y1="17" x2="12" y2="21"></line>
                                            </svg>
                                        </div>
                                        <div class="mode-details">
                                            <div class="mode-title">Dari Produk</div>
                                            <div class="mode-description">Pilih multiple produk untuk transaksi kasir</div>
                                        </div>
                                    </div>
                                    <div class="mode-option {{ $inputMode === 'service' ? 'active' : '' }}"
                                         onclick="selectInputMode('service')">
                                        <input type="radio" name="input_mode" value="service"
                                               {{ $inputMode === 'service' ? 'checked' : '' }}
                                               style="display: none;">
                                        <div class="mode-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                            </svg>
                                        </div>
                                        <div class="mode-details">
                                            <div class="mode-title">Dari Layanan</div>
                                            <div class="mode-description">Pilih layanan/jasa yang telah diberikan</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Simple Amount Input (shown when input_mode is simple) -->
                            <div class="form-group" id="simpleAmountGroup" style="{{ $inputMode === 'simple' ? '' : 'display: none;' }}">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Jumlah Pemasukan <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-prefix">Rp</div>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                           name="amount" value="{{ old('amount', $inputMode === 'simple' ? $transaction->amount : '') }}" placeholder="0"
                                           min="1" step="0.01" {{ $inputMode === 'simple' ? 'required' : '' }} oninput="updateAmountDisplay()">
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
                                    <div class="amount-label">Jumlah yang akan dicatat:</div>
                                    <div class="amount-value">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span id="formattedAmount">Rp0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Cart Interface (shown when input_mode is product) -->
                            <div class="form-group" id="productCartGroup" style="{{ $inputMode === 'product' ? '' : 'display: none;' }}">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    Keranjang Produk <span class="required">*</span>
                                </label>

                                <!-- Product Selection -->
                                <div class="product-selection">
                                    <div class="product-grid">
                                        @foreach($products ?? [] as $product)
                                            <div class="product-card" onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock_quantity }})">
                                                <div class="product-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                                    </svg>
                                                </div>
                                                <div class="product-details">
                                                    <div class="product-name">{{ $product->name }}</div>
                                                    <div class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                                                    <div class="product-stock">Stok: {{ $product->stock_quantity }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Cart Items Table -->
                                <div class="cart-container">
                                    <div class="cart-header">
                                        <h4>Keranjang Belanja</h4>
                                    </div>
                                    <div class="cart-table-container">
                                        <table class="cart-table" id="cartTable">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Subtotal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="cartBody">
                                                <!-- Cart items will be added here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Cart Total -->
                                    <div class="cart-total">
                                        <div class="total-row">
                                            <span class="total-label">Total:</span>
                                            <span class="total-amount" id="cartTotalAmount">Rp0</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden inputs for cart items -->
                                <div id="cartItemsContainer"></div>

                                @error('items')
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

                            <!-- Service Cart Interface (shown when input_mode is service) -->
                            <div class="form-group" id="serviceSelectionGroup" style="{{ $inputMode === 'service' ? '' : 'display: none;' }}">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    Keranjang Layanan <span class="required">*</span>
                                </label>

                                <!-- Service Selection -->
                                <div class="service-selection">
                                    <div class="service-grid">
                                        @foreach($services ?? [] as $service)
                                            <div class="service-card" onclick="addServiceToCart({{ $service->id }}, '{{ addslashes($service->name) }}', {{ $service->price }})">
                                                <div class="service-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                                    </svg>
                                                </div>
                                                <div class="service-details">
                                                    <div class="service-name">{{ $service->name }}</div>
                                                    <div class="service-price">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                                                    <div class="service-description">{{ Str::limit($service->description, 50) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Service Cart Items Table -->
                                <div class="cart-container">
                                    <div class="cart-header">
                                        <h4>Keranjang Layanan</h4>
                                    </div>
                                    <div class="cart-table-container">
                                        <table class="cart-table" id="serviceCartTable">
                                            <thead>
                                                <tr>
                                                    <th>Layanan</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Subtotal</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="serviceCartBody">
                                                <!-- Service cart items will be added here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Cart Total -->
                                    <div class="cart-total">
                                        <div class="total-row">
                                            <span class="total-label">Total:</span>
                                            <span class="total-amount" id="serviceCartTotalAmount">Rp0</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden inputs for service cart items -->
                                <div id="serviceCartItemsContainer"></div>

                                @error('services')
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
                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                       name="description" value="{{ old('description', $transaction->description) }}"
                                       placeholder="Contoh: Penjualan produk, Jasa konsultasi, dll">
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

                            <!-- Date Input -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Tanggal Pemasukan <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                       name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" required>
                                @error('date')
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
                                <a href="{{ route('incomes.show', $transaction) }}" class="btn-secondary">
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
                                    Perbarui Pemasukan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Warning Sidebar -->
            <div class="warning-section">
                <div class="warning-container">
                    <div class="warning-header">
                        <h3 class="warning-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Peringatan
                        </h3>
                    </div>

                    <div class="warning-content">
                        <div class="warning-message">
                            <div class="warning-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </div>
                            <div class="warning-text">
                                <h4>Penting</h4>
                                <p>Mengubah detail transaksi akan memengaruhi saldo rekening dan laporan keuangan. Pastikan semua informasi sudah benar sebelum menyimpan.</p>
                            </div>
                        </div>

                        <div class="warning-tips">
                            <h5>Dampak Perubahan:</h5>
                            <ul>
                                <li><strong>Rekening:</strong> Saldo rekening lama akan berkurang dan saldo rekening baru akan bertambah.</li>
                                <li><strong>Jumlah:</strong> Saldo rekening akan disesuaikan dengan jumlah baru.</li>
                                <li><strong>Kategori:</strong> Akan memengaruhi pengelompokan dalam laporan.</li>
                                <li><strong>Tanggal:</strong> Akan memengaruhi laporan berdasarkan periode waktu.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
let cartItems = [];
let serviceCartItems = [];

function validateForm() {
    const inputMode = document.querySelector('input[name="input_mode"]:checked').value;

    if (inputMode === 'product') {
        if (cartItems.length === 0) {
            alert('Silakan tambahkan produk ke keranjang terlebih dahulu.');
            return false;
        }

        // Check if all items have valid quantities
        for (let item of cartItems) {
            if (item.quantity < 1) {
                alert('Jumlah quantity harus minimal 1 untuk semua produk.');
                return false;
            }
            if (item.quantity > item.stock) {
                alert(`Stok tidak mencukupi untuk ${item.name}.`);
                return false;
            }
        }

        const totalAmount = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        if (totalAmount <= 0) {
            alert('Total pemasukan harus lebih dari 0.');
            return false;
        }
    } else if (inputMode === 'service') {
        if (serviceCartItems.length === 0) {
            alert('Silakan tambahkan layanan ke keranjang terlebih dahulu.');
            return false;
        }

        // Check if all items have valid quantities
        for (let item of serviceCartItems) {
            if (item.quantity < 1) {
                alert('Jumlah quantity harus minimal 1 untuk semua layanan.');
                return false;
            }
        }

        const totalAmount = serviceCartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        if (totalAmount <= 0) {
            alert('Total pemasukan harus lebih dari 0.');
            return false;
        }
    }

    return true;
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

function selectInputMode(mode) {
    // Remove active class from all mode options
    document.querySelectorAll('.mode-option').forEach(option => {
        option.classList.remove('active');
    });

    // Add active class to selected mode
    event.currentTarget.classList.add('active');

    // Check radio button
    event.currentTarget.querySelector('input[type="radio"]').checked = true;

    // Show/hide relevant form groups
    const simpleGroup = document.getElementById('simpleAmountGroup');
    const productGroup = document.getElementById('productCartGroup');
    const serviceGroup = document.getElementById('serviceSelectionGroup');

    if (mode === 'simple') {
        simpleGroup.style.display = '';
        productGroup.style.display = 'none';
        serviceGroup.style.display = 'none';

        // Make amount required, others not required
        document.querySelector('input[name="amount"]').required = true;
    } else if (mode === 'product') {
        simpleGroup.style.display = 'none';
        productGroup.style.display = '';
        serviceGroup.style.display = 'none';

        // Make amount not required
        document.querySelector('input[name="amount"]').required = false;

        // Clear cart when switching to product mode
        cartItems = [];
        updateCartDisplay();
    } else if (mode === 'service') {
        simpleGroup.style.display = 'none';
        productGroup.style.display = 'none';
        serviceGroup.style.display = '';

        // Make amount not required
        document.querySelector('input[name="amount"]').required = false;

        // Clear service cart when switching to service mode
        serviceCartItems = [];
        updateServiceCartDisplay();
    }
}

function addToCart(productId, productName, price, stock) {
    // Check if product already in cart
    const existingItem = cartItems.find(item => item.product_id === productId);

    if (existingItem) {
        // Increase quantity if already in cart
        if (existingItem.quantity < stock) {
            existingItem.quantity++;
            updateCartDisplay();
        } else {
            alert('Stok tidak mencukupi!');
        }
    } else {
        // Add new item to cart
        cartItems.push({
            product_id: productId,
            name: productName,
            price: price,
            quantity: 1,
            stock: stock
        });
        updateCartDisplay();
    }
}

function updateCartDisplay() {
    const cartBody = document.getElementById('cartBody');
    const cartTotalAmount = document.getElementById('cartTotalAmount');
    const cartItemsContainer = document.getElementById('cartItemsContainer');

    // Clear existing rows
    cartBody.innerHTML = '';
    cartItemsContainer.innerHTML = '';

    let total = 0;

    cartItems.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        // Add table row
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>Rp${item.price.toLocaleString('id-ID')}</td>
            <td>
                <div class="quantity-controls">
                    <button type="button" onclick="decreaseQuantity(${index})">-</button>
                    <input type="number" value="${item.quantity}" min="1" max="${item.stock}"
                           onchange="updateQuantity(${index}, this.value)" class="quantity-input">
                    <button type="button" onclick="increaseQuantity(${index})">+</button>
                </div>
            </td>
            <td>Rp${subtotal.toLocaleString('id-ID')}</td>
            <td><button type="button" onclick="removeFromCart(${index})" class="remove-btn">Hapus</button></td>
        `;
        cartBody.appendChild(row);

        // Add hidden inputs for form submission
        cartItemsContainer.innerHTML += `
            <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
            <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            <input type="hidden" name="items[${index}][price]" value="${item.price}">
        `;
    });

    // Update total
    cartTotalAmount.textContent = 'Rp' + total.toLocaleString('id-ID');
}

function increaseQuantity(index) {
    if (cartItems[index].quantity < cartItems[index].stock) {
        cartItems[index].quantity++;
        updateCartDisplay();
    } else {
        alert('Stok tidak mencukupi!');
    }
}

function decreaseQuantity(index) {
    if (cartItems[index].quantity > 1) {
        cartItems[index].quantity--;
        updateCartDisplay();
    }
}

function updateQuantity(index, newQuantity) {
    const qty = parseInt(newQuantity);
    if (qty >= 1 && qty <= cartItems[index].stock) {
        cartItems[index].quantity = qty;
        updateCartDisplay();
    } else if (qty > cartItems[index].stock) {
        alert('Stok tidak mencukupi!');
        updateCartDisplay(); // Reset to previous value
    }
}

function removeFromCart(index) {
    cartItems.splice(index, 1);
    updateCartDisplay();
}

function addServiceToCart(serviceId, serviceName, price) {
    // Check if service already in cart
    const existingItem = serviceCartItems.find(item => item.service_id === serviceId);

    if (existingItem) {
        // Increase quantity if already in cart
        existingItem.quantity++;
        updateServiceCartDisplay();
    } else {
        // Add new item to cart
        serviceCartItems.push({
            service_id: serviceId,
            name: serviceName,
            price: price,
            quantity: 1
        });
        updateServiceCartDisplay();
    }
}

function updateServiceCartDisplay() {
    const serviceCartBody = document.getElementById('serviceCartBody');
    const serviceCartTotalAmount = document.getElementById('serviceCartTotalAmount');
    const serviceCartItemsContainer = document.getElementById('serviceCartItemsContainer');

    // Clear existing rows
    serviceCartBody.innerHTML = '';
    serviceCartItemsContainer.innerHTML = '';

    let total = 0;

    serviceCartItems.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        // Add table row
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>Rp${item.price.toLocaleString('id-ID')}</td>
            <td>
                <div class="quantity-controls">
                    <button type="button" onclick="decreaseServiceQuantity(${index})">-</button>
                    <input type="number" value="${item.quantity}" min="1"
                           onchange="updateServiceQuantity(${index}, this.value)" class="quantity-input">
                    <button type="button" onclick="increaseServiceQuantity(${index})">+</button>
                </div>
            </td>
            <td>Rp${subtotal.toLocaleString('id-ID')}</td>
            <td><button type="button" onclick="removeServiceFromCart(${index})" class="remove-btn">Hapus</button></td>
        `;
        serviceCartBody.appendChild(row);

        // Add hidden inputs for form submission
        serviceCartItemsContainer.innerHTML += `
            <input type="hidden" name="services[${index}][service_id]" value="${item.service_id}">
            <input type="hidden" name="services[${index}][quantity]" value="${item.quantity}">
            <input type="hidden" name="services[${index}][price]" value="${item.price}">
        `;
    });

    // Update total
    serviceCartTotalAmount.textContent = 'Rp' + total.toLocaleString('id-ID');
}

function increaseServiceQuantity(index) {
    serviceCartItems[index].quantity++;
    updateServiceCartDisplay();
}

function decreaseServiceQuantity(index) {
    if (serviceCartItems[index].quantity > 1) {
        serviceCartItems[index].quantity--;
        updateServiceCartDisplay();
    }
}

function updateServiceQuantity(index, newQuantity) {
    const qty = parseInt(newQuantity);
    if (qty >= 1) {
        serviceCartItems[index].quantity = qty;
        updateServiceCartDisplay();
    }
}

function removeServiceFromCart(index) {
    serviceCartItems.splice(index, 1);
    updateServiceCartDisplay();
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

// Pre-populate cart with existing items
function populateExistingItems() {
    @if($inputMode === 'product' && $transaction->incomeItems->whereNotNull('product_id')->count() > 0)
        @foreach($transaction->incomeItems->whereNotNull('product_id') as $item)
            cartItems.push({
                product_id: {{ $item->product_id }},
                name: '{{ addslashes($item->product->name ?? 'Unknown Product') }}',
                price: {{ $item->price }},
                quantity: {{ $item->quantity }},
                stock: {{ $item->product->stock_quantity ?? 0 }}
            });
        @endforeach
        updateCartDisplay();
    @endif

    @if($inputMode === 'service' && $transaction->incomeItems->whereNotNull('service_id')->count() > 0)
        @foreach($transaction->incomeItems->whereNotNull('service_id') as $item)
            serviceCartItems.push({
                service_id: {{ $item->service_id }},
                name: '{{ addslashes($item->service->name ?? 'Unknown Service') }}',
                price: {{ $item->price }},
                quantity: {{ $item->quantity }}
            });
        @endforeach
        updateServiceCartDisplay();
    @endif
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateAmountDisplay();
    populateExistingItems();

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

    // Auto-select input mode
    const selectedMode = document.querySelector('input[name="input_mode"]:checked');
    if (selectedMode) {
        selectedMode.closest('.mode-option').classList.add('active');
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
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
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
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.form-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #065F46;
    margin: 0 0 8px 0;
    text-align: center;
}

.form-subtitle {
    font-size: 14px;
    color: #047857;
    margin: 0;
    text-align: center;
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
    border-color: #22C55E;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
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
    border-color: #22C55E;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

.account-card.selected {
    border-color: #22C55E;
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

.account-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
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
    border-color: #22C55E;
    background: #F0FDF4;
}

.category-tag.selected {
    border-color: #22C55E;
    background: #DCFCE7;
    color: #065F46;
}

/* ===== MODE TOGGLE ===== */
.mode-toggle {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.mode-option {
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

.mode-option:hover {
    border-color: #22C55E;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

.mode-option.active {
    border-color: #22C55E;
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

.mode-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.mode-details {
    flex-grow: 1;
}

.mode-title {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.mode-description {
    font-size: 12px;
    color: #6B7280;
    line-height: 1.4;
}

/* ===== AMOUNT DISPLAY ===== */
.amount-display {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    border: 2px solid #22C55E;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-top: 16px;
}

.amount-label {
    font-size: 14px;
    color: #065F46;
    font-weight: 600;
    margin-bottom: 8px;
}

.amount-value {
    font-size: 24px;
    font-weight: 700;
    color: #047857;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.amount-value svg {
    color: #22C55E;
}

/* ===== PRODUCT SELECTION ===== */
.product-selection, .service-selection {
    margin-bottom: 16px;
}

.product-grid, .service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}

.product-card, .service-card {
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

.product-card:hover, .service-card:hover {
    border-color: #22C55E;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.15);
}

.product-icon, .service-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.product-details, .service-details {
    flex-grow: 1;
}

.product-name, .service-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.product-price, .service-price {
    font-size: 14px;
    color: #22C55E;
    font-weight: 600;
    margin-bottom: 2px;
}

.product-stock {
    font-size: 12px;
    color: #6B7280;
}

.service-description {
    font-size: 12px;
    color: #6B7280;
    line-height: 1.4;
}

/* ===== CART INTERFACE ===== */
.cart-container {
    border: 2px solid #22C55E;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 16px;
}

.cart-header {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    padding: 16px 20px;
    border-bottom: 1px solid #22C55E;
}

.cart-header h4 {
    margin: 0;
    color: #065F46;
    font-size: 16px;
    font-weight: 600;
}

.cart-table-container {
    max-height: 300px;
    overflow-y: auto;
}

.cart-table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th,
.cart-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #E5E7EB;
}

.cart-table th {
    background: #F9FAFB;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.cart-table td {
    font-size: 14px;
    color: #6B7280;
}

.cart-total {
    background: #F0FDF4;
    padding: 16px 20px;
    border-top: 1px solid #22C55E;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-label {
    font-weight: 600;
    color: #065F46;
    font-size: 16px;
}

.total-amount {
    font-weight: 700;
    color: #047857;
    font-size: 18px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-controls button {
    width: 24px;
    height: 24px;
    border: 1px solid #D1D5DB;
    background: #FFFFFF;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: #374151;
}

.quantity-controls button:hover {
    background: #F3F4F6;
}

.quantity-input {
    width: 60px;
    text-align: center;
    border: 1px solid #D1D5DB;
    border-radius: 4px;
    padding: 4px 8px;
    font-size: 14px;
}

.remove-btn {
    background: #EF4444;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 12px;
    font-weight: 500;
}

.remove-btn:hover {
    background: #DC2626;
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

/* ===== WARNING SECTION ===== */
.warning-section {
    margin-bottom: 48px;
}

.warning-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    position: sticky;
    top: 32px;
}

.warning-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.warning-title {
    font-size: 18px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.warning-content {
    padding: 24px;
}

.warning-message {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
    background: #FEF3C7;
    border-radius: 12px;
    padding: 16px;
}

.warning-icon {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    background: #F59E0B;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.warning-text h4 {
    font-size: 16px;
    font-weight: 700;
    color: #92400E;
    margin: 0 0 8px 0;
}

.warning-text p {
    font-size: 14px;
    color: #78350F;
    margin: 0;
    line-height: 1.5;
}

.warning-tips h5 {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 12px 0;
}

.warning-tips ul {
    padding-left: 20px;
    margin: 0;
}

.warning-tips li {
    font-size: 14px;
    color: #4B5563;
    margin-bottom: 8px;
    line-height: 1.5;
}

.warning-tips li:last-child {
    margin-bottom: 0;
}

.warning-tips strong {
    color: #111827;
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
    
    .warning-container {
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
    
    .form-body {
        padding: 24px;
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
    
    .form-body {
        padding: 16px;
    }
    
    .warning-content {
        padding: 16px;
    }
    
    .warning-message {
        flex-direction: column;
        gap: 12px;
    }
}
</style>
@endsection