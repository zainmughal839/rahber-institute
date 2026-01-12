@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-dark text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-receipt-cutoff me-2"></i> Expense Vouchers
                        </h3>
                        <div class="d-flex gap-2 flex-wrap">
                            @can('expense.create')
                            <a href="{{ route('expenses.create') }}" class="btn btn-outline-light btn-sm shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Add New Expense
                            </a>
                            @endcan

                            <!-- View All / Back to Paginated -->
                            @if(isset($showAll) && $showAll)
                            <a href="{{ route('expenses.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('expenses.index', ['show_all' => 1]) }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-list-ul me-1"></i> View All Records
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body border-bottom py-3">
                    <form method="GET" action="{{ route('expenses.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Voucher No</label>
                            <input type="text" name="voucher_no" class="form-control form-control-sm" 
                                   placeholder="e.g. EXP-0001" value="{{ request('voucher_no') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">All Types</option>
                                <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Expense (Debit)</option>
                                <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Refund (Credit)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">From Date</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">To Date</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-funnel me-1"></i> Apply Filter
                            </button>
                            <a href="{{ route('expenses.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th width="150">Voucher No</th>
                                    <th>Expense Head</th>
                                    <th width="110">Date</th>
                                    <th width="130" class="text-end">Amount</th>
                                    <th width="100" class="text-center">Type</th>
                                    <th width="140" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenses as $expense)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ isset($showAll) ? $loop->iteration : ($expenses->firstItem() + $loop->index) }}
                                    </td>
                                    <td class="fw-semibold">{{ $expense->voucher_no }}</td>
                                    <td>
                                        <strong>{{ $expense->title }}</strong>
                                        @if($expense->description)
                                        <br><small class="text-muted">{{ Str::limit($expense->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($expense->voucher_date)->format('d-m-Y') }}</td>
                                    <td class="text-end fw-bold {{ $expense->type === 'credit' ? 'text-success' : 'text-danger' }}">
                                        Rs. {{ number_format($expense->amount, 0) }}
                                        @if($expense->type === 'credit') <small>(Refund)</small> @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $expense->type === 'debit' ? 'bg-danger' : 'bg-success' }} px-3 py-2">
                                            {{ $expense->type === 'debit' ? 'Expense' : 'Refund' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('expenses.voucher', $expense->voucher_no) }}" target="_blank"
                                               class="btn btn-info btn-sm" title="Print Voucher">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                            @can('expense.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-expense"
                                                    data-id="{{ $expense->id }}"
                                                    data-no="{{ $expense->voucher_no }}"
                                                    title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        <!-- Delete Form -->
                                        <form id="delete-form-{{ $expense->id }}"
                                              action="{{ route('expenses.destroy', $expense->id) }}"
                                              method="POST" style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt-cutoff display-1 d-block mb-3"></i>
                                        <h4>No Expense Vouchers Found</h4>
                                        <p>Try adjusting filters or add a new expense.</p>
                                        @can('expense.create')
                                        <a href="{{ route('expenses.create') }}" class="btn btn-primary mt-2">
                                            <i class="bi bi-plus-circle me-1"></i> Add First Expense
                                        </a>
                                        @endcan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination - Only when not showing all -->
                @if(!isset($showAll) || !$showAll)
                <div class="card-footer bg-light border-top text-center">
                    {{ $expenses->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-expense').forEach(btn => {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const no = this.dataset.no;

        Swal.fire({
            title: 'Delete Voucher?',
            text: `Voucher ${no} will be permanently deleted!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>
@endsection