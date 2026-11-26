@extends('layouts.driver')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h1 class="h3 fw-bold mb-1">My Orders</h1>
                    <p class="text-muted mb-0">Manage your deliveries and track earnings</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <x-driver.button variant="ghost" size="sm" icon="fas fa-filter" onclick="toggleFilters()" aria-label="Filter orders" />
                    <x-driver.button variant="primary" size="sm" icon="fas fa-plus" href="{{ route('driver.orders.index') }}?tab=available" aria-label="Browse available orders">
                        Browse Orders
                    </x-driver.button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Collapsible) -->
    <div class="row mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="col-12">
            <div class="card" id="filtersCard" style="display: none;">
                <div class="card-body">
                    <form method="GET" class="row g-3" id="filterForm">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                                <option value="driver_assigned" {{ request('status') === 'driver_assigned' ? 'selected' : '' }}>Assigned</option>
                                <option value="pickup_scheduled" {{ request('status') === 'pickup_scheduled' ? 'selected' : '' }}>Pickup Scheduled</option>
                                <option value="collected" {{ request('status') === 'collected' ? 'selected' : '' }}>Collected</option>
                                <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" name="date_from" class="form-input form-input-sm" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" name="date_to" class="form-input form-input-sm" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i>Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="col-12">
            <x-driver.tabs 
                :tabs="[
                    ['id' => 'available', 'label' => 'Available', 'icon' => 'fas fa-shopping-bag', 'badge' => $availableOrders->count() ?? 0, 'content' => \$availableTabContent],
                    ['id' => 'active', 'label' => 'Active', 'icon' => 'fas fa-truck', 'badge' => $activeOrders->count() ?? 0, 'content' => \$activeTabContent],
                    ['id' => 'completed', 'label' => 'Completed', 'icon' => 'fas fa-check-circle', 'content' => \$completedTabContent],
                ]"
                :activeTab="request('tab') ?? 'available'"
                variant="pills"
                fullWidth="true"
            />
        </div>
    </div>

    <!-- Available Orders Tab -->
    <div class="tab-panel active" id="available-panel" role="tabpanel" aria-labelledby="available-tab">
        @if($availableOrders->count() > 0)
            <!-- Mobile Card View -->
            <div class="d-md-none">
                <div class="row g-3">
                    @foreach($availableOrders as $order)
                        <div class="col-12" data-aos="fade-up" data-aos-delay="{{ $loop->index * 30 }}">
                            <x-driver.card class="order-card h-100" data-order="{{ $order->id }}">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge badge-primary mb-1">{{ $order->order_number }}</span>
                                            <div class="fw-semibold">{{ $order->pickupLocation->name ?? 'Pickup Location' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fs-5 fw-bold text-success">K{{ number_format($order->total, 2) }}</div>
                                            <small class="text-muted">Earn: K{{ number_format($order->total * 0.2, 2) }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Pickup</small>
                                            <div class="fw-medium">{{ $order->pickup_scheduled_at ? $order->pickup_scheduled_at->format('M j, g:i A') : 'ASAP' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Distance</small>
                                            <div class="fw-medium">{{ $order->distance_km ?? 'N/A' }} km</div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-primary flex-1">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        <form action="{{ route('driver.orders.accept', $order) }}" method="POST" class="flex-1">
                                            @csrf
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fas fa-check me-1"></i>Accept
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </x-driver.card>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Desktop Table View -->
            <div class="d-none d-md-block">
                <x-driver.card>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Order #</th>
                                        <th>Client</th>
                                        <th>Pickup Location</th>
                                        <th>Delivery Location</th>
                                        <th style="width: 100px;">Amount</th>
                                        <th style="width: 140px;">Scheduled</th>
                                        <th style="width: 140px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableOrders as $order)
                                    <tr data-order="{{ $order->id }}" data-aos="fade-in" data-aos-delay="{{ $loop->index * 30 }}">
                                        <td>
                                            <span class="badge badge-primary">{{ $order->order_number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->client->name }}</div>
                                            <small class="text-muted">{{ $order->client->phone }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->pickupLocation->name }}</div>
                                            <small class="text-muted">{{ $order->pickupLocation->address }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->deliveryLocation->name }}</div>
                                            <small class="text-muted">{{ $order->deliveryLocation->address }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success fs-5">K{{ number_format($order->total, 2) }}</div>
                                            <small class="text-muted">Your share: K{{ number_format($order->total * 0.2, 2) }}</small>
                                        </td>
                                        <td>
                                            @if($order->pickup_scheduled_at)
                                                <span class="badge badge-info">{{ $order->pickup_scheduled_at->format('M j, g:i A') }}</span>
                                            @else
                                                <span class="badge badge-warning">ASAP</span>
                                            @endif
                                            @if($order->distance_km)
                                                <br><small class="text-muted">{{ $order->distance_km }} km</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-primary btn-sm flex-1" style="min-width: 100px;">
                                                    <i class="fas fa-eye me-1"></i>Details
                                                </a>
                                                <form action="{{ route('driver.orders.accept', $order) }}" method="POST" class="flex-1" style="min-width: 100px;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                                        <i class="fas fa-check me-1"></i>Accept
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-driver.card>
            </div>
        @else
            <x-driver.empty-state 
                icon="fas fa-box-open" 
                title="No Available Orders" 
                message="New orders from customers will appear here. Check back soon!"
                actionText="Refresh"
                actionUrl="{{ request()->url() }}"
                actionIcon="fas fa-sync"
            />
        @endif
    </div>

    <!-- Active Orders Tab -->
    <div class="tab-panel" id="active-panel" role="tabpanel" aria-labelledby="active-tab">
        @if($activeOrders->count() > 0)
            <!-- Mobile Card View -->
            <div class="d-md-none">
                <div class="row g-3">
                    @foreach($activeOrders as $order)
                        <?php
                            $statusConfig = [
                                'driver_assigned' => ['label' => 'Assigned', 'class' => 'badge-warning', 'icon' => 'fas fa-user-clock', 'nextAction' => 'collect'],
                                'pickup_scheduled' => ['label' => 'Pickup Scheduled', 'class' => 'badge-info', 'icon' => 'fas fa-calendar-clock', 'nextAction' => 'collect'],
                                'collected' => ['label' => 'Collected', 'class' => 'badge-primary', 'icon' => 'fas fa-box', 'nextAction' => 'deliver'],
                                'out_for_delivery' => ['label' => 'Out for Delivery', 'class' => 'badge-success', 'icon' => 'fas fa-truck-fast', 'nextAction' => 'deliver'],
                            ];
                            $config = $statusConfig[$order->status] ?? ['label' => ucfirst(str_replace('_', ' ', $order->status)), 'class' => 'badge-gray', 'icon' => 'fas fa-question', 'nextAction' => 'view'];
                        ?>
                        <div class="col-12" data-aos="fade-up" data-aos-delay="{{ $loop->index * 30 }}">
                            <x-driver.card class="order-card h-100 active-order" data-order="{{ $order->id }}" data-status="{{ $order->status }}">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge badge-primary mb-1">{{ $order->order_number }}</span>
                                            <div class="fw-semibold">{{ $order->client->name }}</div>
                                        </div>
                                        <div class="text-end">
                                            <span class="{{ $config['class'] }} d-inline-flex align-items-center gap-1">
                                                <i class="{{ $config['icon'] }}"></i>
                                                {{ $config['label'] }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Pickup</small>
                                            <div class="fw-medium">{{ $order->pickupLocation->name }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Delivery</small>
                                            <div class="fw-medium">{{ $order->deliveryLocation->name }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-primary flex-1">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                        @if($config['nextAction'] === 'collect')
                                            <form action="{{ route('driver.orders.collected', $order) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="btn btn-warning w-100">
                                                    <i class="fas fa-box me-1"></i>Collected
                                                </button>
                                            </form>
                                        @elseif($config['nextAction'] === 'deliver')
                                            <form action="{{ route('driver.orders.delivered', $order) }}" method="POST" class="flex-1">
                                                @csrf
                                                <button type="submit" class="btn btn-success w-100">
                                                    <i class="fas fa-flag-checkered me-1"></i>Deliver
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </x-driver.card>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Desktop Table View -->
            <div class="d-none d-md-block">
                <x-driver.card>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Order #</th>
                                        <th>Client</th>
                                        <th>Pickup Location</th>
                                        <th>Delivery Location</th>
                                        <th style="width: 140px;">Status</th>
                                        <th style="width: 160px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeOrders as $order)
                                    <?php
                                        $statusConfig = [
                                            'driver_assigned' => ['label' => 'Assigned', 'class' => 'badge-warning', 'icon' => 'fas fa-user-clock'],
                                            'pickup_scheduled' => ['label' => 'Pickup Scheduled', 'class' => 'badge-info', 'icon' => 'fas fa-calendar-clock'],
                                            'collected' => ['label' => 'Collected', 'class' => 'badge-primary', 'icon' => 'fas fa-box'],
                                            'out_for_delivery' => ['label' => 'Out for Delivery', 'class' => 'badge-success', 'icon' => 'fas fa-truck-fast'],
                                        ];
                                        $config = $statusConfig[$order->status] ?? ['label' => ucfirst(str_replace('_', ' ', $order->status)), 'class' => 'badge-gray', 'icon' => 'fas fa-question'];
                                    ?>
                                    <tr data-order="{{ $order->id }}" data-status="{{ $order->status }}" data-aos="fade-in" data-aos-delay="{{ $loop->index * 30 }}">
                                        <td>
                                            <span class="badge badge-primary">{{ $order->order_number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->client->name }}</div>
                                            <small class="text-muted">{{ $order->client->phone }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->pickupLocation->name }}</div>
                                            <small class="text-muted">{{ $order->pickupLocation->address }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->deliveryLocation->name }}</div>
                                            <small class="text-muted">{{ $order->deliveryLocation->address }}</small>
                                        </td>
                                        <td>
                                            <span class="{{ $config['class'] }} d-inline-flex align-items-center gap-1 fs-6">
                                                <i class="{{ $config['icon'] }}"></i>
                                                {{ $config['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-primary btn-sm flex-1" style="min-width: 100px;">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                                @if(in_array($order->status, ['driver_assigned', 'pickup_scheduled']))
                                                    <form action="{{ route('driver.orders.collected', $order) }}" method="POST" class="flex-1" style="min-width: 100px;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm w-100">
                                                            <i class="fas fa-box me-1"></i>Collected
                                                        </button>
                                                    </form>
                                                @elseif(in_array($order->status, ['collected', 'out_for_delivery']))
                                                    <form action="{{ route('driver.orders.delivered', $order) }}" method="POST" class="flex-1" style="min-width: 100px;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                                            <i class="fas fa-flag-checkered me-1"></i>Delivered
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-driver.card>
            </div>
        @else
            <x-driver.empty-state 
                icon="fas fa-truck" 
                title="No Active Orders" 
                message="Orders you've accepted will appear here"
                actionText="Browse Available Orders"
                actionUrl="{{ route('driver.orders.index') }}?tab=available"
                actionIcon="fas fa-shopping-bag"
            />
        @endif
    </div>

    <!-- Completed Orders Tab -->
    <div class="tab-panel" id="completed-panel" role="tabpanel" aria-labelledby="completed-tab">
        @if($completedOrders->count() > 0)
            <!-- Mobile Card View -->
            <div class="d-md-none">
                <div class="row g-3">
                    @foreach($completedOrders as $order)
                        <div class="col-12" data-aos="fade-up" data-aos-delay="{{ $loop->index * 30 }}">
                            <x-driver.card class="order-card h-100" data-order="{{ $order->id }}">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <span class="badge badge-success mb-1">{{ $order->order_number }}</span>
                                            <div class="fw-semibold">{{ $order->client->name }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fs-5 fw-bold text-success">K{{ number_format($order->total, 2) }}</div>
                                            <small class="text-muted">Earned: K{{ number_format($order->total * 0.2, 2) }}</small>
                                        </div>
                                    </div>
                                    
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">Completed</small>
                                            <div class="fw-medium">{{ $order->delivery_completed_at ? $order->delivery_completed_at->format('M j, Y') : 'N/A' }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Rating</small>
                                            <div class="fw-medium text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star{{ $i <= 4 ? '' : '-half-alt' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </a>
                                </div>
                            </x-driver.card>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Desktop Table View -->
            <div class="d-none d-md-block">
                <x-driver.card>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 100px;">Order #</th>
                                        <th>Client</th>
                                        <th style="width: 100px;">Amount</th>
                                        <th style="width: 100px;">Earnings</th>
                                        <th style="width: 160px;">Completed</th>
                                        <th style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedOrders as $order)
                                    <tr data-order="{{ $order->id }}" data-aos="fade-in" data-aos-delay="{{ $loop->index * 30 }}">
                                        <td>
                                            <span class="badge badge-success">{{ $order->order_number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $order->client->name }}</div>
                                            <small class="text-muted">{{ $order->client->phone }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-primary fs-5">K{{ number_format($order->total, 2) }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-success fs-5">K{{ number_format($order->total * 0.2, 2) }}</div>
                                        </td>
                                        <td>
                                            {{ $order->delivery_completed_at ? $order->delivery_completed_at->format('M j, Y g:i A') : 'N/A' }}
                                        </td>
                                        <td>
                                            <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-driver.card>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $completedOrders->links() }}
            </div>
        @else
            <x-driver.empty-state 
                icon="fas fa-check-circle" 
                title="No Completed Orders" 
                message="Your completed deliveries will appear here"
                actionText="Browse Available Orders"
                actionUrl="{{ route('driver.orders.index') }}?tab=available"
                actionIcon="fas fa-shopping-bag"
            />
        @endif
    </div>
</div>

@push('styles')
<style>
.order-card {
    transition: all var(--transition-base);
    border-left: 4px solid transparent;
}
.order-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.order-card.active-order {
    border-left-color: var(--color-warning);
}
.order-card[data-status="collected"] {
    border-left-color: var(--color-primary);
}
.order-card[data-status="out_for_delivery"] {
    border-left-color: var(--color-success);
}
.order-card[data-status="delivered"] {
    border-left-color: var(--color-success);
}

/* Tab panel animations */
.tab-panel {
    display: none;
    animation: fadeIn 0.3s ease;
}
.tab-panel.active {
    display: block;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Filter card animation */
#filtersCard {
    animation: slideDown 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
function toggleFilters() {
    const card = document.getElementById('filtersCard');
    const isHidden = card.style.display === 'none';
    card.style.display = isHidden ? 'block' : 'none';
    
    // Scroll to filters
    if (isHidden) {
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Auto-show filters on mobile if there are active filters
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('status') || urlParams.has('date_from') || urlParams.has('date_to')) {
        document.getElementById('filtersCard').style.display = 'block';
    }
    
    // Highlight active tab from URL
    const tab = urlParams.get('tab') || 'available';
    document.querySelectorAll('[role="tab"]').forEach(t => {
        if (t.id === tab + '-tab') {
            t.click();
        }
    });
});

// Keyboard shortcuts for order actions
document.addEventListener('keydown', function(e) {
    // Only when focused on an order row
    const activeRow = document.querySelector('tr[data-order]:focus-within, .order-card:focus-within');
    if (!activeRow) return;
    
    const orderId = activeRow.dataset.order;
    const status = activeRow.dataset.status;
    
    if (e.altKey && e.key === 'c' && ['driver_assigned', 'pickup_scheduled'].includes(status)) {
        e.preventDefault();
        document.querySelector(`form[action*="collected"][action*="${orderId}"] button`)?.click();
    }
    
    if (e.altKey && e.key === 'd' && ['collected', 'out_for_delivery'].includes(status)) {
        e.preventDefault();
        document.querySelector(`form[action*="delivered"][action*="${orderId}"] button`)?.click();
    }
});
</script>
@endpush
@endsection