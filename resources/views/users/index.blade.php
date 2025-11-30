@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="dashboard-layout">
    <!-- Header with enhanced design -->
    <header class="dashboard-header">
        <div class="header-container">
            <div class="header-left">
                <div class="welcome-badge">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                        <circle cx="12" cy="13" r="4"></circle>
                    </svg>
                    <span>Pengguna</span>
                </div>
                <h1 class="page-title">Manajemen Pengguna</h1>
                <p class="page-subtitle">Kelola pengguna dan hak akses sistem</p>
            </div>
            <div class="header-right">
                <a href="{{ route('users.create') }}" class="btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Tambah Pengguna</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Enhanced Key Metrics -->
        <section class="metrics-section">
            <div class="metrics-grid">
                <!-- Total Users -->
                <div class="metric-card metric-primary">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Total Pengguna</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $users->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Semua pengguna</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+12.5%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="totalUsersSparkline"></canvas>
                    </div>
                </div>

                <!-- Admin Users -->
                <div class="metric-card metric-warning">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 12l-6.91-6.26L12 2z"></path>
                                <path d="M12 22l6.91-6.26L12 22z"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Administrator</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $users->filter(function($user) { return $user->userRole && in_array($user->userRole->name, ['super_admin', 'admin']); })->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">Pengguna admin</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+2.1%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="adminUsersSparkline"></canvas>
                    </div>
                </div>

                <!-- Regular Users -->
                <div class="metric-card metric-success">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                                <path d="M22 12h-4v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v2"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Pengguna Sistem</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $regularUsers->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">User sistem asli</span>
                            <div class="metric-trend positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                </svg>
                                <span>+18.3%</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="regularUsersSparkline"></canvas>
                    </div>
                </div>

                <!-- Demo Users -->
                <div class="metric-card metric-info">
                    <div class="metric-header">
                        <div class="metric-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 2l3.09 6.26L22 12l-6.91-6.26L12 2z"></path>
                                <path d="M12 22l6.91-6.26L12 22z"></path>
                            </svg>
                        </div>
                        <div class="metric-badge">
                            <span>Mode Demo</span>
                        </div>
                    </div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $demoUsers->count() }}</div>
                        <div class="metric-details">
                            <span class="metric-label">User untuk testing</span>
                            <div class="metric-trend neutral">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Dinamis</span>
                            </div>
                        </div>
                    </div>
                    <div class="metric-sparkline">
                        <canvas id="demoUsersSparkline"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- Users Data Section -->
        <section class="table-section">
            <div class="section-header">
                <div class="section-title-group">
                    <h2 class="section-title">Daftar Pengguna</h2>
                    <p class="section-subtitle">Kelola pengguna dan peran hak akses</p>
                </div>
                <div class="section-actions">
                    <div class="search-input">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" id="userSearch" placeholder="Cari pengguna..." onkeyup="filterUsers(this.value)">
                    </div>
                    <div class="filter-dropdown">
                        <button class="filter-btn" id="filterBtn">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Filter
                        </button>
                        <div class="filter-options" id="filterOptions">
                            <button class="filter-option active" data-filter="all">Semua</button>
                            <button class="filter-option" data-filter="super_admin">Super Admin</button>
                            <button class="filter-option" data-filter="admin">Admin</button>
                            <button class="filter-option" data-filter="user">Pengguna</button>
                            <button class="filter-option" data-filter="demo">User Demo</button>
                            <button class="filter-option" data-filter="regular">User Sistem</button>
                            <button class="filter-option" data-filter="active">Aktif</button>
                            <button class="filter-option" data-filter="inactive">Tidak Aktif</button>
                        </div>
                    </div>
                    <div class="view-toggle">
                        <button class="toggle-btn active" id="gridView" onclick="toggleView('grid')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                            </svg>
                        </button>
                        <button class="toggle-btn" id="tableView" onclick="toggleView('table')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            @if($users->count() > 0)
                <div class="table-container">
                    <!-- Grid View -->
                    <div class="users-grid" id="usersGridView">
                        @foreach($users as $user)
                            <div class="user-card {{ $user->userRole?->name ?? 'user' }}" data-role="{{ $user->userRole?->name ?? 'user' }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                                <div class="user-header">
                                    <div class="user-avatar">
                                        <div class="avatar-initials">{{ substr($user->name, 0, 1) }}</div>
                                    </div>
                                    <div class="user-status">
                                        <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                        @if($user->demo_mode)
                                            <span class="demo-badge">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M12 2l3.09 6.26L22 12l-6.91-6.26L12 2z"></path>
                                                    <path d="M12 22l6.91-6.26L12 22z"></path>
                                                </svg>
                                                DEMO
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="user-info">
                                    <h3 class="user-name">{{ $user->name }}</h3>
                                    <p class="user-email">{{ $user->email }}</p>
                                    <div class="user-meta">
                                        <span class="user-role {{ $user->userRole?->name ?? 'user' }}">
                                            @switch($user->userRole?->name ?? 'user')
                                                @case('super_admin')
                                                    'Super Admin'
                                                @case('admin')
                                                    'Administrator'
                                                @case('user')
                                                    'Pengguna'
                                                    @default
                                                    'Pengguna'
                                                    @endswitch
                                            </span>
                                        @if($user->branch)
                                            <span class="user-branch">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2z"></path>
                                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                </svg>
                                                {{ $user->branch->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="user-actions">
                                        <a href="{{ route('users.show', $user) }}" class="action-btn view-btn" title="Lihat Detail">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8 11 8 11 8 11 8 11-4 4-8 11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="action-btn edit-btn" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0-2 2H4a2 2 0 0 0-2 2v-7"></path>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        @if((auth()->user()->userRole?->name ?? '') === 'super_admin' || ((auth()->user()->userRole?->name ?? '') === 'admin' && auth()->user()->id !== $user->id))
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete-btn" title="Hapus">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 0-2 2H7a2 2 0 0 0-2 2v-14"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Table View -->
                    <div class="table-responsive d-none" id="usersTableView">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Cabang</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr class="user-row {{ $user->userRole?->name ?? 'user' }}" data-role="{{ $user->userRole?->name ?? 'user' }}" data-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                                        <td>
                                            <div class="user-name-cell">
                                                <div class="user-avatar">
                                                    <div class="avatar-initials">{{ substr($user->name, 0, 1) }}</div>
                                                </div>
                                                {{ $user->name }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="user-email">{{ $user->email }}</div>
                                        </td>
                                        <td>
                                            <span class="user-role {{ $user->userRole?->name ?? 'user' }}">
                                                @switch($user->userRole?->name ?? 'user')
                                                    @case('super_admin')
                                                        'Super Admin'
                                                    @case('admin')
                                                        'Administrator'
                                                    @case('user')
                                                        'Pengguna'
                                                    @default
                                                        'Pengguna'
                                                    @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            @if($user->branch)
                                                <div class="user-branch">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 0-2 2H5a2 2 0 0 0-2 2z"></path>
                                                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                                    </svg>
                                                    {{ $user->branch->name }}
                                                </div>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="status-container">
                                                <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                                @if($user->demo_mode)
                                                    <span class="demo-badge-table">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M12 2l3.09 6.26L22 12l-6.91-6.26L12 2z"></path>
                                                            <path d="M12 22l6.91-6.26L12 22z"></path>
                                                        </svg>
                                                        DEMO
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('users.show', $user) }}" class="action-btn view-btn" title="Lihat Detail">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8 11 8 11 8 11 8 11-4-4-8 11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('users.edit', $user) }}" class="action-btn edit-btn" title="Edit">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0-2 2H4a2 2 0 0 0-2 2v-14"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                </a>
                                                @if((auth()->user()->userRole?->name ?? '') === 'super_admin' || ((auth()->user()->userRole?->name ?? '') === 'admin' && auth()->user()->id !== $user->id))
                                                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn delete-btn" title="Hapus">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path d="M19 6v14a2 2 0 0 0-2 2H7a2 2 0 0 0-2 2v-14"></path>
                                                                <path d="M19 6v14a2 2 0 0 0-2 2H7a2 2 0 0 0-2 2v-14"></path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="pagination-container">
                    {{ $users->links() }}
                </div>
            @else
                <!-- Empty State -->
                <section class="empty-section">
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4-4v-2"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </div>
                        <h3 class="empty-title">Belum ada pengguna</h3>
                        <p class="empty-description">Anda belum menambahkan pengguna apapun ke sistem.</p>
                        <div class="empty-action">
                            <a href="{{ route('users.create') }}" class="btn-primary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                <span>Tambah Pengguna Pertama</span>
                            </a>
                        </div>
                    </div>
                </section>
            @endif
        </section>
    </main>
</div>

<!-- Chart.js for sparklines -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced sparkline configuration
    const sparklineOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { enabled: false }
        },
        scales: {
            x: { display: false },
            y: { display: false }
        },
        elements: {
            line: {
                borderWidth: 2,
                tension: 0.4
            },
            point: {
                radius: 0,
                hoverRadius: 0
            }
        }
    };

    // Total Users Sparkline
    const totalUsersCtx = document.getElementById('totalUsersSparkline')?.getContext('2d');
    if (totalUsersCtx) {
        const gradient = totalUsersCtx.createLinearGradient(0, 0, 0, 40);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.3)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');
        
        new Chart(totalUsersCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [10, 12, 15, 18, 22, 25],
                    borderColor: '#4F46E5',
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Admin Users Sparkline
    const adminUsersCtx = document.getElementById('adminUsersSparkline')?.getContext('2d');
    if (adminUsersCtx) {
        new Chart(adminUsersCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [5, 6, 7, 8, 9, 10],
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: '#F59E0B',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Regular Users Sparkline
    const regularUsersCtx = document.getElementById('regularUsersSparkline')?.getContext('2d');
    if (regularUsersCtx) {
        new Chart(regularUsersCtx, {
            type: 'bar',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [15, 18, 22, 25, 30, 28],
                    backgroundColor: 'rgba(34, 197, 94, 0.2)',
                    borderColor: '#22C55E',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: sparklineOptions
        });
    }

    // Demo Users Sparkline
    const demoUsersCtx = document.getElementById('demoUsersSparkline')?.getContext('2d');
    if (demoUsersCtx) {
        new Chart(demoUsersCtx, {
            type: 'line',
            data: {
                labels: ['', '', '', '', '', ''],
                datasets: [{
                    data: [1, 1, 1, 1, 1, 1],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: sparklineOptions
        });
    }

    // Filter functionality
    const filterBtn = document.getElementById('filterBtn');
    const filterOptions = document.getElementById('filterOptions');
    
    if (filterBtn && filterOptions) {
        filterBtn.addEventListener('click', function() {
            filterOptions.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!filterBtn.contains(e.target) && !filterOptions.contains(e.target)) {
                filterOptions.classList.remove('active');
            }
        });
    }

    // Filter options
    const filterOptionBtns = document.querySelectorAll('.filter-option');
    filterOptionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterOptionBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Filter the table/grid
            const filterType = this.getAttribute('data-filter');
            filterUsersByType(filterType);
            
            // Close dropdown
            filterOptions.classList.remove('active');
        });
    });
});

// Toggle between grid and table view
function toggleView(viewType) {
    const gridView = document.getElementById('usersGridView');
    const tableView = document.getElementById('usersTableView');
    const gridBtn = document.getElementById('gridView');
    const tableBtn = document.getElementById('tableView');
    
    if (viewType === 'grid') {
        gridView.classList.remove('d-none');
        tableView.classList.add('d-none');
        gridBtn.classList.add('active');
        tableBtn.classList.remove('active');
    } else {
        gridView.classList.add('d-none');
        tableView.classList.remove('d-none');
        gridBtn.classList.remove('active');
        tableBtn.classList.add('active');
    }
}

// Filter users function
function filterUsers(searchTerm) {
    const gridCards = document.querySelectorAll('#usersGridView .user-card');
    const tableRows = document.querySelectorAll('#usersTableView .user-row');
    const term = searchTerm.toLowerCase();

    gridCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(term) ? '' : 'none';
    });

    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
}

// Filter users by type function
function filterUsersByType(type) {
    const gridCards = document.querySelectorAll('#usersGridView .user-card');
    const tableRows = document.querySelectorAll('#usersTableView .user-row');

    gridCards.forEach(card => {
        if (type === 'all') {
            card.style.display = '';
        } else if (type === 'active') {
            card.style.display = card.getAttribute('data-status') === 'active' ? '' : 'none';
        } else if (type === 'inactive') {
            card.style.display = card.getAttribute('data-status') === 'inactive' ? '' : 'none';
        } else if (type === 'demo') {
            card.style.display = card.querySelector('.demo-badge') ? '' : 'none';
        } else if (type === 'regular') {
            card.style.display = card.querySelector('.demo-badge') ? 'none' : '';
        } else {
            card.style.display = card.getAttribute('data-role') === type ? '' : 'none';
        }
    });

    tableRows.forEach(row => {
        if (type === 'all') {
            row.style.display = '';
        } else if (type === 'active') {
            row.style.display = row.getAttribute('data-status') === 'active' ? '' : 'none';
        } else if (type === 'inactive') {
            row.style.display = row.getAttribute('data-status') === 'inactive' ? '' : 'none';
        } else if (type === 'demo') {
            row.style.display = row.querySelector('.demo-badge-table') ? '' : 'none';
        } else if (type === 'regular') {
            row.style.display = row.querySelector('.demo-badge-table') ? 'none' : '';
        } else {
            row.style.display = row.getAttribute('data-role') === type ? '' : 'none';
        }
    });
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

/* ===== ENHANCED METRICS SECTION ===== */
.metrics-section {
    margin-bottom: 48px;
}

.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.metric-card {
    background: #FFFFFF;
    border-radius: 20px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.metric-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.metric-primary {
    --color-start: #4F46E5;
    --color-end: #7C3AED;
}

.metric-success {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.metric-warning {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.metric-info {
    --color-start: #3B82F6;
    --color-end: #2563EB;
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.metric-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #FFFFFF;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.metric-primary .metric-icon {
    background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
}

.metric-success .metric-icon {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.metric-warning .metric-icon {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.metric-info .metric-icon {
    background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
}

.metric-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-content {
    margin-bottom: 24px;
}

.metric-value {
    font-size: 32px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.metric-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.metric-label {
    font-size: 14px;
    color: #6B7280;
}

.metric-trend {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: #22C55E;
}

.metric-trend.negative {
    color: #EF4444;
}

.metric-sparkline {
    height: 50px;
}

/* ===== TABLE SECTION ===== */
.table-section {
    margin-bottom: 48px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.section-title-group {
    flex: 1;
}

.section-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.section-subtitle {
    font-size: 14px;
    color: #6B7280;
}

.section-actions {
    display: flex;
    gap: 12px;
}

.search-input {
    position: relative;
    display: flex;
    align-items: center;
}

.search-input svg {
    position: absolute;
    left: 12px;
    color: #9CA3AF;
}

.search-input input {
    padding: 10px 16px 10px 40px;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    width: 250px;
    transition: all 0.2s ease;
}

.search-input input:focus {
    outline: none;
    border-color: #22C55E;
    box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
}

.filter-dropdown {
    position: relative;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn:hover {
    border-color: #22C55E;
    color: #22C55E;
}

.filter-options {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 8px;
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s ease;
    z-index: 10;
}

.filter-options.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.filter-option {
    display: block;
    width: 100%;
    padding: 10px 16px;
    background: transparent;
    border: none;
    text-align: left;
    font-size: 14px;
    color: #6B7280;
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-option:hover {
    background: #F3F4F6;
}

.filter-option.active {
    background: #F3F4F6;
    color: #22C55E;
    font-weight: 600;
}

.view-toggle {
    display: flex;
    gap: 8px;
}

.toggle-btn {
    padding: 10px;
    border: 1px solid #E5E7EB;
    background: #FFFFFF;
    color: #6B7280;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.toggle-btn.active {
    background: #22C55E;
    color: white;
    border-color: #22C55E;
}

.table-container {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-responsive {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
}

.users-table thead {
    background: #F9FAFB;
}

.users-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.users-table tbody tr {
    border-bottom: 1px solid #F3F4F6;
    transition: background-color 0.2s ease;
}

.users-table tbody tr:hover {
    background: #F9FAFB;
}

.users-table td {
    padding: 16px;
    font-size: 14px;
}

.user-name-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

/* ===== USER GRID ===== */
.users-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
    padding: 24px;
}

.user-card {
    background: #FFFFFF;
    border-radius: 16px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    overflow: hidden;
    position: relative;
}

.user-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
}

.user-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--color-start) 0%, var(--color-end) 100%);
}

.user-card.super_admin {
    --color-start: #8B5CF6;
    --color-end: #7C3AED;
}

.user-card.admin {
    --color-start: #F59E0B;
    --color-end: #D97706;
}

.user-card.user {
    --color-start: #22C55E;
    --color-end: #10B981;
}

.user-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 20px 0;
}

.user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 16px;
}

.user-card.super_admin .user-avatar {
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
}

.user-card.admin .user-avatar {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
}

.user-card.user .user-avatar {
    background: linear-gradient(135deg, #22C55E 0%, #10B981 100%);
}

.avatar-initials {
    text-transform: uppercase;
}

.user-info {
    padding: 0 20px 20px;
}

.user-name {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.user-email {
    font-size: 14px;
    color: #6B7280;
    margin-bottom: 12px;
}

.user-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.user-role {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.user-role.super_admin {
    background: rgba(139, 92, 246, 0.1);
    color: #8B5CF6;
}

.user-role.admin {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.user-role.user {
    background: rgba(34, 197, 94, 0.1);
    color: #22C55E;
}

.user-branch {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #6B7280;
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

/* ===== DEMO BADGE STYLES ===== */
.demo-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
    margin-left: 8px;
}

.demo-badge-table {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 6px;
    border-radius: 8px;
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);
    margin-left: 6px;
}

.status-container {
    display: flex;
    align-items: center;
    gap: 6px;
}

.user-actions {
    display: flex;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.user-card:hover .user-actions {
    opacity: 1;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: #F3F4F6;
    color: #6B7280;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.action-btn:hover {
    background: #E5E7EB;
    color: #374151;
}

.view-btn:hover {
    background: rgba(79, 70, 229, 0.1);
    color: #4F46E5;
}

.edit-btn:hover {
    background: rgba(245, 158, 11, 0.1);
    color: #F59E0B;
}

.delete-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: #EF4444;
}

/* ===== EMPTY STATE ===== */
.empty-section {
    margin-bottom: 48px;
}

.empty-state {
    background: #FFFFFF;
    border-radius: 20px;
    border: 1px solid #E5E7EB;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    padding: 60px 40px;
    text-align: center;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
    background: rgba(34, 197, 94, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #22C55E;
}

.empty-title {
    font-size: 24px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.empty-description {
    font-size: 16px;
    color: #6B7280;
    max-width: 500px;
    margin: 0 auto 32px;
}

.empty-action {
    margin-top: 8px;
}

/* ===== PAGINATION ===== */
.pagination-container {
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 1px solid #E5E7EB;
}

.pagination-container span {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 4px;
    border-radius: 6px;
    background: #F3F4F6;
    color: #6B7280;
    font-size: 14px;
    font-weight: 500;
}

.pagination-container a {
    display: inline-block;
    padding: 8px 12px;
    margin: 0 4px;
    border-radius: 6px;
    background: #FFFFFF;
    color: #22C55E;
    border: 1px solid #E5E7EB;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}

.pagination-container a:hover {
    background: #F9FAFB;
    border-color: #22C55E;
}

.pagination-container .current {
    background: #22C55E;
    color: #FFFFFF;
    border-color: #22C55E;
}

/* ===== UTILITY CLASSES ===== */
.d-none {
    display: none !important;
}

.d-inline {
    display: inline !important;
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .dashboard-layout {
        padding: 24px;
    }
    
    .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .users-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
    
    .metrics-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .section-actions {
        width: 100%;
        flex-direction: column;
        gap: 12px;
    }
    
    .search-input input {
        width: 100%;
    }
    
    .users-table {
        font-size: 12px;
    }
    
    .users-table th,
    .users-table td {
        padding: 12px 8px;
    }
    
    .users-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .dashboard-layout {
        padding: 12px;
    }
    
    .page-title {
        font-size: 24px;
    }
    
    .metric-value {
        font-size: 28px;
    }
    
    .user-card {
        padding: 16px;
    }
    
    .user-avatar {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .user-name {
        font-size: 16px;
    }
    
    .user-email {
        font-size: 13px;
    }
    
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endsection