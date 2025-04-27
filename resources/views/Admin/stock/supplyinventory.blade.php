@extends('layouts.user_type.auth')

@section('content')
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Add New Supply</h6>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.stock.supplyinventory.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Product Name</label>
                                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Price</label>
                                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unit_type">Unit Type</label>
                                        <select class="form-control" id="unit_type" name="unit_type" required>
                                            <option value="">Select Unit</option>
                                            <option value="Box" {{ old('unit_type') == 'Box' ? 'selected' : '' }}>Box</option>
                                            <option value="Piece" {{ old('unit_type') == 'Piece' ? 'selected' : '' }}>Piece</option>
                                            <option value="Pack" {{ old('unit_type') == 'Pack' ? 'selected' : '' }}>Pack</option>
                                            <option value="Ream" {{ old('unit_type') == 'Ream' ? 'selected' : '' }}>Ream</option>
                                            <option value="Roll" {{ old('unit_type') == 'Roll' ? 'selected' : '' }}>Roll</option>
                                            <option value="Bottle" {{ old('unit_type') == 'Bottle' ? 'selected' : '' }}>Bottle</option>
                                            <option value="Cartridges" {{ old('unit_type') == 'Cartridges' ? 'selected' : '' }}>Cartridges</option>
                                            <option value="Gallon" {{ old('unit_type') == 'Gallon' ? 'selected' : '' }}>Gallon</option>
                                            <option value="Litre" {{ old('unit_type') == 'Litre' ? 'selected' : '' }}>Litre</option>
                                            <option value="Meter" {{ old('unit_type') == 'Meter' ? 'selected' : '' }}>Meter</option>
                                            <option value="Pound" {{ old('unit_type') == 'Pound' ? 'selected' : '' }}>Pound</option>
                                            <option value="Sheet" {{ old('unit_type') == 'Sheet' ? 'selected' : '' }}>Sheet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter detailed product description">{{ old('description') }}</textarea>
                                        <small class="text-muted">Provide additional details about the product (specifications, usage, etc.)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="image">Product Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <small class="text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <a href="{{ route('admin.inventory') }}" class="btn btn-secondary">CANCEL</a>
                                    <button type="submit" class="btn btn-primary" style="background-color: #821131;">ADD STOCK</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
