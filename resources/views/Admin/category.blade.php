@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Add Category Form -->
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Add New Category</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <button type="submit" class="btn bg-gradient-primary">Add Category</button>
                    </form>
                </div>
            </div>

            <!-- Categories List -->
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Existing Categories</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                <tr>
                                    <td>
                                        <span class="category-name text-xs font-weight-bold">{{ $category->category }}</span>
                                        <form class="edit-form d-none" action="{{ route('categories.update', $category->category) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" class="form-control" name="name" value="{{ $category->category }}">
                                        </form>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-link text-secondary mb-0 edit-btn">
                                            <i class="fas fa-pencil-alt text-xs"></i> Edit
                                        </button>
                                        <button class="btn btn-link text-secondary mb-0 save-btn d-none">
                                            <i class="fas fa-save text-xs"></i> Save
                                        </button>
                                        <form class="d-inline" action="{{ route('categories.destroy', $category->category) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger text-gradient mb-0">
                                                <i class="far fa-trash-alt text-xs"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.edit-btn').click(function() {
            const row = $(this).closest('tr');
            row.find('.category-name').hide();
            row.find('.edit-form').removeClass('d-none');
            $(this).hide();
            row.find('.save-btn').removeClass('d-none');
        });

        $('.save-btn').click(function() {
            $(this).closest('tr').find('.edit-form').submit();
        });
    });
</script>
@endpush
@endsection
