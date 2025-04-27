@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>EDIT SUPPLY</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.supplies.update', $supply->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="control_code">Control Code</label>
                                    <input type="text" name="control_code" class="form-control" value="{{ $supply->control_code }}" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" name="product_name" class="form-control" value="{{ $supply->product_name }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" value="{{ $supply->quantity }}" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="unit_type">Unit Type</label>
                                    <select name="unit_type" class="form-control" required>
                                        <option value="Box" {{ $supply->unit_type == 'Box' ? 'selected' : '' }}>Box</option>
                                        <option value="Piece" {{ $supply->unit_type == 'Piece' ? 'selected' : '' }}>Piece</option>
                                        <option value="Pack" {{ $supply->unit_type == 'Pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="Ream" {{ $supply->unit_type == 'Ream' ? 'selected' : '' }}>Ream</option>
                                        <option value="Roll" {{ $supply->unit_type == 'Roll' ? 'selected' : '' }}>Roll</option>
                                        <option value="Bottle" {{ $supply->unit_type == 'Bottle' ? 'selected' : '' }}>Bottle</option>
                                        <option value="Cartridges" {{ $supply->unit_type == 'Cartridges' ? 'selected' : '' }}>Cartridges</option>
                                        <option value="Gallon" {{ $supply->unit_type == 'Gallon' ? 'selected' : '' }}>Gallon</option>
                                        <option value="Litre" {{ $supply->unit_type == 'Litre' ? 'selected' : '' }}>Litre</option>
                                        <option value="Meter" {{ $supply->unit_type == 'Meter' ? 'selected' : '' }}>Meter</option>
                                        <option value="Pound" {{ $supply->unit_type == 'Pound' ? 'selected' : '' }}>Pound</option>
                                        <option value="Sheet" {{ $supply->unit_type == 'Sheet' ? 'selected' : '' }}>Sheet</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_image">Product Image</label>
                                    <input type="file" name="product_image" class="form-control">
                                </div>
                            </div>
                            @if($supply->product_image)
                            <div class="col-md-6">
                                <img src="{{ asset('storage/' . $supply->product_image) }}" alt="Current Image" style="max-width: 100px;">
                            </div>
                            @endif
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" style="background-color: #821131;">Update Supply</button>
                                <a href="{{ route('admin.inventory') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection