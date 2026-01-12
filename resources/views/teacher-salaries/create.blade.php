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
                            <i class="bi bi-wallet2 me-2"></i>
                            Pay Teacher Salary
                        </h3>
                        <!-- Assuming you have an index route -->
                        @can('teacher-salary.index')
                        <a href="{{ route('teacher-salaries.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Payments
                        </a>
                        @endcan
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

                <!-- Form -->
                <form method="POST" action="{{ route('teacher-salaries.store') }}" id="salaryForm">
                    @csrf

                    <div class="card-body">

                        <!-- Teacher Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Teacher <span class="text-danger">*</span></label>
                                <select name="teacher_id" id="teacher" class="form-select" required>
                                    <option value="">Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }} - {{ $teacher->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Voucher No</label>
                                <input type="text"
                                    class="form-control fw-bold text-success"
                                    value="{{ $nextVoucherNo }}"
                                    readonly>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>

                        <!-- Salary Details -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Base Salary</label>
                                <input type="number" id="salary" class="form-control" readonly value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">D-married Points</label>
                                <input type="number" name="demerits" id="demerits" class="form-control" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Salary Cutting Amount <span class="text-danger">*</span></label>
                                <input type="number" name="cutting_amount" id="cutting_amount" class="form-control" required min="0" value="0">
                            </div>
                        </div>

                        <!-- Final Amount -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Final Payable Amount</label>
                                <input type="number" name="final_amount" id="final_amount" class="form-control" readonly value="0">
                            </div>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher-salaries.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success px-5">
                                <i class="bi bi-check-circle"></i> Pay Salary
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const teacherSelect = document.getElementById('teacher');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const salaryInput = document.getElementById('salary');
        const demeritsInput = document.getElementById('demerits');
        const cuttingInput = document.getElementById('cutting_amount');
        const finalInput = document.getElementById('final_amount');

        const getUrl = '{{ route('teacher-salaries.get-data') }}';

        const fetchSalary = () => {
            if (!teacherSelect.value) return;
            fetch(`${getUrl}?type=salary&teacher_id=${teacherSelect.value}`)
                .then(response => response.json())
                .then(data => {
                    salaryInput.value = data.salary || 0;
                    calculateFinal();
                })
                .catch(() => {
                    salaryInput.value = 0;
                });
        };

        const fetchDemerits = () => {
            if (!teacherSelect.value || !startDate.value || !endDate.value) return;
            fetch(`${getUrl}?type=demerits&teacher_id=${teacherSelect.value}&start=${startDate.value}&end=${endDate.value}`)
                .then(response => response.json())
                .then(data => {
                    demeritsInput.value = data.demerits || 0;
                    // Assuming d_married_points represents the direct deduction amount (summed).
                    // If it's points, you can multiply by a rate, e.g., cuttingInput.value = data.demerits * 100;
                    // But based on context, treating as deduction amount.
                    cuttingInput.value = data.demerits || 0;
                    calculateFinal();
                })
                .catch(() => {
                    demeritsInput.value = 0;
                    cuttingInput.value = 0;
                });
        };

        const calculateFinal = () => {
            const salary = parseFloat(salaryInput.value) || 0;
            const cutting = parseFloat(cuttingInput.value) || 0;
            finalInput.value = Math.max(0, salary - cutting);
        };

        teacherSelect.addEventListener('change', () => {
            fetchSalary();
            fetchDemerits();
        });

        startDate.addEventListener('change', fetchDemerits);
        endDate.addEventListener('change', fetchDemerits);
        cuttingInput.addEventListener('input', calculateFinal);
    });
</script>
@endsection