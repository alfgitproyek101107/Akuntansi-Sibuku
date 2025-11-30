@extends('layouts.app')

@section('title', 'Edit Pergerakan Stok')
@section('page-title', 'Edit Pergerakan Stok')

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
                    <span>Edit Pergerakan Stok</span>
                </div>
                <h1 class="page-title">Edit Pergerakan Stok</h1>
                <p class="page-subtitle">
                    Mengedit: <strong>{{ $stockMovement->product->name ?? 'N/A' }}</strong> - 
                    <span class="movement-badge {{ $stockMovement->type === 'in' ? 'in' : 'out' }}">
                        {{ $stockMovement->type === 'in' ? 'Masuk' : 'Keluar' }} {{ $stockMovement->quantity }}
                    </span>
                </p>
            </div>
            <div class="header-right">
                <a href="{{ route('stock-movements.show', $stockMovement) }}" class="btn-secondary">
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
                        <h2 class="form-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Edit Detail Pergerakan
                        </h2>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('stock-movements.update', $stockMovement) }}" id="stockMovementForm">
                            @csrf
                            @method('PUT')

                            <!-- Product Selection -->
                            <div class="form-group">
                                <label for="product_id" class="form-label">
                                    Produk <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 1 1.73z"></path>
                                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                                        </svg>
                                    </div>
                                    <select class="form-control @error('product_id') is-invalid @enderror"
                                            id="product_id" name="product_id" required>
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id', $stockMovement->product_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }} (Stok Saat Ini: {{ $product->stock_quantity }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('product_id')
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

                            <!-- Movement Type Selection -->
                            <div class="form-group">
                                <label for="type" class="form-label">
                                    Tipe Pergerakan <span class="required">*</span>
                                </label>
                                <div class="movement-type-cards">
                                    <div class="movement-type-card in {{ old('type', $stockMovement->type) == 'in' ? 'selected' : '' }}" data-type="in" onclick="selectMovementType('in')">
                                        <div class="movement-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 5v14m-7-7h14"></path>
                                            </svg>
                                        </div>
                                        <div class="movement-type-title">Stok Masuk</div>
                                        <p class="movement-type-description">
                                            Menambah jumlah inventori. Digunakan untuk pembelian, retur dari pelanggan, atau penyesuaian positif.
                                        </p>
                                    </div>
                                    <div class="movement-type-card out {{ old('type', $stockMovement->type) == 'out' ? 'selected' : '' }}" data-type="out" onclick="selectMovementType('out')">
                                        <div class="movement-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M12 5v14m-7-7h14"></path>
                                            </svg>
                                        </div>
                                        <div class="movement-type-title">Stok Keluar</div>
                                        <p class="movement-type-description">
                                            Mengurangi jumlah inventori. Digunakan untuk penjualan, pemakaian internal, atau kerusakan.
                                        </p>
                                    </div>
                                </div>
                                <input type="hidden" name="type" id="movementTypeInput" value="{{ old('type', $stockMovement->type) }}" required>
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

                            <!-- Quantity and Date -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">
                                        Kuantitas <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                            </svg>
                                        </div>
                                        <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror"
                                               id="quantity" name="quantity" value="{{ old('quantity', $stockMovement->quantity) }}"
                                               placeholder="Masukkan jumlah" required>
                                    </div>
                                    @error('quantity')
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
                                    <label for="date" class="form-label">
                                        Tanggal <span class="required">*</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                        </div>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                               id="date" name="date" value="{{ old('date', $stockMovement->date->format('Y-m-d')) }}" required>
                                    </div>
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
                            </div>

                            <!-- Reference -->
                            <div class="form-group">
                                <label for="reference" class="form-label">
                                    Referensi
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                        </svg>
                                    </div>
                                    <input type="text" class="form-control @error('reference') is-invalid @enderror"
                                           id="reference" name="reference" value="{{ old('reference', $stockMovement->reference) }}"
                                           placeholder="Nomor pembelian, faktur, dll.">
                                </div>
                                @error('reference')
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

                            <!-- Notes -->
                            <div class="form-group">
                                <label for="notes" class="form-label">
                                    Catatan
                                </label>
                                <div class="input-group">
                                    <div class="input-icon textarea-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes" name="notes" rows="4"
                                              placeholder="Tambahkan catatan tambahan jika perlu">{{ old('notes', $stockMovement->notes) }}</textarea>
                                </div>
                                @error('notes')
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
                                <a href="{{ route('stock-movements.show', $stockMovement) }}" class="btn-secondary">
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
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info & Warning Sidebar -->
            <div class="info-section">
                <div class="info-container">
                    <div class="info-header">
                        <h3 class="info-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Informasi Saat Ini
                        </h3>
                    </div>

                    <div class="info-content">
                        <div class="info-item">
                            <h4 class="info-item-title">Produk</h4>
                            <p class="info-item-value">{{ $stockMovement->product->name ?? 'N/A' }}</p>
                        </div>
                        <div class="info-item">
                            <h4 class="info-item-title">Tipe</h4>
                            <div class="movement-type-badge {{ $stockMovement->type }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14m-7-7h14"></path>
                                </svg>
                                {{ $stockMovement->type === 'in' ? 'Stok Masuk' : 'Stok Keluar' }}
                            </div>
                        </div>
                        <div class="info-item">
                            <h4 class="info-item-title">Kuantitas</h4>
                            <p class="info-item-value quantity-display {{ $stockMovement->type === 'in' ? 'positive' : 'negative' }}">
                                {{ $stockMovement->type === 'in' ? '+' : '-' }}{{ $stockMovement->quantity }}
                            </p>
                        </div>
                        <div class="info-item">
                            <h4 class="info-item-title">Tanggal</h4>
                            <p class="info-item-value">{{ $stockMovement->date->format('d M Y') }}</p>
                        </div>
                        <div class="info-item">
                            <h4 class="info-item-title">Dibuat</h4>
                            <p class="info-item-value">{{ $stockMovement->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="warning-container">
                    <div class="warning-header">
                        <h3 class="warning-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Peringatan Penting
                        </h3>
                    </div>

                    <div class="warning-content">
                        <div class="warning-message">
                            <p class="warning-text">
                                <strong>Mengubah pergerakan stok akan mempengaruhi:</strong>
                            </p>
                            <ul class="warning-list">
                                <li>Jumlah stok saat ini untuk produk ini</li>
                                <li>Akurasi laporan dan riwayat inventori</li>
                                <li>Nilai aset inventori</li>
                            </ul>
                            <p class="warning-note">
                                Pastikan perubahan yang Anda buat akurat dan dapat dipertanggungjawabkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function selectMovementType(type) {
    // Remove selected class from all cards
    document.querySelectorAll('.movement-type-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    document.querySelector(`[data-type="${type}"]`).classList.add('selected');

    // Set hidden input value
    document.getElementById('movementTypeInput').value = type;
}

// Auto-select if there's old input
document.addEventListener('DOMContentLoaded', function() {
    const oldType = '{{ old('type', $stockMovement->type) }}';
    if (oldType) {
        selectMovementType(oldType);
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
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
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
    display: flex;
    align-items: center;
    gap: 8px;
}

.movement-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

.movement-badge.in {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.movement-badge.out {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
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
    background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.form-title svg {
    color: #4F46E5;
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
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
    display: block;
}

.required {
    color: #EF4444;
}

.input-group {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 14px;
    color: #9CA3AF;
    pointer-events: none;
}

.textarea-icon {
    top: 14px;
}

.form-control {
    width: 100%;
    padding: 14px 16px 14px 48px;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

textarea.form-control {
    padding-top: 14px;
    padding-left: 48px;
    resize: vertical;
    min-height: 100px;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px;
    padding-right: 40px;
}

.form-control:focus {
    outline: none;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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

/* ===== MOVEMENT TYPE CARDS ===== */
.movement-type-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.movement-type-card {
    border: 2px solid #E5E7EB;
    border-radius: 16px;
    padding: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    background: #FFFFFF;
    position: relative;
    overflow: hidden;
}

.movement-type-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.movement-type-card:hover {
    border-color: #D1D5DB;
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

.movement-type-card:hover::before {
    transform: scaleX(1);
}

.movement-type-card.selected {
    border-color: #4F46E5;
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.15);
}

.movement-type-card.selected::before {
    transform: scaleX(1);
}

.movement-type-card.in {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.movement-type-card.out {
    --color-start: #EF4444;
    --color-end: #DC2626;
}

.movement-type-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    transition: all 0.3s ease;
}

.movement-type-card.in .movement-type-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
}

.movement-type-card.out .movement-type-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
}

.movement-type-card:hover .movement-type-icon {
    transform: scale(1.1);
}

.movement-type-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.movement-type-description {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
    line-height: 1.5;
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

/* ===== INFO SECTION ===== */
.info-section {
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
    background: linear-gradient(135deg, #DBEAFE 0%, #BFDBFE 100%);
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.info-title {
    font-size: 18px;
    font-weight: 700;
    color: #1E40AF;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-content {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    padding: 12px;
    background: #F8FAFC;
    border-radius: 12px;
}

.info-item-title {
    font-size: 14px;
    font-weight: 600;
    color: #6B7280;
    margin-bottom: 4px;
}

.info-item-value {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.quantity-display {
    font-size: 18px;
    font-weight: 700;
}

.quantity-display.positive {
    color: #22C55E;
}

.quantity-display.negative {
    color: #EF4444;
}

/* ===== WARNING CONTAINER ===== */
.warning-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
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
    padding: 20px;
}

.warning-message {
    background: #FEF3C7;
    border-radius: 12px;
    padding: 16px;
    border-left: 4px solid #F59E0B;
}

.warning-text {
    font-size: 14px;
    font-weight: 600;
    color: #92400E;
    margin-bottom: 8px;
}

.warning-list {
    margin: 8px 0 12px;
    padding-left: 20px;
}

.warning-list li {
    font-size: 14px;
    color: #78350F;
    margin-bottom: 4px;
}

.warning-note {
    font-size: 14px;
    color: #78350F;
    margin: 0;
    font-style: italic;
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
    
    .form-body {
        padding: 24px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    
    .movement-type-cards {
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
    
    .movement-type-card {
        padding: 20px;
    }
    
    .movement-type-icon {
        width: 50px;
        height: 50px;
    }
    
    .info-content, .warning-content {
        padding: 16px;
    }
}
</style>
@endsection