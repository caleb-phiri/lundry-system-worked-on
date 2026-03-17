<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client Dashboard') - LaundryApp</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    @stack('styles')
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #4361ee;
            --primary-dark: #3730a3;
            --primary-light: #e0e7ff;
            --primary-soft: #eef2ff;
            --secondary: #0ea5e9;
            --secondary-light: #e0f2fe;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fed7aa;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --dark: #1e293b;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --light: #f8fafc;
            --border: #e2e8f0;
            --white: #ffffff;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray-light);
            border-radius: 4px;
            transition: all 0.3s;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray);
        }

        /* Loading Animation */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeOut 0.5s ease 1s forwards;
        }

        .loader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loader-spinner::after {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border: 4px solid transparent;
            border-top-color: var(--secondary);
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fadeOut {
            to { 
                opacity: 0; 
                visibility: hidden; 
            }
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: var(--shadow-sm);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.3);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.75rem 0;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-lg);
        }

        .navbar-brand {
            font-size: 1.75rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-brand i {
            background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            filter: drop-shadow(0 4px 6px rgba(67, 97, 238, 0.2));
        }

        .navbar-toggler {
            border: 1px solid var(--border);
            padding: 0.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s;
            background: white;
        }

        .navbar-toggler:hover {
            background: var(--light);
            transform: scale(1.05);
            border-color: var(--primary);
        }

        .navbar-toggler:focus {
            box-shadow: none;
            border-color: var(--primary);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(67, 97, 238, 0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Navigation Links */
        .nav-link {
            font-weight: 600;
            color: var(--gray);
            padding: 0.75rem 1.5rem !important;
            border-radius: 2rem;
            margin: 0 0.25rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            font-size: 0.95rem;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(67, 97, 238, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: -1;
        }

        .nav-link:hover::before {
            width: 200px;
            height: 200px;
        }

        .nav-link:hover {
            color: var(--primary);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white !important;
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
        }

        .nav-link.active::before {
            display: none;
        }

        .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .nav-link:hover i {
            transform: scale(1.2) rotate(5deg);
        }

        /* Wallet Badge */
        .wallet-badge {
            background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
            color: var(--primary-dark);
            padding: 0.5rem 1.25rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            border: 2px solid rgba(67, 97, 238, 0.2);
            transition: all 0.3s;
            box-shadow: var(--shadow-sm);
        }

        .wallet-badge:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
            border-color: transparent;
        }

        .wallet-badge i {
            font-size: 1rem;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 1.25rem;
            box-shadow: var(--shadow-xl);
            padding: 0.75rem;
            min-width: 240px;
            animation: slideDown 0.3s ease;
            border: 1px solid var(--border);
            margin-top: 0.5rem;
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

        .dropdown-item {
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-weight: 500;
            color: var(--gray);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .dropdown-item:hover {
            background: var(--light);
            color: var(--primary);
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 1.75rem;
            color: var(--gray);
            transition: all 0.2s;
            font-size: 1.1rem;
        }

        .dropdown-item:hover i {
            color: var(--primary);
            transform: scale(1.2);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--border);
        }

        /* Main Content */
        .content-wrapper {
            min-height: calc(100vh - 80px);
            padding: 2rem 0;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Alert Messages */
        .alert {
            border: none;
            border-radius: 1.25rem;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-lg);
            animation: slideDown 0.5s ease;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(255, 255, 255, 0.5));
            color: #065f46;
            border-left-color: var(--success);
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(255, 255, 255, 0.5));
            color: #991b1b;
            border-left-color: var(--danger);
            backdrop-filter: blur(10px);
        }

        .alert i {
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .btn-close {
            filter: none;
            opacity: 0.5;
            transition: all 0.2s;
        }

        .btn-close:hover {
            opacity: 1;
            transform: scale(1.2);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .navbar {
                padding: 0.5rem 1rem;
            }

            .navbar-brand {
                font-size: 1.5rem;
            }

            .navbar-brand i {
                font-size: 1.75rem;
            }

            .navbar-collapse {
                background: white;
                border-radius: 1.25rem;
                padding: 1.25rem;
                margin-top: 1rem;
                box-shadow: var(--shadow-xl);
                border: 1px solid var(--border);
            }

            .nav-link {
                padding: 0.75rem 1rem !important;
                margin: 0.25rem 0;
                border-radius: 0.75rem;
                font-size: 1rem;
            }

            .nav-link.active {
                background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
                color: var(--primary-dark) !important;
            }

            .wallet-badge {
                display: inline-flex;
                margin-left: 0.5rem;
                padding: 0.4rem 1rem;
            }

            .dropdown-menu {
                border: none;
                box-shadow: none;
                padding: 0;
                margin-top: 0.5rem;
                background: var(--light);
            }

            .dropdown-item {
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }

            .navbar-brand i {
                font-size: 1.5rem;
            }

            .content-wrapper {
                padding: 1rem 0;
            }

            .nav-link span {
                font-size: 0.9rem;
            }

            .wallet-badge {
                font-size: 0.8rem;
                padding: 0.3rem 0.9rem;
                gap: 0.5rem;
            }

            .alert {
                padding: 1rem;
                font-size: 0.9rem;
            }

            .alert i {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand span {
                display: none;
            }

            .navbar-brand i {
                margin-right: 0;
                font-size: 1.75rem;
            }

            .nav-link i {
                margin-right: 0.5rem;
            }

            .nav-link {
                padding: 0.6rem 0.8rem !important;
            }

            .wallet-badge span:last-child {
                display: none;
            }

            .wallet-badge i:last-child {
                margin-right: 0;
            }
        }

        /* Print Styles */
        @media print {
            .navbar,
            .alert,
            .btn,
            .dropdown {
                display: none !important;
            }

            .content-wrapper {
                padding: 0;
                margin: 0;
            }
        }

        /* Utility Classes */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hover-lift {
            transition: transform 0.3s;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Pulse Animation */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
            }
        }

        /* Shimmer Effect */
        .shimmer {
            background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255,255,255,0.4) 50%,
                transparent 100%
            );
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Floating Animation */
        .float {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Ripple Effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .ripple:active::after {
            width: 200px;
            height: 200px;
        }
    </style>
</head>
<body>
    <!-- Loading Animation -->
    <div class="loader" id="loader">
        <div class="loader-spinner"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.dashboard') }}">
                <i class="fas fa-tshirt float"></i>
                <span>LaundryApp</span>
            </a>
            
            <button class="navbar-toggler ripple" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link ripple {{ request()->routeIs('client.dashboard') ? 'active' : '' }}" 
                           href="{{ route('client.dashboard') }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link ripple {{ request()->routeIs('client.orders.*') ? 'active' : '' }}" 
                           href="{{ route('client.orders.index') }}">
                            <i class="fas fa-shopping-bag"></i>
                            <span>My Orders</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link ripple {{ request()->routeIs('client.wallet.*') ? 'active' : '' }}" 
                           href="{{ route('client.wallet.index') }}">
                            <i class="fas fa-wallet"></i>
                            <span>Wallet</span>
                            @php
                                $wallet = auth()->user()->wallet;
                            @endphp
                            <span class="wallet-badge ms-2 pulse">
                                <i class="fas fa-coins"></i>
                                <span>K{{ number_format($wallet->balance ?? 0, 2) }}</span>
                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link ripple {{ request()->routeIs('client.locations.*') ? 'active' : '' }}" 
                           href="{{ route('client.locations.index') }}">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Locations</span>
                        </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle ripple" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user"></i>
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-question-circle"></i>
                                    Help
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content-wrapper" style="margin-top: 80px;">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <i class="fas fa-check-circle"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('client-content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            easing: 'ease-in-out'
        });

        // Hide loader after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader').style.display = 'none';
            }, 500);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Auto close mobile menu on link click
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const navbarCollapse = document.getElementById('navbarNav');
                if (navbarCollapse.classList.contains('show')) {
                    new bootstrap.Collapse(navbarCollapse).hide();
                }
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Close mobile menu if open
                    const navbarCollapse = document.getElementById('navbarNav');
                    if (navbarCollapse.classList.contains('show')) {
                        new bootstrap.Collapse(navbarCollapse).hide();
                    }
                }
            });
        });

        // Add active class to current nav item based on URL
        function setActiveNavItem() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href && currentPath.includes(href) && href !== '#') {
                    link.classList.add('active');
                }
            });
        }

        // Call on page load
        setActiveNavItem();

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + D for Dashboard
            if (e.altKey && e.key === 'd') {
                e.preventDefault();
                window.location.href = "{{ route('client.dashboard') }}";
            }
            
            // Alt + O for Orders
            if (e.altKey && e.key === 'o') {
                e.preventDefault();
                window.location.href = "{{ route('client.orders.index') }}";
            }
            
            // Alt + W for Wallet
            if (e.altKey && e.key === 'w') {
                e.preventDefault();
                window.location.href = "{{ route('client.wallet.index') }}";
            }
            
            // Alt + L for Locations
            if (e.altKey && e.key === 'l') {
                e.preventDefault();
                window.location.href = "{{ route('client.locations.index') }}";
            }
        });

        // Enhanced toast notification using SweetAlert2
        window.showToast = function(message, type = 'success') {
            Swal.fire({
                text: message,
                icon: type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        };

        // Handle offline/online status
        window.addEventListener('offline', function() {
            showToast('You are offline. Some features may be unavailable.', 'warning');
        });

        window.addEventListener('online', function() {
            showToast('You are back online!', 'success');
        });

        // Add pull-to-refresh protection (prevent accidental refreshes)
        let touchstartY = 0;
        document.addEventListener('touchstart', function(e) {
            touchstartY = e.changedTouches[0].screenY;
        }, { passive: true });

        document.addEventListener('touchend', function(e) {
            const touchendY = e.changedTouches[0].screenY;
            const diffY = touchendY - touchstartY;
            
            if (diffY > 100 && window.scrollY === 0) {
                showToast('Pull down to refresh', 'info');
            }
        }, { passive: true });

        // Dynamic greeting based on time of day
        const hour = new Date().getHours();
        const greeting = hour < 12 ? 'Good morning' : (hour < 18 ? 'Good afternoon' : 'Good evening');
        console.log(`${greeting}, {{ auth()->user()->name }}!`);

        // Check for updates (simulated)
        setTimeout(() => {
            console.log('Checking for updates...');
        }, 2000);

        // Ripple effect for buttons
        document.querySelectorAll('.ripple').forEach(button => {
            button.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.width = '0';
                ripple.style.height = '0';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.4)';
                ripple.style.transform = 'translate(-50%, -50%)';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.transition = 'width 0.6s, height 0.6s';
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.style.width = '200px';
                    ripple.style.height = '200px';
                }, 10);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Performance optimization
        if ('connection' in navigator) {
            const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
            if (connection) {
                console.log(`Network type: ${connection.effectiveType}`);
                if (connection.saveData) {
                    showToast('Data saver mode is enabled', 'info');
                }
            }
        }

        // Memory usage warning (simulated)
        if (performance && performance.memory) {
            const memory = performance.memory;
            if (memory.usedJSHeapSize > memory.jsHeapSizeLimit * 0.8) {
                console.warn('High memory usage detected');
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>