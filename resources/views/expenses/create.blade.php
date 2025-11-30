@extends('layouts.app')

@section('title', 'Tambah Pengeluaran Baru')
@section('page-title', 'Tambah Pengeluaran')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Pengeluaran Baru</span>
                </div>
                <h1 class="page-title">Tambah Pengeluaran Baru</h1>
                <p class="page-subtitle">Catat pengeluaran untuk melacak aliran kas keluar</p>
            </div>
            <div class="header-right">
                <a href="{{ route('expenses.index') }}" class="btn-secondary">
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
        <div class="content-grid">
            <!-- Form Section -->
            <section class="form-section">
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </div>
                        <h2 class="form-title">Form Pengeluaran Baru</h2>
                        <p class="form-subtitle">Masukkan detail pengeluaran dengan lengkap dan akurat</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('expenses.store') }}" id="expenseForm" onsubmit="return validateForm()">
                            @csrf

                            <!-- Account Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    Rekening Sumber <span class="required">*</span>
                                </label>
                                <div class="account-grid">
                                    @foreach($accounts as $account)
                                        <div class="account-card {{ request('account') == $account->id ? 'selected' : '' }}"
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
                                                   {{ request('account') == $account->id ? 'checked' : '' }}
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
                                    Kategori Pengeluaran <span class="required">*</span>
                                </label>
                                <div class="category-grid">
                                    @foreach($categories as $category)
                                        <div class="category-tag {{ request('category') == $category->id ? 'selected' : '' }}"
                                             onclick="selectCategory({{ $category->id }}, this)">
                                            {{ $category->name }}
                                            <input type="radio" name="category_id" value="{{ $category->id }}"
                                                   {{ request('category') == $category->id ? 'checked' : '' }}
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
                                    <div class="mode-option {{ request('input_mode', 'simple') === 'simple' ? 'active' : '' }}"
                                         onclick="selectInputMode('simple')">
                                        <input type="radio" name="input_mode" value="simple"
                                               {{ request('input_mode', 'simple') === 'simple' ? 'checked' : '' }}
                                               style="display: none;">
                                        <div class="mode-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                        </div>
                                        <div class="mode-details">
                                            <div class="mode-title">Nominal Saja</div>
                                            <div class="mode-description">Input manual untuk biaya operasional</div>
                                        </div>
                                    </div>
                                    <div class="mode-option {{ request('input_mode', 'simple') === 'product' ? 'active' : '' }}"
                                         onclick="selectInputMode('product')">
                                        <input type="radio" name="input_mode" value="product"
                                               {{ request('input_mode', 'simple') === 'product' ? 'checked' : '' }}
                                               style="display: none;">
                                        <div class="mode-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                                <line x1="12" y1="17" x2="12" y2="21"></line>
                                            </svg>
                                        </div>
                                        <div class="mode-details">
                                            <div class="mode-title">Beli Produk</div>
                                            <div class="mode-description">Restock inventory dengan auto-update stok</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Simple Amount Input (shown when input_mode is simple) -->
                            <div class="form-group" id="simpleAmountGroup" style="{{ request('input_mode', 'simple') === 'simple' ? '' : 'display: none;' }}">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Jumlah Pengeluaran <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-prefix">Rp</div>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                           name="amount" value="{{ old('amount') }}" placeholder="0"
                                           min="1" step="0.01" {{ request('input_mode', 'simple') === 'simple' ? 'required' : '' }} oninput="updateAmountDisplay()">
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
                            <div class="form-group" id="productCartGroup" style="{{ request('input_mode', 'simple') === 'product' ? '' : 'display: none;' }}">
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
                                            <div class="product-card" onclick="addToCart({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->cost_price }}, {{ $product->stock_quantity }})">
                                                <div class="product-icon">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                        <line x1="8" y1="21" x2="16" y2="21"></line>
                                                        <line x1="12" y1="17" x2="12" y2="21"></line>
                                                    </svg>
                                                </div>
                                                <div class="product-details">
                                                    <div class="product-name">{{ $product->name }}</div>
                                                    <div class="product-price">Rp{{ number_format($product->cost_price, 0, ',', '.') }}</div>
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

                                    <!-- Cart Summary -->
                                    <div class="cart-summary">
                                        <div class="summary-row total-row">
                                            <span class="summary-label">Total:</span>
                                            <span class="summary-amount" id="cartTotalAmount">Rp0</span>
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
                                       name="description" value="{{ old('description') }}"
                                       placeholder="Contoh: Pembelian ATK, Biaya transport, dll">
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

                            <!-- Receipt Image Upload -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                    </svg>
                                    Bukti Transaksi (Opsional)
                                </label>
                                <div class="file-upload-area">
                                    <input type="file" class="file-input @error('receipt_image') is-invalid @enderror"
                                           name="receipt_image" id="receipt_image"
                                           accept="image/*" onchange="previewImage(this)">
                                    <div class="file-upload-content">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="9" cy="9" r="2"></circle>
                                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                        </svg>
                                        <div class="upload-text">
                                            <div class="upload-title">Upload Bukti Transaksi</div>
                                            <div class="upload-subtitle">Pilih gambar struk atau bukti pembayaran</div>
                                            <div class="upload-hint">Format: JPG, PNG, maksimal 5MB</div>
                                        </div>
                                    </div>
                                    <div class="image-preview" id="imagePreview" style="display: none;">
                                        <img id="previewImg" src="" alt="Preview" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                                        <button type="button" class="remove-image" onclick="removeImage()">&times;</button>
                                    </div>
                                </div>
                                @error('receipt_image')
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
                                    Tanggal Pengeluaran <span class="required">*</span>
                                </label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                       name="date" value="{{ old('date', date('Y-m-d')) }}" required>
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
                                <a href="{{ route('expenses.index') }}" class="btn-secondary">
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
                                    Simpan Pengeluaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Tips Sidebar -->
            <section class="tips-section">
                <div class="tips-container">
                    <div class="tips-header">
                        <h3 class="tips-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Tips
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
                                <h4>Catat dengan Rinci</h4>
                                <p>Selalu tambahkan keterangan yang jelas untuk setiap pengeluaran agar mudah dilacak di masa depan.</p>
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
                                <h4>Gunakan Tanggal yang Tepat</h4>
                                <p>Pastikan tanggal pengeluaran sesuai dengan saat uang benar-benar dikeluarkan.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Kategori yang Tepat</h4>
                                <p>Pilih kategori yang paling sesuai untuk membantu analisis keuangan Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
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

    if (mode === 'simple') {
        simpleGroup.style.display = '';
        productGroup.style.display = 'none';

        // Make amount required
        document.querySelector('input[name="amount"]').required = true;
    } else if (mode === 'product') {
        simpleGroup.style.display = 'none';
        productGroup.style.display = '';

        // Make amount not required
        document.querySelector('input[name="amount"]').required = false;

        // Clear cart when switching to product mode
        cartItems = [];
        updateCartDisplay();
    }
}

let cartItems = [];

function addToCart(productId, productName, price, stock) {
    // Check if product already in cart
    const existingItem = cartItems.find(item => item.product_id === productId);

    if (existingItem) {
        // Increase quantity if already in cart
        existingItem.quantity++;
        updateCartDisplay();
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

    let totalAmount = 0;

    cartItems.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        totalAmount += subtotal;

        // Add table row
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${item.name}</td>
            <td>Rp${item.price.toLocaleString('id-ID')}</td>
            <td>
                <div class="quantity-controls">
                    <button type="button" onclick="decreaseQuantity(${index})">-</button>
                    <input type="number" value="${item.quantity}" min="1"
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
            <input type="hidden" name="items[${index}][subtotal]" value="${subtotal}">
        `;
    });

    // Update summary
    cartTotalAmount.textContent = 'Rp' + totalAmount.toLocaleString('id-ID');
}

function increaseQuantity(index) {
    // No stock limit for purchases
    cartItems[index].quantity++;
    updateCartDisplay();
}

function decreaseQuantity(index) {
    if (cartItems[index].quantity > 1) {
        cartItems[index].quantity--;
        updateCartDisplay();
    }
}

function updateQuantity(index, newQuantity) {
    const qty = parseInt(newQuantity);
    if (qty >= 1) {
        cartItems[index].quantity = qty;
        updateCartDisplay();
    } else {
        updateCartDisplay(); // Reset to previous value if invalid
    }
}

function removeFromCart(index) {
    cartItems.splice(index, 1);
    updateCartDisplay();
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

// Image preview functions
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const uploadContent = input.closest('.file-upload-area').querySelector('.file-upload-content');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Please select a valid image file.');
            input.value = '';
            return;
        }

        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            uploadContent.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const input = document.getElementById('receipt_image');
    const preview = document.getElementById('imagePreview');
    const uploadContent = document.querySelector('.file-upload-content');

    input.value = '';
    preview.style.display = 'none';
    uploadContent.style.display = 'block';
}

// Form validation
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
            // Note: For purchases (expenses), we don't check stock availability
            // since we're buying and adding to stock
        }

        const totalAmount = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        if (totalAmount <= 0) {
            alert('Total pengeluaran harus lebih dari 0.');
            return false;
        }
    }

    return true;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateAmountDisplay();

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
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
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
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
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
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
    padding: 32px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.form-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #991B1B;
    margin-bottom: 8px;
}

.form-subtitle {
    font-size: 14px;
    color: #B91C1C;
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
    border-color: #EF4444;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
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
    border-color: #EF4444;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}

.account-card.selected {
    border-color: #EF4444;
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}

.account-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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
    border-color: #EF4444;
    background: #FEF2F2;
}

.category-tag.selected {
    border-color: #EF4444;
    background: #FEE2E2;
    color: #991B1B;
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
    border-color: #EF4444;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}

.mode-option.active {
    border-color: #EF4444;
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}

.mode-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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

/* ===== PRODUCT SELECTION ===== */
.product-selection {
    margin-bottom: 24px;
}

.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.product-card {
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

.product-card:hover {
    border-color: #EF4444;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}

.product-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.product-details {
    flex-grow: 1;
}

.product-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.product-price {
    font-size: 14px;
    color: #EF4444;
    font-weight: 600;
    margin-bottom: 2px;
}

.product-stock {
    font-size: 12px;
    color: #6B7280;
}

/* ===== SERVICE SELECTION ===== */
.service-selection {
    margin-bottom: 24px;
}

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.service-card {
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

.service-card:hover {
    border-color: #EF4444;
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
}

.service-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.service-details {
    flex-grow: 1;
}

.service-name {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.service-price {
    font-size: 14px;
    color: #EF4444;
    font-weight: 600;
    margin-bottom: 2px;
}

.service-description {
    font-size: 12px;
    color: #6B7280;
    line-height: 1.4;
}

/* ===== CART INTERFACE ===== */
.cart-container {
    background: #F8FAFC;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #E2E8F0;
    margin-bottom: 16px;
}

.cart-header {
    padding: 16px;
    background: #F1F5F9;
    border-bottom: 1px solid #E2E8F0;
}

.cart-header h4 {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin: 0;
}

.cart-table-container {
    overflow-x: auto;
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
    background: #F1F5F9;
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.cart-table td {
    font-size: 14px;
    color: #6B7280;
}

.cart-table tbody tr:last-child td {
    border-bottom: none;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-controls button {
    width: 24px;
    height: 24px;
    border-radius: 4px;
    border: none;
    background: #EF4444;
    color: white;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-controls button:hover {
    background: #DC2626;
}

.quantity-input {
    width: 60px;
    height: 32px;
    border: 1px solid #D1D5DB;
    border-radius: 4px;
    text-align: center;
    font-size: 14px;
}

.remove-btn {
    padding: 4px 8px;
    border-radius: 4px;
    border: none;
    background: #EF4444;
    color: white;
    font-size: 12px;
    cursor: pointer;
}

.remove-btn:hover {
    background: #DC2626;
}

.cart-summary {
    background: #F0FDF4;
    padding: 16px 20px;
    border-top: 1px solid #EF4444;
}

.cart-summary .summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.cart-summary .summary-row:last-child {
    margin-bottom: 0;
}

.summary-label {
    font-weight: 500;
    color: #991B1B;
    font-size: 14px;
}

.summary-amount {
    font-weight: 600;
    color: #B91C1C;
    font-size: 14px;
}

.cart-summary .total-row .summary-label,
.cart-summary .total-row .summary-amount {
    font-size: 16px;
    font-weight: 700;
    color: #B91C1C;
}

.tax-info {
    text-align: center;
}

.tax-amount {
    font-weight: 600;
    color: #DC2626;
    font-size: 14px;
    margin-bottom: 2px;
}

.tax-rate {
    font-size: 12px;
    color: #6B7280;
    font-weight: 500;
}

/* ===== AMOUNT DISPLAY ===== */
.amount-display {
    background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
    border: 2px solid #EF4444;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    margin-top: 16px;
}

.amount-label {
    font-size: 14px;
    color: #991B1B;
    font-weight: 600;
    margin-bottom: 8px;
}

.amount-value {
    font-size: 24px;
    font-weight: 700;
    color: #B91C1C;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.amount-value svg {
    color: #EF4444;
}

/* ===== FILE UPLOAD ===== */
.file-upload-area {
    position: relative;
}

.file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.file-upload-content {
    border: 2px dashed #E5E7EB;
    border-radius: 12px;
    padding: 32px;
    text-align: center;
    background: #F9FAFB;
    transition: all 0.2s ease;
    cursor: pointer;
}

.file-upload-content:hover {
    border-color: #EF4444;
    background: #FEF2F2;
}

.upload-text {
    margin-top: 16px;
}

.upload-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.upload-subtitle {
    font-size: 14px;
    color: #6B7280;
    margin-bottom: 4px;
}

.upload-hint {
    font-size: 12px;
    color: #9CA3AF;
}

.image-preview {
    position: relative;
    display: inline-block;
    margin-top: 16px;
}

.remove-image {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #EF4444;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
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
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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
    
    .form-body {
        padding: 24px;
    }
    
    .account-grid {
        grid-template-columns: 1fr;
    }
    
    .category-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .mode-toggle {
        grid-template-columns: 1fr;
    }
    
    .product-grid, .service-grid {
        grid-template-columns: 1fr;
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
    
    .cart-table {
        font-size: 12px;
    }
    
    .cart-table th,
    .cart-table td {
        padding: 8px;
    }
}
</style>
@endsection