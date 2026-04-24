{{-- resources/views/client/wallet/show.blade.php --}}
@extends('layouts.app') {{-- Adjust to your layout --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Transaction Details
                    <a href="{{ url('/client/wallet') }}" class="btn btn-sm btn-secondary float-end">Back to Wallet</a>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Transaction ID:</th>
                            <td>{{ $transaction->id }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td>
                                @if($transaction->type == 'credit')
                                    <span class="badge bg-success">Credit</span>
                                @else
                                    <span class="badge bg-danger">Debit</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Amount:</th>
                            <td>${{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($transaction->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($transaction->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Failed</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $transaction->description ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection