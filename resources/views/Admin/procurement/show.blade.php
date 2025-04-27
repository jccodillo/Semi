@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>PROCUREMENT DETAILS</h6>
                        <div>
                            <a href="{{ route('admin.procurement.iar', $procurement->id) }}" class="btn btn-primary btn-sm">Print IAR</a>
                            <a href="{{ route('admin.procurement.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-4 pt-0 pb-4">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-sm">Procurement Information</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="text-xs mb-1 font-weight-bold">IAR No.:</p>
                                    <p class="text-sm mb-2">{{ $procurement->iar_no }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-xs mb-1 font-weight-bold">Date:</p>
                                    <p class="text-sm mb-2">{{ $procurement->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-xs mb-1 font-weight-bold">Supplier:</p>
                                    <p class="text-sm mb-2">{{ $procurement->supplier }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-xs mb-1 font-weight-bold">Created By:</p>
                                    <p class="text-sm mb-2">{{ $procurement->creator->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-xs mb-1 font-weight-bold">Total Amount:</p>
                                    <p class="text-sm mb-2">₱{{ number_format($procurement->total_amount, 2) }}</p>
                                </div>
                                <div class="col-md-12">
                                    <p class="text-xs mb-1 font-weight-bold">Remarks:</p>
                                    <p class="text-sm mb-2">{{ $procurement->remarks ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-uppercase text-sm">Procurement Items</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-uppercase text-xs font-weight-bolder">Stock No.</th>
                                            <th class="text-uppercase text-xs font-weight-bolder">Product Name</th>
                                            <th class="text-uppercase text-xs font-weight-bolder text-center">Quantity</th>
                                            <th class="text-uppercase text-xs font-weight-bolder">Unit Type</th>
                                            <th class="text-uppercase text-xs font-weight-bolder text-end">Price Per Unit</th>
                                            <th class="text-uppercase text-xs font-weight-bolder text-end">Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($procurement->items as $item)
                                        <tr>
                                            <td class="text-xs">{{ $item->stock_no }}</td>
                                            <td class="text-xs">{{ $item->product_name }}</td>
                                            <td class="text-xs text-center">{{ $item->quantity }}</td>
                                            <td class="text-xs">{{ $item->unit_type }}</td>
                                            <td class="text-xs text-end">₱{{ number_format($item->price_per_unit, 2) }}</td>
                                            <td class="text-xs text-end">₱{{ number_format($item->total_amount, 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5" class="text-end text-xs font-weight-bold">Grand Total:</td>
                                            <td class="text-end text-xs font-weight-bold">₱{{ number_format($procurement->total_amount, 2) }}</td>
                                        </tr>
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
@endsection 