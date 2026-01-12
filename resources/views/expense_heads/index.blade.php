{{-- resources/views/expense_heads/index.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">

                <!-- Card Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-list-check me-2"></i>
                            All Expense Heads
                        </h3>
                        <div class="card-tools">
                             @can('expense-head.create')
                            <a href="{{ route('expense_heads.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Expense Head
                            </a>
                            @endcan

                            @if(isset($showAll))
                            <a href="{{ route('expense_heads.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('expense_heads.all') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-list-ul me-1"></i> View All Records
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">#</th>
                                    <th width="150">Code</th>
                                    <th>Expense Head Name</th>
                                    <th>Description</th>
                                    <th width="120" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($expenseHeads as $head)
                                <tr>
                                    <td class="text-center fw-bold">
                                        @if(isset($showAll))
                                            {{ $loop->iteration }}
                                        @else
                                            {{ $loop->iteration + ($expenseHeads->currentPage() - 1) * $expenseHeads->perPage() }}
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info fw-bold px-3 py-2">{{ $head->code }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $head->name }}</strong>
                                    </td>
                                    <td>{{ $head->description ?? '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('expense_heads.edit', $head) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                             @can('expense-head.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $head->id }}"
                                                data-name="{{ $head->name }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $head->id }}"
                                            action="{{ route('expense_heads.destroy', $head) }}"
                                            method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt-cutoff display-1 d-block mb-3"></i>
                                        <h4>No expense heads found</h4>
                                        <p>Start by adding your first expense category like Salary, Utilities, etc.</p>
                                        <a href="{{ route('expense_heads.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add First Expense Head
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination (only show when not viewing all) -->
                @if (!isset($showAll) && $expenseHeads->hasPages())
                <div class="card-footer bg-light border-top">
                    {{ $expenseHeads->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 CDN + Delete Confirmation Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            Swal.fire({
                title: 'Are you sure?',
                text: `Delete expense head "${name}"? This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
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