@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Stock Requests</h6>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="nav-wrapper position-relative end-0 px-4">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist" id="requestTabs">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center" data-bs-toggle="tab" href="#approved" role="tab" aria-selected="true">
                                        Approved <span class="badge bg-success rounded-pill ms-2">{{ $requests->where('status', 'approved')->count() }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" data-bs-toggle="tab" href="#pending" role="tab" aria-selected="false">
                                        Pending <span class="badge bg-warning rounded-pill ms-2">{{ $requests->where('status', 'pending')->count() }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center" data-bs-toggle="tab" href="#rejected" role="tab" aria-selected="false">
                                        Rejected <span class="badge bg-danger rounded-pill ms-2">{{ $requests->where('status', 'rejected')->count() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="ms-auto px-4">
                            <button id="printApproved" class="btn btn-sm print-btn maroon-btn" onclick="printTable('approved')" style="display: inline-flex;">
                                <i class="fas fa-print me-2"></i>PRINT APPROVED
                            </button>
                            <button id="printPending" class="btn btn-sm print-btn maroon-btn" onclick="printTable('pending')" style="display: none;">
                                <i class="fas fa-print me-2"></i>PRINT PENDING
                            </button>
                            <button id="printRejected" class="btn btn-sm print-btn maroon-btn" onclick="printTable('rejected')" style="display: none;">
                                <i class="fas fa-print me-2"></i>PRINT REJECTED
                            </button>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="tab-content" id="requestTabsContent">
                        <!-- Approved Requests -->
                        <div class="tab-pane fade show active" id="approved" role="tabpanel">
                            <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requested By</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Offices</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
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
                                        <p class="text-xs font-weight-bold mb-0">{{ $request->user->name }}</p>
                                    </td>
                                    <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    @if($request->items->count() > 1)
                                                        Multiple Items ({{ $request->items->count() }})
                                                    @else
                                                        {{ $request->items->first()->product_name ?? 'N/A' }}
                                                    @endif
                                                </p>
                                    </td>
                                    <td class="ps-4">
                                        <p class="text-xs text-secondary mb-0">{{ $request->department }}</p>
                                    </td>
                                    <td class="ps-4">
                                        <p class="text-xs text-secondary mb-0">{{ $request->branch }}</p>
                                    </td>
                                    <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($request->items->count() > 1)
                                                        Multiple
                                                    @else
                                                        {{ $request->items->first()->quantity ?? '0' }}
                                                    @endif
                                                </p>
                                    </td>
                                    <td class="ps-4">
                                            <span class="badge bg-success">Approved</span>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <button class="btn btn-sm cyan-btn toggle-details" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#details-{{ $request->request_id }}" 
                                                        aria-expanded="false" 
                                                        aria-controls="details-{{ $request->request_id }}">
                                                    <i class="fas fa-chevron-down toggle-icon"></i> 
                                                    @if($request->items->count() > 1) Details @else View @endif
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="p-0 border-0">
                                                <div class="collapse" id="details-{{ $request->request_id }}">
                                                    <div class="p-3 bg-light">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Type</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($request->items as $item)
                                                                <tr>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->product_name }}</p></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->quantity }}</p></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->category }}</p></td>
                                                                    <td class="ps-4"><span class="badge bg-success">Approved</span></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->created_at->format('M d, Y') }}</p></td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No items found for this request.</td>
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

                        <!-- Pending Requests -->
                        <div class="tab-pane fade" id="pending" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requested By</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Offices</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests->where('status', 'pending') as $request)
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->request_id }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->user->name }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    @if($request->items->count() > 1)
                                                        Multiple Items ({{ $request->items->count() }})
                                                    @else
                                                        {{ $request->items->first()->product_name ?? 'N/A' }}
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">{{ $request->department }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">{{ $request->branch }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($request->items->count() > 1)
                                                        Multiple
                                        @else
                                                        {{ $request->items->first()->quantity ?? '0' }}
                                        @endif
                                                </p>
                                            </td>
                                            <td class="ps-4">
                                                <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td class="ps-4">
                                        <p class="text-xs text-secondary mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                                    </td>
                                    <td class="ps-4">
                                                <button class="btn btn-sm cyan-btn toggle-details" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#details-pending-{{ $request->request_id }}" 
                                                        aria-expanded="false" 
                                                        aria-controls="details-pending-{{ $request->request_id }}">
                                                    <i class="fas fa-chevron-down toggle-icon"></i> 
                                                    @if($request->items->count() > 1) Details @else View @endif
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="p-0 border-0">
                                                <div class="collapse" id="details-pending-{{ $request->request_id }}">
                                                    <div class="p-3 bg-light">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Type</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($request->items as $item)
                                                                <tr>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->product_name }}</p></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->quantity }}</p></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->category }}</p></td>
                                                                    <td class="ps-4"><span class="badge bg-warning">Pending</span></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->created_at->format('M d, Y H:i:s') }}</p></td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No items found for this request.</td>
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

                        <!-- Rejected Requests -->
                        <div class="tab-pane fade" id="rejected" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requested By</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Offices</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests->where('status', 'rejected') as $request)
                                        <tr>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->request_id }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ $request->user->name }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    @if($request->items->count() > 1)
                                                        Multiple Items ({{ $request->items->count() }})
                                                    @else
                                                        {{ $request->items->first()->product_name ?? 'N/A' }}
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">{{ $request->department }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">{{ $request->branch }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($request->items->count() > 1)
                                                        Multiple
                                        @else
                                                        {{ $request->items->first()->quantity ?? '0' }}
                                        @endif
                                                </p>
                                            </td>
                                            <td class="ps-4">
                                                <span class="badge bg-danger">Rejected</span>
                                            </td>
                                            <td class="ps-4">
                                                <p class="text-xs text-secondary mb-0">{{ $request->created_at->format('M d, Y') }}</p>
                                            </td>
                                            <td class="ps-4">
                                                <button class="btn btn-sm cyan-btn toggle-details" 
                                                        data-bs-toggle="collapse" 
                                                        data-bs-target="#details-rejected-{{ $request->request_id }}" 
                                                        aria-expanded="false" 
                                                        aria-controls="details-rejected-{{ $request->request_id }}">
                                                    <i class="fas fa-chevron-down toggle-icon"></i> 
                                                    @if($request->items->count() > 1) Details @else View @endif
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9" class="p-0 border-0">
                                                <div class="collapse" id="details-rejected-{{ $request->request_id }}">
                                                    <div class="p-3 bg-light">
                                                        <table class="table table-sm mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product Name</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Quantity</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Unit Type</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Request Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse($request->items as $item)
                                                                <tr>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->product_name }}</p></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->quantity }}</p></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->category }}</p></td>
                                                                    <td class="ps-4"><span class="badge bg-danger">Rejected</span></td>
                                                                    <td class="ps-4"><p class="text-xs font-weight-bold mb-0">{{ $item->created_at->format('M d, Y') }}</p></td>
                                                                </tr>
                                                                @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No items found for this request.</td>
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
    </div>
</div>

<!-- Print-specific styles -->
<style>
    @media print {
        /* Hide everything except the table being printed */
        body * {
            visibility: hidden;
        }
        .print-buttons,
        .nav-tabs,
        .card-header,
        .btn,
        .actions-column {
            display: none !important;
        }
        
        /* Show only the selected table */
        #printSection,
        #printSection * {
            visibility: visible;
        }
        
        /* Full width for print */
        #printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        /* Ensure table styling for print */
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        /* Add header for print */
        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-header h2 {
            margin: 0;
            font-size: 18px;
        }

        .print-header p {
            margin: 5px 0;
            font-size: 14px;
        }

        /* Hide specific columns for print */
        .actions-column {
            display: none;
        }
    }

    /* Non-print styles */
    .print-buttons {
        display: flex;
        gap: 10px;
    }

    .print-buttons .btn {
        display: flex;
        align-items: center;
    }

    /* Button styles with high specificity */
    .btn.btn-primary, 
    .btn.print-btn.maroon-btn {
        background-color: #821131 !important;
        border-color: #821131 !important;
        color: #fff !important;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        box-shadow: 0 4px 6px rgba(130, 17, 49, 0.15);
        transition: all 0.2s ease;
    }

    .btn.btn-primary:hover, 
    .btn.print-btn.maroon-btn:hover {
        background-color: #6e0f29 !important;
        border-color: #6e0f29 !important;
    }

    .btn.btn-primary:active, 
    .btn.print-btn.maroon-btn:active {
        transform: translateY(1px);
        box-shadow: 0 2px 4px rgba(130, 17, 49, 0.15);
    }

    .btn.btn-sm.cyan-btn,
    .btn.btn-sm.toggle-details {
        background-color: #821131 !important;
        border-color: #821131 !important;
        color: #fff !important;
    }
</style>

<!-- Print functionality script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle tab changes to update print button visibility
        const requestTabs = document.querySelectorAll('#requestTabs .nav-link');
        const printButtons = document.querySelectorAll('.print-btn');
        
        requestTabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(event) {
                const targetId = event.target.getAttribute('href').substring(1);
                
                // Hide all print buttons
                printButtons.forEach(btn => btn.style.display = 'none');
                
                // Show the relevant print button
                const printBtn = document.getElementById('print' + targetId.charAt(0).toUpperCase() + targetId.slice(1));
                if (printBtn) {
                    printBtn.style.display = 'inline-flex';
                }
                
                // Reset all detail toggles when switching tabs
                document.querySelectorAll('.toggle-details').forEach(btn => {
                    const target = document.querySelector(btn.getAttribute('data-bs-target'));
                    if (target && target.classList.contains('show')) {
                        btn.click();
                    }
                });
            });
        });
        
        // Handle toggle details button
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('.toggle-icon');
                
                // Toggle the rotation class
                if (this.getAttribute('aria-expanded') === 'true') {
                    icon.classList.remove('fa-rotate-180');
                } else {
                    icon.classList.add('fa-rotate-180');
                }
            });
        });
    });

    // Print function
    function printTable(tabId) {
        const printContents = document.getElementById(tabId).innerHTML;
        const originalContents = document.body.innerHTML;
        
        document.body.innerHTML = `
            <div class="container mt-4">
                <h2 class="text-center mb-4">Stock Requests - ${tabId.charAt(0).toUpperCase() + tabId.slice(1)}</h2>
                ${printContents}
            </div>
        `;
        
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
    }
</script>

<style>
.nav-pills {
    background: #f8f9fa;
    border-radius: 0.5rem;
    padding: 0.5rem;
}

.nav-pills .nav-link {
    color: #344767;
    font-weight: 500;
    font-size: 14px;
    padding: 10px 20px;
    border-radius: 0.5rem;
    min-width: 120px;
    text-align: center;
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

.btn-secondary {
    background-color: #6c757d;
    border: none;
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-secondary:hover {
    background-color: #5a6268;
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

.toggle-icon {
    transition: transform 0.3s ease;
}
.toggle-icon.fa-rotate-180 {
    transform: rotate(180deg);
}
</style>
@endsection