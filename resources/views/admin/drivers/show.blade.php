@extends('layouts.admin')

@section('title', 'Driver Details - ' . $driver->name)
@section('page-title', 'Driver Details')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Driver Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="bg-primary-light rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-user fa-4x text-primary"></i>
                </div>
                <h4>{{ $driver->name }}</h4>
                <p class="text-muted mb-2">{{ $driver->email }}</p>
                <p class="text-muted mb-3">{{ $driver->phone ?? 'No phone number' }}</p>
                
                <div class="mb-3">
                    @if($driver->status === 'active')
                        <span class="badge bg-success p-2">Active</span>
                    @else
                        <span class="badge bg-secondary p-2">Inactive</span>
                    @endif
                </div>
                
                <div class="d-grid gap-2">
                    <button class="btn btn-warning" onclick="editDriver({{ $driver }})">
                        <i class="fas fa-edit me-2"></i>Edit Driver
                    </button>
                    <button class="btn btn-danger" onclick="deleteDriver({{ $driver->id }})">
                        <i class="fas fa-trash me-2"></i>Delete Driver
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Performance Stats</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-primary mb-1">{{ $stats['total_orders'] }}</h3>
                            <small class="text-muted">Total Orders</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-success mb-1">{{ $stats['completed_orders'] }}</h3>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-warning mb-1">K{{ number_format($stats['total_earnings'], 2) }}</h3>
                            <small class="text-muted">Total Earnings</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 bg-light rounded">
                            <h3 class="text-info mb-1">{{ $stats['rating'] }}</h3>
                            <small class="text-muted">Rating</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->client->name ?? 'N/A' }}</td>
                                <td>K{{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M j, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <p>No orders assigned yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Driver Modal -->
<div class="modal fade" id="editDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.drivers.update', $driver) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $driver->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $driver->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $driver->phone }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ $driver->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $driver->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Driver Modal -->
<div class="modal fade" id="deleteDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.drivers.delete', $driver) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Delete Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong>{{ $driver->name }}</strong>?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editDriver(driver) {
    new bootstrap.Modal(document.getElementById('editDriverModal')).show();
}

function deleteDriver(driverId) {
    new bootstrap.Modal(document.getElementById('deleteDriverModal')).show();
}
</script>
@endpush
@endsection