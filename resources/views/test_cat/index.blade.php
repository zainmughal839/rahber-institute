@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">

            <div class="card card-primary card-outline shadow-lg border-0">

                {{-- HEADER --}}
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-journal-text me-2"></i> Test Categories
                        </h3>

                        @can('test-cat.create')
                        <a href="{{ route('test-cat.create') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add New
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- BODY --}}
                <div class="card-body p-0">
                    <div class="table-responsive">

                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="80">#</th>
                                    <th>Name</th>
                                    <th>Description</th>

                                    @canany(['test-cat.update','test-cat.delete'])
                                    <th class="text-center" width="120">Actions</th>
                                    @endcanany
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($records as $cat)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>

                                    <td>{{ $cat->name }}</td>

                                    <td>
                                        <small class="text-muted">{{ $cat->desc ?? '-' }}</small>
                                    </td>

                                    @canany(['test-cat.update','test-cat.delete'])
                                    <td class="text-center">
                                        <div class="btn-group">

                                            @can('test-cat.update')
                                            <a href="{{ route('test-cat.edit', $cat->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                            @can('test-cat.delete')
                                            <button type="button"
                                                class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $cat->id }}"
                                                data-name="{{ $cat->name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan

                                        </div>

                                        {{-- Hidden Delete Form --}}
                                        @can('test-cat.delete')
                                        <form id="delete-form-{{ $cat->id }}"
                                            action="{{ route('test-cat.destroy', $cat->id) }}"
                                            method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endcan
                                    </td>
                                    @endcanany
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-text display-1 d-block mb-3"></i>
                                        <h4>No Test Categories Found</h4>

                                        @can('test-cat.create')
                                        <a href="{{ route('test-cat.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add First Category
                                        </a>
                                        @endcan
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
