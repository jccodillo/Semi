@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>ADD NEW PROCUREMENT</h6>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <form action="{{ route('admin.procurement.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="iar_no">IAR No.</label>
                                    <input type="text" class="form-control" id="iar_no" name="iar_no" value="{{ $iarNo }}" readonly>
                                    @error('iar_no')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="supplier">Supplier</label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier') }}" required>
                                    @error('supplier')
                                        <div class="text-danger text-xs">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                            <h6 class="mb-0">ITEMS</h6>
                            <button type="button" id="add-item-btn" class="btn btn-sm btn-success">+ Add Item</button>
                        </div>
                        
                        <div id="items-container" class="mb-4">
                            <div class="item bg-light p-3 mb-3 rounded">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-control-label">Stock No.</label>
                                            <input type="text" class="form-control form-control-sm" name="items[0][stock_no]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-control-label">Product Name</label>
                                            <input type="text" class="form-control form-control-sm" name="items[0][product_name]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-control-label">Quantity</label>
                                            <input type="number" min="1" class="form-control form-control-sm quantity-input" name="items[0][quantity]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-control-label">Unit Type</label>
                                            <select class="form-control form-control-sm" name="items[0][unit_type]" required>
                                                <option value="">Select Unit</option>
                                                @foreach($units as $unit)
                                                    <option value="{{ $unit }}">{{ $unit }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-control-label">Price Per Unit</label>
                                            <input type="number" min="0.01" step="0.01" class="form-control form-control-sm price-input" name="items[0][price_per_unit]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end pb-2">
                                        <button type="button" class="btn btn-sm btn-danger remove-item-btn" style="display: none;"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label class="form-control-label">Description</label>
                                            <textarea class="form-control form-control-sm" name="items[0][description]" rows="2" placeholder="Enter item description (Optional)"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-11 text-end">
                                        <div class="text-xs font-weight-bold total-amount">Total: ₱0.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 text-end">
                                <div class="h6 mb-3" id="grand-total">Grand Total: ₱0.00</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="background-color: #821131;">Submit Procurement</button>
                                <a href="{{ route('admin.procurement.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsContainer = document.getElementById('items-container');
        const addItemBtn = document.getElementById('add-item-btn');
        
        // Calculate totals initially
        calculateTotals();
        
        // Handle adding a new item
        addItemBtn.addEventListener('click', function() {
            const items = itemsContainer.querySelectorAll('.item');
            const newIndex = items.length;
            
            // Clone the first item template
            const newItem = items[0].cloneNode(true);
            
            // Update input names with the new index
            newItem.querySelectorAll('input, select, textarea').forEach(element => {
                const name = element.name;
                element.name = name.replace(/\[\d+\]/, `[${newIndex}]`);
                
                // Clear values
                if (element.type !== 'button') {
                    element.value = '';
                }
                
                // Clear textarea content
                if (element.tagName === 'TEXTAREA') {
                    element.textContent = '';
                }
            });
            
            // Show the remove button
            const removeBtn = newItem.querySelector('.remove-item-btn');
            removeBtn.style.display = 'block';
            
            // Add event listeners
            const quantityInput = newItem.querySelector('.quantity-input');
            const priceInput = newItem.querySelector('.price-input');
            
            quantityInput.addEventListener('input', calculateTotals);
            priceInput.addEventListener('input', calculateTotals);
            
            removeBtn.addEventListener('click', function() {
                newItem.remove();
                calculateTotals();
            });
            
            // Add to container
            itemsContainer.appendChild(newItem);
            
            // Focus on the first input of the new item
            newItem.querySelector('input').focus();
        });
        
        // Handle calculation
        function calculateTotals() {
            let grandTotal = 0;
            
            // Calculate each item's total
            document.querySelectorAll('.item').forEach(item => {
                const quantityInput = item.querySelector('.quantity-input');
                const priceInput = item.querySelector('.price-input');
                const totalDisplay = item.querySelector('.total-amount');
                
                const quantity = parseFloat(quantityInput.value) || 0;
                const price = parseFloat(priceInput.value) || 0;
                const total = quantity * price;
                
                totalDisplay.textContent = `Total: ₱${total.toFixed(2)}`;
                grandTotal += total;
            });
            
            // Update grand total
            document.getElementById('grand-total').textContent = `Grand Total: ₱${grandTotal.toFixed(2)}`;
        }
        
        // Add event listeners to initial item's inputs
        document.querySelectorAll('.quantity-input, .price-input').forEach(input => {
            input.addEventListener('input', calculateTotals);
        });
    });
</script>
@endpush
@endsection 