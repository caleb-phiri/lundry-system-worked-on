@extends('layouts.driver')

@section('title', 'My Schedule')
@section('page-title', 'Schedule')

@section('content')
<div class="container-fluid">
    <!-- Upcoming Deliveries -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Upcoming Deliveries</h5>
        </div>
        <div class="card-body">
            @if($upcoming->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Client</th>
                                <th>Pickup Location</th>
                                <th>Scheduled Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcoming as $order)
                            <tr>
                                <td><strong>{{ $order->order_number }}</strong></td>
                                <td>{{ $order->client->name ?? 'N/A' }}</td>
                                <td>{{ $order->pickupLocation->name ?? 'N/A' }}</td>
                                <td>
                                    @if($order->pickup_scheduled_at)
                                        <span class="badge bg-{{ $order->pickup_scheduled_at->isToday() ? 'success' : 'info' }}">
                                            {{ $order->pickup_scheduled_at->format('M j, g:i A') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">ASAP</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->status === 'collected' ? 'info' : 
                                        ($order->status === 'driver_assigned' ? 'warning' : 'primary') 
                                    }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('driver.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                    <h6>No Upcoming Deliveries</h6>
                    <p class="text-muted small">Your schedule will appear here once you accept orders</p>
                    <a href="{{ route('driver.orders.index') }}" class="btn btn-primary btn-sm mt-2">
                        Browse Available Orders
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Calendar View (Optional) -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Calendar</h5>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<style>
    #calendar {
        max-width: 100%;
        margin: 0 auto;
    }
    .fc-event {
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            @foreach($upcoming as $order)
            {
                title: 'Order #{{ $order->order_number }}',
                start: '{{ $order->pickup_scheduled_at ? $order->pickup_scheduled_at->format('Y-m-d\TH:i:s') : now()->format('Y-m-d\TH:i:s') }}',
                url: '{{ route('driver.orders.show', $order) }}',
                backgroundColor: '{{ 
                    $order->status === 'collected' ? '#0dcaf0' : 
                    ($order->status === 'driver_assigned' ? '#ffc107' : '#0d6efd') 
                }}',
                borderColor: 'transparent'
            },
            @endforeach
        ]
    });
    calendar.render();
});
</script>
@endpush
@endsection