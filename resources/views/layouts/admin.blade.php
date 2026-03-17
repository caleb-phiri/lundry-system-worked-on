<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - LaundryApp</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
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
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 99;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0.75rem 2rem;
            border-bottom: 1px solid rgba(226, 232, 240, 0.3);
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .navbar-top.scrolled {
            padding: 0.5rem 2rem;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-md);
        }

        /* Page Title */
        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
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
            border: 1px solid var(--border);
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
            display: flex;
            align-items: center;
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
            font-size: 1rem;
        }

        .dropdown-item:hover i {
            color: var(--primary);
            transform: scale(1.1);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: var(--border);
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            border-right: 1px solid rgba(226, 232, 240, 0.3);
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .sidebar-header span,
        .sidebar.collapsed .user-info {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 1.25rem;
        }

        .sidebar.collapsed .sidebar-header {
            padding: 1rem 0;
            justify-content: center;
        }

        .sidebar-header {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-header i {
            font-size: 2rem;
            color: var(--primary);
            background: linear-gradient(135deg, var(--primary-light), var(--secondary-light));
            padding: 0.5rem;
            border-radius: 12px;
        }

        .sidebar-header span {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Sidebar Toggle */
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
            z-index: 101;
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

        /* Navigation Links */
        .nav {
            padding: 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--gray);
            font-weight: 500;
            border-radius: 0.75rem;
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

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            transition: all 0.3s ease;
            min-height: 100vh;
            margin-top: 70px;
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .bg-primary-light { background: var(--primary-light); color: var(--primary); }
        .bg-success-light { background: var(--success-light); color: var(--success); }
        .bg-warning-light { background: var(--warning-light); color: var(--warning); }
        .bg-danger-light { background: var(--danger-light); color: var(--danger); }
        .bg-info-light { background: var(--info-light); color: var(--info); }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--gray);
        }

        /* Cards */
        .card {
            border: 1px solid var(--border);
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            transition: all 0.3s;
            background: white;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
        }

        .card-header {
            background: linear-gradient(135deg, var(--light) 0%, white 100%);
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header i {
            color: var(--primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            color: white;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-primary:hover::before {
            width: 200px;
            height: 200px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }

        .btn-outline-primary {
            border: 1px solid var(--primary);
            color: var(--primary);
            background: transparent;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        /* Tables */
        .table {
            color: var(--dark);
            margin-bottom: 0;
        }

        .table thead th {
            border-bottom: 2px solid var(--border);
            font-weight: 600;
            color: var(--gray);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border);
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background: var(--light);
        }

        /* Badges */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 500;
            border-radius: 0.5rem;
            font-size: 0.75rem;
        }

        .badge.bg-primary { background: var(--primary-light) !important; color: var(--primary-dark); }
        .badge.bg-success { background: var(--success-light) !important; color: #065f46; }
        .badge.bg-warning { background: var(--warning-light) !important; color: #92400e; }
        .badge.bg-danger { background: var(--danger-light) !important; color: #991b1b; }
        .badge.bg-info { background: var(--info-light) !important; color: #1e40af; }

        /* Alerts */
        .alert {
            border-radius: 1rem;
            border: none;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-md);
            animation: slideDown 0.5s ease;
            display: flex;
            align-items: center;
            border-left: 4px solid transparent;
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-light), #ffffff);
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-light), #ffffff);
            color: #991b1b;
            border-left-color: var(--danger);
        }

        .alert-info {
            background: linear-gradient(135deg, var(--info-light), #ffffff);
            color: #1e40af;
            border-left-color: var(--info);
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

        /* DataTables Customization */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 0.375rem 0.75rem;
            transition: all 0.2s;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            padding: 0.375rem 0.75rem;
            transition: all 0.2s;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary);
            color: white !important;
            border-color: var(--primary);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary-dark) !important;
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1050;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .navbar-top {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 1rem;
                margin-top: 60px;
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
            .navbar-top {
                padding: 0.5rem 1rem;
            }

            .page-title {
                font-size: 1rem;
            }

            .profile-dropdown .btn span {
                display: none;
            }

            .profile-dropdown .btn i {
                margin-right: 0;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            .stat-label {
                font-size: 0.75rem;
            }

            .card-header {
                padding: 0.75rem 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            .table thead th {
                font-size: 0.625rem;
                padding: 0.75rem;
            }

            .table tbody td {
                padding: 0.75rem;
                font-size: 0.875rem;
            }

            .alert {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 0.75rem;
            }

            .stat-card {
                padding: 0.75rem;
            }

            .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .stat-value {
                font-size: 1rem;
            }

            .btn {
                padding: 0.4rem 0.75rem;
                font-size: 0.875rem;
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
            animation: fadeOut 0.5s ease 1s forwards;
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
            to { 
                opacity: 0; 
                visibility: hidden; 
            }
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

            .card {
                box-shadow: none;
                border: 1px solid #ddd;
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
    <nav class="navbar-top" id="navbarTop">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle d-none btn btn-link" onclick="toggleMobileMenu()" id="mobileMenuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <h5 class="page-title mb-0">
                    <i class="fas fa-crown text-primary me-2"></i>
                    @yield('page-title', 'Dashboard')
                </h5>
            </div>
            
            <div class="dropdown profile-dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-2"></i>
                    <span>{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
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
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <!-- Sidebar Toggle (Desktop) -->
        <div class="sidebar-toggle" onclick="toggleSidebar()" id="sidebarToggle">
            <i class="fas fa-chevron-left"></i>
        </div>

        <!-- Sidebar Header -->
        <div class="sidebar-header">
            <i class="fas fa-crown"></i>
            <span>Admin Panel</span>
        </div>

        <!-- Navigation Links -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="badge bg-primary ms-auto">Now</span>
                    @endif
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" 
                   href="{{ route('admin.orders.index') }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}" 
                   href="{{ route('admin.drivers.index') }}">
                    <i class="fas fa-truck"></i>
                    <span>Drivers</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}" 
                   href="{{ route('admin.clients.index') }}">
                    <i class="fas fa-users"></i>
                    <span>Clients</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                   href="{{ route('admin.reports.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" 
                   href="{{ route('admin.settings.index') }}">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
            
            <li class="nav-item mt-4">
                <a class="nav-link text-muted" href="{{ route('client.dashboard') }}">
                    <i class="fas fa-store"></i>
                    <span>Client View</span>
                </a>
            </li>
        </ul>
    </nav>

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

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert" data-aos="fade-down">
                <i class="fas fa-info-circle"></i>
                <div class="flex-grow-1">{{ session('info') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Hide loader after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loader').style.display = 'none';
            }, 500);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbarTop');
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
                localStorage.setItem('adminSidebarCollapsed', 'true');
            } else {
                toggleIcon.classList.remove('fa-chevron-right');
                toggleIcon.classList.add('fa-chevron-left');
                localStorage.setItem('adminSidebarCollapsed', 'false');
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

        // Check screen size for responsive behavior
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
                const collapsed = localStorage.getItem('adminSidebarCollapsed') === 'true';
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

        // Initialize DataTables
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('.data-table').DataTable({
                    responsive: true,
                    pageLength: 25,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search..."
                    }
                });
            }
        });

        // Show toast notification function
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            toast.style.cssText = 'bottom: 20px; right: 20px; z-index: 9999; max-width: 350px;';
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : (type === 'danger' ? 'exclamation-circle' : 'info-circle')} me-2"></i>
                <span>${message}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        };

        // Handle offline/online status
        window.addEventListener('offline', function() {
            showToast('You are offline. Some features may be unavailable.', 'warning');
        });

        window.addEventListener('online', function() {
            showToast('You are back online!', 'success');
        });
    </script>
    
    @stack('scripts')
</body>
</html>