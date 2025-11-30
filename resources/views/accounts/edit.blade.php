@extends('layouts.app')

@section('title', 'Edit Rekening')
@section('page-title', 'Edit Rekening')

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
                    <span>Edit Rekening</span>
                </div>
                <h1 class="page-title">Edit Informasi Rekening</h1>
                <p class="page-subtitle">Perbarui detail rekening yang sudah ada</p>
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
                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                </svg>
                                Edit Rekening
                            </h2>
                            <div class="form-status">
                                <div class="status-dot active"></div>
                                <span>Mode Edit</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <form method="POST" action="{{ route('accounts.update', $account) }}" id="accountForm">
                            @csrf
                            @method('PUT')

                            <!-- Account Type Selection -->
                            <div class="form-group">
                                <label class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

                            <!-- Initial Balance -->
                            <div class="form-group">
                                <label for="balance" class="form-label">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                                <a href="{{ route('accounts.show', $account) }}" class="btn-secondary">
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
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Warning Section -->
            <section class="warning-section">
                <div class="warning-card">
                    <div class="warning-header">
                        <div class="warning-title-wrapper">
                            <h2 class="warning-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 9v2m0 0a9 9 0 1 0 18 0 9 9 0 0 0-18 0z"></path>
                                </svg>
                                Peringatan Penting
                            </h2>
                        </div>
                    </div>
                    <div class="warning-content">
                        <div class="warning-message">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 9v2m0 0a9 9 0 1 0 18 0 9 9 0 0 0-18 0z"></path>
                            </svg>
                            <p>Mengubah saldo di sini tidak akan mempengaruhi riwayat transaksi Anda. Perubahan hanya untuk menyesuaikan saldo awal rekening ini.</p>
                        </div>
                        <div class="warning-tip">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343 3.343A8.001 8.001 0 018.364 0L5.5 10.5a8.001 8.001 0 018.364 0l-5.828 5.829a8.001 8.001 0 000 11.314 0l.547.547L12 21z"></path>
                            </svg>
                            <p>Pastikan saldo yang Anda masukkan benar dan sesuai dengan saldo aktual rekening Anda.</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Account type selection
    const accountTypeCards = document.querySelectorAll('.account-type-card');
    const accountTypeInput = document.getElementById('accountTypeInput');
    
    // Auto-select if there's old input
    const oldType = accountTypeInput.value;
    if (oldType) {
        selectAccountType(oldType);
    }
    
    // Form validation
    const accountForm = document.getElementById('accountForm');
    if (accountForm) {
        accountForm.addEventListener('submit', function(e) {
            // Basic validation
            const accountType = document.getElementById('accountTypeInput').value;
            const accountName = document.getElementById('name').value;
            const accountBalance = document.getElementById('balance').value;
            
            if (!accountType || !accountName || accountBalance === '') {
                e.preventDefault();
                // Show error notification
                showNotification('Mohon lengkapi semua field yang diperlukan', 'error');
                return false;
            }
            
            return true;
        });
    }
});

function selectAccountType(type) {
    // Remove selected class from all cards
    document.querySelectorAll('.account-type-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    document.querySelector(`[data-type="${type}"]`).classList.add('selected');
    
    // Set hidden input value
    document.getElementById('accountTypeInput').value = type;
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

/* ===== ACCOUNT TYPE CARDS ===== */
.account-type-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 8px;
}

.account-type-card {
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

.account-type-card:hover {
    border-color: #4F46E5;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.account-type-card.selected {
    border-color: #4F46E5;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(124, 58, 237, 0.05) 100%);
}

.account-type-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.account-type-card.savings .account-type-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
}

.account-type-card.checking .account-type-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
    color: white;
}

.account-type-card.credit .account-type-icon {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    color: white;
}

.account-type-content {
    flex-grow: 1;
}

.account-type-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.account-type-description {
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

.input-group {
    position: relative;
    display: flex;
}

.input-prefix {
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    padding: 12px 16px;
    background: #F3F4F6;
    border: 2px solid #E5E7EB;
    border-right: none;
    border-radius: 12px 0 0 12px;
    color: #6B7280;
    font-weight: 600;
}

.input-group .form-control {
    padding-left: 60px;
    border-left: none;
    border-radius: 0 12px 12px 0;
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

/* ===== WARNING SECTION ===== */
.warning-section {
    margin-bottom: 24px;
}

.warning-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.warning-header {
    background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
    padding: 20px 28px;
    border-bottom: 1px solid #F3F4F6;
}

.warning-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}

.warning-title {
    font-size: 18px;
    font-weight: 700;
    color: #92400E;
    display: flex;
    align-items: center;
}

.warning-title svg {
    margin-right: 8px;
}

.warning-content {
    padding: 24px 28px;
}

.warning-message {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 16px;
}

.warning-message svg {
    color: #F59E0B;
    flex-shrink: 0;
}

.warning-message p {
    color: #374151;
    font-size: 14px;
    line-height: 1.5;
}

.warning-tip {
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    border: 1px solid #DBEAFE;
    border-radius: 12px;
    padding: 16px;
}

.warning-tip-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: #0369A1;
    margin-bottom: 8px;
}

.warning-tip-title svg {
    width: 20px;
    height: 20px;
}

.warning-tip-text {
    color: #0369A1;
    font-size: 14px;
    line-height: 1.5;
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
    
    .form-header {
        padding: 20px;
    }
    
    .form-body {
        padding: 20px;
    }
    
    .warning-header {
        padding: 16px 20px;
    }
    
    .warning-content {
        padding: 16px 20px;
    }
    
    .account-type-card {
        padding: 16px;
    }
}
</style>
@endsection