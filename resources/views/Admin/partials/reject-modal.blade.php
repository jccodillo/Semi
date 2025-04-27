<!-- Reject Modal -->
<div class="modal fade" id="rejectModal{{ $request->request_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.requests.update-status', ['requestId' => $request->request_id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Reject Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remarks" id="rejectionRemarks{{ $request->request_id }}" required rows="6"></textarea>
                    </div>
                    <div class="mt-3 small text-muted">
                        Form will submit to: {{ route('admin.requests.update-status', ['requestId' => $request->request_id]) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Initialize rejection remarks template
document.addEventListener('DOMContentLoaded', function() {
    // Function to set the rejection remarks template
    function setRejectionTemplate() {
        const textarea = document.getElementById('rejectionRemarks{{ $request->request_id }}');
        if (textarea) {
            let template = `REQUEST REJECTION NOTICE

Your request (ID: {{ $request->request_id }}) has been REJECTED.

REQUEST DETAILS
===================================

Department: {{ $request->department }}
Branch: {{ $request->branch }}
Date Requested: {{ $request->created_at ? $request->created_at->format('M d, Y') : 'N/A' }}

REASON FOR REJECTION
===================================

[Enter your rejection reason here]

Important: If you have any questions or need further clarification,
please contact the supply office.

Thank you for your understanding.`;
            
            textarea.value = template;
            
            // Set cursor position to the placeholder text
            const placeholderPos = template.indexOf('[Enter your rejection reason here]');
            if (placeholderPos !== -1) {
                textarea.setSelectionRange(placeholderPos, placeholderPos + '[Enter your rejection reason here]'.length);
                setTimeout(() => textarea.focus(), 100);
            }
        }
    }
    
    // Handle modal show event
    const modal = document.getElementById('rejectModal{{ $request->request_id }}');
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            setRejectionTemplate();
        });
        
        // Also add a backup for when the modal is first shown
        const rejectButton = document.querySelector('button[data-bs-target="#rejectModal{{ $request->request_id }}"]');
        if (rejectButton) {
            rejectButton.addEventListener('click', function() {
                // Set a timeout to ensure the modal has been created and displayed
                setTimeout(setRejectionTemplate, 100);
            });
        }
    }
    
    // jQuery support if available
    if (typeof $ !== 'undefined') {
        $('#rejectModal{{ $request->request_id }}').on('shown.bs.modal', function() {
            setRejectionTemplate();
        });
    }
});
</script> 