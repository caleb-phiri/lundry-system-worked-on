@extends('layouts.admin')

@section('title', 'Manage Clients')
@section('page-title', 'Clients Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Clients</h5>
    </div>
    <div class="card-body">
        <!-- Search -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, email or phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>Search
                </button>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo me-2"></i>Reset
                </a>
            </div>
        </form>

        <!-- Clients Table -->
        <div class="table-responsive">
            <table class="table table-hover" id="clientsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Joined</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>#{{ $client->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-light rounded-circle p-2 me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <strong>{{ $client->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone ?? 'N/A' }}</td>
                        <td><span class="badge bg-info">{{ $client->orders_count }}</span></td>
                        <td><strong>K{{ number_format($client->total_spent ?? 0, 2) }}</strong></td>
                        <td>{{ $client->created_at->format('M j, Y') }}</td>
                        <td>
                            @if($client->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($client->status === 'inactive')
                                <span class="badge bg-secondary">Inactive</span>
                            @else
                                <span class="badge bg-danger">Suspended</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <p>No clients found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $clients->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#clientsTable').DataTable({
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