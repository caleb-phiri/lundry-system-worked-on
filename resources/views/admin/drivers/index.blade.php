@extends('layouts.admin')

@section('title', 'Manage Drivers')
@section('page-title', 'Drivers Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Drivers</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDriverModal">
                    <i class="fas fa-plus me-2"></i>Add New Driver
                </button>
            </div>
            <div class="card-body">
                <!-- Search and Filter -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email or phone..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filter
                        </button>
                        <a href="{{ route('admin.drivers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </form>

                <!-- Drivers Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="driversTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Completed Orders</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($drivers as $driver)
                            <tr>
                                <td>#{{ $driver->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary-light rounded-circle p-2 me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $driver->name }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $driver->email }}</td>
                                <td>{{ $driver->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $driver->completed_orders_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($driver->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $driver->created_at->format('M j, Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.drivers.show', $driver) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="editDriver({{ $driver }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteDriver({{ $driver->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <p>No drivers found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $drivers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Driver Modal -->
<div class="modal fade" id="addDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.drivers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimum 8 characters</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Driver Modal -->
<div class="modal fade" id="editDriverModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editDriverForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="edit_status" class="form-select" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
            <form id="deleteDriverForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Delete Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this driver?</p>
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
    document.getElementById('edit_name').value = driver.name;
    document.getElementById('edit_email').value = driver.email;
    document.getElementById('edit_phone').value = driver.phone || '';
    document.getElementById('edit_status').value = driver.status || 'active';
    
    document.getElementById('editDriverForm').action = "{{ url('admin/drivers') }}/" + driver.id;
    
    new bootstrap.Modal(document.getElementById('editDriverModal')).show();
}

function deleteDriver(driverId) {
    document.getElementById('deleteDriverForm').action = "{{ url('admin/drivers') }}/" + driverId;
    new bootstrap.Modal(document.getElementById('deleteDriverModal')).show();
}

$(document).ready(function() {
    $('#driversTable').DataTable({
        pageLength: 25,
        ordering: true,
        searching: false,
        paging: false,
        info: false
    });
});
</script>
@endpush
@endsection