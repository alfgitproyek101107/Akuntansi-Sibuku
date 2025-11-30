@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Profil Pengguna</span>
                </div>
                <h1 class="page-title">Profil Saya</h1>
                <p class="page-subtitle">Kelola informasi akun dan preferensi Anda</p>
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

    <!-- Success Notification -->
    @if(session('status'))
        <div class="notification-container">
            <div class="notification success">
                <div class="notification-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="notification-content">
                    <div class="notification-title">Berhasil</div>
                    <div class="notification-message">{{ session('status') }}</div>
                </div>
                <button class="notification-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="dashboard-main">
        <div class="content-grid">
            <!-- Profile Section -->
            <section class="profile-section">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=64748B&color=fff" 
                                 alt="Avatar" class="avatar-image">
                        </div>
                        <div class="profile-info">
                            <h2 class="profile-name">{{ $user->name }}</h2>
                            <p class="profile-email">{{ $user->email }}</p>
                            <p class="profile-date">Bergabung sejak {{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Forms Section -->
            <section class="forms-section">
                <!-- Account Information Form -->
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <h2 class="form-title">Informasi Akun</h2>
                        <p class="form-subtitle">Perbarui informasi dasar akun Anda</p>
                    </div>
                    <div class="form-body">
                        <form method="POST" action="{{ route('users.updateProfile') }}">
                            @csrf

                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    Nama Lengkap <span class="required">*</span>
                                </label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    Alamat Email <span class="required">*</span>
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="form-actions">
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

                <!-- Security Form -->
                <div class="form-container">
                    <div class="form-header">
                        <div class="form-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <h2 class="form-title">Keamanan</h2>
                        <p class="form-subtitle">Kelola kata sandi dan keamanan akun Anda</p>
                    </div>
                    <div class="form-body">
                        <div class="password-info">
                            <p>Untuk mengubah password, klik tombol di bawah ini.</p>
                        </div>
                        
                        <button class="btn-toggle" type="button" id="passwordToggle">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Ganti Password
                        </button>
                        
                        <div class="password-form" id="passwordForm" style="display: none;">
                            <form method="POST" action="{{ route('users.changePassword') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="current_password" class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        Password Lama <span class="required">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        Password Baru <span class="required">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        Konfirmasi Password <span class="required">*</span>
                                    </label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-primary">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                            <polyline points="7 3 7 8 15 8"></polyline>
                                        </svg>
                                        Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password form toggle
    const passwordToggle = document.getElementById('passwordToggle');
    const passwordForm = document.getElementById('passwordForm');
    
    passwordToggle.addEventListener('click', function() {
        if (passwordForm.style.display === 'none') {
            passwordForm.style.display = 'block';
            passwordToggle.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="18 15 12 9 6 15"></polyline>
                </svg>
                Tutup Form Password
            `;
        } else {
            passwordForm.style.display = 'none';
            passwordToggle.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                Ganti Password
            `;
        }
    });
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

/* ===== NOTIFICATION ===== */
.notification-container {
    margin-bottom: 24px;
}

.notification {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    background: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border: 1px solid #E5E7EB;
}

.notification.success {
    border-left: 4px solid #22C55E;
}

.notification-icon {
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

.notification-content {
    flex-grow: 1;
}

.notification-title {
    font-size: 16px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.notification-message {
    font-size: 14px;
    color: #6B7280;
}

.notification-close {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: none;
    background: #F3F4F6;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.notification-close:hover {
    background: #E5E7EB;
    color: #374151;
}

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 32px;
}

/* ===== PROFILE SECTION ===== */
.profile-section {
    margin-bottom: 48px;
}

.profile-card {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    position: sticky;
    top: 32px;
}

.profile-header {
    padding: 32px;
    text-align: center;
}

.profile-avatar {
    margin-bottom: 24px;
}

.avatar-image {
    width: 128px;
    height: 128px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.profile-name {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.profile-email {
    font-size: 16px;
    color: #6B7280;
    margin-bottom: 8px;
}

.profile-date {
    font-size: 14px;
    color: #9CA3AF;
}

/* ===== FORMS SECTION ===== */
.forms-section {
    margin-bottom: 48px;
}

.form-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    margin-bottom: 24px;
}

.form-header {
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
    display: flex;
    align-items: center;
    gap: 16px;
}

.form-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.form-subtitle {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.form-body {
    padding: 24px;
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

.required {
    color: #EF4444;
}

.form-control {
    width: 100%;
    padding: 12px 16px;
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

.password-info {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
}

.password-info p {
    font-size: 14px;
    color: #6B7280;
    margin: 0;
}

.btn-toggle {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    background: #F3F4F6;
    color: #6B7280;
    margin-bottom: 16px;
}

.btn-toggle:hover {
    background: #E5E7EB;
    color: #374151;
}

.password-form {
    border-top: 1px solid #E5E7EB;
    padding-top: 24px;
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 24px;
    border-top: 1px solid #E5E7EB;
    margin-top: 24px;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    background: linear-gradient(135deg, #64748B 0%, #475569 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(100, 116, 139, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(100, 116, 139, 0.4);
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
    
    .profile-card {
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
    
    .form-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary {
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
    
    .profile-header {
        padding: 24px;
    }
}
</style>
@endsection