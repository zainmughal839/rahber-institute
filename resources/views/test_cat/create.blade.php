@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-primary card-outline shadow-lg border-0">

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0">
                    <i class="bi {{ isset($record) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                    {{ isset($record) ? 'Edit Test Category' : 'Create Test Category' }}
                </h3>

                @can('test-cat.index')
                <a href="{{ route('test-cat.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-list-ul me-1"></i> All Test Categories
                </a>
                @endcan
            </div>
        </div>

        <form method="POST"
            action="{{ isset($record) ? route('test-cat.update',$record->id) : route('test-cat.store') }}">
            @csrf
            @if(isset($record)) @method('PUT') @endif

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $record->name ?? '') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="desc" rows="4"
                            class="form-control">{{ old('desc', $record->desc ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            <div class="card-footer bg-light border-top">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('test-cat.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>

                    <button class="btn btn-success">
                        <i class="bi {{ isset($record) ? 'bi-check2-all' : 'bi-save' }}"></i>
                        {{ isset($record) ? 'Update' : 'Create' }}
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
