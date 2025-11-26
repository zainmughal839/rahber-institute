{{-- resources/views/session_program/index.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">

                <!-- Card Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-journal-bookmark-fill me-2"></i>
                            All Session Programs
                        </h3>

                        <!-- Header Buttons -->
                        <div class="card-tools">
                            <a href="{{ route('session_program.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Program
                            </a>

                            @if(isset($showAll))
                            <a href="{{ route('session_program.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('session_program.index', ['all' => 'true']) }}"
                                class="btn btn-outline-light btn-sm">
                                <i class="bi bi-list-ul"></i> View All Records
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">#</th>
                                    <th width="250">Program Title</th>
                                    <th width="200">Session</th>
                                    <th width="200">Seats</th>
                                    <th width="200">Fees</th>
                                    <th width="130" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessionPrograms as $program)
                                <tr>
                                    <!-- Serial -->
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <!-- Program Title -->
                                    <td>
                                        {{ $program->program->name ?? '-' }}

                                        @if($program->program->shortname)
                                        ({{ $program->program->shortname }})
                                        @endif

                                        @if($program->program->program_code)
                                        - {{ $program->program->program_code }}
                                        @endif
                                    </td>

                                    <!-- Session Date Range -->
                                    <td>
                                        {{ \Carbon\Carbon::parse($program->session->start_date)->format('d M, Y') }}
                                        –
                                        {{ \Carbon\Carbon::parse($program->session->end_date)->format('d M, Y') }}
                                    </td>

                                    <td>
                                        {{ $program->seats ?? '-' }}
                                    </td>
                                    <td> {{ number_format($program->fees, 2) ?? '-' }}</td>
                                    <!-- Actions -->
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('session_program.edit', $program->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $program->id }}" data-name="{{ $program->program->name }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $program->id }}"
                                            action="{{ route('session_program.destroy', $program->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-x display-1 d-block mb-3 opacity-50"></i>
                                        <h4>No session programs found</h4>
                                        <p>Create your first program to get started.</p>
                                        <a href="{{ route('session_program.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-circle"></i> Add First Program
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if (!isset($showAll) && $sessionPrograms->hasPages())
                <div class="card-footer bg-light border-top">
                    <div class="d-flex justify-content-center mt-3 mb-3">
                        {{ $sessionPrograms->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete program "${name}"? This cannot be undone!`,
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