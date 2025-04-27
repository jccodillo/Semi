
@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Issue Supplies for Approved Request</h6>
                    <p class="text-sm">Request ID: <strong>{{ $mainRequest->request_id }}</strong></p>
                    <p class="text-sm">Department: <strong>{{ $mainRequest->department }}</strong> | Branch: <strong>{{ $mainRequest->branch }}</strong></p>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.requests.process-issue', $mainRequest->request_id) }}" method="POST">
                        @csrf
                        
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Requested Qty</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Available Qty</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Issue Qty</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($inventoryItems) > 0)
                                        @foreach($inventoryItems as $index => $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        @if($item['inventory']->product_image)
                                                            <div class="me-3">
                                                                <img src="{{ asset('storage/' . $item['inventory']->product_image) }}" class="avatar avatar-sm" style="object-fit: cover;">
                                                            </div>
                                                        @endif
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $item['inventory']->product_name }}</h6>
                                                            <p class="text-xs text-secondary mb-0">{{ $item['inventory']->control_code }}</p>
                                                            <p class="text-xs text-secondary mb-0">Unit: {{ $item['inventory']->unit_type }}</p>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="item_ids[]" value="{{ $item['inventory']->id }}">
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $item['requested_qty'] }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ $item['inventory']->quantity }}</p>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm" 
                                                           name="quantities[]" 
                                                           value="{{ min($item['requested_qty'], $item['inventory']->quantity) }}" 
                                                           min="1" 
                                                           max="{{ $item['inventory']->quantity }}" 
                                                           required>
                                                </td>
                                                <td>
                                                    @if($item['inventory']->quantity == 0)
                                                        <span class="badge badge-sm" style="background-color: #FF0000;">Out of Stock</span>
                                                    @elseif($item['inventory']->quantity < $item['requested_qty'])
                                                        <span class="badge badge-sm" style="background-color: #ffd700;">Partial Stock</span>
                                                    @else
                                                        <span class="badge badge-sm bg-success">Available</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No matching inventory items found for this request.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary" {{ count($inventoryItems) == 0 ? 'disabled' : '' }}>
                                Issue Supplies
                            </button>
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
</style>
@endsection 