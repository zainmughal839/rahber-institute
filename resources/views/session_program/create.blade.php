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
                            <i class="bi bi-link-45deg me-2"></i>
                            Assign Program to Session
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('session_program.index') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-list-ul me-1"></i> All Assignments
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Validation Errors -->
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

                <!-- Form -->
                <form method="POST"
                    action="{{ isset($item) ? route('session_program.update', $item->id) : route('session_program.store') }}"
                    class="form-horizontal">
                    @csrf
                    @if(isset($item))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="row g-4">

                            <!-- Select Session -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        Select Session <span class="text-danger">*</span>
                                    </label>
                                    <select name="session_id"
                                        class="form-control @error('session_id') is-invalid @enderror" required>
                                        <option value="">-- Choose Session --</option>
                                        @foreach($sessions as $s)
                                        <option value="{{ $s->id }}"
                                            {{ (old('session_id', $item->session_id ?? '') == $s->id) ? 'selected' : '' }}>

                                            {{ \Carbon\Carbon::parse($s->start_date)->format('d M Y') }}
                                            to
                                            {{ \Carbon\Carbon::parse($s->end_date)->format('d M Y') }}
                                            @if($s->description) — {{ Str::limit($s->description, 30) }} @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('session_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Select Program -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        Select Program <span class="text-danger">*</span>
                                    </label>
                                    <select name="program_id"
                                        class="form-control @error('program_id') is-invalid @enderror" required>
                                        <option value="">-- Choose Program --</option>
                                        @foreach($programs as $p)
                                        <option value="{{ $p->id }}"
                                            {{ (old('program_id', $item->program_id ?? '') == $p->id) ? 'selected' : '' }}>

                                            {{ $p->name }}
                                            @if($p->shortname) ({{ $p->shortname }}) @endif
                                            @if($p->program_code) - {{ $p->program_code }} @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('program_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Seats -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        Total Seats
                                    </label>
                                    <input type="number" name="seats" min="1" step="1"
                                        class="form-control @error('seats') is-invalid @enderror"
                                        value="{{ old('seats', $item->seats ?? '') }}" placeholder="e.g. 60">
                                    @error('seats')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fees -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">
                                        Program Fees (Per Semester)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rs</span>
                                        <input type="number" name="fees" step="0.01" min="0"
                                            class="form-control @error('fees') is-invalid @enderror"
                                            value="{{ old('fees', $item->fees ?? '') }}" placeholder="e.g. 45000.00">
                                    </div>
                                    @error('fees')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('session_program.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-success btn-l px-3">
                                <i class="bi bi-save"></i> Assign Program
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection