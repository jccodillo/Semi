@extends('layouts.user_type.auth')

@section('content')
    <!-- Hidden QR Reader Element - REMOVED SCANNER CONTAINER -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">STOCK RELEASED </h5>
                            
                            <!-- Add Supply Button -->
                            <a href="{{ route('admin.stocks.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-2"></i> Create Stock
                            </a>
                        </div>
                        
                        <!-- College Filter Section -->
                        <div class="mt-3">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <a href="{{ route('admin.tables') }}" class="btn btn-sm {{ !request('college') ? 'btn-primary' : 'btn-outline-primary' }}">
                                    All Colleges
                                </a>
                                @php
                                    $colleges = App\Models\Stock::select('department')->distinct()->pluck('department');
                                @endphp
                                
                                @foreach($colleges as $college)
                                    <a href="{{ route('admin.tables', ['college' => $college]) }}" 
                                       class="btn btn-sm {{ request('college') == $college ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ $college }}
                                    </a>
                                @endforeach
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">COLLEGE</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">DEPARTMENT</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">STOCK LEVEL</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">UNIT PRICE</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">STATUS</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stocks as $stock)
                                    <tr>
                                        <td class="ps-4 text-center">
                                            <div class="qr-code-wrapper" id="qrcode{{ $stock->id }}">
                                                <div class="d-flex flex-column align-items-center">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#qrModal{{ $stock->id }}" class="qr-code-link">
                                                        {!! $stock->qr_code !!}
                                                    </a>
                                                    <!-- REMOVED SCAN BUTTON -->
                                                </div>
                                            </div>
                                        </td>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
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
                                            <span class="text-secondary text-xs department-text">{{ $stock->department }}</span>
                                        </td>
                                        <td class="ps-4 text-center">
                                            <span class="text-secondary text-xs">{{ $stock->branch }}</span>
                                        </td>
                                        <td class="ps-4 text-center">
                                            <span class="text-secondary text-xs">{{ $stock->quantity }} units</span>
                                        </td>
                                        <td class="ps-4 text-center">
                                            <span class="text-secondary text-xs">₱{{ number_format($stock->price, 2) }}</span>
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
                                            <a href="{{ route('stock.edit', $stock->id) }}" class="action-btn edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" style="background-color: #821131 !important;">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="javascript:void(0)" onclick="printQR({{ $stock->id }})" class="action-btn print" data-bs-toggle="tooltip" data-bs-placement="top" title="Print" style="background-color: #821131 !important;">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Add this hidden div for printing -->
                                    <div id="printSection{{ $stock->id }}" style="display: none;">
                                        <div style="text-align: center; padding: 20px;">
                                            <img src="{{ asset('assets/img/logo.png') }}" alt="Company Logo" style="width: 150px; margin-bottom: 15px;">
                                            <h3>{{ $stock->product_name }}</h3>
                                            <p>Control Number: {{ $stock->control_number }}</p>
                                            {!! QrCode::size(400)->generate(json_encode([
                                                'id' => $stock->id,
                                                'name' => $stock->product_name,
                                                'category' => $stock->category,
                                                'department' => $stock->department,
                                                'branch' => $stock->branch,
                                                'control_number' => $stock->control_number,
                                                'price' => $stock->price,
                                                'quantity' => $stock->quantity
                                            ])) !!}
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer py-3">
                        <div class="d-flex justify-content-end">
                            {{ $stocks->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<style>

.pagination {
    --bs-pagination-color: #821131 !important;
    --bs-pagination-active-bg: #821131 !important;
    --bs-pagination-active-border-color: #821131 !important;
    --bs-pagination-hover-color: #6a0e28 !important;
    --bs-pagination-focus-color: #6a0e28 !important;
    --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(130, 17, 49, 0.25) !important;
}

.page-link:hover {
    background-color: rgba(130, 17, 49, 0.1) !important;
}

.page-item.active .page-link {
    background-color: #821131 !important;
    border-color: #821131 !important;
    color: white !important;
}

.page-link {
    color: #821131 !important;
}

.page-link:focus {
    box-shadow: 0 0 0 0.25rem rgba(130, 17, 49, 0.25) !important;
}

.btn-success, .btn-primary {
    background-color: #821131 !important;
    border: none;
    padding: 8px 16px;
    font-weight: 500;
    color: white;
}

.btn-success:hover, .btn-primary:hover {
    background-color: #bb2d3b !important;
    opacity: 0.9;
}

.table thead th {
    font-size: 0.65rem;
    font-weight: 700;
    padding: 0.5rem;
    color: #8898aa;
    white-space: nowrap;
}

.table tbody td {
    padding: 0.5rem;
    vertical-align: middle;
}

.badge {
    padding: 3px 6px;
    font-weight: 500;
    font-size: 0.65rem;
    border-radius: 3px;
}

.action-btn {
    display: inline-block;
    width: 32px;
    height: 32px;
    line-height: 32px;
    text-align: center;
    margin: 0 4px;
    border-radius: 4px;
    color: white !important;
    text-decoration: none;
    background-color: #dc3545;
}

.action-btn i {
    line-height: inherit;
}

.action-btn:hover {
    opacity: 0.8;
    color: white !important;
    text-decoration: none;
}

.text-dark {
    font-size: 0.75rem;
}

.text-secondary {
    color: #8898aa !important;
    font-size: 0.7rem;
}

td img, .qr-code-cell img {
    max-width: 40px;
    height: auto;
    cursor: pointer;
    transition: opacity 0.3s;
}

td img:hover, .qr-code-cell img:hover {
    opacity: 0.8;
}

.qr-code-cell {
    text-align: center;
}

.table-responsive {
    margin: 0;
    padding: 0 !important;
    width: 100%;
}

.ps-4 {
    padding-left: 0.75rem !important;
}

.table th:nth-child(1), /* QR CODE */
.table td:nth-child(1) {
    width: 8%;
}

.table th:nth-child(2), /* ITEM DETAILS */
.table td:nth-child(2) {
    width: 15%;
}

.table th:nth-child(9), /* ACTIONS */
.table td:nth-child(9) {
    width: 10%;
}

.badge[style*="background-color: #FF0000;"] {
    background-color: #ff0000d9 !important; /* Out of stock */
}

.badge[style*="background-color: #FF5252;"] {
    background-color: #ff5252d9 !important; /* Low stock */
}

.badge[style*="background-color: #4CAF50;"] {
    background-color: #4caf50d9 !important; /* In stock */
}

/* Update action buttons container */
td .d-flex {
    gap: 8px;
}

/* Reduce column spacing */
.table th, 
.table td {
    padding: 0.25rem !important;  /* Reduce overall cell padding */
}

/* Adjust text alignment and spacing */
.text-secondary.text-xs {
    font-size: 0.7rem;  /* Slightly smaller text */
    margin: 0;  /* Remove any margins */
    line-height: 1.2;  /* Tighter line height */
}

/* Optional: If you need specific column widths */
.table th:nth-child(5), /* STOCK LEVEL */
.table td:nth-child(5) {
    width: 10%;
}

.table th:nth-child(6), /* UNIT PRICE */
.table td:nth-child(6) {
    width: 10%;
}

.table th:nth-child(7), /* LAST UPDATED */
.table td:nth-child(7) {
    width: 10%;
}

/* Custom tooltip styling */
.tooltip {
    font-size: 12px;
}

.tooltip .tooltip-inner {
    background-color: #333;
    padding: 4px 8px;
    border-radius: 4px;
}

.tooltip.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #333;
}

.modal {
    visibility: hidden;
    opacity: 0;
}

.modal.show {
    visibility: visible;
    opacity: 1;
}

td img {
    display: block;
    margin: 0 auto;  /* Centers the image horizontally */
    max-width: 40px;
    height: auto;
}

.table td:first-child {
    text-align: center;
    padding: 0.5rem !important;
}

/* Update existing ps-4 style for the first column */
.table td:first-child.ps-4 {
    padding-left: 0 !important;  /* Remove left padding for QR code column */
}

/* Add new styles for department column */
.department-text {
    display: block;
    max-width: 120px;  /* Adjust this value as needed */
    margin: 0 auto;
    word-wrap: break-word;
    white-space: normal;
    line-height: 1.2;
}

/* Update the department column width */
.table th:nth-child(4), /* DEPARTMENT */
.table td:nth-child(4) {
    width: 12%;
    text-align: center;
    vertical-align: middle;
}

/* Remove left padding for department column */
.table td:nth-child(4).ps-4 {
    padding-left: 0 !important;
}

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

.modal img {
    transition: transform 0.3s ease;
}

/* Optional: Add hover effect on the thumbnail */
td img:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* REMOVED SCANNER STYLES */

.modal-lg {
    max-width: 800px;
}

/* REMOVED SCANNER CONTAINER STYLES */

/* REMOVED HTML5-QRCODE ELEMENTS STYLES */

/* REMOVED SCANNER OVERLAY STYLES */

/* REMOVED SCANNER CONTENT STYLES */

/* REMOVED SCANNER HEADER STYLES */

/* REMOVED READER STYLES */

/* REMOVED SCAN RESULT STYLES */

/* REMOVED SCANNED DATA STYLES */

.qr-code-wrapper {
    display: inline-block;
    position: relative;
    padding: 10px 0;
}

.qr-code-wrapper svg {
    width: 100px !important;
    height: 100px !important;
    margin-bottom: 5px;
    transition: transform 0.3s ease;
}

/* REMOVED SCAN BUTTON STYLES */

.table td:first-child {
    min-width: 140px;
    padding: 1rem !important;
}

/* REMOVED ANIMATION FOR SUCCESSFUL SCAN */

.qr-code-link {
    cursor: pointer;
    transition: transform 0.3s ease;
    display: inline-block;
}

.qr-code-link:hover svg {
    transform: scale(1.1);
}

.qr-code-wrapper svg {
    max-width: 80px;
    height: auto;
    margin-bottom: 5px;
}

.modal-body svg {
    width: 300px !important;
    height: 300px !important;
    margin: 0 auto;
    display: block;
}

.modal-footer .btn-primary {
    background-color: #8B0000 !important;
    border-color: #8B0000;
}

.modal-footer .btn-primary:hover {
    background-color: #6a0e28 !important;
    border-color: #6a0e28;
}

.modal-footer .btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.modal-footer .btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

/* Modal styles */
.qr-modal {
    display: block !important;
    visibility: hidden;
    opacity: 0;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.qr-modal.show {
    visibility: visible;
    opacity: 1;
}

.qr-modal .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease;
}

.qr-modal.show .modal-dialog {
    transform: scale(1);
}

.qr-modal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
}

.qr-modal .modal-body {
    padding: 2rem;
}

.qr-modal .qr-code-large {
    display: flex;
    justify-content: center;
    align-items: center;
}

.qr-modal .qr-code-large svg {
    width: 400px !important;
    height: 400px !important;
    max-width: 100%;
    height: auto;
}

.qr-modal .modal-header {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.qr-modal .modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 1rem 1.5rem;
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
    transition: opacity 0.3s ease;
}

.modal-backdrop.show {
    opacity: 0.5;
}

/* Item Details Column Styling */
.table td:nth-child(2) {
    text-align: center;
    vertical-align: middle;
}

.table td:nth-child(2) .d-flex {
    justify-content: center;
    align-items: center;
    gap: 10px;
}

.table td:nth-child(2) .d-flex > div {
    text-align: center;
}

.table td:nth-child(2) p {
    margin: 0;
}

.table td:nth-child(2) .text-xs {
    display: block;
    text-align: center;
}

/* Update image container in item details */
.table td:nth-child(2) .d-flex img {
    margin-right: 10px;
}

/* Ensure consistent spacing */
.table td:nth-child(2) .d-flex {
    padding: 8px 0;
}

/* College filter button styles */
.btn-outline-primary {
    color: #821131 !important;
    border-color: #821131 !important;
}

.btn-outline-primary:hover {
    background-color: rgba(130, 17, 49, 0.1) !important;
}

.btn-primary {
    background-color: #821131 !important;
    border-color: #821131 !important;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Wrap the filters on small screens */
.college-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

</style>

@push('scripts')
<script>
// REMOVED HTML5-QR CODE SCRIPT IMPORT

function printQR(stockId) {
    console.log('Print function called for stock ID:', stockId); // Debug line
    
    try {
        // Get the print content
        const printSection = document.getElementById(`printSection${stockId}`);
        if (!printSection) {
            console.error('Print section not found for ID:', stockId);
            return;
        }
        
        const printContent = printSection.innerHTML;
        
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        if (!printWindow) {
            alert('Please allow pop-ups for printing functionality');
            return;
        }
        
        // Write the content to the new window
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Print QR Code</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 0;
                            padding: 0;
                        }
                        .print-container {
                            width: 100%;
                            max-width: 500px;
                            margin: 0 auto;
                            padding: 20px;
                            text-align: center;
                        }
                        .logo {
                            width: 150px;
                            margin-bottom: 15px;
                        }
                        img {
                            max-width: 400px;
                            height: auto;
                        }
                        h3 {
                            margin: 10px 0;
                            font-size: 20px;
                        }
                        p {
                            margin: 5px 0;
                            font-size: 16px;
                        }
                        @media print {
                            @page {
                                size: A6;
                                margin: 0;
                            }
                            body {
                                margin: 0;
                                padding: 10px;
                            }
                            .print-container {
                                page-break-after: always;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-container">
                        ${printContent}
                    </div>
                </body>
            </html>
        `);
        
        // Wait for the content to load
        printWindow.document.close();
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            setTimeout(function() {
                printWindow.close();
            }, 1000);
        };
    } catch (error) {
        console.error('Error printing QR code:', error);
    }
}

// REMOVED SCAN QR CODE FUNCTION AND OTHER SCANNER RELATED FUNCTIONS

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>
@endpush


