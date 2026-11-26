@extends('layouts.driver')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <!-- Welcome Header -->
    <div class="row mb-6" data-aos="fade-up">
        <div class="col-12">
            <div class="card card-elevated" style="background: linear-gradient(135deg, var(--color-primary-500) 0%, var(--color-primary-600) 100%); border: none;">
                <div class="card-body p-6">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                        <div>
                            <h2 class="mb-2 text-white fw-bold">Welcome back, {{ auth()->user()->name }}! 🚚</h2>
                            <p class="mb-0 text-primary-100">Ready for another day of deliveries? Let's make it happen!</p>
                        </div>
                        <div class="text-end text-md-end">
                            <div class="d-flex align-items-center justify-content-md-end gap-3 flex-wrap">
                                <div class="bg-white/10 backdrop-blur rounded-lg px-4 py-3">
                                    <div class="text-primary-100 text-xs font-medium uppercase tracking-wide">Wallet Balance</div>
                                    <div class="text-white fw-bold fs-3" data-stat="walletBalance">K{{ number_format(auth()->user()->wallet->balance ?? 0, 2) }}</div>
                                </div>
                                <x-driver.button variant="ghost" size="lg" icon="fas fa-sync" class="text-white" onclick="refreshDashboardStats()" aria-label="Refresh stats">
                                    <span class="d-none d-sm-inline">Refresh</span>
                                </x-driver.button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar (Mobile) -->
    <div class="row mb-4 d-md-none" data-aos="fade-up" data-aos-delay="100">
        <div class="col-12">
            <div class="d-flex gap-2 overflow-x-auto pb-2">
                <a href="{{ route('driver.orders.index') }}" class="btn btn-primary btn-sm flex-shrink-0">
                    <i class="fas fa-plus me-1"></i> New Order
                </a>
                <a href="{{ route('driver.earnings') }}" class="btn btn-secondary btn-sm flex-shrink-0">
                    <i class="fas fa-chart-line me-1"></i> Earnings
                </a>
                <a href="{{ route('driver.schedule') }}" class="btn btn-secondary btn-sm flex-shrink-0">
                    <i class="fas fa-calendar me-1"></i> Schedule
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4 mb-6" data-aos="fade-up" data-aos-delay="100">
        <div class="col-6 col-md-3">
            <x-driver.stat-card 
                label="Available Orders" 
                :value="$availableOrders->count() ?? 0" 
                icon="fas fa-shopping-bag"
                icon-class="stat-card-icon-primary"
                :trend="$availableOrders->count() > 0 ? 'Tap to accept' : 'Check back later'"
                trend-class="text-muted"
            />
        </div>
        
        <div class="col-6 col-md-3">
            <x-driver.stat-card 
                label="Active Deliveries" 
                :value="$activeDeliveries->count() ?? 0" 
                icon="fas fa-truck"
                icon-class="stat-card-icon-warning"
                :trend="$activeDeliveries->count() > 0 ? 'In progress' : 'All clear'"
                trend-class="{{ $activeDeliveries->count() > 0 ? 'positive' : '' }}"
            />
        </div>
        
        <div class="col-6 col-md-3">
            <x-driver.stat-card 
                label="Completed Today" 
                :value="$completedToday ?? 0" 
                icon="fas fa-check-circle"
                icon-class="stat-card-icon-success"
                :trend="'+' . ($completedToday ?? 0) . ' today'"
                trend-class="positive"
            />
        </div>
        
        <div class="col-6 col-md-3">
            <x-driver.stat-card 
                label="Today's Earnings" 
                :value="number_format($todayEarnings ?? 0, 2)" 
                icon="fas fa-money-bill-wave"
                icon-class="stat-card-icon-info"
                :trend="'K' . number_format($todayEarnings ?? 0, 2)"
                trend-class="positive"
            />
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Available Orders Card -->
        <div class="col-lg-6 col-xl-7" data-aos="fade-up" data-aos-delay="200">
            <x-driver.card headerClass="bg-white border-0">
                <x-slot name="header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary-100 text-primary-600 rounded-lg p-2">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h5 class="mb-0 fw-semibold">Available Orders</h5>
                        </div>
                        <div class="d-flex gap-2">
                            <x-driver.button variant="ghost" size="sm" icon="fas fa-sync" onclick="refreshAvailableOrders()" aria-label="Refresh orders" />
                            <a href="{{ route('driver.orders.index') }}" class="btn btn-primary btn-sm">
                                View All
                                @if($availableOrders->count() > 0)
                                    <span class="badge badge-danger ms-1">{{ $availableOrders->count() }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </x-slot>
                
                <div class="card-body p-0">
                    @if($availableOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Order</th>
                                        <th>Pickup</th>
                                        <th style="width: 100px;">Amount</th>
                                        <th style="width: 120px;">Scheduled</th>
                                        <th style="width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableOrders->take(5) as $order)
                                    <tr data-order="{{ $order->id }}">
                                        <td>
                                            <strong class="text-primary">{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->pickupLocation->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $order->pickupLocation->address ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-success">K{{ number_format($order->total, 2) }}</span>
                                            <br>
                                            <small class="text-muted">Earn: K{{ number_format($order->total * 0.2, 2) }}</small>
                                        </td>
                                        <td>
                                            @if($order->pickup_scheduled_at)
                                                <span class="badge badge-info">{{ $order->pickup_scheduled_at->format('M j, g:i A') }}</span>
                                            @else
                                                <span class="badge badge-warning">ASAP</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('driver.orders.show', $order) }}" 
                                               class="btn btn-success btn-sm w-100"
                                               data-aos="fade-right"
                                               data-aos-delay="{{ $loop->index * 50 }}">
                                                <i class="fas fa-check me-1"></i>Accept
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($availableOrders->count() > 5)
                            <div class="p-4 text-center border-top">
                                <a href="{{ route('driver.orders.index') }}" class="btn btn-link">
                                    View all {{ $availableOrders->count() }} available orders
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <x-driver.empty-state 
                            icon="fas fa-box-open" 
                            title="No Available Orders" 
                            message="New customer orders will appear here. Pull to refresh or check back soon."
                            actionText="Refresh"
                            actionUrl="{{ route('driver.orders.index') }}"
                            actionIcon="fas fa-sync"
                        />
                    @endif
                </div>
            </x-driver.card>
        </div>

        <!-- Active Deliveries Card -->
        <div class="col-lg-6 col-xl-5" data-aos="fade-up" data-aos-delay="300">
            <x-driver.card headerClass="bg-white border-0">
                <x-slot name="header">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-warning-100 text-warning-600 rounded-lg p-2">
                                <i class="fas fa-truck"></i>
                            </div>
                            <h5 class="mb-0 fw-semibold">Active Deliveries</h5>
                        </div>
                        <a href="{{ route('driver.orders.index') }}?tab=active" class="btn btn-secondary btn-sm">View All</a>
                    </div>
                </x-slot>
                
                <div class="card-body p-0">
                    @if($activeDeliveries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Order</th>
                                        <th>Client</th>
                                        <th style="width: 120px;">Status</th>
                                        <th style="width: 80px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeDeliveries->take(5) as $order)
                                    <?php
                                        $statusConfig = [
                                            'driver_assigned' => ['label' => 'Assigned', 'class' => 'badge-warning', 'icon' => 'fas fa-user-clock'],
                                            'pickup_scheduled' => ['label' => 'Pickup Scheduled', 'class' => 'badge-info', 'icon' => 'fas fa-calendar-clock'],
                                            'collected' => ['label' => 'Collected', 'class' => 'badge-primary', 'icon' => 'fas fa-box'],
                                            'out_for_delivery' => ['label' => 'Out for Delivery', 'class' => 'badge-success', 'icon' => 'fas fa-truck-fast'],
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['label' => ucfirst(str_replace('_', ' ', $order->status)), 'class' => 'badge-gray', 'icon' => 'fas fa-question'];
                                    ?>
                                    <tr data-order="{{ $order->id }}">
                                        <td>
                                            <strong class="text-primary">{{ $order->order_number }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->client->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $order->deliveryLocation->name ?? '' }}</small>
                                        </td>
                                        <td>
                                            <span class="{{ $config['class'] }} d-inline-flex align-items-center gap-1">
                                                <i class="{{ $config['icon'] }}"></i>
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('driver.orders.show', $order) }}" 
                                               class="btn btn-primary btn-sm w-100"
                                               data-aos="fade-left"
                                               data-aos-delay="{{ $loop->index * 50 }}">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($activeDeliveries->count() > 5)
                            <div class="p-4 text-center border-top">
                                <a href="{{ route('driver.orders.index') }}?tab=active" class="btn btn-link">
                                    View all {{ $activeDeliveries->count() }} active deliveries
                                </a>
                            </div>
                        @endif
                    @else
                        <x-driver.empty-state 
                            icon="fas fa-truck" 
                            title="No Active Deliveries" 
                            message="Accept an available order to start delivering"
                            actionText="Browse Orders"
                            actionUrl="{{ route('driver.orders.index') }}"
                            actionIcon="fas fa-shopping-bag"
                        />
                    @endif
                </div>
            </x-driver.card>
        </div>

        <!-- Earnings Quick Stats -->
        <div class="col-xl-5" data-aos="fade-up" data-aos-delay="400">
            <x-driver.card headerClass="bg-white border-0">
                <x-slot name="header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-info-100 text-info-600 rounded-lg p-2">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h5 class="mb-0 fw-semibold">Earnings Overview</h5>
                        </div>
                        <a href="{{ route('driver.earnings') }}" class="btn btn-ghost btn-sm">Details</a>
                    </div>
                </x-slot>
                
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-4">
                            <div class="stat-card text-center p-3">
                                <div class="stat-card-icon stat-card-icon-success mx-auto mb-2">
                                    <i class="fas fa-sun"></i>
                                </div>
                                <div class="stat-card-value text-success fs-4">K{{ number_format($todayEarnings ?? 0, 2) }}</div>
                                <div class="stat-card-label">Today</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-card text-center p-3">
                                <div class="stat-card-icon stat-card-icon-primary mx-auto mb-2">
                                    <i class="fas fa-calendar-week"></i>
                                </div>
                                <div class="stat-card-value text-primary fs-4">K{{ number_format($weekEarnings ?? 0, 2) }}</div>
                                <div class="stat-card-label">This Week</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-card text-center p-3">
                                <div class="stat-card-icon stat-card-icon-info mx-auto mb-2">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="stat-card-value text-info fs-4">K{{ number_format($monthEarnings ?? 0, 2) }}</div>
                                <div class="stat-card-label">This Month</div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="stat-card text-center p-3">
                                <div class="stat-card-icon stat-card-icon-warning mx-auto mb-2">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-card-value text-warning fs-4">K{{ number_format($wallet->lifetime_earnings ?? 0, 2) }}</div>
                                <div class="stat-card-label">Lifetime Earnings</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-card text-center p-3">
                                <div class="stat-card-icon stat-card-icon-danger mx-auto mb-2">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="stat-card-value text-danger fs-4">K{{ number_format($wallet->lifetime_spent ?? 0, 2) }}</div>
                                <div class="stat-card-label">Lifetime Spent</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('driver.earnings') }}" class="btn btn-primary">
                            <i class="fas fa-wallet me-2"></i>View Full Earnings
                        </a>
                    </div>
                </div>
            </x-driver.card>
        </div>
    </div>

    <!-- Quick Actions (Desktop) -->
    <div class="row mt-6 d-none d-md-flex" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('driver.orders.index') }}" class="btn btn-primary btn-lg flex-1" style="max-width: 200px;">
                    <i class="fas fa-plus me-2"></i>Find Orders
                </a>
                <a href="{{ route('driver.earnings') }}" class="btn btn-secondary btn-lg flex-1" style="max-width: 200px;">
                    <i class="fas fa-chart-line me-2"></i>My Earnings
                </a>
                <a href="{{ route('driver.schedule') }}" class="btn btn-secondary btn-lg flex-1" style="max-width: 200px;">
                    <i class="fas fa-calendar me-2"></i>My Schedule
                </a>
                <a href="{{ route('driver.profile') }}" class="btn btn-ghost btn-lg flex-1" style="max-width: 200px;">
                    <i class="fas fa-user me-2"></i>Profile
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Refresh available orders without full page reload
function refreshAvailableOrders() {
    const btn = event.target.closest('button');
    if (btn) {
        btn.classList.add('loading');
        btn.disabled = true;
    }
    
    fetch('{{ route('driver.orders.available') }}', {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        // Update badge counts
        const badges = document.querySelectorAll('[data-stat="availableOrders"]');
        badges.forEach(b => b.textContent = data.count);
        
        // Show toast
        window.toastManager.success('Orders refreshed', { title: 'Updated' });
    })
    .catch(err => {
        console.error(err);
        window.toastManager.danger('Failed to refresh orders');
    })
    .finally(() => {
        if (btn) {
            btn.classList.remove('loading');
            btn.disabled = false;
        }
    });
}

// Pull to refresh on mobile
let touchStartY = 0;
let isPulling = false;

document.addEventListener('touchstart', (e) => {
    if (window.scrollY === 0) {
        touchStartY = e.touches[0].clientY;
        isPulling = true;
    }
}, { passive: true });

document.addEventListener('touchmove', (e) => {
    if (isPulling && window.scrollY === 0) {
        const diff = e.touches[0].clientY - touchStartY;
        if (diff > 100) {
            isPulling = false;
            refreshAvailableOrders();
        }
    }
}, { passive: true });

document.addEventListener('touchend', () => {
    isPulling = false;
});
</script>
@endpush
@endsection