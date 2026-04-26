<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Frutika - Register</title>
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
            background: radial-gradient(circle, #2ecc71 0%, #27ae60 100%);
            top: -100px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .blob-2 {
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, #ff8c42 0%, #ff6b35 100%);
            bottom: -50px;
            left: -100px;
            animation: float 8s ease-in-out infinite 1s;
        }

        .blob-3 {
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, #3498db 0%, #2980b9 100%);
            bottom: 200px;
            right: 5%;
            animation: float 7s ease-in-out infinite 2s;
        }

        .gradient-mesh {
            position: absolute;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(at 20% 50%, rgba(46, 204, 113, 0.1) 0px, transparent 50px),
                radial-gradient(at 80% 80%, rgba(255, 140, 66, 0.1) 0px, transparent 50px);
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
            color: #2ecc71;
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
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Benefits List */
        .benefits-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .benefit-item {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            animation: slideInLeft 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) backwards;
        }

        .benefit-item:nth-child(1) { animation-delay: 0.1s; }
        .benefit-item:nth-child(2) { animation-delay: 0.2s; }
        .benefit-item:nth-child(3) { animation-delay: 0.3s; }

        .benefit-icon {
            font-size: 32px;
            flex-shrink: 0;
        }

        .benefit-content h4 {
            color: white;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .benefit-content p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            line-height: 1.4;
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
            padding: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            max-height: 85vh;
            overflow-y: auto;
        }

        .form-container::-webkit-scrollbar {
            width: 6px;
        }

        .form-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }

        .form-container::-webkit-scrollbar-thumb {
            background: rgba(46, 204, 113, 0.4);
            border-radius: 3px;
        }

        .form-container::-webkit-scrollbar-thumb:hover {
            background: rgba(46, 204, 113, 0.6);
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
            margin-bottom: 28px;
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

        /* Account Type Selector */
        .account-type-section {
            margin-bottom: 28px;
        }

        .account-type-label {
            display: block;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .account-type-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .account-type-option {
            position: relative;
        }

        .account-type-option input[type="radio"] {
            display: none;
        }

        .account-type-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }

        .account-type-option input[type="radio"]:checked + label {
            background: rgba(46, 204, 113, 0.2);
            border-color: rgba(46, 204, 113, 0.5);
            color: #2ecc71;
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.2);
        }

        .account-type-option label:hover {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.12);
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
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
            border-color: rgba(46, 204, 113, 0.5);
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
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
            top: 2px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.5);
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .label-icon {
            font-size: 16px;
        }

        .form-input:focus ~ .floating-label,
        .form-input:not(:placeholder-shown) ~ .floating-label {
            transform: translateY(-28px) scale(0.85);
            color: #2ecc71;
        }

        .input-focus-border {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            background: linear-gradient(90deg, #2ecc71, #27ae60);
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
            margin-top: 6px;
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

        /* Checkbox Terms */
        .terms-checkbox {
            margin-bottom: 24px;
        }

        .checkbox-group {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-width: 18px;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #2ecc71;
        }

        .checkbox-group label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            line-height: 1.5;
            cursor: pointer;
        }

        .checkbox-group label a {
            color: #2ecc71;
            text-decoration: none;
            transition: color 0.3s;
        }

        .checkbox-group label a:hover {
            color: #27ae60;
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
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
        }

        .btn-glow {
            box-shadow: 0 0 20px rgba(46, 204, 113, 0.3);
        }

        .btn-glow:hover {
            box-shadow: 0 0 40px rgba(46, 204, 113, 0.5);
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
            margin: 24px 0;
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
            margin-bottom: 24px;
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
            border-color: rgba(46, 204, 113, 0.5);
            background: rgba(46, 204, 113, 0.1);
        }

        /* Auth Switch */
        .auth-switch {
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .auth-link {
            color: #2ecc71;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .auth-link:hover {
            color: #27ae60;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .auth-wrapper {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .auth-hero {
                display: none;
            }

            .form-container {
                padding: 32px 24px;
                max-height: none;
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

            .account-type-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="frutika-auth-container register-page">
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
                        <div class="welcome-badge">Join us</div>
                        <h1 class="hero-title">Create your account<br>with <span class="brand-name">Frutika</span> </h1>
                        <p class="hero-subtitle">Join our fresh fruit community and enjoy exclusive benefits</p>

                        <div class="benefits-list">
                            <div class="benefit-item">
                                <div class="benefit-icon">✨</div>
                                <div class="benefit-content">
                                    <h4>Special Discounts</h4>
                                    <p>Get exclusive offers and discounts on fresh products</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">🚚</div>
                                <div class="benefit-content">
                                    <h4>Fast Delivery</h4>
                                    <p>Same-day delivery for orders placed before 12 PM</p>
                                </div>
                            </div>
                            <div class="benefit-item">
                                <div class="benefit-icon">💚</div>
                                <div class="benefit-content">
                                    <h4>Fresh Quality</h4>
                                    <p>100% fresh and organic fruits delivered to your door</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Register Form -->
                <div class="auth-form-section">
                    <div class="form-container">
                        <h2 class="form-title">Register</h2>
                        <p class="form-subtitle">Create your account to get started</p>

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

                        <form method="POST" action="{{ route('register') }}" class="auth-form">
                            @csrf

                            <!-- Account Type Selector -->
                            <div class="account-type-section">
                                <label class="account-type-label">Account Type</label>
                                <div class="account-type-options">
                                    <div class="account-type-option">
                                        <input type="radio" id="customer" name="role" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }} required>
                                        <label for="customer">👤 Customer</label>
                                    </div>
                                    <div class="account-type-option">
                                        <input type="radio" id="seller" name="role" value="seller" {{ old('role') === 'seller' ? 'checked' : '' }}>
                                        <label for="seller">🛒 Seller</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Full Name Field -->
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <input
                                        id="name"
                                        type="text"
                                        class="form-input @error('name') input-error @enderror"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autocomplete="name"
                                        placeholder=" ">
                                    <label for="name" class="floating-label">
                                        <span class="label-icon">👤</span>
                                        Full Name
                                    </label>
                                    <div class="input-focus-border"></div>
                                </div>
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

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
                                        autocomplete="new-password"
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

                            <!-- Confirm Password Field -->
                            <div class="form-group">
                                <div class="input-wrapper">
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        class="form-input"
                                        name="password_confirmation"
                                        required
                                        autocomplete="new-password"
                                        placeholder=" ">
                                    <label for="password_confirmation" class="floating-label">
                                        <span class="label-icon">🔐</span>
                                        Confirm Password
                                    </label>
                                    <button type="button" class="toggle-password" onclick="togglePassword(this)">👁️</button>
                                    <div class="input-focus-border"></div>
                                </div>
                            </div>

                            <!-- Terms & Conditions -->
                            <div class="terms-checkbox">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="terms" name="terms" required>
                                    <label for="terms">
                                        I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Register Button -->
                            <button type="submit" class="btn btn-primary btn-glow" id="registerBtn">
                                <span class="btn-text">Create Account</span>
                                <span class="btn-icon">→</span>
                            </button>
                        </form>

                        {{-- <!-- Social Login -->
                        <div class="social-divider">
                            <span>Or sign up with</span>
                        </div>
                        <div class="social-buttons">
                            <button type="button" class="social-btn google-btn" title="Sign up with Google">
                                <svg viewBox="0 0 24 24" width="20" height="20">
                                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                Google
                            </button>
                            <button type="button" class="social-btn apple-btn" title="Sign up with Apple">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
                                    <path d="M17.05 13.5c-.91 2.18-.69 2.6 1.1 4.61 1.84 2.07 2.45 3.15 2.51 3.31.13.2-3.17.46-3.87.3-1.27-.28-2.39-1.47-3.44-3.04-.52-.79-1.04-1.6-1.74-2.57-.32.29-1.39 3.35-2.99 3.99-2.67.96-4.5-.6-4.5-3.66 0-2.45 1.21-5.92 2.88-8.25 1.84-2.62 4.12-4.01 5.28-3.74 1.04.23 1.44.88 2.04 2.57.3.89.55 1.75.73 2.48z"/>
                                </svg>
                                Apple
                            </button>
                        </div> --}}

                        <!-- Login Link -->
                        <p class="auth-switch">
                            Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(btn) {
            const input = btn.closest('.input-wrapper').querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = '🙈';
            } else {
                input.type = 'password';
                btn.textContent = '👁️';
            }
        }

        const registerBtn = document.getElementById('registerBtn');
        const form = registerBtn.closest('form');

        registerBtn.addEventListener('click', function(e) {
            // منع السلوك الافتراضي أولاً
            e.preventDefault();

            if (registerBtn.classList.contains('animating')) {
                return;
            }

            // التحقق من صحة النموذج أولاً
            if (form.checkValidity() === false) {
                e.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // إذا كان النموذج صحيحاً، ابدأ الحركة
            registerBtn.classList.add('animating');

            // بعد انتهاء الحركة، قدم النموذج
            setTimeout(() => {
                form.submit();
            }, 800);
        });
    </script>
</body>
</html>
