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
                            <i class="bi bi-file-earmark-text me-2"></i>
                            Generate Fee Challan
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

                <!-- Form -->
                <form method="POST" action="{{ route('challans.store') }}" id="challanForm">
                    @csrf

                    <div class="card-body">

                        <!-- Session, Program, Class -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Session <span class="text-danger">*</span></label>
                                <select name="session_program_id" id="session" class="form-select" required>
                                    <option value="">Select Session</option>
                                    @foreach($sessions as $sp)
                                        <option value="{{ $sp->id }}">
                                            {{ $sp->session->sessions_name ?? 'Session ID: ' . $sp->id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Program <span class="text-danger">*</span></label>
                                <select name="program_id" id="program" class="form-select" required disabled>
                                    <option value="">First select session</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Class <span class="text-danger">*</span></label>
                                <select name="class_id" id="class" class="form-select" required disabled>
                                    <option value="">First select program</option>
                                </select>
                            </div>
                        </div>

                        <!-- Students Table (Only if tuition needed) -->
                        <div class="col-12 mt-4" id="studentsSection">
                            <label class="fw-semibold mb-2">Students with Pending Fees (For Fee Challan)</label>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="50"><input type="checkbox" id="selectAll"></th>
                                            <th>Sr #</th>
                                            <th>Student Name</th>
                                            <th>Total Fee</th>
                                            <th>Paid</th>
                                            <th>Installment</th>
                                            <th>Payable Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="studentTable">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Please select Class to load students
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Challan Type Options -->
                        <div class="col-12 mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeTuition" name="include_tuition" checked>
                                        <label class="form-check-label fw-bold text-success" for="includeTuition">
                                            <i class="bi bi-book me-2"></i> Include Tuition Fee Installment
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="includeAdditional" name="include_additional" checked>
                                        <label class="form-check-label fw-bold text-success" for="includeAdditional">
                                            <i class="bi bi-plus-circle me-2"></i> Include Additional Charges
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <!-- Additional Fees Repeater -->
                        <!-- Additional Fees Repeater -->
                        <div class="col-12 mt-5" id="additionalFeesSection">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="fw-semibold h5 mb-0">Additional Fees</label>
                                <button type="button" id="addFeeRow" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-plus-circle"></i> Add Fee
                                </button>
                            </div>

                            <div id="additionalFeesContainer">
                                <div class="row mb-3 align-items-end">
                                    <div class="col-md-4">
                                        <input type="text"
                                            name="extra_fees[0][title]"
                                            class="form-control"
                                            placeholder="Fee Title (e.g. Late Fine)">
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text"
                                            name="extra_fees[0][description]"
                                            class="form-control"
                                            placeholder="Description (optional)">
                                    </div>

                                    <div class="col-md-3">
                                        <input type="number"
                                            name="extra_fees[0][amount]"
                                            class="form-control"
                                            placeholder="Amount"
                                            min="0">
                                    </div>

                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-fee-row">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Issue Date <span class="text-danger">*</span></label>
                                <input type="date" name="issue_date" class="form-control" required value="{{ old('issue_date', now()->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                                <input type="date" name="due_date" class="form-control" required value="{{ old('due_date') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('challans.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success px-5" id="submitBtn">
                                <i class="bi bi-file-earmark-check"></i> Generate Challan(s)
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
    const session = document.getElementById('session');
    const program = document.getElementById('program');
    const classSel = document.getElementById('class');
    const tableBody = document.getElementById('studentTable');
    const studentsSection = document.getElementById('studentsSection');
    const additionalFeesSection = document.getElementById('additionalFeesSection');
    const includeTuition = document.getElementById('includeTuition');
    const includeAdditional = document.getElementById('includeAdditional');
    const selectAll = document.getElementById('selectAll');
    const submitBtn = document.getElementById('submitBtn');
    const feesContainer = document.getElementById('additionalFeesContainer');
    const addFeeBtn = document.getElementById('addFeeRow');

    const getUrl = '{{ route('challans.get-data') }}';

    let rowIndex = 1;

    // Toggle Sections
    const toggleSections = () => {
        // Students table HAMESHA visible rahegi
        studentsSection.style.display = 'block';

        // Additional fees toggle
        additionalFeesSection.style.display = includeAdditional.checked ? 'block' : 'none';

        // Tuition off ho to payable disable kar do
        document.querySelectorAll('input[name*="payable_amount"]').forEach(input => {
            input.required = includeTuition.checked;
            if (!includeTuition.checked) {
                input.value = 0;
            }
        });
    };


    includeTuition.addEventListener('change', toggleSections);
    includeAdditional.addEventListener('change', toggleSections);
    toggleSections(); // initial

    const toggleAdditionalInputs = () => {
    document.querySelectorAll('#additionalFeesSection input').forEach(input => {
        input.disabled = !includeAdditional.checked;
    });
};

includeAdditional.addEventListener('change', toggleAdditionalInputs);
toggleAdditionalInputs();


    // Add Fee Row

addFeeBtn.addEventListener('click', () => {
    if (!includeAdditional.checked) return;

    const row = document.createElement('div');
    row.className = 'row mb-3 align-items-end';

    row.innerHTML = `
        <div class="col-md-4">
            <input type="text"
                   name="extra_fees[${rowIndex}][title]"
                   class="form-control"
                   placeholder="Fee Title">
        </div>

        <div class="col-md-4">
            <input type="text"
                   name="extra_fees[${rowIndex}][description]"
                   class="form-control"
                   placeholder="Description (optional)">
        </div>

        <div class="col-md-3">
            <input type="number"
                   name="extra_fees[${rowIndex}][amount]"
                   class="form-control"
                   placeholder="Amount"
                   min="0">
        </div>

        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm remove-fee-row">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;

    feesContainer.appendChild(row);
    rowIndex++;
});

feesContainer.addEventListener('click', e => {
    if (e.target.closest('.remove-fee-row')) {
        e.target.closest('.row').remove();
    }
});


    feesContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-fee-row')) {
            e.target.closest('.row').remove();
        }
    });

    // Load Programs
    session.addEventListener('change', () => {
        const val = session.value;
        program.disabled = true;
        classSel.disabled = true;

        if (!val) return;

        fetch(`${getUrl}?type=programs&session_program_id=${val}`)
            .then(r => r.json())
            .then(data => {
                program.innerHTML = '<option value="">Select Program</option>';
                data.forEach(p => program.innerHTML += `<option value="${p.id}">${p.name}</option>`);
                program.disabled = false;
            });
    });

    // Load Classes
    program.addEventListener('change', () => {
        const val = program.value;
        classSel.disabled = true;

        if (!val) return;

        fetch(`${getUrl}?type=classes&session_program_id=${session.value}&program_id=${val}`)
            .then(r => r.json())
            .then(data => {
                classSel.innerHTML = '<option value="">Select Class</option>';
                data.forEach(c => classSel.innerHTML += `<option value="${c.id}">${c.class_name}</option>`);
                classSel.disabled = false;
            });
    });

    // Load Students
    classSel.addEventListener('change', () => {
        const val = classSel.value;
        if (!val || !includeTuition.checked) {
            tableBody.innerHTML = '';
            return;
        }

        tableBody.innerHTML = '<tr><td colspan="7" class="text-center"><div class="spinner-border"></div></td></tr>';

        fetch(`${getUrl}?type=students&class_id=${val}`)
            .then(r => r.json())
            .then(data => {
                tableBody.innerHTML = '';
                if (data.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-success py-4">All students paid!</td></tr>';
                    return;
                }

                data.forEach((s, i) => {
                    tableBody.innerHTML += `
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="students[${s.id}][selected]" value="1" class="studentCheck form-check-input">
                            </td>
                            <td>${i + 1}</td>
                            <td><strong>${s.name}</strong><br><small>Roll: ${s.rollnum}</small></td>
                            <td>Rs. ${Number(s.total_fee).toLocaleString()}</td>
                            <td>Rs. ${Number(s.paid).toLocaleString()}</td>
                            <td>Rs. ${Number(s.installment).toLocaleString()}</td>
                            <td>
                                <input type="number" step="0" name="students[${s.id}][payable_amount]" 
                                       class="form-control form-control-sm" 
                                       value="${s.installment}" min="0" max="${s.remaining}" required>
                            </td>
                        </tr>`;
                });
            });
    });

    // Submit Button
    function updateSubmitButton() {
        const hasTuition = includeTuition.checked && document.querySelectorAll('.studentCheck:checked').length > 0;
        const hasAdditional = includeAdditional.checked && 
            [...document.querySelectorAll('input[name*="extra_fees"][name$="[amount]"]')].some(i => i.value > 0);

        submitBtn.disabled = !(hasTuition || hasAdditional);
    }

    document.addEventListener('change', function (e) {

    // Agar koi student checkbox select ho
    if (e.target.classList.contains('studentCheck')) {

        const anyChecked = document.querySelectorAll('.studentCheck:checked').length > 0;

        if (anyChecked) {
            includeTuition.checked = true;
        }

        toggleSections();
        updateSubmitButton();
    }
});

    document.addEventListener('input', updateSubmitButton);
    updateSubmitButton();
});
</script>
@endsection