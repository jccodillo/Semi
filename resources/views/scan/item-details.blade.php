@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">ITEM DETAILS</h5>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Image -->
                        @if($stock->description)
                        <div class="col-md-6 mb-4">
                            <div class="border rounded p-3">
                                <h6 class="text-uppercase text-secondary mb-3">Product Image</h6>
                                <img src="{{ asset('storage/' . $stock->description) }}" 
                                     alt="{{ $stock->product_name }}"
                                     class="img-fluid rounded"
                                     style="max-height: 300px; width: auto;">
                            </div>
                        </div>
                        @endif

                        <!-- Product Information -->
                        <div class="col-md-6">
                            <div class="border rounded p-4">
                                <h6 class="text-uppercase text-secondary mb-3">Product Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Product Name:</span><br>
                                            {{ $stock->product_name }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Control Number:</span><br>
                                            {{ $stock->control_number }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Category:</span><br>
                                            {{ $stock->category }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Department:</span><br>
                                            {{ $stock->department }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Branch:</span><br>
                                            {{ $stock->branch }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Price:</span><br>
                                            ₱{{ number_format($stock->price, 2) }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Quantity:</span><br>
                                            {{ $stock->quantity }} units
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-sm mb-1">
                                            <span class="text-dark font-weight-bold">Status:</span><br>
                                            @if($stock->quantity == 0)
                                                <span class="badge bg-danger">OUT OF STOCK</span>
                                            @elseif($stock->quantity < 50)
                                                <span class="badge" style="background-color: #ffd700;">LOW STOCK</span>
                                            @else
                                                <span class="badge bg-success">IN STOCK</span>
                                            @endif
                                        </p>
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
@endsection 