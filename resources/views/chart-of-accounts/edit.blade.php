@extends('layouts.app')

@section('title', 'Edit Akun - ' . $chartOfAccount->name)
@section('page-title', 'Edit Akun')

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
                    <span>Edit Akun</span>
                </div>
                <h1 class="page-title">{{ $chartOfAccount->name }}</h1>
                <p class="page-subtitle">Perbarui informasi akun untuk sistem akuntansi</p>
            </div>
            <div class="header-right">
                <a href="{{ route('chart-of-accounts.show', $chartOfAccount) }}" class="btn-secondary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                    <span>Lihat Detail</span>
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
                        <h2 class="form-title">Edit Informasi Akun</h2>
                        <p class="form-subtitle">Perbarui detail akun yang sudah ada</p>
                    </div>

                    <div class="form-body">
                        <form method="POST" action="{{ route('chart-of-accounts.update', $chartOfAccount) }}">
                            @csrf
                            @method('PUT')

                            <!-- Account Code -->
                            <div class="form-group">
                                <label for="code" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Kode Akun <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                       id="code" name="code" value="{{ old('code', $chartOfAccount->code) }}"
                                       placeholder="Contoh: 1101" required>
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
                            </div>

                            <!-- Account Name -->
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Nama Akun <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $chartOfAccount->name) }}"
                                       placeholder="Contoh: Kas Tunai" required>
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
                                <label for="type" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5-10-5z"></path>
                                    </svg>
                                    Tipe Akun <span class="required">*</span>
                                </label>
                                <select class="form-control @error('type') is-invalid @enderror"
                                        id="type" name="type" required>
                                    <option value="">Pilih Tipe Akun</option>
                                    <option value="asset" {{ old('type', $chartOfAccount->type) == 'asset' ? 'selected' : '' }}>Asset</option>
                                    <option value="liability" {{ old('type', $chartOfAccount->type) == 'liability' ? 'selected' : '' }}>Kewajiban</option>
                                    <option value="equity" {{ old('type', $chartOfAccount->type) == 'equity' ? 'selected' : '' }}>Ekuitas</option>
                                    <option value="revenue" {{ old('type', $chartOfAccount->type) == 'revenue' ? 'selected' : '' }}>Pendapatan</option>
                                    <option value="expense" {{ old('type', $chartOfAccount->type) == 'expense' ? 'selected' : '' }}>Beban</option>
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
                                <label for="category" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M7 7h.01M7 3a4 4 0 0 1 0 7 7 0 0 1 0 7 7 0 0 1 0-7-7 0 0 1-7 7z"></path>
                                    </svg>
                                    Kategori Akun
                                </label>
                                <select class="form-control @error('category') is-invalid @enderror"
                                        id="category" name="category" required>
                                    <option value="">Pilih Kategori Akun</option>
                                    <optgroup label="Asset">
                                        <option value="current_asset" {{ old('category', $chartOfAccount->category) == 'current_asset' ? 'selected' : '' }}>Asset Lancar</option>
                                        <option value="fixed_asset" {{ old('category', $chartOfAccount->category) == 'fixed_asset' ? 'selected' : '' }}>Asset Tetap</option>
                                    </optgroup>
                                    <optgroup label="Kewajiban">
                                        <option value="current_liability" {{ old('category', $chartOfAccount->category) == 'current_liability' ? 'selected' : '' }}>Kewajiban Lancar</option>
                                        <option value="long_term_liability" {{ old('category', $chartOfAccount->category) == 'long_term_liability' ? 'selected' : '' }}>Kewajiban Jangka Panjang</option>
                                    </optgroup>
                                    <optgroup label="Modal">
                                        <option value="owner_equity" {{ old('category', $chartOfAccount->category) == 'owner_equity' ? 'selected' : '' }}>Modal Pemilik</option>
                                        <option value="retained_earnings" {{ old('category', $chartOfAccount->category) == 'retained_earnings' ? 'selected' : '' }}>Laba Ditahan</option>
                                    </optgroup>
                                    <optgroup label="Pendapatan">
                                        <option value="sales_revenue" {{ old('category', $chartOfAccount->category) == 'sales_revenue' ? 'selected' : '' }}>Penjualan</option>
                                        <option value="other_revenue" {{ old('category', $chartOfAccount->category) == 'other_revenue' ? 'selected' : '' }}>Pendapatan Lain</option>
                                    </optgroup>
                                    <optgroup label="Beban">
                                        <option value="cost_of_goods_sold" {{ old('category', $chartOfAccount->category) == 'cost_of_goods_sold' ? 'selected' : '' }}>Harga Pokok Penjualan</option>
                                        <option value="operating_expense" {{ old('category', $chartOfAccount->category) == 'operating_expense' ? 'selected' : '' }}>Beban Operasional</option>
                                        <option value="other_expense" {{ old('category', $chartOfAccount->category) == 'other_expense' ? 'selected' : '' }}>Beban Lain</option>
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
                                <label for="parent_id" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                    Akun Induk
                                </label>
                                <select class="form-control"
                                        id="parent_id" name="parent_id">
                                    <option value="">Pilih Akun Induk (Opsional)</option>
                                    @foreach($parents as $parent)
                                        @if($parent->id !== $chartOfAccount->id)
                                            <option value="{{ $parent->id }}" {{ old('parent_id', $chartOfAccount->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->code }} - {{ $parent->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="form-text">Biarkan kosong jika akun ini bukan akun induk</p>
                            </div>

                            <!-- Normal Balance -->
                            <div class="form-group">
                                <label for="normal_balance" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5 10-5-10-5z"></path>
                                    </svg>
                                    Saldo Normal
                                </label>
                                <select class="form-control @error('normal_balance') is-invalid @enderror"
                                        id="normal_balance" name="normal_balance" required>
                                    <option value="debit" {{ old('normal_balance', $chartOfAccount->normal_balance) == 'debit' ? 'selected' : '' }}>Debit (Kurang)</option>
                                    <option value="credit" {{ old('normal_balance', $chartOfAccount->normal_balance) == 'credit' ? 'selected' : '' }}>Kredit (Tambah)</option>
                                </select>
                                @error('normal_balance')
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

                            <!-- Description -->
                            <div class="form-group">
                                <label for="description" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    Deskripsi
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description"
                                          rows="3">{{ old('description', $chartOfAccount->description) }}</textarea>
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
                                <p class="form-text">Opsional: Berikan deskripsi tambahan untuk akun ini</p>
                            </div>

                            <!-- Active Status -->
                            <div class="form-group">
                                <div class="form-checkbox">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $chartOfAccount->is_active) ? 'checked' : '' }}>
                                    <label for="is_active" class="form-checkbox-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20 6 9 17 4 12 9 9 17 4 4 12 4"></polyline>
                                        </svg>
                                        <span>Aktifkan akun ini</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-actions">
                                <a href="{{ route('chart-of-accounts.show', $chartOfAccount) }}" class="btn-secondary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <span>Lihat Detail</span>
                                </a>
                                <button type="submit" class="btn-primary">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 0-2 2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    <span>Simpan Perubahan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Current Information Section -->
            <div class="info-section">
                <div class="info-container">
                    <div class="info-header">
                        <div class="info-title-wrapper">
                            <h2 class="info-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2L2 7l10 5 10-5-10-5-10-5z"></path>
                                    <path d="M2 17l10 5 10-5-10-5-10-5z"></path>
                                </svg>
                                Informasi Saat Ini
                            </h2>
                        </div>
                    </div>
                    <div class="info-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Kode Akun</div>
                                    <div class="info-value">{{ $chartOfAccount->code }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5-10-5-10-5z"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Nama Akun</div>
                                    <div class="info-value">{{ $chartOfAccount->name }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5-10-5-10-5z"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Tipe Akun</div>
                                    <div class="info-value">
                                        <span class="account-type-badge {{ $chartOfAccount->type }}">
                                            {{ ucfirst($chartOfAccount->type) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2-2v16"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Kategori</div>
                                    <div class="info-value">{{ $chartOfAccount->category ? ucwords(str_replace('_', ' ', $chartOfAccount->category)) : '-' }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5-10-5-10-5z"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Saldo Normal</div>
                                    <div class="info-value">{{ ucfirst($chartOfAccount->normal_balance) }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2L2 7l10 5 10-5-10-5-10-5z"></path>
                                        <path d="M2 17l10 5 10-5-10-5-10-5z"></path>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Saldo Saat Ini</div>
                                    <div class="info-value balance-value {{ $chartOfAccount->balance < 0 ? 'balance-negative' : 'balance-positive' }}">
                                        Rp{{ number_format(abs($chartOfAccount->balance), 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Status</div>
                                    <div class="info-value">
                                        <span class="status-badge {{ $chartOfAccount->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $chartOfAccount->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Auto-generate account code based on type (optional for edit)
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
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'label Helvetica Neue', Arial, sans-serif;
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
    grid-template-columns: 1fr 1fr;
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

.form-text {
    font-size: 12px;
    color: #6B7280;
    margin-top: 8px;
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

.form-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-checkbox input {
    width: 20px;
    height: 20px;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    margin-right: 8px;
}

.form-checkbox-label {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
}

/* ===== INFO SECTION ===== */
.info-section {
    margin-bottom: 48px;
}

.info-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.info-header {
    padding: 24px 32px;
    border-bottom: 1px solid #F3F4F6;
}

.info-title-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}

.info-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin: 0;
}

.info-title svg {
    color: #4F46E5;
    margin-right: 8px;
}

.info-body {
    padding: 24px 32px;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding-bottom: 24px;
}

.info-item:last-child {
    padding-bottom: 0;
}

.info-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: rgba(34, 197, 94, 0.1);
    color: #4F46E5;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-content {
    flex-grow: 1;
}

.info-label {
    font-size: 13px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 4px;
}

.info-value {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.balance-positive {
    color: #22C55E;
}

.balance-negative {
    color: #EF4444;
}

.account-type-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.account-type.asset {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.account-type.liability {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.account-type.equity {
    background: rgba(59, 130, 246, 0.1);
    color: #3B82F6;
}

.account-type.revenue {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
}

.account-type.expense {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.status-inactive {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
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

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .content-grid {
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
    
    .form-body {
        padding: 24px;
    }
    
    .info-body {
        padding: 24px;
    }
    
    .info-grid {
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
        padding: 24px;
    }
    
    .form-body {
        padding: 16px;
    }
    
    .info-header {
        padding: 20px;
    }
    
    .info-body {
        padding: 16px;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding-bottom: 16px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection