@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<style>
.page-header {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
    50% { transform: translate(-50%, -50%) rotate(180deg); }
}

.form-container {
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    border: 1px solid rgba(255,255,255,0.9);
    overflow: hidden;
    margin-bottom: 2rem;
}

.form-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 2rem;
    border-bottom: 1px solid #e5e7eb;
}

.form-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
}

.form-title i {
    margin-right: 0.75rem;
    color: #6366f1;
}

.form-body {
    padding: 2.5rem;
}

.form-group-modern {
    margin-bottom: 2rem;
    position: relative;
}

.form-label-modern {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
}

.form-label-modern i {
    margin-right: 0.5rem;
    color: #6366f1;
    width: 16px;
}

.form-control-modern {
    width: 100%;
    padding: 1rem 1.25rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: #ffffff;
}

.form-control-modern:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    transform: translateY(-1px);
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
}

.btn-modern {
    padding: 0.875rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.btn-primary-modern {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
}

.btn-secondary-modern {
    background: #6b7280;
    color: white;
}

@media (max-width: 768px) {
    .form-body {
        padding: 1.5rem;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-modern {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-user-plus me-3"></i>
                    Tambah Pengguna Baru
                </h1>
                <p class="lead mb-0 opacity-90">Buat akun pengguna baru untuk sistem</p>
            </div>
            <div class="col-lg-4 text-end">
                <a href="{{ route('users.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">
                        <i class="fas fa-user"></i>
                        Detail Pengguna
                    </h2>
                </div>

                <div class="form-body">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name -->
                                <div class="form-group-modern">
                                    <label for="name" class="form-label-modern">
                                        <i class="fas fa-signature"></i>
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control-modern @error('name') border-danger @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="text-danger mt-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Email -->
                                <div class="form-group-modern">
                                    <label for="email" class="form-label-modern">
                                        <i class="fas fa-envelope"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control-modern @error('email') border-danger @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="text-danger mt-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Password -->
                                <div class="form-group-modern">
                                    <label for="password" class="form-label-modern">
                                        <i class="fas fa-lock"></i>
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control-modern @error('password') border-danger @enderror"
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="text-danger mt-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Confirm Password -->
                                <div class="form-group-modern">
                                    <label for="password_confirmation" class="form-label-modern">
                                        <i class="fas fa-lock"></i>
                                        Konfirmasi Password <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control-modern"
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- Role -->
                                <div class="form-group-modern">
                                    <label for="user_role_id" class="form-label-modern">
                                        <i class="fas fa-user-tag"></i>
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control-modern @error('user_role_id') border-danger @enderror"
                                            id="user_role_id" name="user_role_id" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('user_role_id') == $role->id ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_role_id')
                                        <div class="text-danger mt-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Branch -->
                                <div class="form-group-modern">
                                    <label for="branch_id" class="form-label-modern">
                                        <i class="fas fa-building"></i>
                                        Cabang
                                    </label>
                                    <select class="form-control-modern @error('branch_id') border-danger @enderror"
                                            id="branch_id" name="branch_id">
                                        <option value="">Semua Cabang</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted mt-1 d-block">
                                        Kosongkan jika pengguna dapat mengakses semua cabang
                                    </small>
                                    @error('branch_id')
                                        <div class="text-danger mt-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('users.index') }}" class="btn-modern btn-secondary-modern">
                                <i class="fas fa-times"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-save"></i>
                                Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection