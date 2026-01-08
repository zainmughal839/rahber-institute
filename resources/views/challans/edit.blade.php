@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-warning card-outline shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-success text-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-pencil-square me-2"></i>
                            Edit Fee Challan
                        </h3>

                        <a href="{{ route('challans.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Challans
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    {{ session('error') }}
                </div>
                @endif

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Student Info Display -->
                    <div class="row mb-4 p-3 bg-light rounded">
                        <div class="col-md-6">
                            <strong>Challan No:</strong> <span class="fw-bold text-primary">{{ $challan->challan_no }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Student:</strong> 
                            <span class="fw-bold">{{ $challan->student->name }}</span>
                            <small class="text-muted">(Roll: {{ $challan->student->rollnum ?? 'N/A' }})</small>
                        </div>
                        <div class="col-md-6 mt-2">
                            <strong>Current Amount:</strong> 
                            <span class="fw-bold text-success">Rs. {{ number_format($challan->amount, 2) }}</span>
                        </div>
                        <div class="col-md-6 mt-2">
                            <strong>Status:</strong>
                            <span class="badge {{ $challan->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} fs-6 px-3 py-2">
                                {{ ucfirst($challan->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Edit Form (Only if unpaid) -->
                    @if($challan->status === 'paid')
                    <div class="alert alert-danger text-center py-4">
                        <i class="bi bi-lock-fill fs-1 mb-3 d-block"></i>
                        <h5>This challan has already been marked as <strong>PAID</strong></h5>
                        <p class="mb-0">Paid challans cannot be edited for integrity reasons.</p>
                    </div>
                    @else
                    <form method="POST" action="{{ route('challans.update', $challan->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            <!-- Issue Date -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Issue Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="issue_date" 
                                       class="form-control" 
                                       required
                                       value="{{ old('issue_date', $challan->issue_date) }}">
                                @error('issue_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Due Date <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="due_date" 
                                       class="form-control" 
                                       required
                                       value="{{ old('due_date', $challan->due_date) }}">
                                @error('due_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">
                                    Challan Amount <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       step="0.01" 
                                       name="amount" 
                                       class="form-control form-control-lg" 
                                       required
                                       value="{{ old('amount', $challan->amount) }}"
                                       placeholder="e.g. 5000.00">
                                <small class="text-muted">You can manually adjust the amount if needed.</small>
                                @error('amount')
                                <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                        <!-- Footer Buttons -->
                        <div class="card-footer bg-light border-top mt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('challans.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>

                                <button type="submit" class="btn btn-success px-5">
                                    <i class="bi bi-check2-all me-2"></i>
                                    Update Challan
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection