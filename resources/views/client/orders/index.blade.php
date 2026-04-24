@extends('layouts.client')

@section('title', 'My Orders')

@push('styles')
<style>
:root {
    --primary: #0f3b2c;
    --primary-dark: #0a2c20;
    --primary-light: #e8f3ef;
    --secondary: #2c7a4b;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --gray-50: #f9fafb;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
}

/* Page Background */
.page-wrapper {
    background: linear-gradient(135deg, #e8f3ef 0%, #d1fae5 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Main Card */
.order-card {
    background: #fff;
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
}

/* Header Section */
.order-header {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.12);
    padding: 6px 18px 6px 10px;
    border-radius: 50px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.logo-container:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.02);
}

.logo-image {
    height: 45px;
    width: auto;
    border-radius: 10px;
    background: #ffffff;
    padding: 5px;
    object-fit: contain;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.brand-main {
    font-weight: 800;
    font-size: 1.2rem;
    color: #ffffff;
    letter-spacing: 1px;
}

.brand-sub {
    font-weight: 500;
    font-size: 0.75rem;
    color: #cbd5e1;
}

.new-order-btn {
    background: linear-gradient(135deg, var(--secondary), var(--primary));
    color: white;
    padding: 10px 22px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(15, 59, 44, 0.3);
}

.new-order-btn:hover {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 59, 44, 0.4);
    color: white;
}

/* Content Section */
.order-content {
    padding: 2rem;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--gray-800);
    position: relative;
    display: inline-block;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
    border-radius: 2px;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-item {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 1.25rem;
    border-radius: 1rem;
    text-align: center;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.stat-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary), var(--secondary));
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px -8px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 45px;
    height: 45px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    font-size: 1.25rem;
    color: var(--primary);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    font-weight: 600;
}

/* Table Styles */
.table-wrapper {
    border-radius: 1rem;
    overflow-x: auto;
    border: 1px solid #e2e8f0;
    background: white;
}

.orders-table {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
}

.orders-table th {
    padding: 0.875rem 1rem;
    background: #f8fafc;
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.5px;
    color: #64748b;
    border-bottom: 2px solid #e2e8f0;
    text-align: left;
}

.orders-table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.orders-table tr:hover td {
    background: #fafafa;
}

.order-number {
    font-weight: 700;
    color: var(--primary);
    font-size: 0.85rem;
    display: block;
    margin-bottom: 4px;
}

.order-date {
    font-size: 0.7rem;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.basket-name {
    font-weight: 600;
    color: #334155;
    font-size: 0.85rem;
    display: block;
    margin-bottom: 4px;
}

.basket-items {
    font-size: 0.65rem;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.total-amount {
    font-weight: 800;
    color: var(--primary);
    font-size: 1rem;
    display: block;
    margin-bottom: 4px;
}

.item-count {
    font-size: 0.65rem;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.badge-pickup_scheduled { background: #fef3c7; color: #92400e; }
.badge-driver_assigned { background: #dbeafe; color: #215e0e; }
.badge-collected { background: #e0e7ff; color: #3730a3; }
.badge-in_laundry { background: #fce7f3; color: #9d174d; }
.badge-out_for_delivery { background: #fed7aa; color: #9a3412; }
.badge-delivered { background: #d1fae5; color: #065f46; }
.badge-cancelled { background: #fee2e2; color: #991b1b; }

/* Payment Status */
.payment-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.payment-paid { background: #d1fae5; color: #065f46; }
.payment-pending { background: #fef3c7; color: #92400e; }
.payment-failed { background: #fee2e2; color: #991b1b; }
.payment-refunded { background: #e0e7ff; color: #3730a3; }

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    border: none;
    font-size: 0.8rem;
}

.view-btn {
    background: #e8f3ef;
    color: var(--primary);
}

.view-btn:hover {
    background: var(--primary);
    color: white;
    transform: translateY(-2px);
}

.track-btn {
    background: #d1fae5;
    color: var(--success);
}

.track-btn:hover {
    background: var(--success);
    color: white;
    transform: translateY(-2px);
}

.cancel-btn {
    background: #fee2e2;
    color: var(--danger);
}

.cancel-btn:hover {
    background: var(--danger);
    color: white;
    transform: translateY(-2px);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #e8f3ef, #d1fae5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.empty-icon i {
    font-size: 2rem;
    color: var(--primary);
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #334155;
}

.empty-text {
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 1.5rem;
}

.btn-create {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    padding: 10px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.875rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.btn-create:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(15, 59, 44, 0.3);
    color: white;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-btn {
    padding: 6px 16px;
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    text-decoration: none;
    color: #475569;
    font-weight: 500;
    font-size: 0.8rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.pagination-btn:hover {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-color: transparent;
    transform: translateY(-1px);
}

.pagination-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.pagination-dots {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    justify-content: center;
}

.page-dot {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    text-decoration: none;
    color: #475569;
    font-weight: 500;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.page-dot:hover {
    background: #f1f5f9;
    color: var(--primary);
}

.page-dot.active {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
}

/* Cancel Modal */
.modal-content {
    border-radius: 1rem;
    overflow: hidden;
    border: none;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border: none;
    padding: 1rem 1.25rem;
}

.modal-title {
    color: #dc2626;
    font-weight: 700;
    font-size: 1rem;
}

.btn-cancel {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.85rem;
    transition: all 0.2s ease;
}

.btn-cancel:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    color: white;
}

/* Responsive */
@media (max-width: 992px) {
    .orders-table {
        min-width: 750px;
    }
}

@media (max-width: 768px) {
    .order-content {
        padding: 1.25rem;
    }
    
    .section-title {
        font-size: 1.25rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .stat-item {
        padding: 0.875rem;
    }
    
    .stat-value {
        font-size: 1.25rem;
    }
    
    .order-header {
        flex-direction: column;
        text-align: center;
    }
    
    .logo-container {
        justify-content: center;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .action-btn {
        width: 100%;
    }
}
</style>
@endpush

@section('client-content')
<div class="page-wrapper">
    <div class="container">
        <div class="order-card">

            <!-- HEADER -->
            <div class="order-header">
                <div class="logo-container">
                    <img src="{{ asset('images/1.jpg') }}" 
                         alt="Tuwasha Laundry Logo" 
                         class="logo-image">
                    <div class="logo-text">
                        <span class="brand-main">Tuwasha</span>
                        <span class="brand-sub">Laundry Services</span>
                    </div>
                </div>

                <a href="{{ route('client.dashboard') }}" class="new-order-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>New Order</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <!-- CONTENT -->
            <div class="order-content">
                <h2 class="section-title">
                    <i class="fas fa-shopping-bag me-2" style="color: var(--primary);"></i>
                    My Orders
                </h2>

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
                            <div class="stat-label">Active</div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="table-wrapper">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Order Details</th>
                                    <th>Basket & Items</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <div class="order-number">#{{ $order->order_number }}</div>
                                            <div class="order-date">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $order->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="order-date">
                                                <i class="far fa-clock"></i>
                                                {{ $order->created_at->format('g:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="basket-name">
                                                <i class="fas fa-tshirt me-1"></i>
                                                {{ $order->basket->name ?? 'Standard Laundry' }}
                                            </div>
                                            <div class="basket-items">
                                                <i class="fas fa-tag"></i>
                                                @php
                                                    $specialCount = count($order->special_items ?? []);
                                                @endphp
                                                @if($specialCount > 0)
                                                    {{ $specialCount }} special {{ Str::plural('item', $specialCount) }}
                                                @else
                                                    No special items
                                                @endif
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
                                            <div class="status-badge {{ $statusClasses[$order->status] ?? 'badge-pickup_scheduled' }}">
                                                <i class="fas fa-circle"></i>
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </div>
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
                                            <div class="payment-status {{ $paymentClasses[$order->payment_status] ?? 'payment-pending' }}">
                                                <i class="fas fa-{{ $order->payment_status === 'paid' ? 'check-circle' : 'clock' }}"></i>
                                                {{ ucfirst($order->payment_status) }}
                                            </div>
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
                    <button type="submit" class="btn-cancel">
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
        }, 30000);
        
        window.addEventListener('beforeunload', function() {
            clearInterval(refreshInterval);
        });
    }
@endif

// Add smooth transitions and tooltips
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-3px) scale(1.05)';
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