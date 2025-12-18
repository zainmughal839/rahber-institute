@extends('layout.master')

@section('title', 'Announcements')

@section('content')

@php
    $isStudent = session('is_panel_user') 
                 && auth()->user()->userAssignment?->panel_type === 'student';
@endphp


<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card card-primary card-outline shadow-lg border-0">

        {{-- HEADER --}}
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0">
                    <i class="bi bi-megaphone-fill me-2"></i> Announcements
                </h3>

                @can('announcement.create')
                <a href="{{ route('announcements.create') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Create Announcement
                </a>
                @endcan
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60" class="text-center">#</th>
                            <th>Title</th>
                        

                            @canany(['announcement.update','announcement.delete','announcement.index'])
                            <th width="140" class="text-center">Actions</th>
                            @endcanany
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($announcements as $announcement)
                        <tr>

                            {{-- SR --}}
                            <td class="text-center fw-bold">
                                {{ $loop->iteration + (($announcements->currentPage()-1) * $announcements->perPage()) }}
                            </td>

                            {{-- TITLE --}}
                            <td>
                                <strong>{{ $announcement->title }}</strong>
                            </td>

                          

                            {{-- ACTIONS --}}
                            @canany(['announcement.index','announcement.update','announcement.delete'])
                            <td class="text-center">
                                <div class="btn-group">

                                    @can('announcement.index')
                                    <a href="{{ route('announcements.show', $announcement->id) }}"
                                        class="btn btn-info btn-sm" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @endcan

                                    @can('announcement.update')
                                    <a href="{{ route('announcements.edit', $announcement->id) }}"
                                        class="btn btn-warning btn-sm" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    @endcan

                                    @can('announcement.delete')
                                    <button type="button" class="btn btn-danger btn-sm delete-btn"
                                        data-id="{{ $announcement->id }}" data-name="{{ $announcement->title }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    @endcan
                                </div>

                                <form id="delete-form-{{ $announcement->id }}"
                                    action="{{ route('announcements.destroy', $announcement->id) }}" method="POST"
                                    style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                            @endcanany

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-megaphone display-1 d-block mb-3"></i>
                                <h4>No Announcements Found</h4>

                                @can('announcement.create')
                                <a href="{{ route('announcements.create') }}" class="btn btn-primary mt-3">
                                    <i class="bi bi-plus-lg me-2"></i> Create First Announcement
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-3">
                    {{ $announcements->links() }}
                </div>

            </div>
        </div>
    </div>
</div>

{{-- DELETE CONFIRM --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let id = this.dataset.id;
            let name = this.dataset.name;
            Swal.fire({
                title: 'Are you sure?',
                text: `"${name}" will be permanently deleted!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endsection