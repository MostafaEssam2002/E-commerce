<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Registration</title>
    {{-- <link rel="stylesheet" href="{{ asset('css/main.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
</head>

<body>
    <div class="register-form">
        <h2 class="form-title">Create a New Account</h2>

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label>Account Type</label>
                <div class="radio-group">
                    <div class="radio-option">
                        <input type="radio" id="user" name="role" value="user" checked>
                        <label for="user" class="radio-label">
                            <span class="radio-icon">👤</span>
                            Regular User
                        </label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="seller" name="role" value="seller">
                        <label for="seller" class="radio-label">
                            <span class="radio-icon">🛒</span>
                            Seller
                        </label>
                    </div>
                    <div class="radio-option">
                        <input type="radio" id="admin" name="role" value="admin">
                        <label for="admin" class="radio-label">
                            <span class="radio-icon">🧑‍💼</span>
                                Admin
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Profile Picture (Optional)</label>
                <div class="file-input-wrapper">
                    <input type="file" id="avatar" name="avatar" class="file-input" accept="image/*"
                        onchange="previewImage(this)">
                    <label for="avatar" class="file-label">
                        Choose a profile picture or drag it here
                    </label>
                </div>
                <div class="preview-container" id="preview-container" style="display: none;">
                    <img id="avatar-preview" class="avatar-preview" src="" alt="Image Preview">
                </div>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>
    </div>

<script src="{{ asset('assets/js/register.js') }}"></script>
</body>
</html>
