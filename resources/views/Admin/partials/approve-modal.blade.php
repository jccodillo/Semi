@php
use App\Models\SuppliesInventory;
@endphp
<!-- Approve Modal -->
<div class="modal fade" id="approveModal{{ $request->request_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.requests.update-status', ['requestId' => $request->request_id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Approve Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="status" value="approved">
                    <input type="hidden" name="request_details" value="{{ json_encode(['request_id' => $request->request_id, 'department' => $request->department, 'branch' => $request->branch]) }}">
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks" id="approvalRemarks{{ $request->request_id }}" required rows="6"></textarea>
                    </div>
                    <!-- Debug info to show in modal -->
                    <div class="mt-3 small text-muted">
                        Form will submit to: {{ route('admin.requests.update-status', ['requestId' => $request->request_id]) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" onclick="console.log('Approve button clicked for request {{ $request->request_id }}');">Approve</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Generate approval remarks immediately 
(function() {
    // Get pickup date (next Thursday)
    function getNextThursday() {
        const today = new Date();
        const dayOfWeek = today.getDay(); // 0 (Sunday) to 6 (Saturday)
        const daysUntilThursday = (4 - dayOfWeek + 7) % 7; // Thursday is day 4
        
        // If today is Thursday and it's before 5 PM, pickup is today
        // Otherwise, pickup is next Thursday
        if (dayOfWeek === 4 && today.getHours() < 17) {
            return today;
        }
        
        // Calculate next Thursday
        const nextThursday = new Date(today);
        nextThursday.setDate(today.getDate() + daysUntilThursday);
        return nextThursday;
    }
    
    // Format date to readable format
    function formatDate(date) {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', options);
    }
    
    // Prepare the remarks
    function generateRemarks() {
        const pickupDate = getNextThursday();
        const formattedDate = formatDate(pickupDate);
        
        // Get stock items for this request
        let stockDetails = "";
        @if(isset($request->stockItems) && $request->stockItems->count() > 0)
            @foreach($request->stockItems as $index => $item)
                @php
                // Try to find the inventory item to get the control code
                $inventoryItem = \App\Models\SuppliesInventory::where('product_name', $item->product_name)
                    ->where('unit_type', $item->category)
                    ->first();
                
                $stockNo = $inventoryItem ? $inventoryItem->control_code : ($item->control_code ?? 'N/A');
                @endphp
                stockDetails += "{{ $index + 1 }}. Stock No: {{ $stockNo }}\n";
                stockDetails += "   Item: {{ $item->product_name }}\n";
                stockDetails += "   Quantity: {{ $item->quantity }} {{ $item->category }}\n";
                stockDetails += "   Price: ₱{{ number_format($item->price ?? 0, 2) }}\n\n";
            @endforeach
        @else
            @php
            // Try to find the inventory item to get the control code
            $inventoryItem = \App\Models\SuppliesInventory::where('product_name', $request->product_name)
                ->where('unit_type', $request->category)
                ->first();
            
            $stockNo = $inventoryItem ? $inventoryItem->control_code : ($request->control_code ?? 'N/A');
            @endphp
            stockDetails = "1. Stock No: {{ $stockNo }}\n";
            stockDetails += "   Item: {{ $request->product_name ?? 'N/A' }}\n";
            stockDetails += "   Quantity: {{ $request->quantity ?? 'N/A' }} {{ $request->category ?? 'N/A' }}\n";
            stockDetails += "   Price: ₱{{ number_format($request->price ?? 0, 2) }}\n\n";
        @endif
        
        return `Your request (ID: {{ $request->request_id }}) has been APPROVED.

Department: {{ $request->department }}
Branch: {{ $request->branch }}
Date Requested: {{ $request->created_at ? $request->created_at->format('M d, Y') : 'N/A' }}

REQUESTED ITEMS
===================================

${stockDetails}
PICKUP INFORMATION
===================================

Date: ${formattedDate}
Location: Supply Office
Time: 6:00 AM - 4:00 PM

Important: Please bring your ID and request reference number
when collecting your supplies.

Thank you for using our request system!`;
    }
    
    // Function to set the remarks
    function setRemarks() {
        const textarea = document.getElementById('approvalRemarks{{ $request->request_id }}');
        if (textarea) {
            textarea.value = generateRemarks();
        }
    }
    
    // Add form submit handler to log the form submission
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('#approveModal{{ $request->request_id }} form');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submitted for request {{ $request->request_id }}');
                console.log('Form action:', this.action);
                console.log('Form method:', this.method);
            });
        }
        
        // Handle both ways of modal initialization
        if (typeof $ !== 'undefined') {
            $('#approveModal{{ $request->request_id }}').on('shown.bs.modal', function() {
                setRemarks();
            });
        }
        
        // Method 2: Using direct event listener
        const modal = document.getElementById('approveModal{{ $request->request_id }}');
        if (modal) {
            modal.addEventListener('shown.bs.modal', function() {
                setRemarks();
            });
            
            // Also add a backup for when the modal is first shown
            const approveButton = document.querySelector('button[data-bs-target="#approveModal{{ $request->request_id }}"]');
            if (approveButton) {
                approveButton.addEventListener('click', function() {
                    // Set a timeout to ensure the modal has been created and displayed
                    setTimeout(setRemarks, 100);
                });
            }
        }
        
        // Initial call in case the modal is already open
        setRemarks();
    });
})();
</script> 