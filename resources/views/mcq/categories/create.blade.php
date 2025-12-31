@extends('layout.master')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                {{-- HEADER --}}
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi {{ isset($category) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                            {{ isset($category) ? 'Edit MCQ Book Head' : 'Add MCQ Book Head' }}
                        </h3>

                         @can('mcq-category.index') 
                        <a href="{{ route('mcq.categories.index') }}"
                           class="btn btn-light btn-sm">
                            <i class="bi bi-list-ul me-1"></i> All Books Head
                        </a>
                         @endcan 
                    </div>
                </div>

                {{-- FORM --}}
                <form method="POST"
                      action="{{ isset($category)
                            ? route('mcq.categories.update',$category->id)
                            : route('mcq.categories.store') }}">
                    @csrf
                    @if(isset($category)) @method('PUT') @endif

                    <div class="card-body row g-4">

                        {{-- CATEGORY NAME --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category Name *</label>
                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $category->name ?? '') }}"
                                   placeholder="e.g. Biology MCQs"
                                   required>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status *</label>
                            <select name="status" class="form-control" required>
                                <option value="1"
                                    {{ old('status', $category->status ?? 1) == 1 ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0"
                                    {{ old('status', $category->status ?? 1) == 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                        </div>

                        {{-- DESCRIPTION --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Optional description for MCQ category">{{ old('description', $category->description ?? '') }}</textarea>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="card-footer bg-light text-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi {{ isset($category) ? 'bi-check2-all' : 'bi-save' }} me-1"></i>
                            {{ isset($category) ? 'Update' : 'Save' }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
