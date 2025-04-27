@extends('layouts.user_type.auth')
@php
use App\Models\SuppliesInventory;
use App\Models\SupplyTransaction;
@endphp
@section('content')
@if(auth()->user()->isAdmin())
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-3">Stock Requests</h6>
                        <!-- Tabs -->
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#approved" role="tab">
                                        Approved 
                                        <span class="badge rounded-pill bg-success ms-1">{{ $requests->where('status', 'approved')->count() }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#pending" role="tab">
                                        Pending 
                                        <span class="badge rounded-pill bg-warning ms-1">{{ $requests->where('status', 'pending')->count() }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#rejected" role="tab">
                                        Rejected 
                                        <span class="badge rounded-pill bg-danger ms-1">{{ $requests->where('status', 'rejected')->count() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="tab-content" id="myTabContent">
                            <!-- Approved Requests Tab -->
                            <div class="tab-pane fade show active" id="approved" role="tabpanel">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Branch</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requested By</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Items</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Issuance Status</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                            @foreach($requests->where('status', 'approved') as $request)
                                            <tr>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $request->request_id }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $request->department }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $request->branch }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">{{ $request->user->name ?? 'Unknown' }}</p>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        @if(isset($request->stockItems) && $request->stockItems->count() > 0)
                                                            Multiple Items ({{ $request->stockItems->count() }})
                                                        @else
                                                            {{ $request->product_name ?? 'N/A' }}
                                                        @endif
                                                    </p>
                                                </td>
                                                <td class="ps-4">
                                                    <span class="badge bg-success">Approved</span>
                                                </td>
                                                <td class="ps-4">
                                                    @php
                                                        $issuanceStatus = 'Pending Issuance';
                                                        $badgeColor = 'bg-warning';
                                                        
                                                        // Check if any transactions exist for this request's items
                                                        $issuedItems = 0;
                                                        $totalItems = isset($request->stockItems) ? $request->stockItems->count() : 0;
                                                        
                                                        if(isset($request->stockItems) && $totalItems > 0) {
                                                            foreach($request->stockItems as $item) {
                                                                // Check if a supply transaction exists for this item
                                                                $supplyItem = \App\Models\SuppliesInventory::where('product_name', $item->product_name)
                                                                    ->where('unit_type', $item->category)
                                                                    ->first();
                                                                    
                                                                if($supplyItem) {
                                                                    $transaction = \App\Models\SupplyTransaction::where('supply_id', $supplyItem->id)
                                                                        ->where('reference_number', 'like', 'REQ-'.$request->request_id.'%')
                                                                        ->first();
                                                                        
                                                                    if($transaction) {
                                                                        $issuedItems++;
                                                                    }
                                                                }
                                                            }
                                                            
                                                            if($issuedItems == $totalItems) {
                                                                $issuanceStatus = 'Issued';
                                                                $badgeColor = 'bg-success';
                                                            } elseif($issuedItems > 0) {
                                                                $issuanceStatus = 'Partially Issued ('.$issuedItems.'/'.$totalItems.')';
                                                                $badgeColor = 'bg-info';
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badgeColor }}">{{ $issuanceStatus }}</span>
                                                </td>
                                                <td class="ps-4">
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ $request->created_at ? $request->created_at->format('M d, Y') : 'Unknown' }}
                                                    </p>
                                        </td>
                                                <td class="ps-4">
                                                    @if(isset($request->stockItems) && $request->stockItems->count() > 0)
                                                        <button class="btn btn-sm text-white details-btn" 
                                                                style="background-color: #8B0000;"
                                                                data-bs-toggle="collapse" 
                                                                data-bs-target="#details-{{ $request->request_id }}" 
                                                                aria-expanded="false" 
                                                                aria-controls="details-{{ $request->request_id }}">
                                                            <i class="fas fa-chevron-down me-1"></i> Details
                                            </button>
                                            @if($issuanceStatus == 'Pending Issuance' || $issuanceStatus == 'Partially Issued ('.$issuedItems.'/'.$totalItems.')')
                                                            <a href="{{ route('admin.requests.issue', ['requestId' => $request->request_id]) }}" 
                                                               class="btn btn-sm text-white" 
                                                               style="background-color: #004d00;">
                                                                <i class="fas fa-check-circle me-1"></i> Issue Items
                                                            </a>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-sm text-white" style="background-color: #8B0000;" disabled>View</button>
                                            @endif
                                        </td>
                                    </tr>
                                            @if(isset($request->stockItems) && $request->stockItems->count() > 0)
                                            <tr>
                                                <td colspan="8" class="p-0 border-0">
                                                    <div class="collapse" id="details-{{ $request->request_id }}">
                                                        <div class="p-3 bg-light">
                                                            <table class="table table-sm mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Type</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Issuance Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($request->stockItems as $item)
                                                                    <tr>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->product_name }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->quantity }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->category }}</p></td>
                                                                        <td class="ps-4">
                                                                            @php
                                                                                $itemIssuanceStatus = 'Pending';
                                                                                $itemBadgeColor = 'bg-warning';
                                                                                
                                                                                // Check if this specific item has been issued
                                                                                $supplyItem = \App\Models\SuppliesInventory::where('product_name', $item->product_name)
                                                                                    ->where('unit_type', $item->category)
                                                                                    ->first();
                                                                                    
                                                                                if($supplyItem) {
                                                                                    $transaction = \App\Models\SupplyTransaction::where('supply_id', $supplyItem->id)
                                                                                        ->where('reference_number', 'like', 'REQ-'.$request->request_id.'%')
                                                                                        ->first();
                                                                                        
                                                                                    if($transaction) {
                                                                                        $itemIssuanceStatus = 'Issued on ' . $transaction->created_at->format('M d, Y');
                                                                                        $itemBadgeColor = 'bg-success';
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            <span class="badge {{ $itemBadgeColor }}">{{ $itemIssuanceStatus }}</span>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pending Requests Tab -->
                            <div class="tab-pane fade" id="pending" role="tabpanel">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Branch</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requested By</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($requests->where('status', 'pending') as $request)
                                            <tr class="request-row" data-request-id="{{ $request->request_id }}">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <button class="btn btn-link p-0 me-2 toggle-details" 
                                                                style="color: #8B0000;" 
                                                                type="button" 
                                                                data-bs-toggle="collapse" 
                                                                data-bs-target="#details-{{ $request->request_id }}" 
                                                                aria-expanded="false" 
                                                                aria-controls="details-{{ $request->request_id }}">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </button>
                                                        <div>
                                                            <p class="text-xs font-weight-bold mb-0">{{ $request->request_id }}</p>
                                                            @if($request->stockItems->count() > 1)
                                                                <span class="badge" style="background-color: #8B0000;">{{ $request->stockItems->count() }} items</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $request->department }}</p></td>
                                                <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $request->branch }}</p></td>
                                                <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $request->user->name }}</p></td>
                                                <td class="ps-4"><span class="badge badge-sm bg-warning">Pending</span></td>
                                                <td class="ps-4"><p class="text-xs text-secondary mb-0">{{ $request->created_at->format('M d, Y') }}</p></td>
                                                <td class="ps-4">
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $request->request_id }}">
                                                        Approve
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->request_id }}">
                                                        Reject
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="p-0 border-0">
                                                    <div class="collapse" id="details-{{ $request->request_id }}">
                                                        <div class="p-3 bg-light">
                                                            <table class="table table-sm mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Type</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse($request->stockItems as $item)
                                                                    <tr>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->product_name }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->quantity }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->category }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">₱{{ number_format($item->price ?? 0, 2) }}</p></td>
                                                                    </tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">No items found for this request.</td>
                                                                    </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @include('Admin.partials.approve-modal', ['request' => $request])
                                            @include('Admin.partials.reject-modal', ['request' => $request])
                                            @endforeach
                                        </tbody>
                                    </table>
                                        </div>
                                    </div>

                            <!-- Rejected Requests Tab -->
                            <div class="tab-pane fade" id="rejected" role="tabpanel">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Branch</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requested By</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($requests->where('status', 'rejected') as $request)
                                            <tr class="request-row" data-request-id="{{ $request->request_id }}">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <button class="btn btn-link p-0 me-2 toggle-details" 
                                                                style="color: #8B0000;" 
                                                                type="button" 
                                                                data-bs-toggle="collapse" 
                                                                data-bs-target="#details-{{ $request->request_id }}" 
                                                                aria-expanded="false" 
                                                                aria-controls="details-{{ $request->request_id }}">
                                                            <i class="fas fa-chevron-down"></i>
                                                        </button>
                                                        <div>
                                                            <p class="text-xs font-weight-bold mb-0">{{ $request->request_id }}</p>
                                                            @if($request->stockItems->count() > 1)
                                                                <span class="badge" style="background-color: #8B0000;">{{ $request->stockItems->count() }} items</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $request->department }}</p></td>
                                                <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $request->branch }}</p></td>
                                                <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $request->user->name }}</p></td>
                                                <td class="ps-4"><span class="badge badge-sm bg-danger">Rejected</span></td>
                                                <td class="ps-4"><p class="text-xs text-secondary mb-0">{{ $request->created_at->format('M d, Y') }}</p></td>
                                                <td class="ps-4">
                                                    <button class="btn btn-secondary btn-sm" disabled>Processed</button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="p-0 border-0">
                                                    <div class="collapse" id="details-{{ $request->request_id }}">
                                                        <div class="p-3 bg-light">
                                                            <table class="table table-sm mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Type</th>
                                                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Price</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse($request->stockItems as $item)
                                                                    <tr>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->product_name }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->quantity }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->category }}</p></td>
                                                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0">₱{{ number_format($item->price ?? 0, 2) }}</p></td>
                                                                    </tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">No items found for this request.</td>
                                                                    </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="container">
        <div class="alert alert-danger">
            Unauthorized access.
        </div>
    </div>
@endif

<style>
.nav-pills {
    background: #f8f9fa;
    border-radius: 0.5rem;
}

.nav-pills .nav-link {
    color: #344767;
    font-weight: 500;
    font-size: 14px;
    padding: 10px 20px;
    border-radius: 0.5rem;
}

.nav-pills .nav-link.active {
    color: #fff;
    background: linear-gradient(310deg, #c41e3a, #a01830);
    box-shadow: 0 4px 6px rgba(196, 30, 58, 0.15);
}

.nav-pills .nav-link:not(.active):hover {
    color: #c41e3a;
    background-color: rgba(196, 30, 58, 0.1);
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 500;
    border-radius: 30px;
}

.table thead th {
    padding: 12px 16px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    border-bottom: 1px solid #e9ecef;
}

.table tbody td {
    padding: 12px 16px;
    border-bottom: 1px solid #e9ecef;
}

.text-xs {
    font-size: 0.75rem !important;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
}

.toggle-details {
    padding: 0;
    background: none;
    border: none;
    cursor: pointer;
    color: #344767;
}

.toggle-details i {
    transition: transform 0.2s ease;
    display: inline-block;
}

.toggle-details[aria-expanded="true"] i {
    transform: rotate(180deg);
}

.collapse {
    transition: all 0.2s ease;
}

.item-details {
    background-color: #f8f9fa;
}

.item-details td {
    border: none;
}

.bg-light {
    background-color: #f8f9fa !important;
    border-radius: 0.5rem;
}

.table-sm td, .table-sm th {
    padding: 0.5rem;
}

.toggle-details {
    transition: all 0.3s ease;
}

.toggle-details:hover {
    background-color: #6a0e28 !important;
    transform: translateY(-1px);
}

.toggle-details:active {
    transform: translateY(0);
}

.toggle-icon {
    transition: transform 0.3s ease;
}

.toggle-details[aria-expanded="true"] .toggle-icon {
    transform: rotate(180deg);
}

.btn-link.toggle-details:hover {
    color: #6a0e28 !important;
    opacity: 0.9;
}

.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    opacity: 0.9;
}

.details-btn {
    padding: 0.4rem 1rem;
    font-weight: 500;
    letter-spacing: 0.02em;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(139, 0, 0, 0.2);
}

.details-btn:hover {
    background-color: #6a0e28 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(139, 0, 0, 0.3);
}

.details-btn:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(139, 0, 0, 0.2);
}

.details-btn i {
    transition: transform 0.3s ease;
}

.details-btn[aria-expanded="true"] i {
    transform: rotate(180deg);
}

.collapse {
    transition: all 0.3s ease;
}

.bg-light {
    background-color: #f8f9fa !important;
    border-radius: 0.5rem;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    margin: 0.5rem 0;
}

.table-sm {
    margin: 0;
}

.table-sm thead th {
    background-color: rgba(139, 0, 0, 0.05);
    color: #8B0000;
    font-weight: 600;
    padding: 0.75rem 1rem;
}

.table-sm tbody td {
    padding: 0.75rem 1rem;
    vertical-align: middle;
}

.table-sm tbody tr:hover {
    background-color: rgba(139, 0, 0, 0.02);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listen for Bootstrap's collapse events
    document.querySelectorAll('.collapse').forEach(collapse => {
        collapse.addEventListener('show.bs.collapse', function() {
            const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
            if (button) {
                button.setAttribute('aria-expanded', 'true');
            }
        });

        collapse.addEventListener('hide.bs.collapse', function() {
            const button = document.querySelector(`[data-bs-target="#${this.id}"]`);
            if (button) {
                button.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Reset collapse state when switching tabs
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            document.querySelectorAll('.collapse.show').forEach(collapse => {
                bootstrap.Collapse.getInstance(collapse).hide();
            });
        });
    });
});
</script>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle toggle details buttons
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const icon = this.querySelector('.toggle-icon');
            
            // Toggle the rotation class based on aria-expanded state
            if (this.getAttribute('aria-expanded') === 'true') {
                icon.classList.remove('fa-rotate-180');
            } else {
                icon.classList.add('fa-rotate-180');
            }
        });
    });
    
    // Handle tab changes to reset toggles
    const tabLinks = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabLinks.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            // Reset all toggle icons when switching tabs
            document.querySelectorAll('.toggle-details').forEach(btn => {
                const icon = btn.querySelector('.toggle-icon');
                icon.classList.remove('fa-rotate-180');
                
                // Close any open collapses
                const target = document.querySelector(btn.getAttribute('data-bs-target'));
                if (target && target.classList.contains('show')) {
                    new bootstrap.Collapse(target).hide();
                }
            });
        });
    });
    
    // Form submission handling for status updates
    const statusForms = document.querySelectorAll('.status-form');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('select[name="status"]').value;
            const remarks = this.querySelector('textarea[name="remarks"]').value;
            
            if (!status) {
                e.preventDefault();
                alert('Please select a status.');
                return false;
            }
            
            if (!remarks) {
                e.preventDefault();
                alert('Please provide remarks.');
                return false;
            }
            
            return true;
        });
    });
});
</script>

<style>
.toggle-icon {
    transition: transform 0.3s ease;
}
.toggle-icon.fa-rotate-180 {
    transform: rotate(180deg);
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.badge {
    font-size: 0.65rem;
    font-weight: 600;
}
</style>
@endsection

@endsection