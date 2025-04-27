@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Available Stocks</h6>
                        <div class="col-md-5">
                            <div class="department-badge">
                                <span>Department: <strong>{{ Auth::user()->department }}</strong></span>
                                <span class="mx-2">|</span>
                                <span>Branch: <strong>{{ Auth::user()->branch }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">QR CODE</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">ITEM DETAILS</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">UNIT</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">BRANCH</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">STOCK LEVEL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">STATUS</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($stocks->count() > 0)
                                @foreach($stocks as $stock)
                                <tr>
                                    <td class="ps-4 text-center">
                                        <!-- Clickable QR Code -->
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#qrModal{{ $stock->id }}">
                                            {!! $stock->qr_code !!}
                                        </a>
                                        
                                        <!-- QR Code Modal -->
                                        <div class="modal fade" id="qrModal{{ $stock->id }}" tabindex="-1" aria-labelledby="qrModalLabel{{ $stock->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="qrModalLabel{{ $stock->id }}">{{ $stock->product_name }} - QR Code</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        {!! QrCode::size(250)->generate(json_encode([
                                                            'id' => $stock->id,
                                                            'name' => $stock->product_name,
                                                            'category' => $stock->category,
                                                            'department' => $stock->department,
                                                            'branch' => $stock->branch,
                                                            'control_number' => $stock->control_number,
                                                            'quantity' => $stock->quantity
                                                        ])) !!}
                                                        <p class="mt-2 mb-0 text-sm text-muted">Control Number: {{ $stock->control_number }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($stock->description)
                                                <!-- Clickable Image -->
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $stock->id }}">
                                                    <img src="{{ asset('storage/' . $stock->description) }}" 
                                                         alt="{{ $stock->product_name }}" 
                                                         class="me-3"
                                                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; cursor: pointer;">
                                                </a>

                                                <!-- Image Modal -->
                                                <div class="modal fade" id="imageModal{{ $stock->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $stock->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="imageModalLabel{{ $stock->id }}">{{ $stock->product_name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-center">
                                                                <img src="{{ asset('storage/' . $stock->description) }}" 
                                                                     alt="{{ $stock->product_name }}" 
                                                                     style="max-width: 100%; max-height: 70vh; height: auto; border-radius: 8px;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-xs font-weight-bold mb-0">{{ $stock->product_name }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $stock->control_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="ps-4 text-center">
                                        <span class="text-secondary text-xs">{{ $stock->category }}</span>
                                    </td>
                                    <td class="ps-4 text-center">
                                        <span class="text-secondary text-xs">{{ $stock->branch }}</span>
                                    </td>
                                    <td class="ps-4 text-center">
                                        <span class="text-secondary text-xs">{{ $stock->quantity }} units</span>
                                    </td>
                                    <td class="ps-4 text-center">
                                        @if($stock->quantity == 0)
                                            <span class="badge" style="background-color: #FF0000;">OUT OF STOCK</span>
                                        @elseif($stock->quantity < 50)
                                            <span class="badge" style="background-color: #ffd700;">LOW STOCK</span>
                                        @else
                                            <span class="badge" style="background-color: #4CAF50;">IN STOCK</span>
                                        @endif
                                    </td>
                                    <td class="ps-4 text-center">
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#returnModal{{ $stock->id }}">
                                            Return Damaged
                                        </button>
                                    </td>
                                </tr>
                                 <!-- Return Modal for each stock item -->
                                <div class="modal fade" id="returnModal{{ $stock->id }}" tabindex="-1" aria-labelledby="returnModalLabel{{ $stock->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="returnModalLabel{{ $stock->id }}">Return Damaged Item - {{ $stock->product_name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('returns.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="item_id" value="{{ $stock->id }}">
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="qty" class="form-label">Quantity to Return</label>
                                                        <input type="number" class="form-control" id="qty" name="qty" min="1" max="{{ $stock->quantity }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="reason" class="form-label">Reason for Return</label>
                                                        <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Submit Return</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <p class="text-secondary mb-0">No stocks available for your department and branch.</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.modal-header {
    border-bottom: none;
    padding: 1.5rem 1.5rem 1rem;
}

.modal-body {
    padding: 1rem 1.5rem 1.5rem;
}

.modal-footer {
    border-top: none;
    padding: 0 1.5rem 1.5rem;
    justify-content: center;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 0.5rem 2rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Hover effects */
td img:hover, td svg:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
    cursor: pointer;
}

/* QR Code styling */
td svg {
    width: 50px;
    height: 50px;
    transition: transform 0.3s ease;
}

/* Modal QR Code sizing */
.modal svg {
    width: 250px;
    height: 250px;
}

/* New style for department badge */
.department-badge {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    color: #344767;
    border: 1px solid #e9ecef;
    display: flex;
    align-items: center;
}

.department-badge strong {
    color: #821131;
}
</style>