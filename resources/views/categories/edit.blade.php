@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('page-title', 'Edit Kategori')

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
                    <span>Edit Kategori</span>
                </div>
                <h1 class="page-title">Edit Kategori</h1>
                <p class="page-subtitle">Perbarui informasi kategori "{{ $category->name }}"</p>
            </div>
            <div class="header-right">
                <a href="{{ route('categories.show', $category) }}" class="btn-secondary">
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
                        <h2 class="form-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                            Informasi Kategori
                        </h2>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('categories.update', $category) }}">
                            @csrf
                            @method('PUT')

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
                                           id="name" name="name" value="{{ old('name', $category->name) }}"
                                           placeholder="contoh: Gaji, Makanan, Transportasi" required>
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

                            <!-- Category Type -->
                            <div class="form-group">
                                <label for="type" class="form-label">
                                    Tipe Kategori <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-icon">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                        </svg>
                                    </div>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Pilih Tipe Kategori</option>
                                        <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>Pemasukan</option>
                                        <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                                    </select>
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

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="{{ route('categories.show', $category) }}" class="btn-secondary">
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
                                    Perbarui Kategori
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
                                <p>Mengubah tipe kategori dapat memengaruhi cara penggunaannya dalam transaksi. Pastikan perubahan ini sesuai dengan kebutuhan Anda.</p>
                            </div>
                        </div>

                        <div class="warning-tips">
                            <h5>Tips:</h5>
                            <ul>
                                <li>Pastikan nama kategori masih relevan dengan transaksi yang ada</li>
                                <li>Perubahan tipe kategori akan memengaruhi laporan keuangan</li>
                                <li>Transaksi yang sudah ada tidak akan terpengaruh oleh perubahan ini</li>
                            </ul>
                        </div>
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
    color: #F59E0B;
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
    border-color: #F59E0B;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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