@extends('layouts.client')

@section('title', 'My Orders')

@push('styles')
<style>
:root {
    --primary: #0e5fa6;
    --primary-dark: #0b3b6f;
    --secondary: #17b5c8;
    --light: #f5f5f5;
    --white: #ffffff;
    --dark: #333;
    --border: #e0e0e0;
    --success: #4caf50;
    --warning: #ff9800;
    --danger: #f44336;
}

/* Page Background */
body {
    background: linear-gradient(135deg, #dff4f7 0%, #bfe3ea 100%);
}

/* Card */
.order-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* ================= HEADER ================= */
.order-header {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

/* Logo */
.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255,255,255,0.1);
    padding: 6px 16px 6px 6px;
    border-radius: 50px;
    border: 1px solid rgba(255,255,255,0.2);
}

.logo-image {
    height: 48px;
    width: auto;
    border-radius: 10px;
    background: #ffffff;
    padding: 6px;
    object-fit: contain;
}

.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}

.brand-main {
    font-weight: 800;
    font-size: 1.2rem;
    color: #ffffff;
    letter-spacing: 1px;
}

.brand-sub {
    font-weight: 500;
    font-size: 0.9rem;
    color: #e0f7fa;
}

/* New Order Button */
.new-order-btn {
    background: var(--secondary);
    color: white;
    padding: 0.7rem 1.4rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    gap: 8px;
    align-items: center;
    transition: 0.3s;
}

.new-order-btn:hover {
    background: #129aa9;
    transform: translateY(-2px);
}

/* ================= CONTENT ================= */
.order-content {
    padding: 2rem;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 2rem;
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-item {
    background: var(--light);
    padding: 1.5rem;
    border-radius: 16px;
    text-align: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: var(--primary);
}

.stat-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    color: #666;
}

/* Table */
.table-wrapper {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid var(--border);
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
}

.orders-table th,
.orders-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
}

.orders-table th {
    background: var(--light);
    font-size: 0.85rem;
    text-transform: uppercase;
}

.status-badge {
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.badge-delivered { background: var(--success); }
.badge-pending { background: var(--warning); }
.badge-cancelled { background: var(--danger); }

/* Responsive */
@media(max-width:768px){
    .order-header{
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>
@endpush


@section('client-content')
<div class="container py-4">
    <div class="order-card">

        <!-- HEADER -->
        <div class="order-header">
            <div class="logo-container">
                <img src="{{ asset('images/1.jpg') }}" 
                     alt="Tuwasha Laundry Logo" 
                     class="logo-image">

                <div class="logo-text">
                    <span class="brand-main">Tuwasha</span>
                    <span class="brand-sub">Laundry</span>
                </div>
            </div>


            <a href="{{ route('client.dashboard') }}" class="new-order-btn">
                <i class="fas fa-plus-circle"></i>
                <span>New Order</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- CONTENT -->
        <div class="order-content">
            <h2 class="section-title">My Orders</h2>

            @if($orders->isEmpty())
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h3 class="empty-title">No Orders Yet</h3>
                    <p class="empty-text">Ready to get your laundry done? Place your first order now!</p>
                    <a href="{{ route('client.dashboard') }}" class="btn-create">
                        <i class="fas fa-plus-circle"></i>
                        Create Your First Order
                    </a>
                </div>
            @else
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-value">{{ $orders->total() }}</div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value">{{ $orders->where('status', 'delivered')->count() }}</div>
                        <div class="stat-label">Completed</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="stat-value">K{{ number_format($orders->sum('total'), 2) }}</div>
                        <div class="stat-label">Total Spent</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="stat-value">{{ $orders->whereNotIn('status', ['delivered', 'cancelled'])->count() }}</div>
                        <div class="stat-label">Active Orders</div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="table-wrapper">
                    <div class="table-responsive">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order Details</th>
                                    <th>Basket</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <span class="order-number">#{{ $order->order_number }}</span>
                                            <span class="order-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $order->created_at->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="basket-details">
                                                <span class="basket-name">{{ $order->basket->name ?? 'Standard Laundry' }}</span>
                                                <span class="basket-items">
                                                    <i class="fas fa-tag"></i>
                                                    @if(!empty($order->special_items))
                                                        {{ count($order->special_items) }} special {{ Str::plural('item', count($order->special_items)) }}
                                                    @else
                                                        No special items
                                                    @endif
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="total-amount">K{{ number_format($order->total, 2) }}</div>
                                            <div class="item-count">
                                                <i class="fas fa-box"></i>
                                                @php
                                                    $itemCount = 1 + (count($order->special_items ?? []));
                                                @endphp
                                                {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'pending' => 'badge-pickup_scheduled',
                                                    'pickup_scheduled' => 'badge-pickup_scheduled',
                                                    'driver_assigned' => 'badge-driver_assigned',
                                                    'collected' => 'badge-collected',
                                                    'in_laundry' => 'badge-in_laundry',
                                                    'out_for_delivery' => 'badge-out_for_delivery',
                                                    'delivered' => 'badge-delivered',
                                                    'cancelled' => 'badge-cancelled'
                                                ];
                                            @endphp
                                            <span class="status-badge {{ $statusClasses[$order->status] ?? 'badge-pickup_scheduled' }}">
                                                <i class="fas fa-circle"></i>
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $paymentClasses = [
                                                    'paid' => 'payment-paid',
                                                    'pending' => 'payment-pending',
                                                    'failed' => 'payment-failed',
                                                    'refunded' => 'payment-refunded'
                                                ];
                                            @endphp
                                            <span class="payment-status {{ $paymentClasses[$order->payment_status] ?? 'payment-pending' }}">
                                                <i class="fas fa-{{ $order->payment_status === 'paid' ? 'check-circle' : 'clock' }}"></i>
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('client.orders.show', $order) }}" 
                                                   class="action-btn view-btn" 
                                                   title="View Order Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('client.orders.track', $order) }}" 
                                                   class="action-btn track-btn" 
                                                   title="Track Order">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </a>
                                                @if(in_array($order->status, ['pending', 'pickup_scheduled']))
                                                    <button class="action-btn cancel-btn" 
                                                            title="Cancel Order" 
                                                            onclick="cancelOrder({{ $order->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PAGINATION -->
                @if($orders->hasPages())
                    <div class="pagination-wrapper">
                        @if($orders->previousPageUrl())
                            <a href="{{ $orders->previousPageUrl() }}" class="pagination-btn">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </a>
                        @else
                            <span class="pagination-btn disabled">
                                <i class="fas fa-chevron-left"></i>
                                Previous
                            </span>
                        @endif

                        <div class="pagination-dots">
                            @for($i = 1; $i <= $orders->lastPage(); $i++)
                                @if($i == $orders->currentPage())
                                    <span class="page-dot active">{{ $i }}</span>
                                @else
                                    <a href="{{ $orders->url($i) }}" class="page-dot">{{ $i }}</a>
                                @endif
                            @endfor
                        </div>

                        @if($orders->nextPageUrl())
                            <a href="{{ $orders->nextPageUrl() }}" class="pagination-btn">
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-btn disabled">
                                Next
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Cancel Order
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Are you sure you want to cancel this order?</p>
                <div class="alert alert-warning py-2 mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>This action cannot be undone. Any prepaid amount will be refunded to your wallet.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Close
                </button>
                <form id="cancelForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-cancel">
                        <i class="fas fa-trash-alt me-2"></i>
                        Cancel Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Cancel order function
function cancelOrder(orderId) {
    let form = document.getElementById('cancelForm');
    form.action = "{{ url('client/orders') }}/" + orderId + "/cancel";
    
    let modal = new bootstrap.Modal(document.getElementById('cancelModal'));
    modal.show();
}

// Auto-refresh for active orders
@if($orders->isNotEmpty())
    let hasActiveOrders = @json($orders->contains(function($order) {
        return !in_array($order->status, ['delivered', 'cancelled']);
    }));
    
    if (hasActiveOrders) {
        let refreshInterval = setInterval(function() {
            location.reload();
        }, 30000); // Refresh every 30 seconds
        
        // Clear interval when leaving the page
        window.addEventListener('beforeunload', function() {
            clearInterval(refreshInterval);
        });
    }
@endif

// Add smooth transitions and tooltips
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px) scale(1.1)';
    });
    btn.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0) scale(1)';
    });
});

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Handle responsive table scroll
const tableWrapper = document.querySelector('.table-responsive');
if (tableWrapper) {
    tableWrapper.addEventListener('scroll', function() {
        // Add shadow indicators when scrolling
        if (this.scrollLeft > 0) {
            this.style.boxShadow = 'inset -10px 0 10px -10px rgba(0,0,0,0.1)';
        } else {
            this.style.boxShadow = 'none';
        }
    });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + N for New Order
    if (e.altKey && e.key === 'n') {
        e.preventDefault();
        window.location.href = "{{ route('client.dashboard') }}";
    }
    
    // Esc to close modal if open
    if (e.key === 'Escape') {
        let modal = bootstrap.Modal.getInstance(document.getElementById('cancelModal'));
        if (modal) {
            modal.hide();
        }
    }
});
</script>
@endpush
@endsection