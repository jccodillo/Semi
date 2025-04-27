@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4" id="printable-area">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="text-center">STOCK CARD</h3>
                        <a href="{{ route('admin.inventory') }}" class="btn btn-secondary no-print">
                            <i class="fas fa-arrow-left me-2"></i>Back to Inventory
                        </a>
                    </div>
                    <div class="text-center mt-3">
                        <h5>TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h5>
                        <p>Manila</p>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-2">
                    <!-- Item Information Section -->
                    <div class="border mt-4">
                        <table class="table table-bordered mb-0">
                            <tr>
                                <td style="width: 20%;">
                                    <strong>Item:</strong> {{ $supply->product_name }}
                                </td>
                                <td style="width: 60%;">
                                    <strong>Description:</strong> {{ $supply->description ?? 'No description available' }}
                                </td>
                                <td style="width: 20%;">
                                    <strong>Stock No.:</strong> {{ $supply->control_code }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>
                                    <strong>Re-order Point:</strong>
                                    {{ $supply->reorder_point ?? '50' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td>
                                    <strong>Unit of Measure:</strong>
                                    {{ $supply->unit_type }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Stock Movement History -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered" id="stock-movement-table">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="align-middle text-center">Date</th>
                                    <th rowspan="2" class="align-middle text-center">Reference</th>
                                    <th colspan="2" class="text-center">Receipt</th>
                                    <th class="text-center">Issuance</th>
                                    <th rowspan="2" class="align-middle text-center">Balance<br>Qty.</th>
                                    <th rowspan="2" class="align-middle text-center">No. of Days to<br>Consume</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Qty.</th>
                                    <th class="text-center">Qty.</th>
                                    <th class="text-center">Supplier/Office</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($transactions) > 0)
                                    @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('m/d/Y') }}</td>
                                        <td>{{ $transaction->reference_number }}</td>
                                        @if($transaction->transaction_type == 'receipt')
                                            <td>{{ $transaction->quantity }}</td>
                                            <td></td>
                                            <td></td>
                                        @else
                                            <td></td>
                                            <td>{{ $transaction->quantity }}</td>
                                            <td>{{ $transaction->office ?? 'N/A' }}</td>
                                        @endif
                                        <td>{{ $transaction->balance }}</td>
                                        <td>{{ $transaction->days_to_consume ?? '' }}</td>
                                    </tr>       
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center">No transaction history available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Print Button -->
                    <div class="text-center mt-4 no-print">
                        <button id="print-stock-card" class="btn btn-primary" style="background-color: #821131;">
                            <i class="fas fa-print me-2"></i> PRINT STOCK CARD
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print-optimized version (only visible when printing) -->
<div id="print-only-version" style="display: none;">
    <div class="print-header">
        <h3 class="text-center">STOCK CARD</h3>
        <h5 class="text-center">TECHNOLOGICAL UNIVERSITY OF THE PHILIPPINES</h5>
        <p class="text-center">Manila</p>
    </div>
    
    <div class="print-item-info">
        <table class="print-info-table">
            <tr>
                <td style="width: 20%;">
                    <strong>Item:</strong> {{ $supply->product_name }}
                </td>
                <td style="width: 60%;">
                    <strong>Description:</strong> {{ $supply->description ?? 'No description available' }}
                </td>
                <td style="width: 20%;">
                    <strong>Stock No.:</strong> {{ $supply->control_code }}
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>
                    <strong>Re-order Point:</strong> {{ $supply->reorder_point ?? '50' }}
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td>
                    <strong>Unit of Measure:</strong> {{ $supply->unit_type }}
                </td>
            </tr>
        </table>
    </div>
    
    <div class="print-movement">
        <table class="print-movement-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Receipt</th>
                    <th>Issuance</th>
                    <th>Supplier/Office</th>
                    <th>Balance</th>
                    <th>Days</th>
                </tr>
            </thead>
            <tbody>
                @if(count($transactions) > 0)
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at->format('m/d/Y') }}</td>
                        <td>{{ $transaction->reference_number }}</td>
                        @if($transaction->transaction_type == 'receipt')
                            <td>{{ $transaction->quantity }}</td>
                            <td></td>
                            <td></td>
                        @else
                            <td></td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>{{ $transaction->office ?? 'N/A' }}</td>
                        @endif
                        <td>{{ $transaction->balance }}</td>
                        <td>{{ $transaction->days_to_consume ?? '' }}</td>
                    </tr>       
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center">No transaction history available</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6;
    }
    
    .card-header h3, .card-header h5 {
        margin-bottom: 0;
    }
    
    .card-header p {
        margin-bottom: 0;
    }
    
    #print-only-version {
        display: none;
    }
    
    /* Print styles */
    @media print {
        /* Hide the regular page content */
        body * {
            visibility: hidden;
        }
        
        /* Show only the print version */
        #print-only-version {
            display: block !important;
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 0.5cm 0.3cm;
            font-size: 9pt;
        }
        
        #print-only-version * {
            visibility: visible;
        }
        
        /* Header styling */
        .print-header h3 {
            font-size: 14pt;
            margin: 0 0 3px 0;
            font-weight: bold;
        }
        
        .print-header h5 {
            font-size: 11pt;
            margin: 0 0 2px 0;
        }
        
        .print-header p {
            font-size: 9pt;
            margin-bottom: 8px;
        }
        
        /* Item info table */
        .print-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #000;
        }
        
        .print-info-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            font-size: 9pt;
        }
        
        /* Movement table */
        .print-movement-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8pt;
        }
        
        .print-movement-table th,
        .print-movement-table td {
            border: 1px solid #000;
            padding: 2px 1px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            word-break: break-word;
        }
        
        .print-movement-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        /* Column widths optimized for portrait */
        .print-movement-table th:nth-child(1),
        .print-movement-table td:nth-child(1) {
            width: 11%;  /* Date */
        }
        
        .print-movement-table th:nth-child(2),
        .print-movement-table td:nth-child(2) {
            width: 16%;  /* Reference */
        }
        
        .print-movement-table th:nth-child(3),
        .print-movement-table td:nth-child(3),
        .print-movement-table th:nth-child(4),
        .print-movement-table td:nth-child(4) {
            width: 8%;  /* Qty fields */
        }
        
        .print-movement-table th:nth-child(5),
        .print-movement-table td:nth-child(5) {
            width: 30%;  /* Supplier/Office */
        }
        
        .print-movement-table th:nth-child(6),
        .print-movement-table td:nth-child(6) {
            width: 10%;  /* Balance */
        }
        
        .print-movement-table th:nth-child(7),
        .print-movement-table td:nth-child(7) {
            width: 17%;  /* Days to Consume */
        }
        
        /* Page setup */
        @page {
            size: portrait;
            margin: 0.4cm;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Print button click handler
        document.getElementById('print-stock-card').addEventListener('click', function() {
            window.print();
        });
    });
</script>
@endsection 