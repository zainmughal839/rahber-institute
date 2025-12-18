@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-primary card-outline shadow-lg border-0">


      <!-- Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                           <i class="bi {{ isset($record) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                {{ isset($record) ? 'Edit Category' : 'Create New Category' }}
                        </h3>

                        @can('task-cat.index')
                        <a href="{{ route('task-cat.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-list-ul me-1"></i> All Categories
                </a>
                @endcan
                    </div>
                </div>




        <div class="card-body">

            @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST"
                action="{{ isset($record) ? route('task-cat.update', $record->id) : route('task-cat.store') }}">
                @csrf
                @if(isset($record)) @method('PUT') @endif

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $record->name ?? '') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="desc" rows="4" class="form-control">{{ old('desc', $record->desc ?? '') }}</textarea>
                    </div>
                </div>

               

                <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            @can('task-cat.index')
                            <a href="{{ route('task-cat.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    @endcan
                            <button type="submit" class="btn btn-success">
                        <i class="bi {{ isset($record) ? 'bi-check2-all' : 'bi-save' }}"></i>
                        {{ isset($record) ? 'Update' : 'Create' }}
                    </button>
                        </div>
                    </div>

            </form>

        </div>

    </div>
</div>
@endsection
