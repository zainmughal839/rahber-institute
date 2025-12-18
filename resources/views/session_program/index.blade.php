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
                            @can('session_program.create')
                            <a href="{{ route('session_program.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Program
                            </a>
                            @endcan

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
                                    <th width="8" class="text-center">#</th>
                                    <th width="250">Program Titles</th>
                                    <th width="200">Session</th>
                                    @canany(['session_program.update', 'session_program.delete'])
                                    <th width="130" class="text-center">Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessionPrograms as $sp)
                                <tr>
                                    <!-- # -->
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <!-- Programs -->
                                    <td>
                                        @foreach($sp->programs as $p)
                                        <span class="badge bg-primary">{{ $p->name }}</span><br>
                                        @endforeach
                                    </td>

                                    <!-- Session -->
                                    <td>
                                        {{ $sp->session->sessions_name ?? '-' }}
                                    </td>

                                    @canany(['session_program.update','session_program.delete'])
                                    <td class="text-center">
                                        <div class="btn-group">

                                            @can('session_program.update')
                                            <a href="{{ route('session_program.edit',$sp->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                            @can('session_program.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $sp->id }}" data-name="{{ $sp->session->sessions_name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan

                                        </div>

                                        <form id="delete-form-{{ $sp->id }}"
                                            action="{{ route('session_program.destroy',$sp->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        No records found
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