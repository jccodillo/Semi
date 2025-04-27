@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add New Supply</h6>
                    <p class="text-sm">Create a new supply item directly in the stock inventory</p>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.stocks.store-direct') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_name" class="form-control-label">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                    @error('product_name')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="quantity" class="form-control-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                                    @error('quantity')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="price" class="form-control-label">Price (₱)</label>
                                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', '0.00') }}" step="0.01" min="0" required>
                                    @error('price')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category" class="form-control-label">Unit Type</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Select Unit Type</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit }}" {{ old('category') == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="department" class="form-control-label">Department</label>
                                    <select class="form-control" id="department" name="department" required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department }}" {{ old('department') == $department ? 'selected' : '' }}>{{ $department }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="branch" class="form-control-label">Branch/Section</label>
                                    <input type="text" class="form-control" id="branch" name="branch" value="{{ old('branch') }}" required>
                                    @error('branch')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="image" class="form-control-label">Product Image (Optional)</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <span class="text-danger text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.tables') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Supply</button>
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
    .form-control:focus {
        border-color: #821131;
        box-shadow: 0 0 0 0.2rem rgba(130, 17, 49, 0.25);
    }
</style>
@endsection 