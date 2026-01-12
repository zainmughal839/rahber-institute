@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-success card-outline shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-cash-coin me-2"></i>
                            Receive Fee Payment
                        </h3>
                        @can('challan.index')
                        <a href="{{ route('challans.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Challans
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Alerts -->
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

                <div class="card-body">
                    <div class="row">
                        <!-- Search by Challan No -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Search by Challan No</label>
                            <div class="input-group">
                                <input type="text" id="searchChallanNo" class="form-control" placeholder="Enter Challan No">
                                <button class="btn btn-primary" id="searchByChallanBtn">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </div>

                        <!-- Search by Roll No -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">Search by Roll No</label>
                            <div class="input-group">
                                <input type="text" id="searchRollNo" class="form-control" placeholder="Enter Student Roll No">
                                <button class="btn btn-primary" id="searchByRollBtn">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Results Area -->
                    <div id="resultsArea" class="mt-4" style="display:none;">
                        <h5 class="fw-bold mb-3" id="resultsTitle"></h5>

                        <form method="POST" action="{{ route('challans.batch-paid') }}" id="paymentForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-success">
                                        <tr>
                                            <th><input type="checkbox" id="selectAllResults"></th>
                                            <th>Sr #</th>
                                            <th>Student Name</th>
                                            <th>Roll No</th>
                                            <th>Challan No</th>
                                            <th>Amount</th>
                                            <th>Issue Date</th>
                                            <th>Due Date</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultsTable">
                                        <!-- Results will be loaded here -->
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-success btn-lg px-5" id="paySelectedBtn" disabled>
                                    <i class="bi bi-check2-circle me-2"></i>
                                    Mark Selected as Paid
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="text-center py-5 text-muted" style="display:none;">
                        <i class="bi bi-receipt display-1 d-block mb-3"></i>
                        <h4>No Challan Found</h4>
                        <p>Please check the Challan No or Roll No and try again.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchChallanInput = document.getElementById('searchChallanNo');
    const searchRollInput = document.getElementById('searchRollNo');
    const searchChallanBtn = document.getElementById('searchByChallanBtn');
    const searchRollBtn = document.getElementById('searchByRollBtn');
    const resultsArea = document.getElementById('resultsArea');
    const resultsTitle = document.getElementById('resultsTitle');
    const resultsTable = document.getElementById('resultsTable');
    const noResults = document.getElementById('noResults');
    const selectAllResults = document.getElementById('selectAllResults');
    const paySelectedBtn = document.getElementById('paySelectedBtn');

    const unpaidUrl = '{{ route('challans.search-unpaid') }}';

    const showResults = (data, title) => {
        resultsArea.style.display = 'block';
        noResults.style.display = 'none';
        resultsTitle.textContent = title;
        resultsTable.innerHTML = '';

        if (data.length === 0) {
            resultsTable.innerHTML = '<tr><td colspan="8" class="text-center py-4">No unpaid challans found</td></tr>';
            paySelectedBtn.disabled = true;
            return;
        }

        data.forEach((challan, i) => {
            resultsTable.innerHTML += `
                <tr>
                    <td class="text-center">
                        <input type="checkbox" name="challan_ids[]" value="${challan.id}" class="resultCheck form-check-input">
                    </td>
                    <td>${i + 1}</td>
                    <td><strong>${challan.student.name}</strong></td>
                    <td>${challan.student.rollnum ?? 'N/A'}</td>
                    <td class="fw-bold text-primary">${challan.challan_no}</td>
                    <td class="fw-bold text-success">Rs. ${Number(challan.amount).toFixed(2)}</td>
                    <td>${new Date(challan.issue_date).toLocaleDateString('en-GB')}</td>
                    <td>${new Date(challan.due_date).toLocaleDateString('en-GB')}</td>
                </tr>`;
        });

        paySelectedBtn.disabled = true;
        updatePayButton();
    };

    const showNoResults = () => {
        resultsArea.style.display = 'none';
        noResults.style.display = 'block';
    };

    const updatePayButton = () => {
        const checked = document.querySelectorAll('.resultCheck:checked').length;
        paySelectedBtn.disabled = checked === 0;
        paySelectedBtn.innerHTML = checked > 0 
            ? `<i class="bi bi-check2-circle me-2"></i> Mark ${checked} Challan${checked > 1 ? 's' : ''} as Paid`
            : `<i class="bi bi-check2-circle me-2"></i> Mark Selected as Paid`;
    };

    // Search by Challan No
    searchChallanBtn.addEventListener('click', () => {
        const challanNo = searchChallanInput.value.trim();
        if (!challanNo) return alert('Please enter Challan No');

        fetch(`${unpaidUrl}?challan_no=${challanNo}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    showNoResults();
                } else {
                    showResults(data, `Challan Found: ${challanNo}`);
                }
            });
    });

    // Search by Roll No
    searchRollBtn.addEventListener('click', () => {
        const rollNo = searchRollInput.value.trim();
        if (!rollNo) return alert('Please enter Roll No');

        fetch(`${unpaidUrl}?roll_no=${rollNo}`)
            .then(r => r.json())
            .then(data => {
                if (data.length === 0) {
                    showNoResults();
                } else {
                    showResults(data, `Unpaid Challans for Roll No: ${rollNo}`);
                }
            });
    });

    // Select All
    document.addEventListener('change', (e) => {
        if (e.target.id === 'selectAllResults') {
            document.querySelectorAll('.resultCheck').forEach(cb => cb.checked = e.target.checked);
            updatePayButton();
        } else if (e.target.classList.contains('resultCheck')) {
            const allChecked = document.querySelectorAll('.resultCheck').length === document.querySelectorAll('.resultCheck:checked').length;
            selectAllResults.checked = allChecked && document.querySelectorAll('.resultCheck:checked').length > 0;
            updatePayButton();
        }
    });
});
</script>
@endsection