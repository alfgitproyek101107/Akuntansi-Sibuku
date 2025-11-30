@extends('layouts.app')

@section('title', 'Tambah Rekening Baru')
@section('page-title', 'Rekening')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>Tambah Rekening</span>
                </div>
                <h1 class="page-title">Buat Rekening Baru</h1>
                <p class="page-subtitle">Tambahkan rekening baru untuk melacak keuangan Anda</p>
            </div>
            <div class="header-right">
                <div class="date-card">
                    <div class="date-content">
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
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <h2 class="form-title">Detail Rekening</h2>
                        <p class="form-subtitle">Masukkan informasi rekening dengan lengkap</p>
                    </div>
                    <div class="form-body">
                        <form method="POST" action="{{ route('accounts.store') }}" id="accountForm">
                            @csrf

                            <!-- Account Type Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.74-1.51-2.75-1.5-4.5A5.5 5.5 0 0 0 7.5 8c1.76 0 3-.5 4.5-2 1.74 1.51 2.75 1.5 4.5A5.5 5.5 0 0 0 12 19"></path>
                                    </svg>
                                    Tipe Rekening <span class="required">*</span>
                                </label>
                                <div class="account-type-cards">
                                    <div class="account-type-card savings {{ old('type') == 'savings' ? 'selected' : '' }}" data-type="savings" onclick="selectAccountType('savings')">
                                        <div class="account-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.74-1.51-2.75-1.5-4.5A5.5 5.5 0 0 0 7.5 8c1.76 0 3-.5 4.5-2 1.74 1.51 2.75 1.5 4.5A5.5 5.5 0 0 0 12 19"></path>
                                            </svg>
                                        </div>
                                        <div class="account-type-content">
                                            <div class="account-type-title">Tabungan</div>
                                            <div class="account-type-description">Untuk menabung dengan bunga</div>
                                        </div>
                                    </div>
                                    <div class="account-type-card checking {{ old('type') == 'checking' ? 'selected' : '' }}" data-type="checking" onclick="selectAccountType('checking')">
                                        <div class="account-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                <line x1="1" y1="10" x2="23" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div class="account-type-content">
                                            <div class="account-type-title">Giro</div>
                                            <div class="account-type-description">Untuk transaksi sehari-hari</div>
                                        </div>
                                    </div>
                                    <div class="account-type-card credit {{ old('type') == 'credit' ? 'selected' : '' }}" data-type="credit" onclick="selectAccountType('credit')">
                                        <div class="account-type-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                                <line x1="1" y1="10" x2="23" y2="10"></line>
                                            </svg>
                                        </div>
                                        <div class="account-type-content">
                                            <div class="account-type-title">Kredit</div>
                                            <div class="account-type-description">Kartu kredit atau pinjaman</div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="type" id="accountTypeInput" value="{{ old('type') }}" required>
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

                            <!-- Account Name -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Nama Rekening <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control {{ $errors->has('name') ? 'error' : '' }}" 
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="contoh: Tabungan Utama, BCA, Kartu Kredit Mandiri" required>
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

                            <!-- Branch Selection (only show if user has multiple branches) -->
                            @if(isset($availableBranches) && $availableBranches->count() > 1)
                            <div class="form-group">
                                <label for="branch_id" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"></path>
                                        <path d="M8 5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2H8V5z"></path>
                                    </svg>
                                    Cabang <span class="required">*</span>
                                </label>
                                <select class="form-control {{ $errors->has('branch_id') ? 'error' : '' }}" id="branch_id" name="branch_id" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach($availableBranches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', session('active_branch') ?: auth()->user()->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                            @if($branch->is_demo)
                                                (Demo)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_id')
                                    <div class="form-error">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @endif

                            <!-- Initial Balance -->
                            <div class="form-group">
                                <label for="balance" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Saldo Awal <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-prefix">Rp</span>
                                    <input type="number" step="0.01" min="0" class="form-control {{ $errors->has('balance') ? 'error' : '' }}"
                                           id="balance" name="balance" value="{{ old('balance', 0) }}"
                                           placeholder="0.00" required>
                                </div>
                                @error('balance')
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
                                <a href="{{ route('accounts.index') }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                    Batal
                                </a>
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    Simpan Rekening
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Help Sidebar -->
            <section class="help-section">
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
                        <div class="help-item">
                            <div class="help-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.74-1.51-2.75-1.5-4.5A5.5 5.5 0 0 0 7.5 8c1.76 0 3-.5 4.5-2 1.74 1.51 2.75 1.5 4.5A5.5 5.5 0 0 0 12 19"></path>
                                </svg>
                            </div>
                            <div class="help-text">
                                <h4>Tipe Rekening</h4>
                                <p>Pilih tipe rekening yang sesuai dengan kebutuhan Anda. Tabungan untuk dana darurat, Giro untuk transaksi harian, atau Kredit untuk kartu kredit.</p>
                            </div>
                        </div>

                        <div class="help-item">
                            <div class="help-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </div>
                            <div class="help-text">
                                <h4>Nama Rekening</h4>
                                <p>Beri nama yang jelas dan mudah diingat untuk setiap rekening agar memudahkan identifikasi saat transaksi.</p>
                            </div>
                        </div>

                        <div class="help-item">
                            <div class="help-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                            </div>
                            <div class="help-text">
                                <h4>Saldo Awal</h4>
                                <p>Masukkan saldo yang sesuai dengan jumlah uang yang benar-benar ada di rekening Anda saat ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
function selectAccountType(type) {
    // Remove selected class from all account type cards
    document.querySelectorAll('.account-type-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    document.querySelector(`.account-type-card[data-type="${type}"]`).classList.add('selected');

    // Set hidden input value
    document.getElementById('accountTypeInput').value = type;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-select account type if pre-selected
    const selectedType = document.getElementById('accountTypeInput').value;
    if (selectedType) {
        document.querySelector(`.account-type-card[data-type="${selectedType}"]`).classList.add('selected');
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
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(100, 116, 139, 0.3);
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

.date-card {
    background: #FFFFFF;
    border-radius: 12px;
    padding: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.date-content {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 16px;
}

.date-day {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
}

.date-details {
    display: flex;
    flex-direction: column;
}

.date-month {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
}

.date-year {
    font-size: 12px;
    color: #9CA3AF;
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
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    padding: 32px;
    text-align: center;
    border-bottom: 1px solid #E5E7EB;
}

.form-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.form-subtitle {
    font-size: 14px;
    color: #6B7280;
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
    border-color: #64748B;
    box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
}

.form-control::placeholder {
    color: #9CA3AF;
}

.form-control.error {
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

/* ===== ACCOUNT TYPE CARDS ===== */
.account-type-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 16px;
}

.account-type-card {
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

.account-type-card:hover {
    border-color: #64748B;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.15);
}

.account-type-card.selected {
    border-color: #64748B;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.15);
}

.account-type-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.account-type-content {
    flex-grow: 1;
}

.account-type-title {
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.account-type-description {
    font-size: 12px;
    color: #6B7280;
    line-height: 1.4;
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
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(100, 116, 139, 0.4);
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

.help-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 24px;
}

.help-item:last-child {
    margin-bottom: 0;
}

.help-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(100, 116, 139, 0.1);
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.help-text h4 {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 8px;
}

.help-text p {
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
    
    .account-type-cards {
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
    
    .help-content {
        padding: 16px;
    }
    
    .help-item {
        flex-direction: column;
        gap: 12px;
    }
}
</style>
@endsection