@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add Supply Quantity</h6>
                    <p class="text-sm">Increase quantity for: <strong>{{ $supply->product_name }}</strong></p>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Current Supply Information</h6>
                                    <div class="d-flex align-items-center mb-3">
                                        @if($supply->product_image)
                                            <img src="{{ asset('storage/' . $supply->product_image) }}" 
                                                 alt="{{ $supply->product_name }}" 
                                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-right: 20px;">
                                        @else
                                            <div style="width: 80px; height: 80px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-right: 20px;">
                                                <i class="fas fa-box fa-2x text-secondary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="mb-1"><strong>Control Code:</strong> {{ $supply->control_code }}</p>
                                            <p class="mb-1"><strong>Current Quantity:</strong> {{ $supply->quantity }} {{ $supply->unit_type }}</p>
                                            <p class="mb-0">
                                                <strong>Status:</strong>
                                                @if($supply->quantity == 0)
                                                    <span class="badge" style="background-color: #FF0000;">Out of Stock</span>
                                                @elseif($supply->quantity <= 50)
                                                    <span class="badge" style="background-color: #ffd700;">Low Stock</span>
                                                @else
                                                    <span class="badge bg-success">In Stock</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.supplies.update', $supply->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="quantity" class="form-control-label">Additional Quantity to Add</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                                         <span class="input-group-text bg-primary" style="background-color:rgb(141, 66, 87) !important; color: white; cursor: pointer;" id="decrement-btn">
                                            <i class="fas fa-minus"></i>
                                        </span>
                                        <span class="input-group-text bg-primary" style="background-color: #821131 !important; color: white; cursor: pointer;" id="increment-btn">
                                            <i class="fas fa-plus"></i>
                                        </span>
                                       
                                    </div>
                                    <small class="form-text text-muted">Enter the number of units you want to add to the current quantity ({{ $supply->quantity }}).</small>
                                    @error('quantity')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.inventory') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Supply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background-color: #821131;
        border-color: #821131;
    }
    .btn-primary:hover {
        background-color: #6a0e28;
        border-color: #6a0e28;
    }
    .badge {
        padding: 0.4em 0.6em;
        font-size: 0.75em;
    }
    
    /* Hide spinner buttons for number input */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* For Firefox */
    input[type="number"] {
        -moz-appearance: textfield;
    }
    
    /* Style for plus button */
    .input-group-text {
        transition: all 0.2s ease;
    }
    
    .input-group-text:hover {
        background-color: #6a0e28 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide the default spinner buttons
    document.querySelector('input[type=number]').style.appearance = 'textfield';
    
    // Add click handler for the plus button
    document.getElementById('increment-btn').addEventListener('click', function() {
        const input = document.getElementById('quantity');
        input.value = parseInt(input.value || 0) + 1;
    });
    
    // Add click handler for the minus button
    document.getElementById('decrement-btn').addEventListener('click', function() {
        const input = document.getElementById('quantity');
        const currentValue = parseInt(input.value || 0);
        // Ensure the value doesn't go below 1 (as specified in the min attribute)
        if (currentValue > 1) {
            input.value = currentValue - 1;
        }
    });
});
</script>
@endsection 