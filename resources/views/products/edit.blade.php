@extends('layouts.app')

@section('title', 'Edit Produk')

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
                    <span>Edit Produk</span>
                </div>
                <h1 class="page-title">Edit Produk</h1>
                <p class="page-subtitle">Perbarui informasi produk {{ $product->name }}</p>
            </div>
            <div class="header-right">
                <div class="stock-indicator">
                    <div class="stock-label">Stok Saat Ini</div>
                    <div class="stock-value {{ $product->stock_quantity > 0 ? 'positive' : ($product->stock_quantity <= 0 ? 'negative' : 'neutral') }}">
                        {{ $product->stock_quantity }}
                    </div>
                </div>
                <a href="{{ route('products.show', $product) }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>Lihat</span>
                </a>
                <a href="{{ route('products.index') }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali</span>
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
                        <h2 class="form-title">Form Edit Produk</h2>
                        <p class="form-subtitle">Perbarui informasi produk di bawah ini</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('products.update', $product) }}">
                            @csrf
                            @method('PUT')

                            <!-- Product Category -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    Kategori Produk <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <select class="form-control @error('product_category_id') is-invalid @enderror" 
                                            id="product_category_id" 
                                            name="product_category_id" 
                                            required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($productCategories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('product_category_id', $product->product_category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                    </div>
                                </div>
                                @error('product_category_id')
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

                            <!-- Product Name -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                    Nama Produk <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $product->name) }}" 
                                           placeholder="contoh: Laptop ASUS ROG, iPhone 14 Pro, etc." 
                                           required>
                                    <div class="input-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                    </div>
                                </div>
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

                            <!-- SKU -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    SKU (Stock Keeping Unit)
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" 
                                           class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" 
                                           name="sku" 
                                           value="{{ old('sku', $product->sku) }}" 
                                           placeholder="contoh: LAP-001, IPH-014, etc.">
                                    <div class="input-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                @error('sku')
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

                            <!-- Price & Cost Row -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        Harga Jual <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <span class="input-prefix">Rp</span>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('sale_price') is-invalid @enderror"
                                               id="sale_price"
                                               name="sale_price"
                                               value="{{ old('sale_price', $product->sale_price) }}"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('price')
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
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="1" x2="12" y2="23"></line>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                        </svg>
                                        Harga Beli <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <span class="input-prefix">Rp</span>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               class="form-control @error('purchase_price') is-invalid @enderror"
                                               id="purchase_price"
                                               name="purchase_price"
                                               value="{{ old('purchase_price', $product->purchase_price) }}"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                    @error('cost_price')
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
                            </div>

                            <!-- Tax Configuration Section -->
                            <div class="tax-configuration-section">
                                <div class="section-header">
                                    <h3 class="section-title">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        Konfigurasi Pajak
                                    </h3>
                                    <p class="section-subtitle">Atur pengaturan pajak untuk produk ini</p>
                                </div>

                                <!-- Tax Rule Selection -->
                                <div class="form-group">
                                    <label class="form-label" for="tax_rule_id">
                                        <span class="label-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </span>
                                        Aturan Pajak
                                    </label>
                                    <div class="input-wrapper">
                                        <select class="form-control @error('tax_rule_id') is-invalid @enderror"
                                                id="tax_rule_id"
                                                name="tax_rule_id">
                                            <option value="">-- Pilih Aturan Pajak (Opsional) --</option>
                                            @foreach($taxRules as $rule)
                                                <option value="{{ $rule->id }}"
                                                        data-percentage="{{ $rule->percentage }}"
                                                        data-type="{{ $rule->type }}"
                                                        {{ old('tax_rule_id', $product->tax_rule_id) == $rule->id ? 'selected' : '' }}>
                                                    {{ $rule->name }} ({{ $rule->percentage }}%)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-help">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        <span>Pilih aturan pajak untuk mengisi pengaturan secara otomatis</span>
                                    </div>
                                    @error('tax_rule_id')
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

                                <!-- Manual Tax Settings Row -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="tax_percentage">
                                            <span class="label-icon">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                                </svg>
                                            </span>
                                            Persentase Pajak <span class="required">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <input type="number"
                                                   class="form-control @error('tax_percentage') is-invalid @enderror"
                                                   id="tax_percentage"
                                                   name="tax_percentage"
                                                   value="{{ old('tax_percentage', $product->tax_percentage ?? 0) }}"
                                                   placeholder="0.00"
                                                   min="0"
                                                   max="100"
                                                   step="0.01"
                                                   required>
                                            <div class="input-suffix">%</div>
                                        </div>
                                        @error('tax_percentage')
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
                                    <div class="form-group">
                                        <label class="form-label" for="tax_type">
                                            <span class="label-icon">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                                </svg>
                                            </span>
                                            Tipe Pajak <span class="required">*</span>
                                        </label>
                                        <div class="input-wrapper">
                                            <select class="form-control @error('tax_type') is-invalid @enderror"
                                                    id="tax_type"
                                                    name="tax_type"
                                                    required>
                                                <option value="">-- Pilih Tipe --</option>
                                                <option value="ppn" {{ old('tax_type', $product->tax_type ?? 'ppn') == 'ppn' ? 'selected' : '' }}>PPN</option>
                                                <option value="non_pajak" {{ old('tax_type', $product->tax_type) == 'non_pajak' ? 'selected' : '' }}>Non Pajak</option>
                                                <option value="ppn_0" {{ old('tax_type', $product->tax_type) == 'ppn_0' ? 'selected' : '' }}>PPN 0%</option>
                                                <option value="bebas_pajak" {{ old('tax_type', $product->tax_type) == 'bebas_pajak' ? 'selected' : '' }}>Bebas Pajak</option>
                                            </select>
                                        </div>
                                        @error('tax_type')
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
                                </div>

                                <!-- Tax Included Toggle -->
                                <div class="form-group">
                                    <div class="toggle-group">
                                        <label class="toggle-label">
                                            <input type="checkbox"
                                                   id="is_tax_included"
                                                   name="is_tax_included"
                                                   value="1"
                                                   {{ old('is_tax_included', $product->is_tax_included ?? false) ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                            <span class="toggle-text">Harga sudah termasuk pajak</span>
                                        </label>
                                    </div>
                                    <div class="form-help">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        <span>Aktifkan jika harga jual sudah termasuk pajak</span>
                                    </div>
                                </div>

                                <!-- Tax Calculation Preview -->
                                <div class="tax-preview-section">
                                    <div class="preview-header">
                                        <h4>Pratinjau Perhitungan Pajak</h4>
                                    </div>
                                    <div class="preview-body">
                                        <div class="preview-row">
                                            <span class="preview-label">Harga Jual:</span>
                                            <span class="preview-value" id="preview-sale-price">Rp{{ number_format($product->sale_price ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="preview-row">
                                            <span class="preview-label">Pajak (<span id="preview-tax-rate">{{ $product->tax_percentage ?? 0 }}%</span>):</span>
                                            <span class="preview-value" id="preview-tax-amount">Rp{{ number_format($product->getTaxAmountForSale() ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="preview-row total-row">
                                            <span class="preview-label">Total:</span>
                                            <span class="preview-value" id="preview-total-price">Rp{{ number_format($product->getSalePriceWithTax() ?? $product->sale_price ?? 0, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock & Unit Row -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        Satuan
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('unit') is-invalid @enderror" 
                                               id="unit" 
                                               name="unit" 
                                               value="{{ old('unit', $product->unit) }}" 
                                               placeholder="contoh: pcs, kg, liter, box">
                                        <div class="input-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('unit')
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
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        </svg>
                                        Stok Saat Ini
                                    </label>
                                    <div class="stock-display">
                                        <div class="stock-value">{{ $product->stock_quantity }}</div>
                                        <div class="stock-note">Gunakan pergerakan stok untuk menyesuaikan</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Deskripsi Produk
                                </label>
                                <div class="input-wrapper">
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Tambahkan deskripsi detail tentang produk ini (opsional)">{{ old('description', $product->description) }}</textarea>
                                    <div class="input-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                                <div class="form-help">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                    <span>Deskripsi opsional, namun sangat membantu dalam mengidentifikasi produk</span>
                                </div>
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
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 17 21"></polyline>
                                        <polyline points="7 3 7 8 15 8 15 3"></polyline>
                                    </svg>
                                    <span>Update Produk</span>
                                </button>
                                <a href="{{ route('products.index') }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    <span>Batal</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Section -->
            <div class="sidebar-section">
                <!-- Product Info Card -->
                <div class="info-container">
                    <div class="info-header product-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            </svg>
                            Informasi Produk
                        </h3>
                    </div>
                    <div class="info-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Nama Produk</div>
                                <div class="info-value">{{ $product->name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">SKU</div>
                                <div class="info-value sku-code">{{ $product->sku ?? 'Tidak ada SKU' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Kategori</div>
                                <div class="info-value">{{ $product->productCategory->name ?? 'Tidak ada kategori' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Stok Saat Ini</div>
                                <div class="info-value stock-badge {{ $product->stock_quantity > 0 ? 'available' : ($product->stock_quantity <= 0 ? 'empty' : 'low') }}">
                                    {{ $product->stock_quantity }} {{ $product->unit ?? 'pcs' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Summary Card -->
                <div class="info-container">
                    <div class="info-header finance-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            Ringkasan Finansial
                        </h3>
                    </div>
                    <div class="info-body">
                        <div class="finance-stats">
                            <div class="finance-item">
                                <div class="finance-label">Harga Jual</div>
                                <div class="finance-value price-value">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                            <div class="finance-item">
                                <div class="finance-label">Harga Beli</div>
                                <div class="finance-value cost-value">Rp{{ number_format($product->cost_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="finance-item">
                                <div class="finance-label">Profit Margin</div>
                                <div class="finance-value">
                                    @php
                                        $margin = $product->price > 0 ? (($product->price - $product->cost_price) / $product->price) * 100 : 0;
                                    @endphp
                                    <span class="margin-badge {{ $margin > 0 ? 'positive' : ($margin < 0 ? 'negative' : 'neutral') }}">
                                        {{ number_format($margin, 1) }}%
                                    </span>
                                </div>
                            </div>
                            <div class="finance-item">
                                <div class="finance-label">Nilai Stok</div>
                                <div class="finance-value">Rp{{ number_format($product->stock_quantity * $product->cost_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline Card -->
                <div class="info-container">
                    <div class="info-header activity-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            Aktivitas Produk
                        </h3>
                    </div>
                    <div class="info-body">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon created">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Dibuat</div>
                                    <div class="activity-date">{{ $product->created_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon updated">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Terakhir Diupdate</div>
                                    <div class="activity-date">{{ $product->updated_at->format('d M Y, H:i') }}</div>
                                </div>
                            </div>
                            
                            <div class="activity-item">
                                <div class="activity-icon movements">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="17 1 21 5 17 9"></polyline>
                                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                        <polyline points="7 23 3 19 7 15"></polyline>
                                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                    </svg>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-title">Total Pergerakan</div>
                                    <div class="activity-date">{{ $product->stockMovements()->count() }} pergerakan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="actions-container">
                    <div class="actions-header">
                        <h3 class="actions-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v6m0 6v6m4.22-13.22l4.24 4.24M1.54 1.54l4.24 4.24M20.46 20.46l-4.24-4.24M1.54 20.46l4.24-4.24"></path>
                            </svg>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="actions-body">
                        <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'in']) }}" class="action-card">
                            <div class="action-icon add-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Stok</h4>
                                <p class="action-description">Catat stok masuk</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'out']) }}" class="action-card">
                            <div class="action-icon remove-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Kurangi Stok</h4>
                                <p class="action-description">Catat stok keluar</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('stock-movements.index', ['product_id' => $product->id]) }}" class="action-card">
                            <div class="action-icon history-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="1 4 1 10 7 10"></polyline>
                                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Riwayat Stok</h4>
                                <p class="action-description">Lihat semua pergerakan</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <button class="action-card" onclick="confirmDelete()">
                            <div class="action-icon delete-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Hapus Produk</h4>
                                <p class="action-description">Hapus produk ini</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong>{{ $product->name }}</strong>?</p>
                <p class="text-danger">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait produk.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
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

.header-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.stock-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 12px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.stock-label {
    font-size: 12px;
    color: #6B7280;
    font-weight: 500;
}

.stock-value {
    font-size: 24px;
    font-weight: 700;
}

.stock-value.positive {
    color: #10B981;
}

.stock-value.negative {
    color: #EF4444;
}

.stock-value.neutral {
    color: #F59E0B;
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

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
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

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.form-control {
    width: 100%;
    padding: 14px 16px 14px 44px;
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

.form-control.is-invalid {
    border-color: #EF4444;
}

.input-prefix {
    position: absolute;
    left: 16px;
    color: #9CA3AF;
    font-weight: 500;
    z-index: 1;
}

.input-icon {
    position: absolute;
    left: 16px;
    color: #9CA3AF;
    z-index: 1;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

select.form-control {
    padding-left: 16px;
    cursor: pointer;
}

select.form-control + .input-icon {
    right: 16px;
    left: auto;
    pointer-events: none;
}

/* Stock Display */
.stock-display {
    background: #F8FAFC;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
}

.stock-display .stock-value {
    font-size: 24px;
    font-weight: 700;
    color: #1F2937;
    margin-bottom: 4px;
}

.stock-display .stock-note {
    font-size: 12px;
    color: #6B7280;
}

/* Form Error & Help */
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

.form-help {
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-help span {
    font-size: 13px;
    color: #6B7280;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    padding-top: 24px;
    border-top: 1px solid #E5E7EB;
    margin-top: 32px;
}

/* ===== SIDEBAR SECTION ===== */
.sidebar-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* ===== INFO CONTAINER ===== */
.info-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.info-header {
    padding: 24px;
    color: white;
}

.product-header {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
}

.finance-header {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
}

.activity-header {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-body {
    padding: 24px;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
}

.info-label {
    font-size: 14px;
    color: #6B7280;
}

.info-value {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
    text-align: right;
}

.sku-code {
    font-family: monospace;
    background: #E5E7EB;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.stock-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.stock-badge.available {
    background: rgba(16, 185, 129, 0.1);
    color: #065F46;
}

.stock-badge.low {
    background: rgba(245, 158, 11, 0.1);
    color: #92400E;
}

.stock-badge.empty {
    background: rgba(239, 68, 68, 0.1);
    color: #991B1B;
}

/* Finance Stats */
.finance-stats {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.finance-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #F9FAFB;
    border-radius: 8px;
}

.finance-label {
    font-size: 14px;
    color: #6B7280;
}

.finance-value {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
}

.price-value {
    color: #10B981;
}

.cost-value {
    color: #6B7280;
}

.margin-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.margin-badge.positive {
    background: rgba(16, 185, 129, 0.1);
    color: #065F46;
}

.margin-badge.negative {
    background: rgba(239, 68, 68, 0.1);
    color: #991B1B;
}

.margin-badge.neutral {
    background: rgba(107, 114, 128, 0.1);
    color: #4B5563;
}

/* Activity List */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    flex-shrink: 0;
}

.activity-icon.created {
    background: #3B82F6;
}

.activity-icon.updated {
    background: #F59E0B;
}

.activity-icon.movements {
    background: #10B981;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-size: 14px;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 2px;
}

.activity-date {
    font-size: 12px;
    color: #6B7280;
}

/* ===== ACTIONS CONTAINER ===== */
.actions-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.actions-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.actions-title {
    font-size: 20px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.actions-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.action-card {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 3px;
    height: 100%;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.action-card:nth-child(1) {
    --color-start: #10B981;
    --color-end: #059669;
}

.action-card:nth-child(2) {
    --color-start: #EF4444;
    --color-end: #DC2626;
}

.action-card:nth-child(3) {
    --color-start: #3B82F6;
    --color-end: #2563EB;
}

.action-card:nth-child(4) {
    --color-start: #8B5CF6;
    --color-end: #7C3AED;
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
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

.add-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10B981;
}

.remove-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.history-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.delete-icon {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
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
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.action-arrow {
    color: #9CA3AF;
    transition: all 0.2s ease;
}

.action-card:hover .action-arrow {
    color: #3B82F6;
    transform: translateX(4px);
}

/* ===== TAX CONFIGURATION STYLES ===== */
.tax-configuration-section {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 28px;
}

.section-header {
    margin-bottom: 24px;
    text-align: center;
}

.section-title {
    font-size: 18px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.section-subtitle {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.toggle-group {
    margin-bottom: 16px;
}

.toggle-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.toggle-slider {
    position: relative;
    width: 44px;
    height: 24px;
    background: #E5E7EB;
    border-radius: 12px;
    transition: background-color 0.3s ease;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

input[type="checkbox"]:checked + .toggle-slider {
    background: #3B82F6;
}

input[type="checkbox"]:checked + .toggle-slider::before {
    transform: translateX(20px);
}

input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.toggle-text {
    user-select: none;
}

.input-suffix {
    position: absolute;
    right: 16px;
    color: #6B7280;
    font-weight: 600;
    z-index: 1;
}

.input-wrapper .form-control {
    padding-right: 50px; /* Make room for suffix */
}

/* Tax Preview Section */
.tax-preview-section {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 20px;
}

.preview-header {
    background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
    padding: 16px 20px;
    border-bottom: 1px solid #E5E7EB;
}

.preview-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #1E40AF;
    display: flex;
    align-items: center;
    gap: 8px;
}

.preview-body {
    padding: 20px;
}

.preview-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #F3F4F6;
}

.preview-row:last-child {
    border-bottom: none;
}

.preview-row.total-row {
    border-top: 2px solid #E5E7EB;
    margin-top: 8px;
    padding-top: 16px;
    font-weight: 700;
}

.preview-label {
    font-size: 14px;
    color: #6B7280;
}

.preview-value {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
}

.total-row .preview-value {
    color: #1E40AF;
    font-size: 16px;
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
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
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
    
    .header-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .page-title {
        font-size: 28px;
    }
    
    .form-body {
        padding: 24px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
    
    .info-container, .actions-container {
        position: static;
        margin-top: 20px;
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
    
    .info-header, .actions-header {
        padding: 20px;
    }
    
    .info-body, .actions-body {
        padding: 16px;
    }
    
    .action-card {
        padding: 16px;
    }
    
    .activity-item {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profit Calculator
    const priceInput = document.getElementById('sale_price');
    const costInput = document.getElementById('purchase_price');

    // Tax Configuration Elements
    const taxRuleSelect = document.getElementById('tax_rule_id');
    const taxPercentageInput = document.getElementById('tax_percentage');
    const taxTypeSelect = document.getElementById('tax_type');
    const isTaxIncludedCheckbox = document.getElementById('is_tax_included');

    // Tax Preview Elements
    const previewSalePrice = document.getElementById('preview-sale-price');
    const previewTaxRate = document.getElementById('preview-tax-rate');
    const previewTaxAmount = document.getElementById('preview-tax-amount');
    const previewTotalPrice = document.getElementById('preview-total-price');

    function updateTaxPreview() {
        const salePrice = parseFloat(priceInput.value) || 0;
        const taxPercentage = parseFloat(taxPercentageInput.value) || 0;
        const isTaxIncluded = isTaxIncludedCheckbox.checked;

        let displayPrice, taxAmount, totalPrice;

        if (isTaxIncluded) {
            // Price includes tax
            totalPrice = salePrice;
            taxAmount = salePrice - (salePrice / (1 + taxPercentage / 100));
            displayPrice = salePrice - taxAmount;
        } else {
            // Tax added to price
            displayPrice = salePrice;
            taxAmount = salePrice * (taxPercentage / 100);
            totalPrice = salePrice + taxAmount;
        }

        previewSalePrice.textContent = 'Rp' + displayPrice.toLocaleString('id-ID');
        previewTaxRate.textContent = taxPercentage.toFixed(2) + '%';
        previewTaxAmount.textContent = 'Rp' + taxAmount.toLocaleString('id-ID');
        previewTotalPrice.textContent = 'Rp' + totalPrice.toLocaleString('id-ID');
    }

    function handleTaxRuleChange() {
        const selectedOption = taxRuleSelect.options[taxRuleSelect.selectedIndex];
        if (selectedOption.value) {
            const percentage = selectedOption.getAttribute('data-percentage') || 0;
            taxPercentageInput.value = percentage;

            // Auto-select tax type based on percentage
            if (percentage == 0) {
                taxTypeSelect.value = 'non_pajak';
            } else if (percentage == 11) {
                taxTypeSelect.value = 'ppn';
            } else {
                taxTypeSelect.value = 'ppn';
            }
        }
        updateTaxPreview();
    }

    // Event listeners
    priceInput.addEventListener('input', updateTaxPreview);
    taxRuleSelect.addEventListener('change', handleTaxRuleChange);
    taxPercentageInput.addEventListener('input', updateTaxPreview);
    isTaxIncludedCheckbox.addEventListener('change', updateTaxPreview);

    // Initialize on page load
    updateTaxPreview();
});

function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection