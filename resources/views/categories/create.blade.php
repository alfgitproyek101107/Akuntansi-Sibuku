@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                    </svg>
                    <span>Kategori Baru</span>
                </div>
                <h1 class="page-title">Tambah Kategori Baru</h1>
                <p class="page-subtitle">Buat kategori baru untuk mengorganisir transaksi Anda</p>
            </div>
            <div class="header-right">
                <a href="{{ route('categories.index') }}" class="btn-secondary">
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
            <div class="form-section">
                <div class="form-container">
                    <div class="form-header">
                        <h2 class="form-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                            </svg>
                            Detail Kategori
                        </h2>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('categories.store') }}" id="categoryForm">
                            @csrf

                            <!-- Category Type Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    Tipe Kategori <span class="required">*</span>
                                </label>
                                <div class="category-type-cards">
                                    <div class="category-type-card income" data-type="income" onclick="selectCategoryType('income')">
                                        <div class="category-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </div>
                                        <div class="category-type-title">Pemasukan</div>
                                        <p class="category-type-description">
                                            Untuk uang yang masuk seperti gaji, freelance, bonus, dll.
                                        </p>
                                    </div>
                                    <div class="category-type-card expense" data-type="expense" onclick="selectCategoryType('expense')">
                                        <div class="category-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </div>
                                        <div class="category-type-title">Pengeluaran</div>
                                        <p class="category-type-description">
                                            Untuk uang yang keluar seperti makanan, transportasi, hiburan, dll.
                                        </p>
                                    </div>
                                </div>
                                <input type="hidden" name="type" id="categoryTypeInput" required>
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

                            <!-- Category Name -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    Nama Kategori <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                        </svg>
                                    </div>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="contoh: Gaji, Makanan, Transportasi, Bonus" required>
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

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="{{ route('categories.index') }}" class="btn-secondary">
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
                                    Simpan Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Help Sidebar -->
            <div class="help-section">
                <div class="help-container">
                    <div class="help-header">
                        <h3 class="help-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            Panduan
                        </h3>
                    </div>

                    <div class="help-content">
                        <div class="help-section">
                            <h4 class="help-section-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 11l3 3L22 4"></path>
                                    <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                                </svg>
                                Contoh Kategori
                            </h4>
                            <div class="help-examples">
                                <div class="example-category">
                                    <strong>Pemasukan:</strong> Gaji, Freelance, Bonus, Investasi, Hadiah
                                </div>
                                <div class="example-category">
                                    <strong>Pengeluaran:</strong> Makanan, Transportasi, Belanja, Tagihan, Hiburan
                                </div>
                            </div>
                        </div>

                        <div class="help-tip">
                            <div class="help-tip-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Tips Penting
                            </div>
                            <p class="help-tip-text">
                                Pilih tipe kategori yang sesuai dengan arus uang. Kategori yang tepat akan memudahkan
                                Anda menganalisis pengeluaran dan pemasukan secara lebih akurat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function selectCategoryType(type) {
    // Remove selected class from all cards
    document.querySelectorAll('.category-type-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    document.querySelector(`[data-type="${type}"]`).classList.add('selected');

    // Set hidden input value
    document.getElementById('categoryTypeInput').value = type;
}

// Auto-select if there's old input
document.addEventListener('DOMContentLoaded', function() {
    const oldType = '{{ old('type') }}';
    if (oldType) {
        selectCategoryType(oldType);
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
    top: 50%;
    transform: translateY(-50%);
    color: #9CA3AF;
    pointer-events: none;
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

/* ===== CATEGORY TYPE CARDS ===== */
.category-type-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.category-type-card {
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

.category-type-card::before {
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

.category-type-card:hover {
    border-color: #D1D5DB;
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
}

.category-type-card:hover::before {
    transform: scaleX(1);
}

.category-type-card.selected {
    border-color: #4F46E5;
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    box-shadow: 0 8px 25px rgba(79, 70, 229, 0.15);
}

.category-type-card.selected::before {
    transform: scaleX(1);
}

.category-type-card.income {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.category-type-card.expense {
    --color-start: #EF4444;
    --color-end: #DC2626;
}

.category-type-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    transition: all 0.3s ease;
}

.category-type-card.income .category-type-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
}

.category-type-card.expense .category-type-icon {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    color: white;
}

.category-type-card:hover .category-type-icon {
    transform: scale(1.1);
}

.category-type-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.category-type-description {
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

/* ===== HELP SECTION ===== */
.help-section {
    margin-bottom: 48px;
}

.help-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    position: sticky;
    top: 32px;
}

.help-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 20px;
    border-bottom: 1px solid #E5E7EB;
}

.help-title {
    font-size: 18px;
    font-weight: 700;
    color: #92400E;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.help-content {
    padding: 24px;
}

.help-section {
    margin-bottom: 24px;
}

.help-section:last-child {
    margin-bottom: 0;
}

.help-section-title {
    font-size: 16px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.help-section-title svg {
    color: #F59E0B;
}

.help-examples {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 16px;
}

.example-category {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 14px;
    color: #4B5563;
}

.example-category:last-child {
    margin-bottom: 0;
}

.help-tip {
    background: #F0F9FF;
    border: 1px solid #BAE6FD;
    border-radius: 12px;
    padding: 16px;
    margin-top: 16px;
}

.help-tip-title {
    font-size: 14px;
    font-weight: 600;
    color: #0369A1;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.help-tip-text {
    color: #0284C7;
    margin: 0;
    font-size: 14px;
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
    
    .help-container {
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
    
    .category-type-cards {
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
    
    .category-type-card {
        padding: 20px;
    }
    
    .category-type-icon {
        width: 50px;
        height: 50px;
    }
    
    .help-content {
        padding: 16px;
    }
}
</style>
@endsection