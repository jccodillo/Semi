@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0 px-3">
            <h6 class="mb-0">Create Request</h6>
        </div>
        <div class="card-body pt-4 p-3">
            <form action="{{ route('user.requests.store') }}" method="POST" role="form text-left" id="requestForm">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ Auth::user()->department }}" 
                                   readonly
                                   id="department_display">
                            <input type="hidden" 
                                   name="department" 
                                   id="department"
                                   value="{{ Auth::user()->department }}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="branch">Branch</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ Auth::user()->branch }}" 
                                   readonly
                                   id="branch_display">
                            <input type="hidden" 
                                   name="branch" 
                                   id="branch"
                                   value="{{ Auth::user()->branch }}"
                                   required>
                        </div>
                    </div>
                </div>

                <div id="items-container">
                    <div class="item-entry border rounded p-3 mb-3">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group mb-0">
                                    <label class="form-control-label small mb-1">Product Name</label>
                                    <select class="form-control form-control-sm product-select" name="items[0][product_name]" required>
                                        <option value="">Select Product</option>
                                        @foreach($supplies as $supply)
                                            <option value="{{ $supply->product_name }}" 
                                                data-quantity="{{ $supply->quantity }}"
                                                data-unit-type="{{ $supply->unit_type }}"
                                                class="{{ $supply->quantity < 10 ? 'text-danger' : '' }}">
                                                {{ $supply->product_name }} 
                                                @if($supply->quantity < 10)
                                                    (Low Stock - {{ $supply->quantity }} left)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-control-label small mb-1">Quantity</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-outline-secondary quantity-decrease" style="border-color: #dee2e6;"><i class="fas fa-minus"></i></button>
                                        </div>
                                        <input type="number" class="form-control form-control-sm quantity-input text-center" name="items[0][quantity]" required min="1">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary quantity-increase" style="border-color: #dee2e6;"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <small class="text-danger quantity-error" style="display: none;">Not enough stock available</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-0">
                                    <label class="form-control-label small mb-1">Unit type</label>
                                    <select class="form-control form-control-sm bg-light" name="items[0][category]" required disabled>
                                        <option value="">Select Unit</option>
                                        <option value="Box">Box</option>
                                        <option value="Piece">Piece</option>
                                        <option value="Pack">Pack</option>
                                        <option value="Ream">Ream</option>
                                        <option value="Roll">Roll</option>
                                        <option value="Botle">Botle</option>
                                        <option value="Cartridges">Cartridges</option>
                                        <option value="Gallon">Gallon</option>
                                        <option value="Litre">Litre</option>
                                        <option value="Meter">Meter</option>
                                        <option value="Pound">Pound</option>
                                        <option value="Sheet">Sheet</option>
                                    </select>
                                    <input type="hidden" name="items[0][category]" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group mb-0">
                                    <label class="form-control-label small mb-1 invisible">Action</label>
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-item" style="display: none; background-color: #821131; border-color: #821131;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <input type="hidden" class="form-control" name="items[0][price]" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="button" class="btn btn-sm" id="add-item" style="background-color: #821131 !important; border-color: #821131 !important; color: white !important;">Add Another Item</button>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-md mt-4 mb-4 me-2" id="print-request-btn" style="background-color: #2d3748; color: #fff;">Print Request</button>
                    <button type="submit" class="btn btn-md mt-4 mb-4" style="background-color: #821131; color: #fff;">Create Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Printable Form Template (Hidden) -->
<div id="printable-form" style="display: none;">
    <div class="print-container" style="width: 8.5in; margin: 0 auto; font-family: Arial, sans-serif; display: flex; flex-direction: column; min-height: 10in;">
        <!-- Header Section -->
        <div style="margin-bottom: 15px;">
            <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
                <tr>
                    <td style="width: 20%; border: 1px solid black; padding: 5px; vertical-align: middle; text-align: center;" rowspan="4">
                        <img src="{{ asset('assets/img/TUP.logo.png') }}" alt="TUP Logo" style="height: 80px;">
                    </td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center;" colspan="1" rowspan="2">
                        <div style="font-weight: bold; font-size: 14px;">TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</div>
                        <div style="font-size: 10px; margin-top: 3px;">
                            Ayala Blvd., Ermita, Manila, 1000, Philippines | Tel No. +632-5301-3001 local 124<br>
                            Fax No. +632-8521-4063 | Email: supply@tup.edu.ph | Website: www.tup.edu.ph
                        </div>
                    </td>
                    <td style="width: 12%; border: 1px solid black; padding: 3px; font-size: 11px; vertical-align: middle;">Index No.</td>
                    <td style="width: 15%; border: 1px solid black; padding: 3px; font-size: 11px; color: #c41e3a; vertical-align: middle; text-align: left; padding-left: 10px;">TUPM-F-SUP-22-RQS</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 3px; font-size: 11px; vertical-align: middle;">Revision No.</td>
                    <td style="border: 1px solid black; padding: 3px; font-size: 11px; vertical-align: middle; text-align: left; padding-left: 10px;">00</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 5px; text-align: center; font-weight: bold; font-size: 14px;" rowspan="2">
                        REQUEST SLIP OF SUPPLIES AND MATERIALS
                    </td>
                    <td style="border: 1px solid black; padding: 3px; font-size: 11px; vertical-align: middle;">Date</td>
                    <td style="border: 1px solid black; padding: 3px; font-size: 11px; vertical-align: middle; text-align: left; padding-left: 10px;" id="header-date-cell">04222025</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 3px; font-size: 11px; vertical-align: middle;">Page</td>
                    <td style="border: 1px solid black; padding: 3px; font-size: 11px; vertical-align: middle; text-align: left; padding-left: 10px;">1 / 1</td>
                </tr>
            </table>
        </div>
        
        <!-- Info Section -->
        <div style="margin-bottom: 10px;">
            <div style="font-size: 12px;">
                <div><strong>DATE:</strong> <span id="print-date"></span></div>
                <div><strong>OFFICE:</strong> <span id="print-department"></span> - <span id="print-branch"></span></div>
            </div>
        </div>
        
        <!-- Items Table - Takes up available space -->
        <div style="flex-grow: 1;">
            <table id="print-items-table" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <thead>
                    <tr>
                        <th style="border: 1px solid black; padding: 6px; width: 15%; font-size: 12px; text-align: center;">Quantity</th>
                        <th style="border: 1px solid black; padding: 6px; width: 15%; font-size: 12px; text-align: center;">Unit</th>
                        <th style="border: 1px solid black; padding: 6px; width: 70%; font-size: 12px; text-align: center;">Description</th>
                    </tr>
                </thead>
                <tbody id="print-items-body">
                    <!-- Items will be inserted here -->
                </tbody>
            </table>
        </div>
        
        <!-- Footer Section - Always at the bottom -->
        <div style="margin-top: auto; margin-bottom: 20px;">
            <!-- Signature lines -->
            <div style="display: flex; justify-content: space-between; margin-top: 30px; page-break-inside: avoid;">
                <div style="width: 40%;">
                    <div style="text-align: center;">
                        <div style="border-top: 1px solid black; width: 100%;"></div>
                        <div style="margin-top: 5px;">Requested by:</div>
                    </div>
                </div>
                <div style="width: 40%;">
                    <div style="text-align: center;">
                        <div style="border-top: 1px solid black; width: 100%;"></div>
                        <div style="margin-top: 5px;">Issued by:</div>
                    </div>
                </div>
            </div>
            
            <!-- Prepared by section -->
            <div style="margin-top: 30px; margin-bottom: 20px; page-break-inside: avoid;">
                <div style="width: 100%; border: 1px solid black;">
                    <div style="padding: 5px; font-size: 10px;">Prepared by:</div>
                    <div style="border-top: 1px solid black;"></div>
                    <div style="padding: 5px; font-size: 10px;">Signature</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.quantity-error {
    font-size: 0.875em;
    margin-top: 0.25rem;
}

.input-error {
    border-color: #dc3545;
}

.input-error:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Button styles */
.btn-submit {
    background-color: #821131;
    color: #fff;
    border-color: #821131;
}

.btn-submit:hover {
    background-color: #6e0f29;
    border-color: #6e0f29;
}

.btn-remove {
    background-color: #821131;
    border-color: #821131;
}

.btn-remove:hover {
    background-color: #6e0f29;
    border-color: #6e0f29;
}

@media print {
    body * {
        visibility: hidden;
    }
    #printable-form, #printable-form * {
        visibility: visible;
    }
    #printable-form {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        display: block !important;
    }
    
    /* Hide browser's header and footer */
    @page {
        margin: 0;
        size: auto;
    }
    html, body {
        margin: 0;
        padding: 0;
    }
    .print-container {
        padding: 0.5in;
    }
}
</style>
@endsection

@push('scripts')
<script>
// Handle product selection change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const itemEntry = e.target.closest('.item-entry');
        const unitTypeSelect = itemEntry.querySelector('select[name$="[category]"]');
        const unitTypeHidden = itemEntry.querySelector('input[type="hidden"][name$="[category]"]');
        const quantityInput = itemEntry.querySelector('.quantity-input');
        const quantityError = itemEntry.querySelector('.quantity-error');
        
        if (selectedOption.value) {
            const unitType = selectedOption.getAttribute('data-unit-type');
            const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity'));
            
            // Set the unit type in visible select
            Array.from(unitTypeSelect.options).forEach(option => {
                if (option.value.toLowerCase() === unitType.toLowerCase()) {
                    option.selected = true;
                }
            });
            
            // Also set the hidden input value for form submission
            unitTypeHidden.value = unitType;

            // Set max attribute and validate current value
            quantityInput.setAttribute('max', availableQuantity);
            validateQuantity(quantityInput, availableQuantity);
        }
    }
});

// Add quantity input validation
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantity-input')) {
        const itemEntry = e.target.closest('.item-entry');
        const productSelect = itemEntry.querySelector('.product-select');
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        
        if (selectedOption.value) {
            const availableQuantity = parseInt(selectedOption.getAttribute('data-quantity'));
            validateQuantity(e.target, availableQuantity);
        }
    }
});

// Validation function
function validateQuantity(input, availableQuantity) {
    const quantity = parseInt(input.value);
    const formGroup = input.closest('.form-group');
    const error = formGroup.querySelector('.quantity-error');
    
    if (quantity > availableQuantity) {
        input.classList.add('input-error');
        error.style.display = 'block';
        error.textContent = `Not enough stock available (Max: ${availableQuantity})`;
        input.setCustomValidity(`Maximum available quantity is ${availableQuantity}`);
    } else if (quantity < 1) {
        input.classList.add('input-error');
        error.style.display = 'block';
        error.textContent = 'Quantity must be at least 1';
        input.setCustomValidity('Quantity must be at least 1');
    } else {
        input.classList.remove('input-error');
        error.style.display = 'none';
        input.setCustomValidity('');
    }
}

// Replace the existing add-item click handler with this improved version
document.getElementById('add-item').addEventListener('click', function() {
    console.log('Add item button clicked');
    
    const container = document.getElementById('items-container');
    const itemEntries = container.querySelectorAll('.item-entry');
    const newIndex = itemEntries.length;
    
    console.log('Current items count:', newIndex);
    
    // Clone the first item entry
    const template = itemEntries[0].cloneNode(true);
    
    // Reset all inputs and selects in the clone
    template.querySelectorAll('input, select').forEach(input => {
        // Update the index in the name attribute
        if (input.name) {
            const oldName = input.name;
            const newName = input.name.replace(/\[(\d+)\]/, `[${newIndex}]`);
            console.log(`Renaming ${oldName} to ${newName}`);
            input.name = newName;
        }
        
        // Reset values
        if (input.tagName === 'SELECT') {
            input.selectedIndex = 0;
        } else if (input.type === 'hidden' && input.name && input.name.includes('[price]')) {
            input.value = '0';
        } else if (input.type === 'hidden' && input.name && input.name.includes('[category]')) {
            input.value = '';
        } else if (input.type !== 'hidden') {
            input.value = '';
        }
        
        // Clear any validation states
        input.classList.remove('input-error');
        if (input.setCustomValidity) {
            input.setCustomValidity('');
        }
    });

    // Reset error message
    const errorMsg = template.querySelector('.quantity-error');
    if (errorMsg) {
        errorMsg.style.display = 'none';
    }

    // Show remove button
    const removeButton = template.querySelector('.remove-item');
    if (removeButton) {
        removeButton.style.display = 'inline-block';
    }

    // Add the cloned template to the container
    container.appendChild(template);
    console.log('New item added. Total items:', container.querySelectorAll('.item-entry').length);

    // Show all remove buttons
    container.querySelectorAll('.remove-item').forEach(button => {
        button.style.display = container.children.length > 1 ? 'inline-block' : 'none';
    });
});

// Handle item removal
document.getElementById('items-container').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-item')) {
        const container = document.getElementById('items-container');
        e.target.closest('.item-entry').remove();

        // Hide all remove buttons if only one item remains
        if (container.children.length === 1) {
            container.querySelector('.remove-item').style.display = 'none';
        }

        // Reindex the remaining items
        container.querySelectorAll('.item-entry').forEach((item, index) => {
            item.querySelectorAll('input, select').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                }
            });
        });
    }
});

// Print Request button handler
document.getElementById('print-request-btn').addEventListener('click', function() {
    const department = document.getElementById('department_display').value;
    const branch = document.getElementById('branch_display').value;
    const today = new Date();
    const dateString = today.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    // Set department, branch and date in the printable form
    document.getElementById('print-department').textContent = department;
    document.getElementById('print-branch').textContent = branch;
    document.getElementById('print-date').textContent = dateString;
    
    // Set the date in the header table
    const headerDateCell = document.getElementById('header-date-cell');
    if (headerDateCell) {
        // Format date as MMDDYYYY without separators
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const year = today.getFullYear();
        headerDateCell.textContent = month + day + year;
    }
    
    // Get all items
    const itemEntries = document.querySelectorAll('.item-entry');
    let validItems = [];
    
    itemEntries.forEach((entry) => {
        const productSelect = entry.querySelector('.product-select');
        const quantityInput = entry.querySelector('.quantity-input');
        const unitTypeHidden = entry.querySelector('input[type="hidden"][name$="[category]"]');
        
        if (productSelect.value && quantityInput.value && unitTypeHidden.value) {
            validItems.push({
                product_name: productSelect.options[productSelect.selectedIndex].text.trim().split('(')[0].trim(),
                quantity: quantityInput.value,
                category: unitTypeHidden.value
            });
        }
    });
    
    if (validItems.length === 0) {
        alert('Please fill in at least one item completely before printing');
        return;
    }
    
    // Clear the items table first
    const itemsBody = document.getElementById('print-items-body');
    itemsBody.innerHTML = '';
    
    // Add each item to the print table
    validItems.forEach(item => {
        const row = document.createElement('tr');
        
        // Quantity cell
        const quantityCell = document.createElement('td');
        quantityCell.style.border = '1px solid black';
        quantityCell.style.padding = '6px';
        quantityCell.style.textAlign = 'center';
        quantityCell.style.fontSize = '12px';
        quantityCell.textContent = item.quantity;
        row.appendChild(quantityCell);
        
        // Unit cell
        const unitCell = document.createElement('td');
        unitCell.style.border = '1px solid black';
        unitCell.style.padding = '6px';
        unitCell.style.textAlign = 'center';
        unitCell.style.fontSize = '12px';
        unitCell.textContent = item.category;
        row.appendChild(unitCell);
        
        // Description cell
        const descCell = document.createElement('td');
        descCell.style.border = '1px solid black';
        descCell.style.padding = '6px';
        descCell.style.fontSize = '12px';
        descCell.textContent = item.product_name;
        row.appendChild(descCell);
        
        itemsBody.appendChild(row);
    });
    
    // Trigger print
    window.print();
});

// Form submission handler
document.getElementById('requestForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Temporarily prevent form submission
    
    console.log('Form is being submitted');
    console.log('Form action:', this.action);
    console.log('Form method:', this.method);
    
    // Collect form data for debugging
    const formData = new FormData(this);
    const formDataObj = {};
    formData.forEach((value, key) => {
        if (!formDataObj[key]) {
            formDataObj[key] = value;
        } else {
            if (!Array.isArray(formDataObj[key])) {
                formDataObj[key] = [formDataObj[key]];
            }
            formDataObj[key].push(value);
        }
    });
    console.log('Form data:', formDataObj);
    
    // Validate items
    const itemEntries = document.querySelectorAll('.item-entry');
    let validItems = [];
    
    itemEntries.forEach((entry, index) => {
        const productName = entry.querySelector('[name^="items"][name$="[product_name]"]').value;
        const quantity = entry.querySelector('[name^="items"][name$="[quantity]"]').value;
        const category = entry.querySelector('input[type="hidden"][name$="[category]"]').value;
        
        if (productName && quantity && category) {
            validItems.push({
                index: index,
                product_name: productName,
                quantity: quantity,
                category: category
            });
        }
    });
    
    console.log('Valid items:', validItems);
    
    if (validItems.length === 0) {
        alert('Please fill in at least one item completely');
        return false;
    }
    
    // Check for any validation errors
    const hasErrors = document.querySelectorAll('.input-error').length > 0;
    if (hasErrors) {
        alert('Please fix the validation errors before submitting');
        return false;
    }
    
    // If validation passes, submit the form
    console.log('Form validation passed, submitting to:', this.action);
    this.submit();
});

// Handle quantity increase button
document.addEventListener('click', function(e) {
    if (e.target.closest('.quantity-increase')) {
        const button = e.target.closest('.quantity-increase');
        const input = button.closest('.input-group').querySelector('.quantity-input');
        const currentValue = parseInt(input.value) || 0;
        const max = parseInt(input.getAttribute('max')) || 9999;
        
        // Increase value but don't exceed max
        if (currentValue < max) {
            input.value = currentValue + 1;
            // Trigger input event to validate quantity
            input.dispatchEvent(new Event('input'));
        }
    }
});

// Handle quantity decrease button
document.addEventListener('click', function(e) {
    if (e.target.closest('.quantity-decrease')) {
        const button = e.target.closest('.quantity-decrease');
        const input = button.closest('.input-group').querySelector('.quantity-input');
        const currentValue = parseInt(input.value) || 0;
        
        // Decrease value but don't go below 1
        if (currentValue > 1) {
            input.value = currentValue - 1;
            // Trigger input event to validate quantity
            input.dispatchEvent(new Event('input'));
        }
    }
});
</script>
@endpush