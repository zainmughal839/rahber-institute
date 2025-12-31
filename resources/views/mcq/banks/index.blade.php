@extends('layout.master')

@section('title','MCQ Banks')

@section('content')
<div class="container-fluid py-4">

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                {{-- HEADER --}}
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-collection me-2"></i> MCQ Categories
                        </h3>

                          @can('mcq.banks.create')
                        <a href="{{ route('mcq.banks.create') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i> Add Categories
                        </a>
                        @endcan
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">#</th>
                                    <th>Bank Name</th>
                                    <th>Category</th>
                                    <th width="120" class="text-center">Status</th>
                                    @canany(['mcq.banks.update','mcq.banks.delete','mcq.banks.index']) 
                                    <th width="180" class="text-center">Actions</th>
                                    @endcanany
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($banks as $bank)
                                <tr>
                                    <td class="text-center fw-bold">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $bank->name }}
                                    </td>

                                    <td>
                                        <span class="badge bg-info">
                                            {{ $bank->category->name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge {{ $bank->status ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $bank->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    @canany(['mcq.banks.update','mcq.banks.delete','mcq.banks.index']) 
                                    <td class="text-center">
                                        <div class="btn-group">

                                      
                                         @can('mcq.banks.index')
                                            {{-- VIEW QUESTIONS --}}
                                            <a href="{{ route('mcq.banks.questions.index',$bank->id) }}"
                                                class="btn btn-primary btn-sm" title="View Questions">
                                                <i class="bi bi-question-circle"></i>
                                            </a>
                                            @endcan

                                        
                                             @can('mcq.banks.index')
                                            {{-- ADD QUESTIONS --}}
                                            <a href="{{ route('mcq.banks.questions.create',$bank->id) }}"
                                                class="btn btn-success btn-sm" title="Add Questions">
                                                <i class="bi bi-plus-circle"></i>
                                            </a>
                                            @endcan

                                              @can('mcq.banks.update')
                                            {{-- EDIT --}}
                                            <a href="{{ route('mcq.banks.edit',$bank->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @endcan

                                              @can('mcq.banks.delete')
                                            {{-- DELETE --}}
                                            <button type="button" class="btn btn-danger btn-sm delete-btn"
                                                data-id="{{ $bank->id }}" data-name="{{ $bank->name }}" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endcan
                                        </div>

                                        {{-- DELETE FORM --}}
                                        <form id="delete-form-{{ $bank->id }}"
                                            action="{{ route('mcq.banks.destroy',$bank->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                    @endcanany
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-collection-x display-1 d-block mb-3"></i>
                                        <h4>No MCQ Categories Found</h4>
                                        <p>Create MCQ Categories to add questions.</p>

                                        <a href="{{ route('mcq.banks.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Create First Bank
                                        </a>
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