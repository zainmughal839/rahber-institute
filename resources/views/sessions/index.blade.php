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
                            <!-- Add New Session Button -->
                            @can('session.create')
                            <a href="{{ route('sessions.create') }}" class="btn btn-light btn-sm shadow-sm me-2">
                                <i class="bi bi-plus-circle me-1"></i> Add New Session
                            </a>
                            @endcan
                            @if(isset($showAll))
                            <a href="{{ route('sessions.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-arrow-left-circle"></i> Back to Paginated
                            </a>
                            @else
                            <a href="{{ route('sessions.all') }}" class="btn btn-outline-light btn-sm">
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
                                    <th width="350">Start Date</th>
                                    <th width="350">End Date</th>
                                    <th>Description</th>
                                    <th width="130" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sessions as $s)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <span class="">
                                            {{ \Carbon\Carbon::parse($s->start_date)->format('d M, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="">
                                            {{ \Carbon\Carbon::parse($s->end_date)->format('d M, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ Str::limit($s->description, 100) ?: '-' }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <!-- Edit Button -->
                                            @can('session.update')
                                            <a href="{{ route('sessions.edit', $s->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                            <!-- Delete Button -->
                                            @can('session.delete')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $s->id }}" ...>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        <!-- Hidden Delete Form -->
                                        <form id="delete-form-{{ $s->id }}"
                                            action="{{ route('sessions.destroy', $s->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x display-1 d-block mb-3 opacity-50"></i>
                                        <h4>No sessions found</h4>
                                        <p>Create your first academic session to get started.</p>
                                        <a href="{{ route('sessions.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-circle"></i> Add First Session
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

<!-- SweetAlert2 Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');

        Swal.fire({
            title: 'Are you sure?',
            text: `Delete session from "${name}"? This cannot be undone!`,
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