@extends('layouts.app')

@section('title', 'Detail Kategori: ' . $productCategory->name)

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                    </svg>
                    <span>Detail Kategori</span>
                </div>
                <h1 class="page-title">{{ $productCategory->name }}</h1>
                <p class="page-subtitle">{{ $productCategory->description ?: 'Kategori produk tanpa deskripsi' }}</p>
            </div>
            <div class="header-right">
                <a href="{{ route('product-categories.edit', $productCategory) }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    <span>Edit Kategori</span>
                </a>
                <a href="{{ route('product-categories.index') }}" class="btn-secondary">
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
            <!-- Main Content Section -->
            <div class="main-content-section">
                <!-- Category Information -->
                <div class="info-container">
                    <div class="info-header">
                        <div class="info-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <h2 class="info-title">Informasi Kategori</h2>
                        <p class="info-subtitle">Detail informasi tentang kategori ini</p>
                    </div>
                    <div class="info-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">ID Kategori</div>
                                <div class="info-value">#{{ $productCategory->id }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nama Kategori</div>
                                <div class="info-value">{{ $productCategory->name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Deskripsi</div>
                                <div class="info-value">{{ $productCategory->description ?: 'Tidak ada deskripsi' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Produk</div>
                                <div class="info-value">{{ $products->count() }} produk</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Layanan</div>
                                <div class="info-value">{{ $services->count() }} layanan</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Total Nilai</div>
                                <div class="info-value">Rp{{ number_format($products->sum('price') + $services->sum('price'), 0, ',', '.') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dibuat</div>
                                <div class="info-value">{{ $productCategory->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Terakhir Update</div>
                                <div class="info-value">{{ $productCategory->updated_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span class="badge badge-success">Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div class="items-container">
                    <div class="items-header">
                        <div class="items-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </div>
                        <div class="items-title">
                            <h2>Produk dalam Kategori</h2>
                            <p>{{ $products->count() }} produk terdaftar</p>
                        </div>
                        <a href="{{ route('products.create', ['category' => $productCategory->id]) }}" class="btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Tambah Produk</span>
                        </a>
                    </div>

                    <div class="items-body">
                        @if($products->count() > 0)
                            <div class="items-list">
                                @foreach($products as $product)
                                    <div class="item-card">
                                        <div class="item-image">
                                            <div class="image-placeholder">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                    <polyline points="21 15 16 10 5 21"></polyline>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-name">{{ $product->name }}</div>
                                            @if($product->sku)
                                                <div class="item-meta">SKU: {{ $product->sku }}</div>
                                            @endif
                                            @if($product->description)
                                                <div class="item-description">{{ Str::limit($product->description, 100) }}</div>
                                            @endif
                                        </div>
                                        <div class="item-stats">
                                            <div class="price-badge">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                                            <div class="stock-info">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                                </svg>
                                                {{ $product->stock_quantity }} unit
                                            </div>
                                        </div>
                                        <div class="item-actions">
                                            <a href="{{ route('products.show', $product) }}" class="action-btn-small view-btn" title="Lihat Detail">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('products.edit', $product) }}" class="action-btn-small edit-btn" title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                    </svg>
                                </div>
                                <h3 class="empty-title">Tidak ada produk ditemukan</h3>
                                <p class="empty-description">Belum ada produk yang terkait dengan kategori ini</p>
                                <div class="empty-action">
                                    <a href="{{ route('products.create', ['category' => $productCategory->id]) }}" class="btn-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Tambah Produk Pertama</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Services Section -->
                <div class="items-container">
                    <div class="items-header">
                        <div class="items-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="9" r="2"></circle>
                                <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                            </svg>
                        </div>
                        <div class="items-title">
                            <h2>Layanan dalam Kategori</h2>
                            <p>{{ $services->count() }} layanan terdaftar</p>
                        </div>
                        <a href="{{ route('services.create', ['category' => $productCategory->id]) }}" class="btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Tambah Layanan</span>
                        </a>
                    </div>

                    <div class="items-body">
                        @if($services->count() > 0)
                            <div class="items-list">
                                @foreach($services as $service)
                                    <div class="item-card">
                                        <div class="item-image">
                                            <div class="image-placeholder service-placeholder">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="9" r="2"></circle>
                                                    <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="item-info">
                                            <div class="item-name">{{ $service->name }}</div>
                                            @if($service->description)
                                                <div class="item-description">{{ Str::limit($service->description, 100) }}</div>
                                            @endif
                                        </div>
                                        <div class="item-stats">
                                            <div class="price-badge">Rp{{ number_format($service->price, 0, ',', '.') }}</div>
                                            <div class="service-badge">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="9" r="2"></circle>
                                                    <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                                </svg>
                                                Layanan
                                            </div>
                                        </div>
                                        <div class="item-actions">
                                            <a href="{{ route('services.show', $service) }}" class="action-btn-small view-btn" title="Lihat Detail">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="{{ route('services.edit', $service) }}" class="action-btn-small edit-btn" title="Edit">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="9" r="2"></circle>
                                        <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                    </svg>
                                </div>
                                <h3 class="empty-title">Tidak ada layanan ditemukan</h3>
                                <p class="empty-description">Belum ada layanan yang terkait dengan kategori ini</p>
                                <div class="empty-action">
                                    <a href="{{ route('services.create', ['category' => $productCategory->id]) }}" class="btn-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span>Tambah Layanan Pertama</span>
                                    </a>
                                </div>
                            </div>
                        @endif
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
                            Tips Mengelola Kategori
                        </h3>
                    </div>

                    <div class="tips-content">
                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 11l3 3L22 4"></path>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Organisasi Produk</h4>
                                <p>Kategori yang baik membantu pengguna menemukan produk dengan lebih mudah.</p>
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
                                <h4>Deskripsi yang Jelas</h4>
                                <p>Berikan deskripsi yang informatif untuk membantu pengguna memahami kategori ini.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <rect x="7" y="7" width="10" height="10"></rect>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Struktur yang Logis</h4>
                                <p>Buat struktur kategori yang logis dan terorganisir dengan baik.</p>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Kategori Populer</h4>
                                <div class="example-tags">
                                    <span class="example-tag">Elektronik</span>
                                    <span class="example-tag">Pakaian</span>
                                    <span class="example-tag">Makanan</span>
                                    <span class="example-tag">Peralatan Rumah</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
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
                        <a href="{{ route('products.create', ['category' => $productCategory->id]) }}" class="action-card">
                            <div class="action-icon products-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                    <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Produk</h4>
                                <p class="action-description">Buat produk baru dalam kategori ini</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('services.create', ['category' => $productCategory->id]) }}" class="action-card">
                            <div class="action-icon services-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="M22 12v-2a4 4 0 0 0-4-4h-1"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Tambah Layanan</h4>
                                <p class="action-description">Buat layanan baru dalam kategori ini</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('product-categories.edit', $productCategory) }}" class="action-card">
                            <div class="action-icon edit-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Edit Kategori</h4>
                                <p class="action-description">Ubah informasi kategori ini</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>

                        <a href="{{ route('reports.index') }}" class="action-card">
                            <div class="action-icon reports-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <div class="action-content">
                                <h4 class="action-title">Lihat Laporan</h4>
                                <p class="action-description">Analisis penjualan dan inventori</p>
                            </div>
                            <div class="action-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
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

/* ===== MAIN CONTENT SECTION ===== */
.main-content-section {
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
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #065F46;
    margin-bottom: 4px;
}

.info-subtitle {
    font-size: 14px;
    color: #047857;
    margin: 0;
}

.info-body {
    padding: 24px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-label {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
}

.info-value {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

.badge-success {
    background: rgba(34, 197, 94, 0.1);
    color: #065f46;
}

/* ===== ITEMS CONTAINER ===== */
.items-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.items-header {
    background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.items-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
}

.items-title h2 {
    font-size: 20px;
    font-weight: 700;
    color: #065F46;
    margin-bottom: 4px;
}

.items-title p {
    font-size: 14px;
    color: #047857;
    margin: 0;
}

.items-body {
    padding: 24px;
}

/* ===== ITEMS LIST ===== */
.items-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.item-card {
    display: flex;
    align-items: center;
    padding: 16px;
    background: #F9FAFB;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.item-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    background: #FFFFFF;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    margin-right: 16px;
    flex-shrink: 0;
}

.image-placeholder {
    width: 100%;
    height: 100%;
    background: #E5E7EB;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9CA3AF;
}

.service-placeholder {
    background: #DCFCE7;
    color: #22C55E;
}

.item-info {
    flex-grow: 1;
    margin-right: 16px;
}

.item-name {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.item-meta {
    font-size: 12px;
    color: #9CA3AF;
    margin-bottom: 4px;
}

.item-description {
    font-size: 14px;
    color: #6B7280;
    line-height: 1.5;
}

.item-stats {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
    margin-right: 16px;
}

.price-badge {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
}

.stock-info,
.service-badge {
    display: flex;
    align-items: center;
    font-size: 12px;
    color: #6b7280;
    background: #e5e7eb;
    padding: 4px 8px;
    border-radius: 12px;
}

.service-badge {
    background: #DCFCE7;
    color: #065F46;
}

.item-actions {
    display: flex;
    gap: 8px;
}

.action-btn-small {
    width: 32px;
    height: 32px;
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

.action-btn-small:hover {
    background: #E5E7EB;
    color: #374151;
}

.view-btn:hover {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.edit-btn:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 60px 40px;
    color: #6B7280;
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
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
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

.example-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 8px;
}

.example-tag {
    background: #F3F4F6;
    color: #374151;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
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
    --color-start: #22C55E;
    --color-end: #10B981;
}

.action-card:nth-child(2) {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.action-card:nth-child(3) {
    --color-start: #667eea;
    --color-end: #764ba2;
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

.products-icon {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.services-icon {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.edit-icon {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
}

.reports-icon {
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
    color: #667eea;
    transform: translateX(4px);
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
    
    .items-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .item-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .item-image {
        margin-right: 0;
        width: 100%;
        height: 120px;
    }
    
    .item-info {
        margin-right: 0;
        width: 100%;
    }
    
    .item-stats {
        align-items: flex-start;
        width: 100%;
        margin-right: 0;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .items-body {
        padding: 16px;
    }
    
    .items-header {
        padding: 20px;
    }
    
    .info-body {
        padding: 16px;
    }
    
    .info-header {
        padding: 20px;
    }
    
    .tips-content, .actions-body {
        padding: 16px;
    }
    
    .tips-header, .actions-header {
        padding: 20px;
    }
    
    .action-card {
        padding: 16px;
    }
    
    .tip-item {
        flex-direction: column;
        gap: 12px;
    }
}
</style>
@endsection