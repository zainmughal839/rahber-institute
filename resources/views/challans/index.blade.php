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

                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-receipt me-2"></i> Fee Challans
                        </h3>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('challans.create') }}" class="btn btn-outline-light btn-sm shadow-sm">
                                <i class="bi bi-plus-circle me-1"></i> Generate Challan
                            </a>
                            <a href="{{ route('challans.pay') }}" class="btn btn-outline-light btn-sm shadow-sm">
                                <i class="bi bi-cash-coin me-1"></i> Pay Challans
                            </a>
                            <button class="btn btn-outline-light btn-sm" id="printSelected">
                                <i class="bi bi-printer"></i> Print Selected
                            </button>

                            <!-- View All / Back to Paginated -->
                            @if(isset($showAll) && $showAll)
                            <a href="{{ route('challans.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('challans.index', ['show_all' => 1]) }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-list-ul me-1"></i> View All Records
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card-body border-bottom py-3">
                    <form method="GET" action="{{ route('challans.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <input type="text" name="challan_no" class="form-control form-control-sm" placeholder="Challan No" value="{{ request('challan_no') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="roll_no" class="form-control form-control-sm" placeholder="Roll No" value="{{ request('roll_no') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select form-select-sm">
                                <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-funnel me-1"></i> Filter
                            </button>
                            <a href="{{ route('challans.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <form id="printForm" method="POST" action="{{ route('challans.printMultiple') }}" target="_blank">
                            @csrf
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th width="50" class="text-center">#</th>
                                        <th width="150">Challan No</th>
                                        <th>Student</th>
                                        <th width="110">Issue Date</th>
                                        <th width="110">Due Date</th>
                                        <th width="120">Amount</th>
                                        <th width="100" class="text-center">Status</th>
                                        <th width="200" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($challans as $index => $challan)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="challan-check" value="{{ $challan->id }}">
                                        </td>
                                        <td class="text-center fw-bold">
                                            {{ isset($showAll) ? ($loop->iteration) : ($challans->firstItem() + $loop->index) }}
                                        </td>
                                        <td class="fw-semibold">{{ $challan->challan_no }}</td>
                                        <td>
                                            <strong>{{ $challan->student->name }}</strong><br>
                                            <small class="text-muted">Roll: {{ $challan->student->rollnum ?? '-' }}</small>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($challan->issue_date)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($challan->due_date)->format('d-m-Y') }}</td>
                                        <td class="fw-bold text-success">
                                            Rs. {{ number_format($challan->amount, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $challan->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2">
                                                {{ ucfirst($challan->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('challans.print', $challan->id) }}" target="_blank"
                                                   class="btn btn-info btn-sm" title="Print Challan">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                                {{-- <a href="{{ route('challans.edit', $challan->id) }}"
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>--}}
                                                <button type="button"
                                                        class="btn btn-danger btn-sm delete-challan"
                                                        data-id="{{ $challan->id }}"
                                                        data-no="{{ $challan->challan_no }}"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>

                                            <form id="delete-form-{{ $challan->id }}"
                                                  action="{{ route('challans.destroy', $challan->id) }}"
                                                  method="POST" style="display:none;">
                                                @csrf @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="bi bi-receipt-cutoff display-1 d-block mb-3"></i>
                                            <h4>No Challans Found</h4>
                                            <p>Try adjusting filters or generate new challans.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>

                @if(!isset($showAll) || !$showAll)
                <div class="card-footer bg-light border-top">
                    {{ $challans->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.challan-check').forEach(cb => cb.checked = this.checked);
});

document.getElementById('printSelected').addEventListener('click', function () {
    let checked = document.querySelectorAll('.challan-check:checked');
    if (checked.length === 0) {
        alert('Please select at least one challan');
        return;
    }

    let form = document.getElementById('printForm');
    form.innerHTML = '@csrf';

    checked.forEach(cb => {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'challan_ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });

    form.submit();
});
</script>

<!-- SweetAlert2 for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-challan').forEach(btn => {
    btn.addEventListener('click', function () {
        const challanId = this.dataset.id;
        const challanNo = this.dataset.no;

        Swal.fire({
            title: 'Delete Challan?',
            text: `Challan ${challanNo} will be permanently deleted!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + challanId).submit();
            }
        });
    });
});
</script>
@endsection