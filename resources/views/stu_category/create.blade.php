@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">

            <div class="card card-primary card-outline shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi {{ isset($category) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                            {{ isset($category) ? 'Edit Student Category' : 'Add Student Category' }}
                        </h3>

                        @can('stu_category.index')
                        <a href="{{ route('stu-category.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Categories
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Form -->
                <form method="POST"
                    action="{{ isset($category) ? route('stu-category.update', $category->id) : route('stu-category.store') }}">
                    @csrf
                    @if(isset($category)) @method('PUT') @endif

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $category->name ?? '') }}" required>

                                @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="desc" rows="3" class="form-control @error('desc') is-invalid @enderror"
                                    placeholder="Enter description...">{{ old('desc', $category->desc ?? '') }}</textarea>

                                @error('desc')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            @can('stu_category.index')
                            <a href="{{ route('stu-category.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            @endcan
                            <button type="submit" class="btn btn-success">
                                <i class="bi {{ isset($category) ? 'bi-check2-all' : 'bi-save' }}"></i>
                                {{ isset($category) ? 'Update' : 'Save Category' }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
@endsection