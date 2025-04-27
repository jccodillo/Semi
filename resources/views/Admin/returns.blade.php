@extends('layouts.user_type.auth')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Return Requests</h4>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                            <tr>
                                <td>{{ $return->user->name ?? 'N/A' }}</td>
                                <td>{{ $return->item->product_name ?? 'N/A' }}</td>
                                <td>{{ $return->quantity }}</td>
                                <td>{{ $return->reason }}</td>
                                <td>
                                    <span class="badge bg-{{ $return->status === 'pending' ? 'warning' : ($return->status === 'approved' ? 'success' : ($return->status === 'replace' ? 'danger' : 'secondary')) }}">
                                        {{ strtoupper($return->status) }}
                                    </span>
                                </td>
                                <td>{{ $return->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.returns.update', $return->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $return->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $return->status === 'approved' ? 'selected' : '' }}>Approve</option>
                                            <option value="rejected" {{ $return->status === 'rejected' ? 'selected' : '' }}>Reject</option>
                                            <option value="replace" {{ $return->status === 'replace' ? 'selected' : '' }}>Replace</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No return requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection