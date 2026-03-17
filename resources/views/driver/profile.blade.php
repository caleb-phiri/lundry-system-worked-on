@extends('layouts.driver')

@section('title', 'My Profile')
@section('page-title', 'Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="bg-primary-light rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user fa-4x text-primary"></i>
                    </div>
                    <h4>{{ auth()->user()->name }}</h4>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <p class="text-muted">{{ auth()->user()->phone ?? 'No phone' }}</p>
                    
                    <div class="mt-3">
                        <span class="badge bg-success p-2">Active Driver</span>
                    </div>
                    
                    <hr>
                    
                    <div class="text-start">
                        <p><i class="fas fa-calendar me-2"></i> Member since: {{ auth()->user()->created_at->format('M j, Y') }}</p>
                        <p><i class="fas fa-truck me-2"></i> Total Deliveries: {{ auth()->user()->orders()->where('status', 'delivered')->count() }}</p>
                        <p><i class="fas fa-star me-2"></i> Rating: 4.8 (156 reviews)</p>
                    </div>
                    
                    <button class="btn btn-primary w-100 mt-3" onclick="editProfile()">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <!-- Performance Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Performance Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">On-Time Delivery</label>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" style="width: 98%"></div>
                                </div>
                                <small class="text-muted">98% (245/250 orders)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Customer Rating</label>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-warning" style="width: 96%"></div>
                                </div>
                                <small class="text-muted">4.8/5.0 (156 reviews)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Acceptance Rate</label>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-info" style="width: 92%"></div>
                                </div>
                                <small class="text-muted">92% (230/250 offers)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted">Completion Rate</label>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                                </div>
                                <small class="text-muted">100% (250/250 accepted)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Activity</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @foreach(auth()->user()->orders()->latest()->take(5)->get() as $order)
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <i class="fas fa-circle text-{{ $order->status === 'delivered' ? 'success' : 'primary' }}"></i>
                            </div>
                            <div>
                                <strong>Order #{{ $order->order_number }}</strong>
                                <p class="text-muted small mb-0">
                                    {{ $order->status === 'delivered' ? 'Delivered' : ucfirst(str_replace('_', ' ', $order->status)) }}
                                    • {{ $order->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('driver.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editProfile() {
    new bootstrap.Modal(document.getElementById('editProfileModal')).show();
}
</script>
@endpush
@endsection