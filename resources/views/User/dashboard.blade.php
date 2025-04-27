@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center mb-4">
        <!-- Statistics Cards -->
        <div class="col-xl-8 mb-4">
            <div class="row">
                <div class="col-xl-4 col-sm-4 mb-xl-0 mb-2">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body text-center p-3">
                            <h5 class="text-muted mb-2">Pending Requests</h5>
                            <h2 class="display-4 fw-bold" style="color: #821131;">{{ $pendingRequestsCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-4 mb-xl-0 mb-2">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body text-center p-3">
                            <h5 class="text-muted mb-2">Approved</h5>
                            <h2 class="display-4 fw-bold" style="color: #821131;">{{ $approvedRequestsCount }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-4 mb-xl-0 mb-2">
                    <div class="card shadow-sm hover-card">
                        <div class="card-body text-center p-3">
                            <h5 class="text-muted mb-2">Rejected</h5>
                            <h2 class="display-4 fw-bold" style="color: #821131;">{{ $rejectedRequestsCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Supply Office Schedule - Compact Version -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-2">
                    <h6 class="text-center mb-0">Supply Office Schedule</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom schedule-item">
                                <div>
                                    <span class="fw-bold">Monday-Wednesday:</span>
                                    <small class="d-block">Request of Supplies/Materials</small>
                                    <small class="text-muted"><a href="mailto:supply@tup.edu.ph" class="text-teal">supply@tup.edu.ph</a></small>
                                </div>
                                <span>-</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 mb-3 border-bottom schedule-item">
                                <div>
                                    <span class="fw-bold">Tuesday-Thursday:</span>
                                    <small class="d-block">Receiving of Delivery</small>
                                </div>
                                <span>9:00am-4:00pm</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom schedule-item">
                                <div>
                                    <span class="fw-bold">Thursday:</span>
                                    <small class="d-block">Issuances of Supplies</small>
                                </div>
                                <span>6:00am-4:00pm</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center p-2 mb-3 border-bottom schedule-item">
                                <div>
                                    <span class="fw-bold">Friday:</span>
                                    <small class="d-block">Return of Unserviceable Equipment</small>
                                </div>
                                <span>8:00am-4:00pm</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <small class="text-muted">Location: Supply/Procurement Building</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="text-center mb-0">Recent Requests</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th class="text-center">Request ID</th>
                                    <th class="text-center">Requested By</th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                    <tr>
                                        <td class="text-center">{{ $request->request_id }}</td>
                                        <td class="text-center">{{ $request->user->name }}</td>
                                        <td class="text-center">{{ $request->product_name }}</td>
                                        <td class="text-center">
                                            @if($request->status == 'pending')
                                                <span class="badge rounded-pill bg-warning">Pending</span>
                                            @elseif($request->status == 'approved')
                                                <span class="badge rounded-pill bg-success">Approved</span>
                                            @else
                                                <span class="badge rounded-pill bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $request->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $recentRequests->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Card hover effect */
.hover-card {
    transition: transform 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
}

/* Table styling */
.table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-bottom: 2px solid #821131;
}

.badge {
    padding: 0.5em 1em;
}

/* Card shadows and borders */
.card {
    border: none;
    border-radius: 15px;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Typography */
h5.text-muted {
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.display-4 {
    font-size: 2.5rem;
}

/* Pagination styling */
.pagination {
    margin-bottom: 0 !important;
}

.page-item.active .page-link {
    background-color: #821131 !important;
    border-color: #821131 !important;
}

.page-link {
    color: #821131;
}

.page-link:hover {
    color: #6a0e28;
}

.page-item.disabled .page-link {
    color: #6c757d;
}

/* Schedule styling */
.text-teal {
    color: #20c997;
}

.text-teal:hover {
    color: #0ca678;
    text-decoration: underline;
}

.border-bottom {
    border-bottom: 1px solid rgba(0,0,0,.05)!important;
}

.schedule-item {
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #821131 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    margin-bottom: 8px;
}

.col-md-6:first-child {
    border-right: 1px dashed #dee2e6;
}
</style>
@endsection