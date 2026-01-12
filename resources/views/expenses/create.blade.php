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
                            <i class="bi bi-receipt-cutoff me-2"></i>
                            Add New Expense Voucher
                        </h3>
                         @can('expense.index')
                        <a href="{{ route('expenses.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-list-ul me-1"></i> All Expenses
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Alerts -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible m-3">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <h5><i class="bi bi-exclamation-triangle me-2"></i> Please fix the errors:</h5>
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
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        <!-- Voucher Date -->
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Voucher Date <span class="text-danger">*</span></label>
                                <input type="date" name="voucher_date" class="form-control" required
                                       value="{{ old('voucher_date', now()->format('Y-m-d')) }}">
                            </div>
                          
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Auto Voucher No</label>
                            <input type="text" class="form-control bg-light" value="{{ $nextVoucherNo ?? 'EXP-0001' }}" readonly>
                            <small class="text-muted">Auto-generated on save</small>
                        </div>

                        <!-- Expense Entries Repeater -->
                        <div class="mt-4">
                            <!-- <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0 text-danger">
                                    <i class="bi bi-cart-dash me-2"></i> Expense Entries
                                </h5>
                                <button type="button" id="addExpenseRow" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i> Add Entry
                                </button>
                            </div> -->

                            <div id="expensesContainer">
                                <!-- First Row -->
                                <div class="row mb-3 align-items-end expense-row">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Expense Head <span class="text-danger">*</span></label>
                                        <select name="entries[0][expense_head_id]" class="form-select expense-head-select" required>
                                            <option value="">Select Head</option>
                                            @foreach($expenseHeads as $head)
                                                <option value="{{ $head->id }}" data-code="{{ $head->code }}">
                                                    {{ $head->name }} ({{ $head->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Code</label>
                                        <input type="text" class="form-control head-code" readonly placeholder="Auto">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="entries[0][amount]" class="form-control amount-input" 
                                               placeholder="0" min="-999999999" step="1" required>
                                        <!-- <small class="text-muted">Use negative for refund/adjustment</small> -->
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Image (Optional)</label>
                                        <input type="file" name="entries[0][image]" class="form-control" accept="image/*">
                                    </div>

                                    <!-- <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-row mt-4">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div> -->

                                    <div class="col-12 mt-2">
                                        <textarea name="entries[0][description]" class="form-control" rows="2"
                                                  placeholder="Description (optional)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Summary -->
                        <div class="row mt-4">
                            <div class="col-md-4 offset-md-8">
                                <div class="card border-success">
                                    <div class="card-body text-end">
                                        <h5 class="text-success">
                                            Total Expense Amount: 
                                            <span id="totalAmount">Rs. 0</span>
                                        </h5>
                                        <small class="text-muted">Negative = Refund/Adjustment</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between">
                            @can('expense.index')
                            <a href="{{ route('expenses.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back
                            </a>
                            @endcan
                            <button type="submit" class="btn btn-success px-5">
                                <i class="bi bi-save me-1"></i> Save Expense Voucher
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowIndex = 1;

    // Auto-fill head code
    document.querySelectorAll('.expense-head-select').forEach(select => {
        select.addEventListener('change', function () {
            const code = this.options[this.selectedIndex].getAttribute('data-code');
            this.closest('.expense-row').querySelector('.head-code').value = code || '';
        });
    });

    // Add new row
    document.getElementById('addExpenseRow').addEventListener('click', function () {
        const container = document.getElementById('expensesContainer');
        const row = document.createElement('div');
        row.className = 'row mb-3 align-items-end expense-row';
        row.innerHTML = `
            <div class="col-md-4">
                <label class="form-label fw-semibold">Expense Head <span class="text-danger">*</span></label>
                <select name="entries[${rowIndex}][expense_head_id]" class="form-select expense-head-select" required>
                    <option value="">Select Head</option>
                    @foreach($expenseHeads as $head)
                        <option value="{{ $head->id }}" data-code="{{ $head->code }}">
                            {{ $head->name }} ({{ $head->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Code</label>
                <input type="text" class="form-control head-code" readonly placeholder="Auto">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                <input type="number" name="entries[${rowIndex}][amount]" class="form-control amount-input" 
                       placeholder="0" min="-999999999" step="1" required>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Image</label>
                <input type="file" name="entries[${rowIndex}][image]" class="form-control" accept="image/*">
            </div>

            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-danger btn-sm remove-row mt-4">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <div class="col-12 mt-2">
                <textarea name="entries[${rowIndex}][description]" class="form-control" rows="2"
                          placeholder="Description (optional)"></textarea>
            </div>
        `;

        container.appendChild(row);
        rowIndex++;

        // Re-attach event for new select
        row.querySelector('.expense-head-select').addEventListener('change', function () {
            const code = this.options[this.selectedIndex].getAttribute('data-code');
            row.querySelector('.head-code').value = code || '';
        });

        updateTotal();
    });

    // Remove row
    document.getElementById('expensesContainer').addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('.expense-row').remove();
            updateTotal();
        }
    });

    // Update total
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.amount-input').forEach(input => {
            const val = parseFloat(input.value) || 0;
            total += val;
        });
        document.getElementById('totalAmount').textContent = 
            'Rs. ' + Math.abs(total).toLocaleString() + (total < 0 ? ' (Refund)' : '');
    }

    document.querySelectorAll('.amount-input').forEach(input => {
        input.addEventListener('input', updateTotal);
    });

    updateTotal();
});
</script>
@endsection