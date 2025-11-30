<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', App\Models\AppSetting::getValue('app_name', 'Akuntansi Sibuku'))</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-50 font-inter">
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <span class="sidebar-title">{{ App\Models\AppSetting::getValue('app_name', 'Akuntansi Sibuku') }}</span>
            </div>
            <button class="sidebar-close-btn" id="sidebarCloseBtn">
                <i class="fas fa-times"></i>
            </button>
            @if(Auth::check() && Auth::user()->demo_mode)
                <div class="demo-watermark">
                    <span>DEMO MODE</span>
                </div>
            @endif
        </div>

        <div class="sidebar-menu">
            <ul class="nav-list">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('accounts*') ? 'active' : '' }}" href="{{ route('accounts.index') }}">
                        <i class="fas fa-wallet nav-icon"></i>
                        <span class="nav-text">Akun</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        <i class="fas fa-tags nav-icon"></i>
                        <span class="nav-text">Kategori</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('incomes*') ? 'active' : '' }}" href="{{ route('incomes.index') }}">
                        <i class="fas fa-plus-circle nav-icon"></i>
                        <span class="nav-text">Pemasukan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('expenses*') ? 'active' : '' }}" href="{{ route('expenses.index') }}">
                        <i class="fas fa-minus-circle nav-icon"></i>
                        <span class="nav-text">Pengeluaran</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transfers*') ? 'active' : '' }}" href="{{ route('transfers.index') }}">
                        <i class="fas fa-exchange-alt nav-icon"></i>
                        <span class="nav-text">Transfer</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('chart-of-accounts*') ? 'active' : '' }}" href="{{ route('chart-of-accounts.index') }}">
                        <i class="fas fa-book nav-icon"></i>
                        <span class="nav-text">Chart of Accounts</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('recurring-templates*') ? 'active' : '' }}" href="{{ route('recurring-templates.index') }}">
                        <i class="fas fa-calendar-alt nav-icon"></i>
                        <span class="nav-text">Berulang</span>
                    </a>
                </li>

                <!-- Inventory Section -->
                <li class="nav-item">
                    <div class="nav-link submenu-toggle {{ request()->routeIs('product-categories*') || request()->routeIs('products*') || request()->routeIs('services*') || request()->routeIs('customers*') || request()->routeIs('stock-movements*') ? 'active-parent' : '' }}" onclick="toggleSubmenu('inventoryMenu')">
                        <i class="fas fa-boxes nav-icon"></i>
                        <span class="nav-text">Inventori</span>
                        <i class="fas fa-chevron-down submenu-chevron" id="inventoryChevron"></i>
                    </div>
                    <ul class="submenu {{ request()->routeIs('product-categories*') || request()->routeIs('products*') || request()->routeIs('services*') || request()->routeIs('customers*') || request()->routeIs('stock-movements*') ? 'is-open' : '' }}" id="inventoryMenu">
                        <li><a class="submenu-link {{ request()->routeIs('product-categories*') ? 'active' : '' }}" href="{{ route('product-categories.index') }}">Kategori Produk</a></li>
                        <li><a class="submenu-link {{ request()->routeIs('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">Produk</a></li>
                        <li><a class="submenu-link {{ request()->routeIs('services*') ? 'active' : '' }}" href="{{ route('services.index') }}">Layanan</a></li>
                        <li><a class="submenu-link {{ request()->routeIs('customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">Pelanggan</a></li>
                        <li><a class="submenu-link {{ request()->routeIs('stock-movements*') ? 'active' : '' }}" href="{{ route('stock-movements.index') }}">Pergerakan Stok</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <div class="nav-link submenu-toggle {{ request()->routeIs('reports*') ? 'active-parent' : '' }}" onclick="toggleSubmenu('reportsMenu')">
                        <i class="fas fa-chart-bar nav-icon"></i>
                        <span class="nav-text">Laporan</span>
                        <i class="fas fa-chevron-down submenu-chevron" id="reportsChevron"></i>
                    </div>
                    <ul class="submenu {{ request()->routeIs('reports*') ? 'is-open' : '' }}" id="reportsMenu">
                        <li><a class="submenu-link {{ request()->routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">Ringkasan Laporan</a></li>
                    </ul>
                </li>

                <!-- Management Section -->
                <li class="nav-item">
                    <div class="nav-link submenu-toggle {{ request()->routeIs('branches*') || request()->routeIs('users*') || request()->routeIs('tax*') ? 'active-parent' : '' }}" onclick="toggleSubmenu('managementMenu')">
                        <i class="fas fa-cogs nav-icon"></i>
                        <span class="nav-text">Manajemen</span>
                        <i class="fas fa-chevron-down submenu-chevron" id="managementChevron"></i>
                    </div>
                    <ul class="submenu {{ request()->routeIs('branches*') || request()->routeIs('users*') || request()->routeIs('tax*') ? 'is-open' : '' }}" id="managementMenu">
                        <li><a class="submenu-link {{ request()->routeIs('branches*') ? 'active' : '' }}" href="{{ route('branches.index') }}">Cabang</a></li>
                        <li><a class="submenu-link {{ request()->routeIs('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">Pengguna</a></li>
                        <li><a class="submenu-link {{ request()->routeIs('tax*') ? 'active' : '' }}" href="{{ route('tax.index') }}">Pajak</a></li>
                    </ul>
                </li>

                <!-- Settings Section -->
                <li class="nav-item">
                    <div class="nav-link submenu-toggle {{ request()->routeIs('settings*') ? 'active-parent' : '' }}" onclick="toggleSubmenu('settingsMenu')">
                        <i class="fas fa-cog nav-icon"></i>
                        <span class="nav-text">Pengaturan</span>
                        <i class="fas fa-chevron-down submenu-chevron" id="settingsChevron"></i>
                    </div>
                    <ul class="submenu {{ request()->routeIs('settings*') ? 'is-open' : '' }}" id="settingsMenu">
                        <li><a class="submenu-link {{ request()->routeIs('settings.index') ? 'active' : '' }}" href="{{ route('settings.index') }}">Umum</a></li>
                        <li><a class="submenu-link {{ request()->routeIs('settings.whatsapp*') ? 'active' : '' }}" href="{{ route('settings.whatsapp.index') }}">WhatsApp</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Page Title -->
                <div class="page-title-section">
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                </div>

                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Branch Switcher -->
                    @if(\App\Models\Branch::count() > 1)
                    <div class="branch-switcher">
                        <button class="branch-btn" type="button" onclick="toggleBranchDropdown()">
                            <i class="fas fa-building"></i>
                            <span>{{ session('active_branch') ? \App\Models\Branch::find(session('active_branch'))?->name ?? 'Pilih Cabang' : (Auth::user()->branch?->name ?? 'Semua Cabang') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="branch-dropdown" id="branchDropdown">
                            @if(Auth::user()->userRole?->name === 'super_admin' || !Auth::user()->branch)
                                <form method="POST" action="{{ route('branches.switch', 0) }}" class="branch-option {{ !session('active_branch') ? 'active' : '' }}">
                                    @csrf
                                    <button type="submit">
                                        <i class="fas fa-globe"></i>
                                        <span>Semua Cabang</span>
                                    </button>
                                </form>
                            @endif
                            @foreach(\App\Models\Branch::all() as $branch)
                                @if(Auth::user()->userRole?->name === 'super_admin' ||
                                    !Auth::user()->branch ||
                                    Auth::user()->branch->id === $branch->id)
                                    <form method="POST" action="{{ route('branches.switch', $branch->id) }}" class="branch-option {{ session('active_branch') == $branch->id ? 'active' : '' }}">
                                        @csrf
                                        <button type="submit">
                                            <i class="fas fa-building"></i>
                                            <span>{{ $branch->name }}</span>
                                        </button>
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- User Menu -->
                    <div class="user-menu">
                        <button class="user-btn" type="button" onclick="toggleUserDropdown()">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('users.profile') }}" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Profil</span>
                            </a>
                            <a href="{{ route('settings.index') }}" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Pengaturan</span>
                            </a>
                            @if(Auth::user()->userRole && in_array(Auth::user()->userRole->name, ['super_admin', 'admin']))
                                <div class="dropdown-divider"></div>
                                <a href="{{ route('users.index') }}" class="dropdown-item">
                                    <i class="fas fa-users-cog"></i>
                                    <span>Kelola Pengguna</span>
                                </a>
                                <a href="{{ route('tax.index') }}" class="dropdown-item">
                                    <i class="fas fa-calculator"></i>
                                    <span>Pengaturan Pajak</span>
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="content">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="alert-close" onclick="closeAlert(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="alert-close" onclick="closeAlert(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>
                        <strong>Terjadi kesalahan:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </span>
                    <button type="button" class="alert-close" onclick="closeAlert(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif


            @yield('content')
        </main>
    </div>

    <!-- Created By Component (can be included in pages where needed) -->
    @if(isset($creator) && $creator)
    <div class="created-by-component">
        <div class="created-by-avatar">
            <img src="{{ $creator->avatar ?? asset('images/default-avatar.png') }}" alt="{{ $creator->name }}">
        </div>
        <div class="created-by-info">
            <div class="created-by-label">Dibuat oleh</div>
            <div class="created-by-name">{{ $creator->name }}</div>
            <div class="created-by-date">{{ isset($model) && $model->created_at ? $model->created_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }}</div>
        </div>
        <div class="created-by-actions">
            <button class="created-by-action-btn" title="Kirim Pesan">
                <i class="fas fa-envelope"></i>
            </button>
            <button class="created-by-action-btn" title="Lihat Profil">
                <i class="fas fa-user"></i>
            </button>
        </div>
    </div>
    @endif

    <script>
        function toggleSubmenu(menuId) {
            const menu = document.getElementById(menuId);
            const chevron = document.getElementById(menuId.replace('Menu', 'Chevron'));

            menu.classList.toggle('is-open');
            
            if (menu.classList.contains('is-open')) {
                chevron.classList.remove('fa-chevron-down');
                chevron.classList.add('fa-chevron-up');
            } else {
                chevron.classList.remove('fa-chevron-up');
                chevron.classList.add('fa-chevron-down');
            }
        }

        function toggleBranchDropdown() {
            const dropdown = document.getElementById('branchDropdown');
            dropdown.classList.toggle('show');
        }

        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        function closeAlert(button) {
            const alert = button.parentElement;
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }


        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');

            mobileMenuBtn?.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-mobile-open');
                sidebarOverlay.classList.toggle('show');
            });

            sidebarCloseBtn?.addEventListener('click', function() {
                sidebar.classList.remove('sidebar-mobile-open');
                sidebarOverlay.classList.remove('show');
            });

            sidebarOverlay?.addEventListener('click', function() {
                sidebar.classList.remove('sidebar-mobile-open');
                sidebarOverlay.classList.remove('show');
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.branch-switcher')) {
                    document.getElementById('branchDropdown')?.classList.remove('show');
                }
                if (!e.target.closest('.user-menu')) {
                    document.getElementById('userDropdown')?.classList.remove('show');
                }
            });

            setTimeout(function() {
                document.querySelectorAll('.alert').forEach(function(alert) {
                    if (alert.querySelector('.alert-close')) {
                        closeAlert(alert.querySelector('.alert-close'));
                    }
                });
            }, 5000);

            document.querySelectorAll('form').forEach(function(form) {
                form.addEventListener('submit', function() {
                    const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                    submitButtons.forEach(function(button) {
                        button.disabled = true;
                        const originalText = button.innerHTML;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                        button.dataset.originalText = originalText;
                    });
                });
            });
        });
    </script>
    @stack('scripts')

    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            /* Modern Dark Theme Colors */
            --sidebar-bg: #1F2937;
            --sidebar-hover: rgba(255, 255, 255, 0.05);
            --sidebar-border: rgba(255, 255, 255, 0.08);
            --text-primary: #F9FAFB;
            --text-secondary: #D1D5DB;
            --text-muted: #9CA3AF;
            /* Vibrant Accent Color */
            --accent-color: #22C55E;
            --accent-color-hover: #10B981;
            --accent-light: rgba(34, 197, 94, 0.15);
            /* Light Content Area */
            --content-bg: #F8FAFC;
            --header-bg: #FFFFFF;
            --border-color: #E5E7EB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
        }

        /* ===== BASE STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--content-bg);
            color: var(--gray-700);
            line-height: 1.6;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            z-index: 1050;
            transform: translateX(0);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 28px 24px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-color-hover));
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }

        .sidebar-close-btn {
            display: none;
            background: var(--sidebar-hover);
            border: 1px solid var(--sidebar-border);
            color: var(--text-secondary);
            cursor: pointer;
            padding: 8px;
            font-size: 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .nav-list {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: var(--text-primary);
            transform: translateX(4px);
        }

        .nav-link.active {
            background-color: var(--accent-light);
            color: var(--accent-color);
            font-weight: 600;
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--accent-color);
            border-radius: 0 4px 4px 0;
            box-shadow: 0 0 10px rgba(34, 197, 94, 0.5);
        }

        .nav-link.active-parent {
            background-color: var(--sidebar-hover);
            color: var(--text-primary);
            font-weight: 600;
        }

        .nav-icon {
            font-size: 18px;
            width: 24px;
            margin-right: 16px;
            text-align: center;
            transition: color 0.2s ease;
        }
        
        .nav-link:hover .nav-icon {
            color: var(--accent-color);
        }

        .nav-text {
            flex: 1;
        }

        .submenu-toggle {
            cursor: pointer;
        }

        .submenu-chevron {
            font-size: 12px;
            transition: transform 0.2s ease;
            color: var(--text-muted);
        }
        
        .nav-link.active-parent .submenu-chevron {
            color: var(--text-secondary);
        }

        .submenu {
            list-style: none;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.3s ease, opacity 0.2s ease;
        }

        .submenu.is-open {
            max-height: 500px;
            opacity: 1;
        }

        .submenu-link {
            display: block;
            padding: 11px 24px 11px 60px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.2s ease;
        }

        .submenu-link:hover {
            color: var(--text-primary);
            background-color: var(--sidebar-hover);
            transform: translateX(4px);
        }

        .submenu-link.active {
            color: var(--accent-color);
            font-weight: 500;
        }

        /* ===== SIDEBAR OVERLAY ===== */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* ===== HEADER ===== */
        .header {
            background: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: var(--header-height);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--gray-600);
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .mobile-menu-btn:hover {
            background: var(--gray-100);
        }

        .page-title-section {
            flex: 1;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* ===== BRANCH SWITCHER ===== */
        .branch-switcher {
            position: relative;
        }

        .branch-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            background: var(--gray-100);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--gray-700);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .branch-btn:hover {
            background: var(--gray-200);
            border-color: var(--gray-300);
        }

        .branch-btn i {
            font-size: 16px;
        }

        .branch-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            z-index: 1000;
        }

        .branch-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .branch-option {
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .branch-option button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            border: none;
            background: none;
            color: var(--gray-700);
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .branch-option button:hover {
            background: var(--gray-100);
        }

        .branch-option.active button {
            background: var(--accent-light);
            color: var(--accent-color);
            font-weight: 600;
        }

        /* ===== USER MENU ===== */
        .user-menu {
            position: relative;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            background: none;
            border: none;
            color: var(--gray-700);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 10px;
        }

        .user-btn:hover {
            background: var(--gray-100);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-color-hover));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.25);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.2s ease;
            z-index: 1000;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .dropdown-item:hover {
            background: var(--gray-100);
        }

        .dropdown-item.text-danger {
            color: #EF4444;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, 0.05);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 4px 0;
        }

        /* ===== CONTENT ===== */
        .content {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ===== ALERTS ===== */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            border: 1px solid;
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, #F0FDF4 0%, #DCFCE7 100%);
            border: 1px solid rgba(34, 197, 94, 0.2);
            color: #065F46;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.1);
        }

        .alert-danger {
            background: linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #991B1B;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
        }

        .alert i {
            font-size: 20px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-close {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            padding: 4px;
            margin-left: auto;
            opacity: 0.7;
            transition: opacity 0.2s ease;
        }

        .alert-close:hover {
            opacity: 1;
        }
        
        .alert span {
            flex: 1;
        }
        
        .alert span ul {
            margin-left: 20px;
            margin-top: 8px;
        }

        /* ===== DEMO WATERMARK ===== */
        .demo-watermark {
            position: absolute;
            top: 50%;
            right: 24px;
            transform: translateY(-50%) rotate(-45deg);
            background: rgba(255, 193, 7, 0.9);
            color: #856404;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
            z-index: 10;
        }

        /* ===== CREATED BY COMPONENT ===== */
        .created-by-component {
            display: flex;
            align-items: center;
            gap: 16px;
            background: #FFFFFF;
            border-radius: 16px;
            padding: 20px;
            margin-top: 24px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .created-by-component:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .created-by-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 3px solid var(--accent-light);
        }

        .created-by-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .created-by-info {
            flex-grow: 1;
        }

        .created-by-label {
            font-size: 12px;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .created-by-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 4px;
        }

        .created-by-date {
            font-size: 14px;
            color: var(--gray-600);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .created-by-date::before {
            content: '';
            display: inline-block;
            width: 14px;
            height: 14px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
        }

        .created-by-actions {
            display: flex;
            gap: 8px;
        }

        .created-by-action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: var(--gray-100);
            color: var(--gray-600);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .created-by-action-btn:hover {
            background: var(--accent-light);
            color: var(--accent-color);
            transform: scale(1.1);
        }


        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.sidebar-mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: block;
            }

            .sidebar-close-btn {
                display: block;
            }

            .page-title {
                font-size: 20px;
            }

            .header-actions {
                gap: 8px;
            }

            .branch-switcher {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }

            .header-content {
                padding: 0 20px;
            }

            .page-title {
                font-size: 18px;
            }

            .user-btn span {
                display: none;
            }

            .created-by-component {
                flex-direction: column;
                text-align: center;
                gap: 12px;
            }

            .created-by-actions {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .content {
                padding: 16px;
            }

            .header-content {
                padding: 0 16px;
            }

            .page-title {
                font-size: 16px;
            }
        }
    </style>
</body>
</html>