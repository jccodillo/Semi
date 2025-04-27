@extends('layouts.user_type.auth')

@section('content')
<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Edit Stock Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('stock.update', $stock->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT')
                    
                    @if($errors->any())
                        <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                            <span class="alert-text text-white">
                                {{$errors->first()}}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name" class="form-control-label">Product Name</label>
                                <input class="form-control" type="text" name="product_name" value="{{ old('product_name', $stock->product_name) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="control_number" class="form-control-label">Control Number</label>
                                <input class="form-control" type="text" name="control_number" value="{{ old('control_number', $stock->control_number) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category" class="form-control-label">Category</label>
                                <input class="form-control" type="text" name="category" value="{{ old('category', $stock->category) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department" class="form-control-label">Department</label>
                                <input class="form-control" type="text" name="department" value="{{ old('department', $stock->department) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price" class="form-control-label">Price</label>
                                <input class="form-control" type="number" step="0.01" name="price" value="{{ old('price', $stock->price) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity" class="form-control-label">Quantity</label>
                                <input class="form-control" type="number" name="quantity" value="{{ old('quantity', $stock->quantity) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_reason" class="form-control-label">Reason for Edit</label>
                                <textarea class="form-control" name="edit_reason" rows="3" required 
                                    placeholder="Please provide a reason for this edit"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0 px-3">
                                    <h6 class="mb-0">Edit History</h6>
                                </div>
                                <div class="card-body pt-4 p-3">
                                    <div class="timeline timeline-one-side">
                                        @foreach($stock->editHistories()->latest()->get() as $history)
                                            <div class="timeline-block mb-3">
                                                <span class="timeline-step">
                                                    <i class="fas fa-history text-primary"></i>
                                                </span>
                                                <div class="timeline-content">
                                                    <h6 class="text-dark text-sm font-weight-bold mb-0">
                                                        {{ $history->created_at->format('F j, Y g:i A') }}
                                                        by {{ $history->editor->name }}
                                                    </h6>
                                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                                        Reason: {{ $history->reason }}
                                                    </p>
                                                    <p class="text-sm mt-3 mb-2">
                                                        Changes:
                                                    </p>
                                                    <ul class="list-group list-group-flush">
                                                        @foreach(json_decode($history->changes, true) as $field => $change)
                                                            <li class="list-group-item border-0 ps-0 pt-0 text-sm">
                                                                <strong>{{ ucfirst($field) }}:</strong> 
                                                                {{ $change['old'] }} → {{ $change['new'] }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.tables') }}" class="btn btn-light m-0 me-2">Cancel</a>
                        <button type="submit" class="btn bg-gradient-primary m-0">Update Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
