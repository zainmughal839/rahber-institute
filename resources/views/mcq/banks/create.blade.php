@extends('layout.master')

@section('title', isset($bank) ? 'Edit MCQ Bank' : 'Create MCQ Bank')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0">
                    <i class="bi {{ isset($bank) ? 'bi-pencil-square' : 'bi-plus-circle' }} me-2"></i>
                    {{ isset($bank) ? 'Edit MCQ Categories' : 'Create MCQ Categories' }}
                </h3>

                 @can('mcq.banks.index')
                <a href="{{ route('mcq.banks.index') }}"
                   class="btn btn-light btn-sm shadow-sm">
                    <i class="bi bi-list-ul me-1"></i> Mcqs Categories
                </a>
                @endcan
            </div>
        </div>

        <form method="POST"
              action="{{ isset($bank)
                        ? route('mcq.banks.update',$bank->id)
                        : route('mcq.banks.store') }}">

            @csrf
            @isset($bank)
                @method('PUT')
            @endisset

            <div class="card-body row g-4">

                {{-- BANK NAME --}}
                <div class="col-md-6">
                    <label class="fw-semibold">Bank Name</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $bank->name ?? '') }}"
                           required>
                </div>

                {{-- CATEGORY --}}
                <div class="col-md-6">
                    <label class="fw-semibold">MCQ Category</label>
                    <select name="mcq_category_id"
                            class="form-control"
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('mcq_category_id', $bank->mcq_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DESCRIPTION --}}
                <div class="col-12">
                    <label>Description</label>
                    <textarea name="description"
                              rows="3"
                              class="form-control">{{ old('description', $bank->description ?? '') }}</textarea>
                </div>

                {{-- STATUS --}}
                <div class="col-md-4">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="1"
                            {{ old('status', $bank->status ?? 1) == 1 ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0"
                            {{ old('status', $bank->status ?? 1) == 0 ? 'selected' : '' }}>
                            Inactive
                        </option>
                    </select>
                </div>

            </div>

            <div class="card-footer text-end">
                <button class="btn btn-success px-4">
                    <i class="bi bi-save me-1"></i>
                    {{ isset($bank) ? 'Update Bank' : 'Save Bank' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
