@extends('layouts.app')

@section('title', 'Pengaturan Cabang - Akuntansi Sibuku')
@section('page-title', 'Pengaturan Cabang')

@section('content')
<div class="settings-container">
    <!-- Header -->
    <div class="settings-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="settings-title">Manajemen Cabang</h1>
                <p class="settings-subtitle">Kelola cabang perusahaan dan pengaturan multi-branch</p>
            </div>
            <div class="header-actions">
                @if(Auth::user()->userRole && in_array(Auth::user()->userRole->name, ['super_admin', 'admin']))
                    <button class="btn-primary" onclick="openCreateBranchModal()">
                        <i class="fas fa-plus"></i>
                        Tambah Cabang Baru
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Current Branch Info -->
    @if(session('active_branch') || Auth::user()->branch)
        <div class="current-branch-card">
            <div class="branch-info">
                <div class="branch-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="branch-details">
                    <h3>Cabang Aktif Saat Ini</h3>
                    @if(session('active_branch'))
                        @php $activeBranch = \App\Models\Branch::find(session('active_branch')) @endphp
                        @if($activeBranch)
                            <p class="branch-name">{{ $activeBranch->name }}</p>
                            <p class="branch-address">{{ $activeBranch->address ?? 'Alamat belum diatur' }}</p>
                        @endif
                    @else
                        <p class="branch-name">{{ Auth::user()->branch->name }}</p>
                        <p class="branch-address">{{ Auth::user()->branch->address ?? 'Alamat belum diatur' }}</p>
                    @endif
                </div>
            </div>
            <div class="branch-actions">
                <form method="POST" action="{{ route('branches.switch', 0) }}" class="inline-form">
                    @csrf
                    <button type="submit" class="btn-secondary">
                        <i class="fas fa-globe"></i>
                        Lihat Semua Cabang
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Branches List -->
    <div class="branches-grid">
        @foreach(\App\Models\Branch::all() as $branch)
            <div class="branch-card {{ session('active_branch') == $branch->id ? 'active' : '' }}">
                <div class="branch-header">
                    <div class="branch-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="branch-status">
                        @if(session('active_branch') == $branch->id)
                            <span class="status-badge active">Aktif</span>
                        @else
                            <span class="status-badge inactive">Tidak Aktif</span>
                        @endif
                    </div>
                </div>

                <div class="branch-content">
                    <h3 class="branch-name">{{ $branch->name }}</h3>
                    <p class="branch-address">{{ $branch->address ?? 'Alamat belum diatur' }}</p>
                    <div class="branch-meta">
                        <span class="meta-item">
                            <i class="fas fa-phone"></i>
                            {{ $branch->phone ?? 'Tidak ada telepon' }}
                        </span>
                        <span class="meta-item">
                            <i class="fas fa-envelope"></i>
                            {{ $branch->email ?? 'Tidak ada email' }}
                        </span>
                    </div>
                </div>

                <div class="branch-actions">
                    @if(Auth::user()->userRole && in_array(Auth::user()->userRole->name, ['super_admin', 'admin']))
                        <form method="POST" action="{{ route('branches.switch', $branch->id) }}" class="inline-form">
                            @csrf
                            <button type="submit" class="btn-primary {{ session('active_branch') == $branch->id ? 'active' : '' }}">
                                <i class="fas fa-exchange-alt"></i>
                                {{ session('active_branch') == $branch->id ? 'Cabang Aktif' : 'Pilih Cabang' }}
                            </button>
                        </form>
                        <button class="btn-secondary" onclick="editBranch({{ $branch->id }})">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn-danger" onclick="deleteBranch({{ $branch->id }}, '{{ $branch->name }}')">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    @else
                        @if(Auth::user()->branch && Auth::user()->branch->id === $branch->id)
                            <span class="current-branch-label">Cabang Anda</span>
                        @endif
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Branch Statistics -->
    @if(Auth::user()->userRole && in_array(Auth::user()->userRole->name, ['super_admin', 'admin']))
        <div class="branch-stats">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Total Cabang</h4>
                        <p class="stat-value">{{ \App\Models\Branch::count() }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Total Pengguna</h4>
                        <p class="stat-value">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Total Rekening</h4>
                        <p class="stat-value">{{ \App\Models\Account::count() }}</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-content">
                        <h4>Total Transaksi</h4>
                        <p class="stat-value">{{ \App\Models\Transaction::count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Create/Edit Branch Modal -->
<div id="branchModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Cabang Baru</h3>
            <button class="modal-close" onclick="closeBranchModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="branchForm" method="POST">
            @csrf
            <input type="hidden" id="branchId" name="branch_id">

            <div class="form-group">
                <label for="name">Nama Cabang *</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" rows="3"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Telepon</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeBranchModal()">Batal</button>
                <button type="submit" class="btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateBranchModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Cabang Baru';
    document.getElementById('branchForm').action = '{{ route("branches.store") }}';
    document.getElementById('branchForm').reset();
    document.getElementById('branchId').value = '';
    document.getElementById('branchModal').classList.add('show');
}

function editBranch(id) {
    // Fetch branch data and populate form
    fetch(`/branches/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Cabang';
            document.getElementById('branchForm').action = `/branches/${id}`;
            document.getElementById('branchId').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('address').value = data.address || '';
            document.getElementById('phone').value = data.phone || '';
            document.getElementById('email').value = data.email || '';

            // Add method override for PUT
            let methodInput = document.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                document.getElementById('branchForm').appendChild(methodInput);
            }
            methodInput.value = 'PUT';

            document.getElementById('branchModal').classList.add('show');
        });
}

function deleteBranch(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus cabang "${name}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/branches/${id}`;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}

function closeBranchModal() {
    document.getElementById('branchModal').classList.remove('show');
}

// Close modal when clicking outside
document.getElementById('branchModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBranchModal();
    }
});
</script>

<style>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
}

.settings-header {
    margin-bottom: 32px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.settings-title {
    font-size: 28px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.settings-subtitle {
    color: #6B7280;
    font-size: 16px;
}

.header-actions .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.current-branch-card {
    background: linear-gradient(135deg, #F0F9FF 0%, #E0F2FE 100%);
    border: 1px solid #0EA5E9;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.branch-info {
    display: flex;
    align-items: center;
    gap: 16px;
}

.branch-info .branch-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #0EA5E9 0%, #0284C7 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.branch-details h3 {
    font-size: 18px;
    font-weight: 600;
    color: #0C4A6E;
    margin-bottom: 4px;
}

.branch-name {
    font-size: 20px;
    font-weight: 700;
    color: #0C4A6E;
    margin-bottom: 2px;
}

.branch-address {
    color: #64748B;
    font-size: 14px;
}

.branches-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.branch-card {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s ease;
    position: relative;
}

.branch-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.branch-card.active {
    border-color: #4F46E5;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
}

.branch-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 16px;
}

.branch-header .branch-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #F3F4F6 0%, #E5E7EB 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6B7280;
    font-size: 18px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background: rgba(34, 197, 94, 0.1);
    color: #16A34A;
}

.status-badge.inactive {
    background: rgba(156, 163, 175, 0.1);
    color: #6B7280;
}

.branch-content {
    margin-bottom: 20px;
}

.branch-content .branch-name {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.branch-content .branch-address {
    color: #6B7280;
    font-size: 14px;
    margin-bottom: 12px;
}

.branch-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748B;
}

.meta-item i {
    width: 14px;
    color: #9CA3AF;
}

.branch-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.branch-actions .btn-primary,
.branch-actions .btn-secondary,
.branch-actions .btn-danger {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}

.branch-actions .btn-primary {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
}

.branch-actions .btn-primary.active {
    background: linear-gradient(135deg, #16A34A 0%, #15803D 100%);
}

.branch-actions .btn-secondary {
    background: #F3F4F6;
    color: #374151;
    border: 1px solid #D1D5DB;
}

.branch-actions .btn-danger {
    background: #FEF2F2;
    color: #DC2626;
    border: 1px solid #FECACA;
}

.current-branch-label {
    background: rgba(34, 197, 94, 0.1);
    color: #16A34A;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}

.inline-form {
    display: inline;
}

.branch-stats {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 24px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
    border-radius: 12px;
    border: 1px solid #E2E8F0;
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.stat-content h4 {
    font-size: 14px;
    color: #64748B;
    margin-bottom: 4px;
    font-weight: 500;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid #E5E7EB;
}

.modal-header h3 {
    font-size: 20px;
    font-weight: 700;
    color: #111827;
}

.modal-close {
    background: none;
    border: none;
    font-size: 20px;
    color: #6B7280;
    cursor: pointer;
    padding: 4px;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.2s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 20px;
    border-top: 1px solid #E5E7EB;
}

.form-actions .btn-secondary {
    background: #F3F4F6;
    color: #374151;
    border: 1px solid #D1D5DB;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
}

.form-actions .btn-primary {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .branches-grid {
        grid-template-columns: 1fr;
    }

    .current-branch-card {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .branch-actions {
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection