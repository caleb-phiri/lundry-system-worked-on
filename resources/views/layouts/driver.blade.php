<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Driver Panel') - Laundry App</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --primary-soft: #ecfdf5;
            --secondary: #3b82f6;
            --secondary-light: #dbeafe;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fed7aa;
            --danger: #ef4444;
            --danger-light: #fee2e2;
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
            background: linear-gradient(135deg, #f1f5f9 0%, #f8fafc 100%);
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

        /* Top Navbar */
        .navbar-top {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0.75rem 2rem;
            box-shadow: var(--shadow-sm);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.3);
            transition: all 0.3s ease;
        }

        .navbar-top.scrolled {
            padding: 0.5rem 2rem;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .online-badge {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
            color: white;
            padding: 0.35rem 1rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .online-badge i {
            font-size: 0.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Profile Dropdown */
        .profile-dropdown .btn {
            background: var(--light);
            border: 1px solid var(--border);
            border-radius: 2rem;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            color: var(--dark);
            transition: all 0.3s;
        }

        .profile-dropdown .btn:hover {
            background: white;
            border-color: var(--primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .profile-dropdown .btn i {
            color: var(--primary);
        }

        .dropdown-menu {
            border: none;
            border-radius: 1rem;
            box-shadow: var(--shadow-xl);
            padding: 0.5rem;
            min-width: 220px;
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

        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            color: var(--gray);
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: var(--light);
            color: var(--primary);
            transform: translateX(5px);
        }

        .dropdown-item i {
            width: 1.5rem;
            color: var(--gray);
            transition: all 0.2s;
        }

        .dropdown-item:hover i {
            color: var(--primary);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--border);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 73px;
            bottom: 0;
            left: 0;
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 1.5rem 0;
            overflow-y: auto;
            z-index: 999;
            border-right: 1px solid rgba(226, 232, 240, 0.3);
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .user-role {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 1rem;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 1.25rem;
        }

        .sidebar.collapsed .user-avatar {
            width: 50px;
            height: 50px;
        }

        .sidebar-toggle {
            position: absolute;
            right: -12px;
            top: 20px;
            width: 24px;
            height: 24px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
        }

        .sidebar-toggle:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: scale(1.1);
        }

        .sidebar-toggle i {
            font-size: 0.75rem;
            transition: transform 0.3s;
        }

        .sidebar.collapsed .sidebar-toggle i {
            transform: rotate(180deg);
        }

        /* User Profile in Sidebar */
        .user-profile {
            padding: 1.5rem 1rem;
            text-align: center;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 3px solid white;
            box-shadow: var(--shadow-md);
            transition: all 0.3s;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: var(--shadow-lg);
        }

        .user-avatar i {
            font-size: 2.5rem;
            color: var(--primary);
        }

        .user-info h6 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .user-role {
            display: inline-block;
            padding: 0.25rem 1rem;
            background: var(--primary-soft);
            color: var(--primary-dark);
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Navigation Links */
        .nav-links {
            padding: 0 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: var(--gray);
            font-weight: 500;
            border-radius: 1rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, var(--primary-light), transparent);
            transition: width 0.3s;
            z-index: -1;
        }

        .nav-link:hover::before {
            width: 100%;
        }

        .nav-link:hover {
            color: var(--primary);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, var(--primary-light) 0%, transparent 100%);
            color: var(--primary-dark);
            font-weight: 600;
            border-left: 3px solid var(--primary);
        }

        .nav-link i {
            width: 1.75rem;
            font-size: 1.1rem;
            transition: all 0.3s;
        }

        .nav-link span {
            transition: all 0.3s;
        }

        .nav-link .badge {
            margin-left: auto;
            font-size: 0.625rem;
            padding: 0.25rem 0.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            margin-top: 73px;
            padding: 2rem;
            transition: all 0.3s ease;
            min-height: calc(100vh - 73px);
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Alert Messages */
        .alert {
            border: none;
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            animation: slideDown 0.5s ease;
            display: flex;
            align-items: center;
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-light), #ffffff);
            color: var(--success-dark);
            border-left: 4px solid var(--success);
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-light), #ffffff);
            color: var(--danger-dark);
            border-left: 4px solid var(--danger);
        }

        .alert i {
            font-size: 1.25rem;
            margin-right: 1rem;
        }

        .btn-close {
            filter: none;
            opacity: 0.5;
            transition: all 0.2s;
        }

        .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .sidebar {
                width: 240px;
            }
            .main-content {
                margin-left: 240px;
            }
        }

        @media (max-width: 992px) {
            .navbar-top {
                padding: 0.75rem 1rem;
            }

            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1050;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
                padding: 1rem;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
                animation: fadeIn 0.3s ease;
            }

            .mobile-overlay.show {
                display: block;
            }

            .mobile-menu-toggle {
                display: block !important;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: var(--dark);
                margin-right: 1rem;
            }

            .sidebar-toggle {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .navbar-top .online-badge span {
                display: none;
            }

            .profile-dropdown .btn span {
                display: none;
            }

            .profile-dropdown .btn i {
                margin-right: 0;
            }

            .user-avatar {
                width: 60px;
                height: 60px;
            }

            .user-avatar i {
                font-size: 2rem;
            }

            .alert {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand span {
                display: none;
            }

            .main-content {
                padding: 0.75rem;
            }

            .profile-dropdown .btn {
                padding: 0.5rem 0.75rem;
            }
        }

        /* Loading Animation */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeOut 0.5s ease 2s forwards;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fadeOut {
            to { opacity: 0; visibility: hidden; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Print Styles */
        @media print {
            .sidebar,
            .navbar-top,
            .alert,
            .btn {
                display: none !important;
            }

            .main-content {
                margin: 0;
                padding: 0;
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
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Loading Animation -->
    <div class="loader" id="loader">
        <div class="loader-spinner"></div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()"></div>

    <!-- Top Navbar -->
    <nav class="navbar-top" id="navbar">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle d-none" onclick="toggleMobileMenu()" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <h5 class="mb-0 navbar-brand">
                    <i class="fas fa-truck text-primary me-2"></i>
                    <span>Driver Panel</span>
                </h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Online Status -->
                <div class="online-badge">
                    <i class="fas fa-circle"></i>
                    <span>Online</span>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown profile-dropdown">
                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('driver.profile') }}">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('driver.earnings') }}">
                                <i class="fas fa-wallet me-2"></i>Earnings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('driver.schedule') }}">
                                <i class="fas fa-calendar me-2"></i>Schedule
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Sidebar Toggle (Desktop) -->
        <div class="sidebar-toggle" onclick="toggleSidebar()" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </div>

        <!-- User Profile -->
        <div class="user-profile">
            <div class="user-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-info">
                <h6>{{ auth()->user()->name }}</h6>
                <span class="user-role">Driver</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <div class="nav-links">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}" 
                       href="{{ route('driver.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                        @if(request()->routeIs('driver.dashboard'))
                            <span class="badge bg-primary ms-auto">Now</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.orders.*') ? 'active' : '' }}" 
                       href="{{ route('driver.orders.index') }}">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.earnings') ? 'active' : '' }}" 
                       href="{{ route('driver.earnings') }}">
                        <i class="fas fa-wallet"></i>
                        <span>Earnings</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.schedule') ? 'active' : '' }}" 
                       href="{{ route('driver.schedule') }}">
                        <i class="fas fa-calendar"></i>
                        <span>Schedule</span>
                    </a>
                </li>
                
                <li class="nav-item mt-4">
                    <a class="nav-link text-muted" href="{{ route('client.dashboard') }}">
                        <i class="fas fa-store"></i>
                        <span>Switch to Client</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="fas fa-check-circle"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="fas fa-exclamation-circle"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
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

        // Sidebar toggle (Desktop)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleIcon = document.querySelector('#sidebarToggle i');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const toggleBtn = document.getElementById('mobileMenuToggle');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show');
            
            if (sidebar.classList.contains('mobile-open')) {
                document.body.style.overflow = 'hidden';
                toggleBtn.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                document.body.style.overflow = '';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        }

        // Check screen size for mobile menu
        function checkScreenSize() {
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (window.innerWidth <= 992) {
                mobileToggle.classList.remove('d-none');
                sidebar.classList.remove('collapsed');
                document.getElementById('mainContent').classList.remove('expanded');
            } else {
                mobileToggle.classList.add('d-none');
                sidebar.classList.remove('mobile-open');
                document.getElementById('mobileOverlay').classList.remove('show');
                document.body.style.overflow = '';
                
                // Restore desktop sidebar state
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    sidebar.classList.add('collapsed');
                    document.getElementById('mainContent').classList.add('expanded');
                    document.querySelector('#sidebarToggle i').classList.remove('fa-chevron-left');
                    document.querySelector('#sidebarToggle i').classList.add('fa-chevron-right');
                }
            }
        }

        // Initial check
        checkScreenSize();

        // Check on resize
        window.addEventListener('resize', function() {
            checkScreenSize();
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    toggleMobileMenu();
                }
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + S to toggle sidebar
            if (e.altKey && e.key === 's') {
                e.preventDefault();
                if (window.innerWidth > 992) {
                    toggleSidebar();
                }
            }
            
            // Esc to close mobile menu
            if (e.key === 'Escape' && window.innerWidth <= 992) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('mobile-open')) {
                    toggleMobileMenu();
                }
            }
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
                }
            });
        });

        // Add touch support for mobile
        let touchstartX = 0;
        let touchendX = 0;

        document.addEventListener('touchstart', e => {
            touchstartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', e => {
            touchendX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            // Swipe right to open menu
            if (touchendX < touchstartX && Math.abs(touchendX - touchstartX) > 50) {
                if (!sidebar.classList.contains('mobile-open') && window.innerWidth <= 992) {
                    toggleMobileMenu();
                }
            }
            
            // Swipe left to close menu
            if (touchendX > touchstartX && Math.abs(touchendX - touchstartX) > 50) {
                if (sidebar.classList.contains('mobile-open')) {
                    toggleMobileMenu();
                }
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>