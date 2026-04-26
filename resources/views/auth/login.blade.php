<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Frutika - Login</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            font-family: 'Segoe UI', Trebuchet MS, sans-serif;
            background: #0f172a;
        }

        .frutika-auth-container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1a2e4a 50%, #0f1f3a 100%);
            overflow: hidden;
        }

        /* Animated Background */
        .animated-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            opacity: 0.15;
            filter: blur(40px);
            border-radius: 50%;
        }

        .blob-1 {
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, #ff8c42 0%, #ff6b35 100%);
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .blob-2 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #2ecc71 0%, #27ae60 100%);
            bottom: -50px;
            left: -100px;
            animation: float 8s ease-in-out infinite 1s;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #9b59b6 0%, #8e44ad 100%);
            bottom: 200px;
            right: 5%;
            animation: float 7s ease-in-out infinite 2s;
        }

        .gradient-mesh {
            position: absolute;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(at 20% 50%, rgba(255, 140, 66, 0.1) 0px, transparent 50px),
                radial-gradient(at 80% 80%, rgba(46, 204, 113, 0.1) 0px, transparent 50px);
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(20px) translateX(10px); }
        }

        /* Main Content */
        .auth-content {
            position: relative;
            z-index: 10;
            width: 100%;
            padding: 20px;
        }

        .auth-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            max-width: 1200px;
            margin: 0 auto;
            align-items: center;
        }

        /* Hero Section */
        .auth-hero {
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: slideInLeft 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .welcome-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 24px;
            color: #ff8c42;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            width: fit-content;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 700;
            color: white;
            line-height: 1.2;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-name {
            background: linear-gradient(135deg, #ff8c42 0%, #ff6b35 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 40px;
            line-height: 1.6;
        }

        /* Fruit Decorations */
        .fruit-decorations {
            position: relative;
            height: 120px;
        }

        .fruit {
            position: absolute;
            font-size: 60px;
            opacity: 0;
            animation: fruitFloat 4s ease-in-out infinite;
        }

        .fruit-orange { left: 0; animation-delay: 0s; }
        .fruit-apple { left: 80px; animation-delay: 0.5s; }
        .fruit-banana { left: 160px; animation-delay: 1s; }
        .fruit-grape { left: 240px; animation-delay: 1.5s; }

        @keyframes fruitFloat {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.8);
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                transform: translateY(-40px) scale(1);
            }
        }

        /* Form Section */
        .auth-form-section {
            animation: slideInRight 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .form-title {
            font-size: 32px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 32px;
        }

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-content p {
            font-size: 13px;
            line-height: 1.4;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 24px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: white;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .form-input::placeholder {
            color: transparent;
        }

        .form-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 140, 66, 0.5);
            box-shadow: 0 0 0 3px rgba(255, 140, 66, 0.1);
        }

        .form-input.input-error {
            border-color: rgba(239, 68, 68, 0.5);
            background: rgba(239, 68, 68, 0.05);
        }

        .form-input.input-error:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .floating-label {
            position: absolute;
            left: 16px;
            top: 14px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.5);
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .label-icon {
            font-size: 16px;
        }

        .form-input:focus ~ .floating-label,
        .form-input:not(:placeholder-shown) ~ .floating-label {
            transform: translateY(-38px) scale(1.2);
            color: #ff8c42;
        }

        .input-focus-border {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            background: linear-gradient(90deg, #ff8c42, #ff6b35);
            border-radius: 2px;
            width: 0;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-input:focus ~ .input-focus-border {
            width: 100%;
        }

        .error-message {
            display: block;
            font-size: 12px;
            color: #fca5a5;
            margin-top: 8px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            font-size: 16px;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        /* Form Footer */
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            font-size: 13px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #ff8c42;
        }

        .checkbox-label {
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: color 0.3s;
        }

        .checkbox-label:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .forgot-link {
            color: #ff8c42;
            text-decoration: none;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #ff6b35;
            text-decoration: underline;
        }

        /* Buttons */
        .btn {
            width: 100%;
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            left: 50%;
            top: 0;
            border-radius: 50%;
            transition: width 0.6s;
        }

        .btn:hover::before {
            width: 300px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff8c42 0%, #ff6b35 100%);
            color: white;
        }

        .btn-glow {
            box-shadow: 0 0 20px rgba(255, 140, 66, 0.3);
        }

        .btn-glow:hover {
            box-shadow: 0 0 40px rgba(255, 140, 66, 0.5);
            transform: translateY(-2px);
        }

        .btn-text {
            position: relative;
            z-index: 1;
        }

        .btn-icon {
            position: relative;
            z-index: 1;
            transition: transform 0.3s;
        }

        .btn:hover .btn-icon {
            transform: translateX(4px);
        }

        @keyframes slideRight {
            0% {
                transform: translateX(0);
                opacity: 1;
            }
            100% {
                transform: translateX(600%);
                opacity: 1;
            }
        }

        .btn.animating .btn-text {
            animation: slideRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .btn.animating .btn-icon {
            animation: slideRight 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .btn.animating {
            pointer-events: none;
        }

        /* Social Buttons */
        .social-divider {
            text-align: center;
            margin: 32px 0;
            position: relative;
        }

        .social-divider::before,
        .social-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: calc(50% - 60px);
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-divider::before {
            left: 0;
        }

        .social-divider::after {
            right: 0;
        }

        .social-divider span {
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            position: relative;
            background: rgba(255, 255, 255, 0.08);
            padding: 0 20px;
        }

        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 32px;
        }

        .social-btn {
            padding: 12px;
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            color: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .social-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .google-btn:hover {
            border-color: rgba(255, 140, 66, 0.5);
            background: rgba(255, 140, 66, 0.1);
        }

        /* Auth Switch */
        .auth-switch {
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .auth-link {
            color: #ff8c42;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .auth-link:hover {
            color: #ff6b35;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-wrapper {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .auth-hero {
                display: none;
            }

            .form-container {
                padding: 32px 24px;
            }

            .form-title {
                font-size: 24px;
            }

            .hero-title {
                font-size: 28px;
            }

            .social-buttons {
                grid-template-columns: 1fr;
            }

            .blob {
                filter: blur(60px);
            }

            .blob-1 {
                width: 200px;
                height: 200px;
            }

            .blob-2 {
                width: 200px;
                height: 200px;
            }

            .blob-3 {
                width: 150px;
                height: 150px;
            }
        }

        @media (max-width: 480px) {
            .auth-content {
                padding: 16px;
            }

            .form-container {
                padding: 24px 16px;
            }

            .form-title {
                font-size: 20px;
            }

            .form-input {
                padding: 12px 12px 12px 40px;
                font-size: 16px;
            }

            .floating-label {
                font-size: 12px;
            }

            .welcome-badge {
                font-size: 10px;
                padding: 6px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="frutika-auth-container login-page">
        <!-- Animated Background -->
        <div class="animated-bg">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>
            <div class="gradient-mesh"></div>
        </div>

        <!-- Main Content -->
        <div class="auth-content">
            <div class="auth-wrapper">
                <!-- Left Side - Hero Section -->
                <div class="auth-hero">
                    <div class="hero-content">
                        <div class="welcome-badge">Welcome back</div>
                        <h1 class="hero-title">Welcome back to<br><span class="brand-name">Frutika</span> </h1>
                        <p class="hero-subtitle">Fresh, healthy, and delicious products await you</p>

                        <div class="fruit-decorations">
                            <div class="fruit fruit-orange">🍊</div>
                            <div class="fruit fruit-apple">🍎</div>
                            <div class="fruit fruit-banana">🍌</div>
                            <div class="fruit fruit-grape">🍇</div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="auth-form-section">
                    <div class="form-container">
                        <h2 class="form-title">Login</h2>
                        <p class="form-subtitle">Sign in to your account</p>

                        @if ($errors->any())
                            <div class="alert alert-error">
                                <svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <div class="alert-content">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="auth-form">
                            @csrf

                            <!-- Email Field -->
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-input @error('email') input-error @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        autofocus
                                        placeholder=" ">
                                    <label for="email" class="floating-label">
                                        <span class="label-icon">✉️</span>
                                        Email Address
                                    </label>
                                    <div class="input-focus-border"></div>
                                </div>
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-input @error('password') input-error @enderror"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder=" ">
                                    <label for="password" class="floating-label">
                                        <span class="label-icon">🔒</span>
                                        Password
                                    </label>
                                    <button type="button" class="toggle-password" onclick="togglePassword(this)">👁️</button>
                                    <div class="input-focus-border"></div>
                                </div>
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="form-footer">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember" class="checkbox-label">Remember me</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn btn-primary btn-glow" id="loginBtn">
                                <span class="btn-text">Sign In</span>
                                <span class="btn-icon">→</span>
                            </button>
                        </form>

                        {{-- <!-- Social Login -->
                        <div class="social-divider">
                            <span>Or continue with</span>
                        </div>
                        <div class="social-buttons">
                            <button type="button" class="social-btn google-btn" title="Sign in with Google">
                                <svg viewBox="0 0 24 24" width="20" height="20">
                                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Google
                            </button>
                            <button type="button" class="social-btn apple-btn" title="Sign in with Apple">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                    <path d="M17.05 13.5c-.91 2.18-.69 2.6 1.1 4.61 1.84 2.07 2.45 3.15 2.51 3.31.13.2-3.17.46-3.87.3-1.27-.28-2.39-1.47-3.44-3.04-.52-.79-1.04-1.6-1.74-2.57-.32.29-1.39 3.35-2.99 3.99-2.67.96-4.5-.6-4.5-3.66 0-2.45 1.21-5.92 2.88-8.25 1.84-2.62 4.12-4.01 5.28-3.74 1.04.23 1.44.88 2.04 2.57.3.89.55 1.75.73 2.48z"/>
                                </svg>
                                Apple
                            </button>
                        </div> --}}

                        <!-- Register Link -->
                        <p class="auth-switch">
                            Don't have an account? <a href="{{ route('register') }}" class="auth-link">Create one</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(btn) {
            const input = btn.closest('.input-wrapper').querySelector('input[type="password"], input[type="text"]');
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    btn.textContent = '🙈';
                } else {
                    input.type = 'password';
                    btn.textContent = '👁️';
                }
            }
        }

        const loginBtn = document.getElementById('loginBtn');
        const form = loginBtn.closest('form');

        loginBtn.addEventListener('click', function(e) {
            // منع السلوك الافتراضي أولاً
            e.preventDefault();

            if (loginBtn.classList.contains('animating')) {
                return;
            }

            // التحقق من صحة النموذج أولاً
            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // إذا كان النموذج صحيحاً، ابدأ الحركة
            loginBtn.classList.add('animating');

            // بعد انتهاء الحركة، قدم النموذج
            setTimeout(() => {
                form.submit();
            }, 800);
        });
    </script>
</body>
</html>
