{{-- resources/views/sessions/index.blade.php --}}
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
                            <i class="bi bi-calendar3 me-2"></i>
                            All Sessions
                        </h3>
                        <div class="card-tools">
                            @can('session.create')
                            <a href="{{ route('sessions.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Session
                            </a>
                            @endcan

                            @if(isset($showAll))
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle me-1"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('sessions.all') }}" class="btn btn-outline-light btn-sm">
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
                                    <th width="200">Session Name</th>
                                    <th width="150">Start Year</th>
                                    <th width="150">End Year</th>
                                    <th width="140" class="text-center">Status</th>
                                    @canany(['session.update', 'session.delete'])
                                    <th width="120" class="text-center">Actions</th>
                                    @endcan
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($sessions as $s)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                     <!-- Session Name -->
                                    <td>
                                        <strong>{{ $s->sessions_name }}</strong>
                                    </td>

                                    <!-- Start / End Year -->
                                    <td>{{ $s->start_date }}</td>
                                    <td>{{ $s->end_date }}</td>

                                   

                                    <!-- Status -->
                                    <td class="text-center">
                                        @php
                                        $currentYear = now()->year;
                                        @endphp

                                        @if($currentYear >= (int)$s->start_date && $currentYear <= (int)$s->end_date)
                                            <span class="badge bg-success rounded-pill px-3">Active</span>
                                            @elseif($currentYear < (int)$s->start_date)
                                                <span
                                                    class="badge bg-warning text-dark rounded-pill px-3">Upcoming</span>
                                                @else
                                                <span class="badge bg-secondary rounded-pill px-3">Expired</span>
                                                @endif
                                    </td>

                                    @canany(['session.update', 'session.delete'])
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('session.update')
                                            <a href="{{ route('sessions.edit', $s->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                            @can('session.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $s->id }}" data-name="{{ $s->sessions_name }}"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        <form id="delete-form-{{ $s->id }}"
                                            action="{{ route('sessions.destroy', $s->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x display-1 d-block mb-3"></i>
                                        <h4>No sessions found</h4>
                                        <p>Add your academic or fiscal year sessions here.</p>
                                        <a href="{{ route('sessions.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add First Session
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if (!isset($showAll) && $sessions->hasPages())
                <div class="card-footer bg-light border-top">
                    {{ $sessions->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Delete Confirmation (same as programs) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete session "${name}"? This cannot be undone!`,
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