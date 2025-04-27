@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Create Stock from Request</h6>
                </div>
                <div class="card-body">
                    @if(session('request_data'))
                        <div class="alert" style="background-color: #8B0000;">
                            <h6 class="text-white mb-3">Request Items to Process</h6>
                            <div class="table-responsive bg-white rounded p-3">
                                <table class="table table-sm align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-dark text-xxs font-weight-bolder ps-2" style="width: 15%;">PRODUCT NAME</th>
                                            <th class="text-uppercase text-dark text-xxs font-weight-bolder ps-2" style="width: 8%;">QUANTITY</th>
                                            <th class="text-uppercase text-dark text-xxs font-weight-bolder ps-2" style="width: 8%;">PRICE</th>
                                            <th class="text-uppercase text-dark text-xxs font-weight-bolder ps-2" style="width: 15%;">DEPARTMENT</th>
                                            <th class="text-uppercase text-dark text-xxs font-weight-bolder ps-2" style="width: 15%;">BRANCH</th>
                                            <th class="text-uppercase text-dark text-xxs font-weight-bolder ps-2" style="width: 15%;">CATEGORY</th>
                                            <th class="text-uppercase text-dark text-xxs font-weight-bolder ps-2" style="width: 24%;">IMAGE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(session('request_data') as $index => $item)
                                        <tr>
                                            <td class="ps-2" style="width: 15%;">
                                                <p class="text-dark text-sm font-weight-bold mb-0">{{ $item['product_name'] }}</p>
                                            </td>
                                            <td class="ps-2 text-center" style="width: 8%;">
                                                <p class="text-dark text-sm font-weight-bold mb-0">{{ $item['quantity'] }}</p>
                                            </td>
                                            <td class="ps-2" style="width: 8%;">
                                                <p class="text-dark text-sm font-weight-bold mb-0">₱{{ number_format($item['price'], 2) }}</p>
                                            </td>
                                            <td class="ps-2" style="width: 15%;">
                                                <p class="text-dark text-sm font-weight-bold mb-0">{{ $item['department'] }}</p>
                                            </td>
                                            <td class="ps-2" style="width: 15%;">
                                                <p class="text-dark text-sm font-weight-bold mb-0">{{ $item['branch'] }}</p>
                                            </td>
                                            <td class="ps-2" style="width: 15%;">
                                                <p class="text-dark text-sm font-weight-bold mb-0">{{ $item['category'] }}</p>
                                            </td>
                                            <td class="ps-2" style="width: 24%;">
                                                <div class="form-group mb-0">
                                                    <input type="file" 
                                                           class="form-control form-control-sm" 
                                                           id="image_{{ $index }}" 
                                                           name="images[{{ $index }}]" 
                                                           accept="image/*"
                                                           data-product="{{ $item['product_name'] }}">
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <form action="{{ route('admin.stocks.store') }}" method="POST" enctype="multipart/form-data" id="stockForm">
                            @csrf
                            <input type="hidden" name="request_data" value="{{ json_encode(session('request_data')) }}">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn text-white" style="background-color: #8B0000;">Create Stock</button>
                                    <a href="{{ route('admin.tables') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <h6 class="text-dark mb-1">No Request Data Available</h6>
                            <p class="mb-0">Stock items can only be created from approved requests. Please process a request first.</p>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <a href="{{ route('admin.requests.index') }}" class="btn text-white" style="background-color: #8B0000;">
                                    View Pending Requests
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add file input change listener to show selected filename
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const productName = e.target.dataset.product;
            if (fileName) {
                console.log(`Selected file for ${productName}: ${fileName}`);
            }
        });
    });
});
</script>
@endpush
@endsection