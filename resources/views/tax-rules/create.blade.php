@extends('layouts.app')

@section('title', 'Tambah Aturan Pajak')
@section('page-title', 'Tambah Aturan Pajak')

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
                    <span>Tambah Aturan Pajak</span>
                </div>
                <h1 class="page-title">Tambah Aturan Pajak Baru</h1>
                <p class="page-subtitle">Buat aturan pajak untuk produk dan layanan</p>
            </div>
            <div class="header-right">
                <div class="date-indicator">
                    <div class="date-display">
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

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Form Section -->
            <section class="form-section">
                <div class="form-card">
                    <div class="form-header">
                        <div class="form-title-wrapper">
                            <h2 class="form-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                                Tambah Aturan Pajak
                            </h2>
                            <div class="form-status">
                                <div class="status-dot active"></div>
                                <span>Mode Tambah</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <form method="POST" action="{{ route('tax-rules.store') }}" id="taxRuleForm">
                            @csrf

                            <!-- Type Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.74-1.51-2.75-1.5-4.5A5.5 5.5 0 0 0 7.5 8c1.76 0 3-.5 4.5-2 1.74 1.51 2.75 1.5 4.5A5.5 5.5 0 0 0 12 19"></path>
                                    </svg>
                                    Tipe Pajak <span class="required">*</span>
                                </label>
                                <div class="tax-type-cards">
                                    <div class="tax-type-card input" data-type="input" onclick="selectTaxType('input')">
                                        <div class="tax-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 3v18h18"></path>
                                                <path d="M18.7 8l-5.1 5.2-2.8-2.7L7 14.3"></path>
                                            </svg>
                                        </div>
                                        <div class="tax-type-content">
                                            <div class="tax-type-title">Pajak Masukan</div>
                                            <div class="tax-type-description">Pajak yang dibayar saat membeli</div>
                                        </div>
                                    </div>
                                    <div class="tax-type-card output" data-type="output" onclick="selectTaxType('output')">
                                        <div class="tax-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M21 3v18H3"></path>
                                                <path d="M7 16l5.1-5.2 2.8 2.7L18 9.7"></path>
                                            </svg>
                                        </div>
                                        <div class="tax-type-content">
                                            <div class="tax-type-title">Pajak Keluaran</div>
                                            <div class="tax-type-description">Pajak yang diterima saat menjual</div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="type" id="taxTypeInput" value="{{ old('type') }}" required>
                                @error('type')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tax Rule Name -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Nama Aturan Pajak <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control {{ $errors->has('name') ? 'error' : '' }}"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="contoh: PPN 11%, PPh 22, Non Pajak" required>
                                @error('name')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Percentage -->
                            <div class="form-group">
                                <label for="percentage" class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Persentase (%) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="100" class="form-control {{ $errors->has('percentage') ? 'error' : '' }}"
                                           id="percentage" name="percentage" value="{{ old('percentage', 0) }}"
                                           placeholder="0.00" required>
                                    <span class="input-suffix">%</span>
                                </div>
                                @error('percentage')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Code -->
                            <div class="form-group">
                                <label for="code" class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M10 9l-6 6 6 6"></path>
                                        <path d="M20 4v7a4 4 0 0 1-4 4H5"></path>
                                    </svg>
                                    Kode Aturan (Opsional)
                                </label>
                                <input type="text" class="form-control {{ $errors->has('code') ? 'error' : '' }}"
                                       id="code" name="code" value="{{ old('code') }}"
                                       placeholder="contoh: PPN11, PPH22, NONPAJAK">
                                <div class="form-help">Kode unik untuk identifikasi aturan pajak</div>
                                @error('code')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                    Deskripsi (Opsional)
                                </label>
                                <textarea class="form-control {{ $errors->has('description') ? 'error' : '' }}"
                                          id="description" name="description" rows="3"
                                          placeholder="Deskripsikan aturan pajak ini...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    Status Aktif
                                </label>
                                <div class="status-toggle">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="toggle-label">Aturan pajak ini aktif</span>
                                </div>
                                @error('is_active')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="{{ route('tax-rules.index') }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    Batal
                                </a>
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5-2 2H5a2 2 0 0 1-2 2v14a2 2 0 0 1 2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                    </svg>
                                    Simpan Aturan Pajak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Info Section -->
            <section class="info-section">
                <div class="info-card">
                    <div class="info-header">
                        <div class="info-icon info">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <h2 class="info-title">Informasi Aturan Pajak</h2>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <div class="info-label">Pajak Masukan</div>
                            <div class="info-value">Pajak yang dibayar ketika membeli barang/jasa</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Pajak Keluaran</div>
                            <div class="info-value">Pajak yang diterima ketika menjual barang/jasa</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Persentase</div>
                            <div class="info-value">Nilai persentase pajak (0-100%)</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Kode</div>
                            <div class="info-value">Kode unik untuk identifikasi (opsional)</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tax type selection
    const taxTypeCards = document.querySelectorAll('.tax-type-card');
    const taxTypeInput = document.getElementById('taxTypeInput');

    // Auto-select if there's old input
    const oldType = taxTypeInput.value;
    if (oldType) {
        selectTaxType(oldType);
    }

    // Form validation
    const taxRuleForm = document.getElementById('taxRuleForm');
    if (taxRuleForm) {
        taxRuleForm.addEventListener('submit', function(e) {
            // Basic validation
            const taxType = document.getElementById('taxTypeInput').value;
            const taxName = document.getElementById('name').value;
            const taxPercentage = document.getElementById('percentage').value;

            if (!taxType || !taxName || taxPercentage === '') {
                e.preventDefault();
                showNotification('Mohon lengkapi semua field yang diperlukan', 'error');
                return false;
            }

            return true;
        });
    }
});

function selectTaxType(type) {
    // Remove selected class from all cards
    document.querySelectorAll('.tax-type-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    document.querySelector(`[data-type="${type}"]`).classList.add('selected');

    // Set hidden input value
    document.getElementById('taxTypeInput').value = type;
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
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
}

.date-indicator {
    background: #FFFFFF;
    border-radius: 16px;
    padding: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border: 1px solid #E5E7EB;
}

.date-display {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
}

.date-day {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    line-height: 1;
}

.date-details {
    display: flex;
    flex-direction: column;
}

.date-month {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.date-year {
    font-size: 12px;
    color: #9CA3AF;
}

/* ===== MAIN CONTENT ===== */
.dashboard-main {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

/* ===== FORM SECTION ===== */
.form-section {
    margin-bottom: 24px;
}

.form-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 28px;
    border-bottom: 1px solid #F3F4F6;
}

.form-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    display: flex;
    align-items: center;
}

.form-title svg {
    color: #4F46E5;
    margin-right: 8px;
}

.form-status {
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

.form-body {
    padding: 28px;
}

.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-label svg {
    color: #4F46E5;
    width: 20px;
    height: 20px;
}

.required {
    color: #EF4444;
    margin-left: 4px;
}

/* ===== TAX TYPE CARDS ===== */
.tax-type-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-bottom: 8px;
}

.tax-type-card {
    background: #FFFFFF;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    padding: 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 16px;
}

.tax-type-card:hover {
    border-color: #4F46E5;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.tax-type-card.selected {
    border-color: #4F46E5;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(124, 58, 237, 0.05) 100%);
}

.tax-type-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.tax-type-card.input .tax-type-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
}

.tax-type-card.output .tax-type-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
}

.tax-type-content {
    flex-grow: 1;
}

.tax-type-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.tax-type-description {
    font-size: 13px;
    color: #6B7280;
}

/* ===== FORM CONTROLS ===== */
.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;
    font-size: 14px;
    transition: all 0.2s ease;
    background: #FFFFFF;
}

.form-control:focus {
    outline: none;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-control.error {
    border-color: #EF4444;
}

.form-control[rows] {
    resize: vertical;
    min-height: 80px;
}

.input-group {
    position: relative;
    display: flex;
}

.input-group .form-control {
    padding-right: 60px;
    border-right: none;
    border-radius: 12px 0 0 12px;
}

.input-suffix {
    position: absolute;
    right: 0;
    top: 0;
    height: 100%;
    padding: 12px 16px;
    background: #F3F4F6;
    border: 2px solid #E5E7EB;
    border-left: none;
    border-radius: 0 12px 12px 0;
    color: #6B7280;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.form-help {
    font-size: 12px;
    color: #6B7280;
    margin-top: 4px;
}

.form-error {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    color: #EF4444;
    font-size: 14px;
}

.form-error svg {
    flex-shrink: 0;
}

/* ===== STATUS TOGGLE ===== */
.status-toggle {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #4F46E5;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.toggle-label {
    font-size: 14px;
    color: #374151;
    font-weight: 500;
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 8px;
    border-top: 1px solid #F3F4F6;
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
    transition: all 0.2s ease;
    text-decoration: none;
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
    border: 2px solid #E5E7EB;
}

.btn-secondary:hover {
    background: #F9FAFB;
    color: #374151;
}

/* ===== INFO SECTION ===== */
.info-section {
    margin-bottom: 24px;
}

.info-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #4F46E5 0%, #7C3AED 100%);
}

.info-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
}

.info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.info-icon.info {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 14px;
    color: #6B7280;
    font-weight: 500;
}

.info-value {
    font-size: 16px;
    color: #111827;
    font-weight: 600;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-main {
        grid-template-columns: 1fr;
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

    .tax-type-cards {
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

    .form-header {
        padding: 20px;
    }

    .form-body {
        padding: 20px;
    }

    .info-card {
        padding: 20px;
    }

    .tax-type-card {
        padding: 16px;
    }
}
</style>
@endsection