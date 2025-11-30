@extends('layouts.app')

@section('title', 'Tambah Akun Baru')
@section('page-title', 'Tambah Akun Baru')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                    <span>Akun Baru</span>
                </div>
                <h1 class="page-title">Tambah Akun Baru</h1>
                <p class="page-subtitle">Buat akun baru untuk Chart of Accounts</p>
            </div>
            <div class="header-right">
                <a href="{{ route('chart-of-accounts.index') }}" class="btn-secondary">
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
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                            </svg>
                        </div>
                        <h2 class="form-title">Informasi Akun</h2>
                        <p class="form-subtitle">Masukkan detail akun yang akan dibuat</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('chart-of-accounts.store') }}">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Account Code -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        Kode Akun <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           id="code"
                                           name="code"
                                           value="{{ old('code') }}"
                                           class="form-control @error('code') is-invalid @enderror"
                                           placeholder="Contoh: 1111"
                                           required>
                                    @error('code')
                                        <div class="form-error">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <p class="form-help">Kode unik untuk mengidentifikasi akun</p>
                                </div>

                                <!-- Account Name -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        Nama Akun <span class="required">*</span>
                                    </label>
                                    <input type="text"
                                           id="name"
                                           name="name"
                                           value="{{ old('name') }}"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Contoh: Kas Toko"
                                           required>
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

                                <!-- Account Type -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                        </svg>
                                        Tipe Akun <span class="required">*</span>
                                    </label>
                                    <select id="type"
                                            name="type"
                                            class="form-control @error('type') is-invalid @enderror"
                                            required>
                                        <option value="">Pilih Tipe Akun</option>
                                        <option value="asset" {{ old('type') == 'asset' ? 'selected' : '' }}>Asset</option>
                                        <option value="liability" {{ old('type') == 'liability' ? 'selected' : '' }}>Kewajiban</option>
                                        <option value="equity" {{ old('type') == 'equity' ? 'selected' : '' }}>Modal</option>
                                        <option value="revenue" {{ old('type') == 'revenue' ? 'selected' : '' }}>Pendapatan</option>
                                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Beban</option>
                                    </select>
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

                                <!-- Account Category -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 0 1 0 2.828l-7 7a2 2 0 0 1-2.828 0l-7-7A1.994 1.994 0 0 1 3 12V7a4 4 0 0 1 4-4z"/>
                                        </svg>
                                        Kategori Akun <span class="required">*</span>
                                    </label>
                                    <select id="category"
                                            name="category"
                                            class="form-control @error('category') is-invalid @enderror"
                                            required>
                                        <option value="">Pilih Kategori Akun</option>
                                        <optgroup label="Asset">
                                            <option value="current_asset" {{ old('category') == 'current_asset' ? 'selected' : '' }}>Asset Lancar</option>
                                            <option value="fixed_asset" {{ old('category') == 'fixed_asset' ? 'selected' : '' }}>Asset Tetap</option>
                                        </optgroup>
                                        <optgroup label="Kewajiban">
                                            <option value="current_liability" {{ old('category') == 'current_liability' ? 'selected' : '' }}>Kewajiban Lancar</option>
                                            <option value="long_term_liability" {{ old('category') == 'long_term_liability' ? 'selected' : '' }}>Kewajiban Jangka Panjang</option>
                                        </optgroup>
                                        <optgroup label="Modal">
                                            <option value="owner_equity" {{ old('category') == 'owner_equity' ? 'selected' : '' }}>Modal Pemilik</option>
                                            <option value="retained_earnings" {{ old('category') == 'retained_earnings' ? 'selected' : '' }}>Laba Ditahan</option>
                                        </optgroup>
                                        <optgroup label="Pendapatan">
                                            <option value="sales_revenue" {{ old('category') == 'sales_revenue' ? 'selected' : '' }}>Penjualan</option>
                                            <option value="other_revenue" {{ old('category') == 'other_revenue' ? 'selected' : '' }}>Pendapatan Lain</option>
                                        </optgroup>
                                        <optgroup label="Beban">
                                            <option value="cost_of_goods_sold" {{ old('category') == 'cost_of_goods_sold' ? 'selected' : '' }}>Harga Pokok Penjualan</option>
                                            <option value="operating_expense" {{ old('category') == 'operating_expense' ? 'selected' : '' }}>Beban Operasional</option>
                                            <option value="other_expense" {{ old('category') == 'other_expense' ? 'selected' : '' }}>Beban Lain</option>
                                        </optgroup>
                                    </select>
                                    @error('category')
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

                                <!-- Parent Account -->
                                <div class="form-group">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <circle cx="12" cy="12" r="6"></circle>
                                            <circle cx="12" cy="12" r="2"></circle>
                                        </svg>
                                        Akun Induk
                                    </label>
                                    <select id="parent_id"
                                            name="parent_id"
                                            class="form-control">
                                        <option value="">Pilih Akun Induk (Opsional)</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->code }} - {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="form-help">Biarkan kosong jika ini adalah akun utama</p>
                                </div>

                                <!-- Description -->
                                <div class="form-group md:col-span-2">
                                    <label class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        Deskripsi
                                    </label>
                                    <textarea id="description"
                                              name="description"
                                              rows="3"
                                              class="form-control"
                                              placeholder="Deskripsi tambahan untuk akun ini">{{ old('description') }}</textarea>
                                    <p class="form-help">Opsional: Berikan penjelasan lebih detail tentang akun ini</p>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="{{ route('chart-of-accounts.index') }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    <span>Batal</span>
                                </a>
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    <span>Simpan Akun</span>
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
                            Panduan Membuat Akun
                        </h3>
                    </div>

                    <div class="tips-content">
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
                                <h4>Kode Akun</h4>
                                <ul class="tip-list">
                                    <li>1xxx: Asset</li>
                                    <li>2xxx: Kewajiban</li>
                                    <li>3xxx: Modal</li>
                                    <li>4xxx: Pendapatan</li>
                                    <li>5xxx: Beban</li>
                                </ul>
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
                                <h4>Saldo Normal</h4>
                                <ul class="tip-list">
                                    <li>Asset: Debit</li>
                                    <li>Kewajiban: Kredit</li>
                                    <li>Modal: Kredit</li>
                                    <li>Pendapatan: Kredit</li>
                                    <li>Beban: Debit</li>
                                </ul>
                            </div>
                        </div>

                        <div class="tip-item">
                            <div class="tip-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                                </svg>
                            </div>
                            <div class="tip-text">
                                <h4>Struktur Akun</h4>
                                <p>Akun induk adalah akun utama yang dapat memiliki akun anak di bawahnya. Ini membantu dalam mengorganisir struktur akun secara hierarkis.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Auto-generate account code based on type
document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    const codeInput = document.getElementById('code');

    if (codeInput.value === '' || codeInput.value.match(/^\d{4}$/)) {
        let prefix = '';
        switch(type) {
            case 'asset': prefix = '1'; break;
            case 'liability': prefix = '2'; break;
            case 'equity': prefix = '3'; break;
            case 'revenue': prefix = '4'; break;
            case 'expense': prefix = '5'; break;
        }

        if (prefix && codeInput.value === '') {
            // Find next available code
            fetch(`/api/chart-of-accounts/next-code?type=${type}`)
                .then(response => response.json())
                .then(data => {
                    codeInput.value = data.code;
                })
                .catch(() => {
                    codeInput.value = prefix + '001';
                });
        }
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
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
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
    padding: 32px;
    text-align: center;
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
    margin-bottom: 8px;
}

.form-subtitle {
    font-size: 14px;
    color: #047857;
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

.form-help {
    font-size: 13px;
    color: #6B7280;
    margin-top: 8px;
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
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
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

.tip-list {
    list-style: none;
    padding-left: 0;
    margin: 8px 0 0;
}

.tip-list li {
    font-size: 14px;
    color: #6B7280;
    margin-bottom: 4px;
    padding-left: 16px;
    position: relative;
}

.tip-list li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: #22C55E;
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
}
</style>
@endsection