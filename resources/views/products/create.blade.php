@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <span>Tambah Produk</span>
                </div>
                <h1 class="page-title">Tambah Produk Baru</h1>
                <p class="page-subtitle">Tambahkan produk baru ke inventori Anda</p>
            </div>
            <div class="header-right">
                <div class="header-stats">
                    <div class="stat-item">
                        <div class="stat-label">Total Produk</div>
                        <div class="stat-value">{{ \App\Models\Product::count() }}</div>
                    </div>
                    <div class="stat-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                    </div>
                </div>
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
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <h2 class="form-title">Form Produk Baru</h2>
                        <p class="form-subtitle">Isi formulir di bawah untuk menambahkan produk baru</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Product Category -->
                            <div class="form-group">
                                <label class="form-label" for="product_category_id">
                                    <span class="label-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                    </span>
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
                                                    {{ old('product_category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                <label class="form-label" for="name">
                                    <span class="label-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                            <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                        </svg>
                                    </span>
                                    Nama Produk <span class="required">*</span>
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Contoh: Laptop ASUS ROG, iPhone 14 Pro, dll." 
                                           required>
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
                                <label class="form-label" for="sku">
                                    <span class="label-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                    </span>
                                    SKU (Stock Keeping Unit)
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" 
                                           class="form-control @error('sku') is-invalid @enderror" 
                                           id="sku" 
                                           name="sku" 
                                           value="{{ old('sku') }}" 
                                           placeholder="Contoh: LAP-001, IPH-014, dll.">
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
                                    <label class="form-label" for="price">
                                        <span class="label-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                        </span>
                                        Harga Jual <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <div class="input-prefix">Rp</div>
                                        <input type="number"
                                               class="form-control @error('sale_price') is-invalid @enderror"
                                               id="sale_price"
                                               name="sale_price"
                                               value="{{ old('sale_price') }}"
                                               placeholder="0.00"
                                               min="0"
                                               step="0.01"
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
                                    <label class="form-label" for="cost_price">
                                        <span class="label-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                        </span>
                                        Harga Beli <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <div class="input-prefix">Rp</div>
                                        <input type="number"
                                               class="form-control @error('purchase_price') is-invalid @enderror"
                                               id="purchase_price"
                                               name="purchase_price"
                                               value="{{ old('purchase_price') }}"
                                               placeholder="0.00"
                                               min="0"
                                               step="0.01"
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
                                                        {{ old('tax_rule_id') == $rule->id ? 'selected' : '' }}>
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
                                                   value="{{ old('tax_percentage', 0) }}"
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
                                                <option value="ppn" {{ old('tax_type', 'ppn') == 'ppn' ? 'selected' : '' }}>PPN</option>
                                                <option value="non_pajak" {{ old('tax_type') == 'non_pajak' ? 'selected' : '' }}>Non Pajak</option>
                                                <option value="ppn_0" {{ old('tax_type') == 'ppn_0' ? 'selected' : '' }}>PPN 0%</option>
                                                <option value="bebas_pajak" {{ old('tax_type') == 'bebas_pajak' ? 'selected' : '' }}>Bebas Pajak</option>
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
                                                   {{ old('is_tax_included', false) ? 'checked' : '' }}>
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
                                            <span class="preview-value" id="preview-sale-price">Rp0</span>
                                        </div>
                                        <div class="preview-row">
                                            <span class="preview-label">Pajak (<span id="preview-tax-rate">0%</span>):</span>
                                            <span class="preview-value" id="preview-tax-amount">Rp0</span>
                                        </div>
                                        <div class="preview-row total-row">
                                            <span class="preview-label">Total:</span>
                                            <span class="preview-value" id="preview-total-price">Rp0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Stock & Unit Row -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="stock_quantity">
                                        <span class="label-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                            </svg>
                                        </span>
                                        Stok Awal <span class="required">*</span>
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="number" 
                                               class="form-control @error('stock_quantity') is-invalid @enderror" 
                                               id="stock_quantity" 
                                               name="stock_quantity" 
                                               value="{{ old('stock_quantity', 0) }}" 
                                               placeholder="0" 
                                               min="0" 
                                               required>
                                    </div>
                                    @error('stock_quantity')
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
                                    <label class="form-label" for="unit">
                                        <span class="label-icon">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="9" r="2"></circle>
                                                <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                            </svg>
                                        </span>
                                        Satuan
                                    </label>
                                    <div class="input-wrapper">
                                        <input type="text" 
                                               class="form-control @error('unit') is-invalid @enderror" 
                                               id="unit" 
                                               name="unit" 
                                               value="{{ old('unit') }}" 
                                               placeholder="Contoh: pcs, kg, liter, box">
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
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label class="form-label" for="description">
                                    <span class="label-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </span>
                                    Deskripsi Produk
                                </label>
                                <div class="input-wrapper">
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              placeholder="Tambahkan deskripsi detail tentang produk ini (opsional)">{{ old('description') }}</textarea>
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

                            <!-- Product Image -->
                            <div class="form-group">
                                <label class="form-label" for="productImage">
                                    <span class="label-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </span>
                                    Gambar Produk
                                </label>
                                <div class="image-upload-container">
                                    <div class="image-preview" id="imagePreview">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="12" cy="12" r="3"></circle>
                                            <line x1="12" y1="9" x2="12" y2="15"></line>
                                        </svg>
                                    </div>
                                    <div class="image-upload-area" id="dropZone">
                                        <div class="upload-icon">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8 17 8"></polyline>
                                                <line x1="12" y1="21"></line>
                                            </svg>
                                        </div>
                                        <p>Drag & drop gambar di sini atau <span class="browse-link">browse</span></p>
                                        <input type="file" id="productImage" name="image" accept="image/*" class="hidden">
                                    </div>
                                </div>
                                @error('image')
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
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8 15 3"></polyline>
                                    </svg>
                                    <span>Simpan Produk</span>
                                </button>
                                <a href="{{ route('products.index') }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>
                                    <span>Batal</span>
                                </a>
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
                            Tips Menambah Produk
                        </h3>
                    </div>

                    <div class="tips-content">
                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 11l3 3L22 4"></path>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-7"></path>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Nama yang Deskriptif</h4>
                                <p>Beri nama yang jelas dan mudah dipahami untuk setiap produk agar mudah ditemukan oleh pengguna.</p>
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
                                <h4>Deskripsi yang Bermanfaat</h4>
                                <p>Tambahkan deskripsi yang jelas tentang fitur dan manfaat produk untuk membantu penjualan.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Stok yang Akurat</h4>
                                <p>Masukkan jumlah stok yang akurat untuk menghindari kehabisan atau kelebihan stok.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Harga yang Kompetitif</h4>
                                <p>Tentukan harga jual dan harga beli yang realistis untuk memaksimalkan profit margin.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <polyline points="21 15 16 10 5 21"></polyline>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Gambar Produk</h4>
                                <p>Upload gambar produk yang jelas dan berkualitas tinggi untuk meningkatkan daya tarik pembeli.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profit Calculator -->
                <div class="profit-calculator-container">
                    <div class="profit-calculator-header">
                        <h3 class="profit-calculator-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                            Kalkulator Profit
                        </h3>
                    </div>
                    <div class="profit-calculator-body">
                        <div class="profit-item">
                            <span class="profit-label">Harga Jual:</span>
                            <span class="profit-value" id="displayPrice">Rp0</span>
                        </div>
                        <div class="profit-item">
                            <span class="profit-label">Harga Beli:</span>
                            <span class="profit-value" id="displayCost">Rp0</span>
                        </div>
                        <div class="profit-item">
                            <span class="profit-label">Profit:</span>
                            <span class="profit-value" id="displayProfit">Rp0</span>
                        </div>
                        <div class="profit-item">
                            <span class="profit-label">Margin:</span>
                            <span class="profit-value" id="displayMargin">0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profit Calculator
    const priceInput = document.getElementById('sale_price');
    const costInput = document.getElementById('purchase_price');
    const displayPrice = document.getElementById('displayPrice');
    const displayCost = document.getElementById('displayCost');
    const displayProfit = document.getElementById('displayProfit');
    const displayMargin = document.getElementById('displayMargin');

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

    function calculateProfit() {
        const price = parseFloat(priceInput.value) || 0;
        const cost = parseFloat(costInput.value) || 0;

        const profit = price - cost;
        const margin = price > 0 ? (profit / price) * 100 : 0;

        displayPrice.textContent = 'Rp' + price.toLocaleString('id-ID');
        displayCost.textContent = 'Rp' + cost.toLocaleString('id-ID');
        displayProfit.textContent = 'Rp' + profit.toLocaleString('id-ID');
        displayMargin.textContent = margin.toFixed(1) + '%';

        // Color code profit
        if (profit > 0) {
            displayProfit.style.color = '#10b981';
        } else if (profit < 0) {
            displayProfit.style.color = '#ef4444';
        } else {
            displayProfit.style.color = '#6b7280';
        }

        // Update tax preview
        updateTaxPreview();
    }

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
    priceInput.addEventListener('input', calculateProfit);
    costInput.addEventListener('input', calculateProfit);
    taxRuleSelect.addEventListener('change', handleTaxRuleChange);
    taxPercentageInput.addEventListener('input', updateTaxPreview);
    isTaxIncludedCheckbox.addEventListener('change', updateTaxPreview);
    
    // Image Upload
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('productImage');
    const imagePreview = document.getElementById('imagePreview');
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop);
    
    // Handle file selection
    fileInput.addEventListener('change', handleFileSelect);
    
    // Open file dialog when browse link is clicked
    dropZone.querySelector('.browse-link').addEventListener('click', () => {
        fileInput.click();
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files.length > 0) {
            handleFiles(files);
        }
    }
    
    function handleFileSelect(e) {
        const files = e.target.files;
        if (files.length > 0) {
            handleFiles(files);
        }
    }
    
    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            
            // Check if file is an image
            if (!file.type.match('image.*')) {
                alert('Please select an image file');
                return;
            }
            
            // Read the file
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Update preview
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Product Image" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
            };
            
            reader.readAsDataURL(file);
        }
    }
    
    // Initialize on page load
    calculateProfit();
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

.header-stats {
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.stat-label {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.8);
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: white;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
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
    gap: 16px;
}

.form-label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
}

.label-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    margin-right: 8px;
    color: #6B7280;
}

.required {
    color: #EF4444;
    margin-left: 4px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
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

.input-prefix {
    position: absolute;
    left: 16px;
    font-weight: 600;
    color: #6B7280;
    z-index: 1;
}

.input-wrapper .form-control {
    padding-left: 40px; /* Adjusted to accommodate prefix */
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* Image Upload */
.image-upload-container {
    margin-bottom: 28px;
}

.image-preview {
    width: 100%;
    height: 200px;
    border-radius: 12px;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 16px;
}

.image-upload-area {
    width: 100%;
    height: 200px;
    border-radius: 12px;
    border: 2px dashed #D1D5DB;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #F9FAFB;
    transition: all 0.3s ease;
    cursor: pointer;
}

.image-upload-area:hover {
    background: #F3F4F6;
    border-color: #3B82F6;
}

.upload-icon {
    margin-bottom: 16px;
}

.upload-icon svg {
    width: 32px;
    height: 32px;
    color: #9CA3AF;
}

.browse-link {
    color: #3B82F6;
    font-weight: 600;
    text-decoration: underline;
}

.browse-link:hover {
    color: #2563EB;
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

/* ===== TIPS SECTION ===== */
.tips-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.tips-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.tips-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.tips-title {
    font-size: 20px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
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

/* ===== PROFIT CALCULATOR ===== */
.profit-calculator-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.profit-calculator-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.profit-calculator-title {
    font-size: 20px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.profit-calculator-body {
    padding: 24px;
}

.profit-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #F3F4F6;
}

.profit-item:last-child {
    border-bottom: none;
}

.profit-label {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
}

.profit-value {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
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
    
    .form-row {
        grid-template-columns: 1fr;
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
    
    .tips-content, .profit-calculator-body {
        padding: 16px;
    }
    
    .tips-header, .profit-calculator-header {
        padding: 20px;
    }
    
    .tip-item {
        flex-direction: column;
        gap: 12px;
    }
}
</style>
@endsection