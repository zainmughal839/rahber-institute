{{-- resources/views/permissions/index.blade.php --}}
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
                            <i class="bi bi-lock me-2"></i>
                            All Permissions
                            <span class="badge bg-light text-dark ms-2">
                                {{ $permissions->flatten()->count() }} Total
                            </span>
                        </h3>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80" class="text-center">#</th>
                                    <th>Group</th>
                                    <th>Permission</th>
                                    <th>Display Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions as $group => $perms)
                                @foreach($perms as $index => $p)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->parent->index * $perms->count() + $index + 1 }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ ucfirst($group) }}</span>
                                    </td>
                                    <td>
                                        <code>{{ $p->name }}</code>
                                    </td>
                                    <td>
                                        {{ $p->display_name ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-lock display-1 d-block mb-3 opacity-50"></i>
                                        <h4>No permissions found</h4>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection