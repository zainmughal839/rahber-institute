@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-plus-circle me-2"></i> Add New Expense Head
                        </h3>
                        @can('expense-head.index')
                        <a href="{{ route('expense_heads.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Expense Heads
                        </a>
                        @endcan
                    </div>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <h4><i class="bi bi-exclamation-triangle"></i> Please fix the errors:</h4>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('expense_heads.store') }}" class="form-horizontal">
                    @csrf
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Expense Head Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    placeholder="e.g. Staff Salary" value="{{ old('name') }}" required>
                                @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Expense Head Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                    placeholder="e.g. EXP-001" value="{{ old('code') }}" required>
                                @error('code')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description (Optional)</label>
                                <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                                    placeholder="Brief description...">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('expense_heads.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save"></i> Save Expense Head
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection