@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="settings-container">
    <!-- Header -->
    <div class="settings-header">
        <div class="header-content">
            <div class="header-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </div>
            <div class="header-text">
                <h1>Pengaturan Sistem</h1>
                <p>Kelola semua aspek sistem akuntansi Anda</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="settings-tabs">
        <div class="tabs-header">
            <button class="tab-button {{ request('tab', 'general') === 'general' ? 'active' : '' }}"
                    onclick="switchTab('general')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <span>Umum</span>
            </button>


            <button class="tab-button {{ request('tab') === 'notifications' ? 'active' : '' }}"
                    onclick="switchTab('notifications')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <span>Notifikasi</span>
            </button>

            @can('manage branches')
            <a href="{{ route('settings.branches') }}" class="tab-button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"></path>
                    <line x1="3" y1="7" x2="21" y2="7"></line>
                </svg>
                <span>Cabang</span>
            </a>
            @endcan

            @can('system maintenance')
            <button class="tab-button {{ request('tab') === 'system' ? 'active' : '' }}"
                    onclick="switchTab('system')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                    <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                    <line x1="6" y1="6" x2="6.01" y2="6"></line>
                    <line x1="6" y1="18" x2="6.01" y2="18"></line>
                </svg>
                <span>Sistem</span>
            </button>
            @endcan
        </div>

        <div class="tabs-content">
            <!-- General Settings Tab -->
            <div id="general-tab" class="tab-content {{ request('tab', 'general') === 'general' ? 'active' : '' }}">
                <div class="settings-section">
                    <h3>Pengaturan Umum</h3>
                    <p>Konfigurasi informasi dasar aplikasi</p>

                    <form method="POST" action="{{ route('settings.updateGeneral') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="app_name">Nama Aplikasi</label>
                                <input type="text" id="app_name" name="app_name"
                                       value="{{ old('app_name', App\Models\AppSetting::getValue('app_name', 'Sistem Akuntansi')) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="company_name">Nama Perusahaan</label>
                                <input type="text" id="company_name" name="company_name"
                                       value="{{ old('company_name', App\Models\AppSetting::getValue('company_name', '')) }}">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Notifications Tab -->
            <div id="notifications-tab" class="tab-content {{ request('tab') === 'notifications' ? 'active' : '' }}">
                <div class="settings-section">
                    <h3>Pengaturan Notifikasi</h3>
                    <p>Konfigurasi notifikasi yang ingin Anda terima</p>

                    <form method="POST" action="{{ route('settings.updateNotifications') }}">
                        @csrf
                        @method('PUT')

                        <div class="notification-settings">
                            <div class="notification-item">
                                <h4>Email Notifications</h4>
                                <div class="checkbox-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="notification_invoice_overdue_email" value="1">
                                        <span class="checkmark"></span>
                                        Tagihan Jatuh Tempo
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="notification_payment_received_email" value="1" checked>
                                        <span class="checkmark"></span>
                                        Pembayaran Diterima
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">Simpan Pengaturan Notifikasi</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- System Tab -->
            @can('system maintenance')
            <div id="system-tab" class="tab-content {{ request('tab') === 'system' ? 'active' : '' }}">
                <div class="settings-section">
                    <h3>Pemeliharaan Sistem</h3>
                    <p>Alat untuk mengelola sistem dan performa</p>

                    <div class="system-tools">
                        <div class="tool-group">
                            <h4>Cache Management</h4>
                            <form method="POST" action="{{ route('settings.clearCache') }}" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn-secondary">Bersihkan Cache</button>
                            </form>
                        </div>

                        <div class="tool-group">
                            <h4>Application Optimization</h4>
                            <form method="POST" action="{{ route('settings.optimize') }}" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn-secondary">Optimalkan Aplikasi</button>
                            </form>
                        </div>

                        <div class="tool-group">
                            <h4>Database Backup</h4>
                            <form method="POST" action="{{ route('settings.backup') }}" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn-warning">Buat Backup Database</button>
                            </form>
                        </div>

                        <div class="tool-group">
                            <h4>Log Management</h4>
                            <form method="POST" action="{{ route('settings.clearLogs') }}" style="display: inline;">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn-danger">Bersihkan Log</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>

<style>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.settings-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.header-text h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.header-text p {
    color: #6b7280;
    margin: 0.5rem 0 0 0;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
}

.settings-tabs {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.tabs-header {
    display: flex;
    border-bottom: 1px solid #e5e7eb;
    overflow-x: auto;
}

.tab-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    transition: all 0.2s;
    white-space: nowrap;
}

.tab-button:hover {
    background: #f9fafb;
    color: #374151;
}

.tab-button.active {
    background: #667eea;
    color: white;
    border-bottom: 2px solid #667eea;
}

.tabs-content {
    padding: 2rem;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.settings-section h3 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.settings-section p {
    color: #6b7280;
    margin-bottom: 2rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.875rem;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-label input[type="checkbox"] {
    width: 1.2rem;
    height: 1.2rem;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-warning:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}

.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.system-tools {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.tool-group {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.tool-group h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
}

.notification-settings {
    margin-bottom: 2rem;
}

.notification-item {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.notification-item h4 {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 1rem;
}

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

@media (max-width: 768px) {
    .settings-container {
        padding: 1rem;
    }

    .tabs-header {
        flex-wrap: wrap;
    }

    .tab-button {
        flex: 1;
        justify-content: center;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .system-tools {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
function switchTab(tabName) {
    // Update URL without page reload
    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.pushState({}, '', url);

    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });

    // Show selected tab
    document.getElementById(tabName + '-tab').classList.add('active');

    // Add active class to clicked button
    event.target.closest('.tab-button').classList.add('active');
}
</script>
@endsection