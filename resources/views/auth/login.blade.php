<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sibuku Akuntansi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-primary: #f8fafc;
            --bg-card: #ffffff;
            --text-primary: #1a202c;
            --text-secondary: #4a5568;
            --text-muted: #718096;
            --accent-color: #4f46e5;
            --accent-hover: #4338ca;
            --accent-light: #e0e7ff;
            --border-color: #e2e8f0;
            --error-color: #ef4444;
            --success-color: #10b981;
            --side-bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            width: 100%;
            position: relative;
        }

        /* Background decoration */
        .bg-decoration {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.05;
        }

        .bg-circle-1 {
            width: 500px;
            height: 500px;
            background: var(--accent-color);
            top: -200px;
            right: -100px;
        }

        .bg-circle-2 {
            width: 300px;
            height: 300px;
            background: var(--success-color);
            bottom: -100px;
            left: -50px;
        }

        /* Left Side - Explanation Panel */
        .side-panel {
            flex: 1;
            background: var(--side-bg-gradient);
            color: white;
            padding: 80px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .side-content {
            max-width: 500px;
        }

        .side-content .badge {
            display: inline-flex;
            align-items: center;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 32px;
            backdrop-filter: blur(4px);
        }
        
        .side-content .badge i {
            margin-right: 8px;
        }

        .side-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 24px;
            color: white;
        }

        .side-content p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.85);
            line-height: 1.7;
            margin-bottom: 40px;
        }

        .feature-list {
            list-style: none;
        }

        .feature-list li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 24px;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .feature-list li i {
            color: white;
            margin-right: 16px;
            font-size: 1.2rem;
            margin-top: 3px;
        }

        /* Right Side - Login Form */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background-color: var(--bg-card);
            border-radius: 20px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 48px;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            background: var(--side-bg-gradient);
            border-radius: 16px;
            margin-bottom: 24px;
            color: white;
            font-size: 28px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .card-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .card-header p {
            font-size: 16px;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        /* Style for icons inside input */
        .input-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 18px;
            transition: color 0.2s ease;
            z-index: 2; /* Ensure icon is above the input's background */
        }

        .input-icon-left {
            left: 16px;
        }

        .input-icon-right {
            right: 16px;
            cursor: pointer;
        }
        
        .input-icon-right:hover {
            color: var(--text-primary);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 50px; /* Increased left padding for icon space */
            font-size: 16px;
            color: var(--text-primary);
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        /* Specific padding for inputs with a right icon */
        .form-control.has-right-icon {
            padding-right: 50px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            background-color: var(--bg-card);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .form-control:focus ~ .input-icon-left {
            color: var(--accent-color);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: var(--accent-color);
        }

        .checkbox-wrapper label {
            font-size: 14px;
            color: var(--text-secondary);
            cursor: pointer;
        }

        .form-options a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .form-options a:hover {
            color: var(--accent-hover);
            text-decoration: underline;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 14px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: var(--side-bg-gradient);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--accent-color);
            border: 1px solid var(--border-color);
            margin-top: 20px;
        }

        .btn-secondary:hover {
            background-color: var(--accent-light);
            transform: translateY(-2px);
        }

        .btn i {
            margin-right: 8px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 28px 0;
            color: var(--text-muted);
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: var(--border-color);
        }

        .divider span {
            padding: 0 16px;
        }
        
        /* Invalid state */
        .form-group.is-invalid .form-control {
            border-color: var(--error-color);
        }
        .form-group.is-invalid .form-control:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }
        .invalid-feedback {
            color: var(--error-color);
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        /* Loading state */
        .btn-primary.loading {
            position: relative;
            color: transparent;
        }

        .btn-primary.loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .side-panel {
                padding: 60px 40px;
            }
            .side-content h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .side-panel {
                padding: 40px 20px;
                text-align: center;
            }
            .main-content {
                padding: 20px;
            }
            .login-card {
                padding: 32px 24px;
            }
            .side-content h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Background decoration -->
        <div class="bg-decoration">
            <div class="bg-circle bg-circle-1"></div>
            <div class="bg-circle bg-circle-2"></div>
        </div>

        <!-- Left Side: Explanation -->
        <aside class="side-panel">
            <div class="side-content">
                <div class="badge">
                    <i class="fas fa-sparkles"></i> Platform Terpercaya
                </div>
                <h1>Kelola Keuangan Bisnis Anda Lebih Cerdas & Efisien</h1>
                <p>Sibuku adalah solusi akuntansi all-in-one yang dirancang untuk menyederhanakan proses finansial, memberikan wawasan real-time, dan membantu Anda mengambil keputusan bisnis yang lebih baik.</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Dashboard & Laporan Keuangan Real-Time</li>
                    <li><i class="fas fa-check-circle"></i> Manajemen Penagihan & Pembayaran Terpadu</li>
                    <li><i class="fas fa-check-circle"></i> Keamanan Data Tingkat Enterprise</li>
                    <li><i class="fas fa-check-circle"></i> Akses Kapan Saja, Di Mana Saja</li>
                </ul>
            </div>
        </aside>

        <!-- Right Side: Login Form -->
        <main class="main-content">
            <section class="login-card">
                <header class="card-header">
                    <div class="logo">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h1>Sibuku Akuntansi</h1>
                    <p>Masuk untuk melanjutkan ke dashboard Anda</p>
                </header>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group @error('email') is-invalid @enderror">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon input-icon-left"></i>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group @error('password') is-invalid @enderror">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock input-icon input-icon-left"></i>
                            <input type="password" id="password" name="password" class="form-control has-right-icon" required>
                            <i class="fas fa-eye-slash input-icon input-icon-right" onclick="togglePassword('password')"></i>
                        </div>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <div class="checkbox-wrapper">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Ingat saya</label>
                        </div>
                        <a href="#">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="btn btn-primary" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </button>
                </form>

                <div class="divider">
                    <span>atau</span>
                </div>

                <a href="{{ route('demo.login') }}" class="btn btn-secondary">
                    <i class="fas fa-play-circle"></i> Coba Versi Demo
                </a>
            </section>
        </main>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            // Use a more robust selector to find the toggle icon
            const icon = input.parentElement.querySelector('.input-icon-right');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        // Add loading state to login button
        document.querySelector('form').addEventListener('submit', function() {
            const loginBtn = document.getElementById('loginBtn');
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
        });
    </script>
</body>
</html>