@extends('layouts.app')

@section('title', 'Pengaturan Pajak')
@section('page-title', 'Pajak')

@section('content')
<div class="dashboard-layout">
    <!-- Header -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Pengaturan Pajak</span>
                </div>
                <h1 class="page-title">Konfigurasi Pajak</h1>
                <p class="page-subtitle">Atur pengaturan pajak dan integrasi CoreTax</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <form method="POST" action="{{ route('tax.settings.update') }}" class="settings-form">
            @csrf
            @method('PUT')

            <div class="settings-grid">
                <!-- Company Information -->
                <section class="settings-section">
                    <div class="section-header">
                        <h3 class="section-title">Informasi Perusahaan</h3>
                        <p class="section-description">Informasi dasar perusahaan untuk faktur pajak</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="company_name" class="form-label">Nama Perusahaan *</label>
                            <input type="text" class="form-control @error('company_name') error @enderror"
                                   id="company_name" name="company_name"
                                   value="{{ old('company_name', $taxSettings->company_name ?? '') }}" required>
                            @error('company_name')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="npwp" class="form-label">NPWP</label>
                            <input type="text" class="form-control @error('npwp') error @enderror"
                                   id="npwp" name="npwp"
                                   value="{{ old('npwp', $taxSettings->npwp ?? '') }}"
                                   placeholder="15 digit NPWP">
                            @error('npwp')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="company_address" class="form-label">Alamat Perusahaan</label>
                            <textarea class="form-control @error('company_address') error @enderror"
                                      id="company_address" name="company_address" rows="3">{{ old('company_address', $taxSettings->company_address ?? '') }}</textarea>
                            @error('company_address')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status PKP</label>
                            <div class="checkbox-group">
                                <input type="hidden" name="is_pkp" value="0">
                                <input type="checkbox" id="is_pkp" name="is_pkp" value="1"
                                       {{ old('is_pkp', $taxSettings->is_pkp ?? false) ? 'checked' : '' }}>
                                <label for="is_pkp">Perusahaan adalah Pengusaha Kena Pajak (PKP)</label>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Tax Rates -->
                <section class="settings-section">
                    <div class="section-header">
                        <h3 class="section-title">Tarif Pajak</h3>
                        <p class="section-description">Konfigurasi tarif pajak yang berlaku</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="ppn_rate" class="form-label">PPN (%) *</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('ppn_rate') error @enderror"
                                   id="ppn_rate" name="ppn_rate"
                                   value="{{ old('ppn_rate', $taxSettings->ppn_rate ?? 11.00) }}" required>
                            @error('ppn_rate')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="ppn_umkm_rate" class="form-label">PPN UMKM (%) *</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('ppn_umkm_rate') error @enderror"
                                   id="ppn_umkm_rate" name="ppn_umkm_rate"
                                   value="{{ old('ppn_umkm_rate', $taxSettings->ppn_umkm_rate ?? 1.10) }}" required>
                            @error('ppn_umkm_rate')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pph_21_rate" class="form-label">PPh 21 (%) *</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control @error('pph_21_rate') error @enderror"
                                   id="pph_21_rate" name="pph_21_rate"
                                   value="{{ old('pph_21_rate', $taxSettings->pph_21_rate ?? 5.00) }}" required>
                            @error('pph_21_rate')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="default_tax_type" class="form-label">Tipe Pajak Default *</label>
                            <select class="form-control @error('default_tax_type') error @enderror"
                                    id="default_tax_type" name="default_tax_type" required>
                                <option value="">Pilih Tipe Pajak</option>
                                <option value="ppn" {{ old('default_tax_type', $taxSettings->default_tax_type ?? 'ppn') == 'ppn' ? 'selected' : '' }}>PPN 11%</option>
                                <option value="ppn_umkm" {{ old('default_tax_type', $taxSettings->default_tax_type ?? 'ppn') == 'ppn_umkm' ? 'selected' : '' }}>PPN UMKM 1.1%</option>
                                <option value="pph_21" {{ old('default_tax_type', $taxSettings->default_tax_type ?? 'ppn') == 'pph_21' ? 'selected' : '' }}>PPh 21</option>
                                <option value="pph_22" {{ old('default_tax_type', $taxSettings->default_tax_type ?? 'ppn') == 'pph_22' ? 'selected' : '' }}>PPh 22</option>
                                <option value="pph_23" {{ old('default_tax_type', $taxSettings->default_tax_type ?? 'ppn') == 'pph_23' ? 'selected' : '' }}>PPh 23</option>
                            </select>
                            @error('default_tax_type')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </section>

                <!-- CoreTax Integration -->
                <section class="settings-section">
                    <div class="section-header">
                        <h3 class="section-title">Integrasi CoreTax</h3>
                        <p class="section-description">Konfigurasi koneksi ke sistem CoreTax</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="coretax_api_token" class="form-label">API Token CoreTax</label>
                            <input type="password" class="form-control @error('coretax_api_token') error @enderror"
                                   id="coretax_api_token" name="coretax_api_token"
                                   value="{{ old('coretax_api_token', $taxSettings->coretax_api_token ?? '') }}"
                                   placeholder="Masukkan API token dari CoreTax">
                            @error('coretax_api_token')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="coretax_base_url" class="form-label">Base URL CoreTax</label>
                            <input type="url" class="form-control @error('coretax_base_url') error @enderror"
                                   id="coretax_base_url" name="coretax_base_url"
                                   value="{{ old('coretax_base_url', $taxSettings->coretax_base_url ?? 'https://api.coretax.com') }}"
                                   placeholder="https://api.coretax.com">
                            @error('coretax_base_url')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sync_retry_attempts" class="form-label">Maksimal Retry *</label>
                            <input type="number" min="1" max="10" class="form-control @error('sync_retry_attempts') error @enderror"
                                   id="sync_retry_attempts" name="sync_retry_attempts"
                                   value="{{ old('sync_retry_attempts', $taxSettings->sync_retry_attempts ?? 3) }}" required>
                            @error('sync_retry_attempts')
                                <div class="form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="checkbox-grid">
                        <div class="checkbox-group">
                            <input type="hidden" name="auto_sync_enabled" value="0">
                            <input type="checkbox" id="auto_sync_enabled" name="auto_sync_enabled" value="1"
                                   {{ old('auto_sync_enabled', $taxSettings->auto_sync_enabled ?? false) ? 'checked' : '' }}>
                            <label for="auto_sync_enabled">Aktifkan sinkronisasi otomatis dengan CoreTax</label>
                        </div>
                    </div>
                </section>

                <!-- Tax Behavior -->
                <section class="settings-section">
                    <div class="section-header">
                        <h3 class="section-title">Perilaku Pajak</h3>
                        <p class="section-description">Konfigurasi bagaimana pajak dihitung dan dikelola</p>
                    </div>

                    <div class="checkbox-grid">
                        <div class="checkbox-group">
                            <input type="hidden" name="include_tax_in_price" value="0">
                            <input type="checkbox" id="include_tax_in_price" name="include_tax_in_price" value="1"
                                   {{ old('include_tax_in_price', $taxSettings->include_tax_in_price ?? false) ? 'checked' : '' }}>
                            <label for="include_tax_in_price">Harga sudah termasuk pajak</label>
                        </div>

                        <div class="checkbox-group">
                            <input type="hidden" name="auto_calculate_tax" value="0">
                            <input type="checkbox" id="auto_calculate_tax" name="auto_calculate_tax" value="1"
                                   {{ old('auto_calculate_tax', $taxSettings->auto_calculate_tax ?? true) ? 'checked' : '' }}>
                            <label for="auto_calculate_tax">Hitung pajak secara otomatis pada transaksi</label>
                        </div>

                        <div class="checkbox-group">
                            <input type="hidden" name="require_tax_invoice" value="0">
                            <input type="checkbox" id="require_tax_invoice" name="require_tax_invoice" value="1"
                                   {{ old('require_tax_invoice', $taxSettings->require_tax_invoice ?? false) ? 'checked' : '' }}>
                            <label for="require_tax_invoice">Wajibkan faktur pajak untuk transaksi kena pajak</label>
                        </div>

                        <div class="checkbox-group">
                            <input type="hidden" name="enable_branch_tax" value="0">
                            <input type="checkbox" id="enable_branch_tax" name="enable_branch_tax" value="1"
                                   {{ old('enable_branch_tax', $taxSettings->enable_branch_tax ?? true) ? 'checked' : '' }}>
                            <label for="enable_branch_tax">Aktifkan fitur pajak untuk cabang ini</label>
                        </div>
                    </div>
                </section>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="{{ route('tax.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">Simpan Pengaturan</button>
                </div>
            </div>
        </form>
    </main>
</div>

<style>
/* Form Styles */
.settings-form {
    background: #FFFFFF;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
    overflow: hidden;
}

.settings-grid {
    padding: 32px;
}

.settings-section {
    margin-bottom: 40px;
}

.settings-section:last-child {
    margin-bottom: 0;
}

.section-header {
    margin-bottom: 24px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.section-description {
    font-size: 14px;
    color: #6B7280;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-control:focus {
    outline: none;
    border-color: #64748B;
    box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
}

.form-control.error {
    border-color: #EF4444;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

.checkbox-grid {
    display: grid;
    gap: 16px;
    margin-top: 20px;
}

.checkbox-group {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    accent-color: #64748B;
}

.checkbox-group label {
    font-size: 14px;
    color: #374151;
    line-height: 1.5;
    margin: 0;
    cursor: pointer;
}

.form-error {
    font-size: 13px;
    color: #EF4444;
    margin-top: 6px;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 32px;
    border-top: 1px solid #E5E7EB;
    margin-top: 40px;
}

.btn-primary, .btn-secondary {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.btn-secondary {
    background: #FFFFFF;
    color: #6B7280;
    border: 1px solid #D1D5DB;
}

.btn-secondary:hover {
    background: #F9FAFB;
    border-color: #9CA3AF;
}

/* Responsive */
@media (max-width: 768px) {
    .settings-grid {
        padding: 20px;
    }

    .form-grid {
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // NPWP formatting
    const npwpInput = document.getElementById('npwp');
    if (npwpInput) {
        npwpInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 15) {
                value = value.substring(0, 15);
            }

            // Format: XX.XXX.XXX.X-XXX.XXX
            if (value.length >= 2) value = value.substring(0, 2) + '.' + value.substring(2);
            if (value.length >= 6) value = value.substring(0, 6) + '.' + value.substring(6);
            if (value.length >= 10) value = value.substring(0, 10) + '.' + value.substring(10);
            if (value.length >= 12) value = value.substring(0, 12) + '-' + value.substring(12);
            if (value.length >= 15) value = value.substring(0, 15) + '.' + value.substring(15);

            e.target.value = value;
        });
    }

    // PKP status change handler
    const pkpCheckbox = document.getElementById('is_pkp');
    const requireTaxInvoiceCheckbox = document.getElementById('require_tax_invoice');

    if (pkpCheckbox && requireTaxInvoiceCheckbox) {
        pkpCheckbox.addEventListener('change', function() {
            if (!this.checked) {
                requireTaxInvoiceCheckbox.checked = false;
                requireTaxInvoiceCheckbox.disabled = true;
            } else {
                requireTaxInvoiceCheckbox.disabled = false;
            }
        });

        // Initial state
        if (!pkpCheckbox.checked) {
            requireTaxInvoiceCheckbox.disabled = true;
        }
    }
});
</script>
@endsection