@extends('layouts.user_type.auth')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">My Return Requests</h4>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Date Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($returns as $return)
                        <tr>
                            <td>{{ $return->item->product_name ?? 'N/A' }}</td>
                            <td>{{ $return->quantity }}</td>
                            <td>{{ $return->reason }}</td>
                            <td>
                                <span class="badge bg-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'approved' ? 'success' : 'danger') }}">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td>{{ $return->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No return requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection