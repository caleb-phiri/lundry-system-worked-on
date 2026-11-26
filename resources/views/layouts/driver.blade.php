<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Driver Panel') - Laundry App</title>
    
    <!-- PWA Meta -->
    <meta name="theme-color" content="#10b981">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Driver Panel">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="{{ asset('css/driver-design-system.css') }}">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    
    <!-- Chart.js for earnings charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    @stack('styles')
</head>
<body>
    <!-- Loading Animation -->
    <div class="loader" id="loader" role="status" aria-label="Loading">
        <div class="loader-spinner"></div>
    </div>

    <!-- Mobile Overlay -->
    <div class="mobile-overlay" id="mobileOverlay" onclick="toggleMobileMenu()" aria-hidden="true"></div>

    <!-- Toast Container -->
    <x-driver.toast />

    <!-- Top Navbar -->
    <nav class="navbar-top" id="navbar" role="navigation" aria-label="Main navigation">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle d-none" onclick="toggleMobileMenu()" id="mobileMenuToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                
                <h5 class="mb-0 navbar-brand">
                    <i class="fas fa-truck text-primary me-2"></i>
                    <span>Driver Panel</span>
                </h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Online Status -->
                <div class="online-badge" id="onlineStatus">
                    <i class="fas fa-circle" aria-hidden="true"></i>
                    <span>Online</span>
                </div>

                <!-- Quick Actions (Mobile) -->
                <div class="d-none d-md-flex gap-2">
                    <a href="{{ route('driver.orders.index') }}" class="btn btn-primary btn-sm" aria-label="View orders">
                        <i class="fas fa-shopping-bag"></i>
                        <span class="d-none d-sm-inline">Orders</span>
                    </a>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown profile-dropdown">
                    <button class="btn btn-ghost" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                        <x-driver.avatar :name="auth()->user()->name" :size="'sm'" :status="'online'" />
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" role="menu">
                        <li>
                            <a class="dropdown-item" href="{{ route('driver.profile') }}" role="menuitem">
                                <i class="dropdown-item-icon fas fa-user"></i>
                                Profile
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('driver.earnings') }}" role="menuitem">
                                <i class="dropdown-item-icon fas fa-wallet"></i>
                                Earnings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('driver.schedule') }}" role="menuitem">
                                <i class="dropdown-item-icon fas fa-calendar"></i>
                                Schedule
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" role="menuitem">
                                <i class="dropdown-item-icon fas fa-cog"></i>
                                Settings
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item danger" role="menuitem">
                                    <i class="dropdown-item-icon fas fa-sign-out-alt"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar" role="navigation" aria-label="Sidebar navigation">
        <!-- Sidebar Toggle (Desktop) -->
        <button class="sidebar-toggle" onclick="toggleSidebar()" id="sidebarToggle" aria-label="Collapse sidebar" aria-expanded="true" aria-controls="mainContent">
            <i class="fas fa-chevron-left"></i>
        </button>

        <!-- User Profile -->
        <div class="user-profile">
            <x-driver.avatar :name="auth()->user()->name" :size="'2xl'" :status="'online'" />
            <div class="user-info">
                <h6>{{ auth()->user()->name }}</h6>
                <span class="user-role">Driver</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="nav-links" aria-label="Main navigation">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}" 
                       href="{{ route('driver.dashboard') }}"
                       aria-current="{{ request()->routeIs('driver.dashboard') ? 'page' : 'false' }}">
                        <i class="fas fa-tachometer-alt" aria-hidden="true"></i>
                        <span>Dashboard</span>
                        @if(request()->routeIs('driver.dashboard'))
                            <span class="badge badge-primary ms-auto">Now</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.orders.*') ? 'active' : '' }}" 
                       href="{{ route('driver.orders.index') }}"
                       aria-current="{{ request()->routeIs('driver.orders.*') ? 'page' : 'false' }}">
                        <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                        <span>My Orders</span>
                        @if(isset($availableOrdersCount) && $availableOrdersCount > 0)
                            <span class="badge badge-danger ms-auto">{{ $availableOrdersCount }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.earnings') ? 'active' : '' }}" 
                       href="{{ route('driver.earnings') }}"
                       aria-current="{{ request()->routeIs('driver.earnings') ? 'page' : 'false' }}">
                        <i class="fas fa-wallet" aria-hidden="true"></i>
                        <span>Earnings</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('driver.schedule') ? 'active' : '' }}" 
                       href="{{ route('driver.schedule') }}"
                       aria-current="{{ request()->routeIs('driver.schedule') ? 'page' : 'false' }}">
                        <i class="fas fa-calendar" aria-hidden="true"></i>
                        <span>Schedule</span>
                    </a>
                </li>
                
                <li class="nav-item mt-4">
                    <a class="nav-link text-muted" href="{{ route('client.dashboard') }}">
                        <i class="fas fa-store" aria-hidden="true"></i>
                        <span>Switch to Client</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent" role="main">
        @if(session('success') || session('error') || session('warning') || session('info'))
            <!-- Session flashes are handled by toast component -->
        @endif

        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            once: true,
            easing: 'ease-out-cubic',
            offset: 50
        });

        // Hide loader after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                if (loader) {
                    loader.style.opacity = '0';
                    loader.style.visibility = 'hidden';
                    setTimeout(() => loader.remove(), 500);
                }
            }, 300);
        });

        // Navbar scroll effect
        let lastScrollY = 0;
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (!navbar) return;
            
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            // Hide/show on scroll
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            
            lastScrollY = currentScrollY;
        });

        // Sidebar toggle (Desktop)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleIcon = document.querySelector('#sidebarToggle i');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            const isCollapsed = sidebar.classList.contains('collapsed');
            toggleIcon.classList.toggle('fa-chevron-left', !isCollapsed);
            toggleIcon.classList.toggle('fa-chevron-right', isCollapsed);
            
            document.getElementById('sidebarToggle').setAttribute('aria-expanded', !isCollapsed);
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Mobile menu toggle
        function toggleMobileMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const toggleBtn = document.getElementById('mobileMenuToggle');
            
            const isOpen = sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('show', isOpen);
            
            if (isOpen) {
                document.body.style.overflow = 'hidden';
                toggleBtn.innerHTML = '<i class="fas fa-times"></i>';
                toggleBtn.setAttribute('aria-expanded', 'true');
            } else {
                document.body.style.overflow = '';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        }

        // Check screen size for mobile menu
        function checkScreenSize() {
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (window.innerWidth <= 992) {
                mobileToggle.classList.remove('d-none');
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            } else {
                mobileToggle.classList.add('d-none');
                sidebar.classList.remove('mobile-open');
                document.getElementById('mobileOverlay').classList.remove('show');
                document.body.style.overflow = '';
                
                // Restore desktop sidebar state
                const collapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (collapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                    document.querySelector('#sidebarToggle i').classList.remove('fa-chevron-left');
                    document.querySelector('#sidebarToggle i').classList.add('fa-chevron-right');
                }
            }
        }

        // Initial check
        checkScreenSize();

        // Check on resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(checkScreenSize, 100);
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
            
            // Escape to close mobile menu
            if (e.key === 'Escape' && window.innerWidth <= 992) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar.classList.contains('mobile-open')) {
                    toggleMobileMenu();
                }
            }
            
            // Alt + O to go to orders
            if (e.altKey && e.key === 'o') {
                e.preventDefault();
                window.location.href = '{{ route('driver.orders.index') }}';
            }
            
            // Alt + E to go to earnings
            if (e.altKey && e.key === 'e') {
                e.preventDefault();
                window.location.href = '{{ route('driver.earnings') }}';
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

        // Add touch support for mobile swipe gestures
        let touchstartX = 0;
        let touchstartY = 0;
        
        document.addEventListener('touchstart', e => {
            touchstartX = e.changedTouches[0].screenX;
            touchstartY = e.changedTouches[0].screenY;
        }, { passive: true });
        
        document.addEventListener('touchend', e => {
            const touchendX = e.changedTouches[0].screenX;
            const touchendY = e.changedTouches[0].screenY;
            handleSwipe(touchstartX, touchendX, touchstartY, touchendY);
        }, { passive: true });
        
        function handleSwipe(startX, endX, startY, endY) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            const diffX = endX - startX;
            const diffY = endY - startY;
            
            // Only handle horizontal swipes
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                // Swipe right to open menu (from left edge)
                if (diffX > 0 && startX < 50) {
                    if (!sidebar.classList.contains('mobile-open') && window.innerWidth <= 992) {
                        toggleMobileMenu();
                    }
                }
                
                // Swipe left to close menu
                if (diffX < 0 && sidebar.classList.contains('mobile-open')) {
                    toggleMobileMenu();
                }
            }
        }

        // Online status simulation (replace with real WebSocket)
        function updateOnlineStatus(online) {
            const badge = document.getElementById('onlineStatus');
            if (badge) {
                badge.classList.toggle('online', online);
                badge.classList.toggle('offline', !online);
                badge.querySelector('span').textContent = online ? 'Online' : 'Offline';
                badge.querySelector('i').classList.toggle('fa-circle', online);
                badge.querySelector('i').classList.toggle('fa-circle', !online);
            }
        }

        // Real-time order updates (Laravel Echo placeholder)
        // Uncomment and configure when Echo is set up
        /*
        window.Echo?.channel(`driver.{{ auth()->id() }}`)
            .listen('OrderAvailable', (e) => {
                window.toastManager.info('New order available: ' + e.orderNumber, {
                    title: 'New Order',
                    action: { label: 'View', callback: () => window.location.href = '{{ route('driver.orders.index') }}' }
                });
                refreshDashboardStats();
            })
            .listen('OrderAssigned', (e) => {
                window.toastManager.success('Order assigned: ' + e.orderNumber);
                refreshDashboardStats();
            })
            .listen('OrderStatusChanged', (e) => {
                window.toastManager.info('Order ' + e.orderNumber + ' status: ' + e.status);
                if (document.querySelector('[data-order="' + e.orderId + '"]')) {
                    location.reload();
                }
            });
        */

        // Refresh dashboard stats
        function refreshDashboardStats() {
            fetch('{{ route('driver.dashboard.stats') }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                // Update stat cards
                Object.keys(data).forEach(key => {
                    const el = document.querySelector('[data-stat="' + key + '"]');
                    if (el) el.textContent = data[key];
                });
            })
            .catch(console.error);
        }

        // Auto-refresh for active orders page
        if (document.body.classList.contains('active-orders')) {
            setInterval(refreshDashboardStats, 30000);
        }

        // Service Worker Registration (PWA)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered:', reg.scope))
                    .catch(err => console.log('SW registration failed:', err));
            });
        }

        // Network status detection
        window.addEventListener('online', () => {
            updateOnlineStatus(true);
            window.toastManager.success('You are back online');
        });
        
        window.addEventListener('offline', () => {
            updateOnlineStatus(false);
            window.toastManager.warning('You are offline. Changes will sync when reconnected.', { persistent: true });
        });
    </script>
    
    @stack('scripts')
</body>
</html>