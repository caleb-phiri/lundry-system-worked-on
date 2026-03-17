@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary: #1a73e8;
        --primary-dark: #1558b0;
        --primary-light: #e8f0fe;
        --secondary: #6c5ce7;
        --secondary-light: #e9e4ff;
        --success: #00b894;
        --danger: #d63031;
        --dark: #2d3436;
        --gray: #636e72;
        --gray-light: #b2bec3;
        --light: #f5f6fa;
        --white: #ffffff;
        --border: #dfe6e9;
        --shadow-sm: 0 4px 6px rgba(0,0,0,0.05);
        --shadow-md: 0 10px 15px -3px rgba(0,0,0,0.1);
        --shadow-lg: 0 20px 25px -5px rgba(0,0,0,0.1);
        --shadow-xl: 0 25px 50px -12px rgba(0,0,0,0.25);
    }

    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: 'Inter', sans-serif;
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Animated Background */
    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        animation: moveBackground 60s linear infinite;
        opacity: 0.5;
        pointer-events: none;
    }

    @keyframes moveBackground {
        from { transform: translate(0, 0); }
        to { transform: translate(100px, 100px); }
    }

    /* Floating Bubbles */
    .bubble {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        backdrop-filter: blur(5px);
        animation: float 20s infinite;
        border: 1px solid rgba(255, 255, 255, 0.2);
        pointer-events: none;
        z-index: 1;
    }

    .bubble1 {
        width: 150px;
        height: 150px;
        top: 10%;
        left: 5%;
        animation-delay: 0s;
    }

    .bubble2 {
        width: 100px;
        height: 100px;
        bottom: 15%;
        right: 8%;
        animation-delay: 5s;
    }

    .bubble3 {
        width: 70px;
        height: 70px;
        top: 20%;
        right: 15%;
        animation-delay: 2s;
    }

    .bubble4 {
        width: 50px;
        height: 50px;
        bottom: 30%;
        left: 10%;
        animation-delay: 8s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(10deg); }
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        position: relative;
        z-index: 10;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        padding: 40px 35px;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: var(--shadow-xl);
        position: relative;
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.3);
        animation: slideUp 0.6s ease;
        transition: all 0.3s;
    }

    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0,0,0,0.3);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Logo Section */
    .logo-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
        background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        animation: pulse 2s infinite;
        box-shadow: var(--shadow-md);
    }

    .logo-wrapper i {
        font-size: 40px;
        color: var(--primary);
        filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
    }

    .logo-wrapper::after {
        content: '';
        position: absolute;
        top: -5px;
        left: -5px;
        right: -5px;
        bottom: -5px;
        border: 2px dashed var(--primary);
        border-radius: 50%;
        animation: spin 10s linear infinite;
        opacity: 0.5;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(26, 115, 232, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(26, 115, 232, 0); }
        100% { box-shadow: 0 0 0 0 rgba(26, 115, 232, 0); }
    }

    .app-title {
        text-align: center;
        margin-bottom: 5px;
        font-weight: 700;
        font-size: 24px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .welcome {
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }

    .subtitle {
        text-align: center;
        font-size: 13px;
        margin-bottom: 30px;
        color: var(--gray);
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray);
        z-index: 1;
        font-size: 16px;
    }

    .form-control {
        border: 2px solid var(--border) !important;
        border-radius: 30px !important;
        padding: 14px 20px 14px 45px !important;
        font-size: 14px;
        transition: all 0.3s;
        background: var(--white);
        width: 100%;
        box-shadow: none !important;
    }

    .form-control:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px var(--primary-light) !important;
        outline: none;
    }

    .form-control::placeholder {
        color: var(--gray-light);
        font-weight: 300;
    }

    .form-control.is-invalid {
        border-color: var(--danger) !important;
    }

    .invalid-feedback {
        color: var(--danger);
        font-size: 12px;
        margin-top: 5px;
        margin-left: 15px;
    }

    /* Options Row */
    .options-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 25px 0 30px;
    }

    /* Custom Checkbox */
    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .checkbox-wrapper span {
        font-size: 13px;
        color: var(--gray);
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 42px;
        height: 22px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        border-radius: 34px;
        transition: 0.3s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 2px;
        bottom: 2px;
        background: white;
        border-radius: 50%;
        transition: 0.3s;
        box-shadow: var(--shadow-sm);
    }

    input:checked + .slider {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    }

    input:checked + .slider:before {
        transform: translateX(20px);
    }

    /* Forgot Password */
    .forgot-link {
        font-size: 13px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
    }

    .forgot-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* Login Button */
    .login-btn {
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 30px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(26, 115, 232, 0.3);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .login-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .login-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .login-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(26, 115, 232, 0.4);
    }

    .login-btn:active {
        transform: translateY(0);
    }

    /* Divider */
    .divider {
        text-align: center;
        margin: 25px 0 20px;
        font-size: 13px;
        color: var(--gray);
        position: relative;
    }

    .divider::before,
    .divider::after {
        content: "";
        position: absolute;
        top: 50%;
        width: calc(50% - 80px);
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--border), transparent);
    }

    .divider::before { left: 0; }
    .divider::after { right: 0; }

    .divider span {
        background: white;
        padding: 0 15px;
    }

    /* Social Login */
    .social-login {
        display: flex;
        justify-content: center;
        gap: 18px;
        margin-bottom: 20px;
    }

    .social-btn {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: none;
        background: white;
        font-size: 18px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: var(--shadow-md);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .social-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.1);
        transform: translateX(-100%);
        transition: transform 0.3s;
    }

    .social-btn:hover::before {
        transform: translateX(0);
    }

    .social-btn.google { color: #DB4437; }
    .social-btn.facebook { color: #4267B2; }

    .social-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: var(--shadow-lg);
    }

    /* Signup */
    .signup {
        text-align: center;
        font-size: 13px;
        color: var(--gray);
    }

    .signup a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
        transition: all 0.3s;
        position: relative;
    }

    .signup a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.3s;
    }

    .signup a:hover::after {
        width: 100%;
    }

    /* Demo Section */
    .demo-section {
        margin-top: 25px;
        padding: 20px 15px;
        background: linear-gradient(135deg, var(--light), white);
        border-radius: 20px;
        border: 1px solid var(--border);
    }

    .demo-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-size: 14px;
    }

    .demo-title i {
        color: var(--warning);
    }

    .demo-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .demo-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        color: white;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: var(--shadow-sm);
    }

    .demo-btn.admin {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    }

    .demo-btn.driver {
        background: linear-gradient(135deg, var(--success) 0%, #00a884 100%);
    }

    .demo-btn.client {
        background: linear-gradient(135deg, var(--secondary) 0%, #5b4bc4 100%);
    }

    .demo-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .demo-note {
        font-size: 11px;
        color: var(--gray);
        margin-top: 10px;
        text-align: center;
    }

    .demo-note code {
        background: var(--light);
        padding: 2px 6px;
        border-radius: 4px;
        color: var(--primary);
    }

    /* Responsive */
    @media (max-width: 480px) {
        .login-card {
            padding: 30px 20px;
        }

        .logo-wrapper {
            width: 60px;
            height: 60px;
        }

        .logo-wrapper i {
            font-size: 30px;
        }

        .welcome {
            font-size: 22px;
        }

        .options-row {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .divider::before,
        .divider::after {
            width: calc(50% - 60px);
        }

        .demo-buttons {
            flex-direction: column;
        }

        .demo-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Loading State */
    .login-btn.loading {
        pointer-events: none;
        opacity: 0.8;
    }

    .login-btn.loading .btn-text {
        opacity: 0;
    }

    .login-btn.loading .btn-loader {
        display: inline-block;
    }

    .btn-loader {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        position: absolute;
    }

    /* Success Animation */
    .success-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 10px;
        padding: 12px 20px;
        box-shadow: var(--shadow-lg);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 1000;
        animation: slideInRight 0.3s ease;
        border-left: 4px solid var(--success);
    }

    .success-toast i {
        color: var(--success);
        font-size: 18px;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>

<!-- Floating Bubbles -->
<div class="bubble bubble1"></div>
<div class="bubble bubble2"></div>
<div class="bubble bubble3"></div>
<div class="bubble bubble4"></div>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Logo -->
        <div class="logo-wrapper">
            <i class="fas fa-tshirt"></i>
        </div>

        <h5 class="app-title">Laundry Buddy</h5>
        <div class="welcome">Welcome Back! 👋</div>
        <div class="subtitle">Sign in to manage your laundry services</div>

        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="text"
                       id="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Phone Number or Email"
                       class="form-control @error('email') is-invalid @enderror"
                       required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password"
                       id="password"
                       name="password"
                       placeholder="Password"
                       class="form-control @error('password') is-invalid @enderror"
                       required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="options-row">
                <label class="checkbox-wrapper">
                    <span>Remember Me</span>
                    <label class="switch">
                        <input type="checkbox" name="remember">
                        <span class="slider"></span>
                    </label>
                </label>

                <a href="#" class="forgot-link">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <span class="btn-text">Sign In</span>
                <span class="btn-loader"></span>
            </button>
        </form>

        <div class="divider">
            <span>Or log in with</span>
        </div>

        <div class="social-login">
            <button class="social-btn google" onclick="socialLogin('google')">
                <i class="fab fa-google"></i>
            </button>
            <button class="social-btn facebook" onclick="socialLogin('facebook')">
                <i class="fab fa-facebook-f"></i>
            </button>
        </div>

        <div class="signup">
            Don't have an account?
            <a href="{{ route('register') }}">Create Account</a>
        </div>

        <!-- Demo Login Section -->
        <div class="demo-section">
            <div class="demo-title">
                <i class="fas fa-flask"></i>
                <span>Demo Accounts</span>
            </div>
            <div class="demo-buttons">
                <button type="button" class="demo-btn admin" onclick="fillLogin('admin@laundry.com')">
                    <i class="fas fa-crown"></i>
                    Admin
                </button>
                <button type="button" class="demo-btn driver" onclick="fillLogin('driver1@laundry.com')">
                    <i class="fas fa-truck"></i>
                    Driver
                </button>
                <button type="button" class="demo-btn client" onclick="fillLogin('client1@laundry.com')">
                    <i class="fas fa-user"></i>
                    Client
                </button>
            </div>
            <div class="demo-note">
                <i class="fas fa-info-circle me-1"></i>
                Password for all accounts: <code>password</code>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fill login credentials
function fillLogin(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password';
    
    // Highlight fields
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    
    emailField.style.transition = 'all 0.3s';
    passwordField.style.transition = 'all 0.3s';
    
    emailField.style.backgroundColor = '#e8f0fe';
    passwordField.style.backgroundColor = '#e8f0fe';
    
    setTimeout(() => {
        emailField.style.backgroundColor = '';
        passwordField.style.backgroundColor = '';
    }, 500);
    
    // Show toast notification
    showToast('Demo credentials filled!');
}

// Social login
function socialLogin(provider) {
    showToast(`Logging in with ${provider}...`);
}

// Show toast notification
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'success-toast';
    toast.innerHTML = `
        <i class="fas fa-check-circle"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Form submission with loading state
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('loginBtn');
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (!email || !password) {
        e.preventDefault();
        showToast('Please fill in all fields');
        return;
    }
    
    btn.classList.add('loading');
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + 1 for admin, 2 for driver, 3 for client
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case '1':
                e.preventDefault();
                fillLogin('admin@laundry.com');
                break;
            case '2':
                e.preventDefault();
                fillLogin('driver1@laundry.com');
                break;
            case '3':
                e.preventDefault();
                fillLogin('client1@laundry.com');
                break;
        }
    }
});

// Add ripple effect to buttons
document.querySelectorAll('.login-btn, .demo-btn, .social-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        ripple.style.position = 'absolute';
        ripple.style.width = '0';
        ripple.style.height = '0';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255,255,255,0.5)';
        ripple.style.transform = 'translate(-50%, -50%)';
        ripple.style.transition = 'width 0.6s, height 0.6s, opacity 0.6s';
        ripple.style.left = e.clientX - this.offsetLeft + 'px';
        ripple.style.top = e.clientY - this.offsetTop + 'px';
        
        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.style.width = '200px';
            ripple.style.height = '200px';
            ripple.style.opacity = '0';
        }, 10);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});

// Auto-fill first demo account on page load (optional)
window.addEventListener('load', function() {
    // Uncomment to auto-fill client credentials
    // setTimeout(() => {
    //     fillLogin('client1@laundry.com');
    // }, 500);
});
</script>
@endpush
@endsection