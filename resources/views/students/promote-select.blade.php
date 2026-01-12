@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow border-0">
        <div class="card-header bg-primary text-white py-3">
            <div class=" d-flex justify-content-between">
            <h4 class="mb-0">
                <i class="bi bi-arrow-up-circle me-2"></i>
                Promote Student - Select Student
            </h4>

            {{-- Header Buttons --}}
            <div class="d-flex align-items-center gap-2">


                {{-- Back --}}
                <a href="{{ route('students.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-list-ul me-1"></i> All Student
                </a>

                {{-- View All --}}
                @if($students instanceof \Illuminate\Pagination\LengthAwarePaginator && $students->hasMorePages())
                    <a href="{{ route('students.promote.select', array_merge(request()->query(), ['all' => true])) }}"
                       class="btn btn-outline-light  btn-sm">
                        <i class="bi bi-list-ul me-1"></i> View All
                    </a>
                @endif

                {{-- Back to Pagination --}}
                @if(!($students instanceof \Illuminate\Pagination\LengthAwarePaginator))
                    <a href="{{ route('students.promote.select', request()->except('all')) }}"
                       class="btn btn-outline-light btn-sm">
                        <i class="bi bi-arrow-left-circle me-1"></i> Paginated View
                    </a>
                @endif

                
            </div>
            </div>
        </div>

        <div class="card-body">

            {{-- Search Form --}}
            <form method="GET" action="{{ route('students.promote.select') }}" class="mb-4">
                <div class="input-group">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search by Name, Roll No or Father Name..."
                           value="{{ request('search') }}">

                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search me-1"></i> Search
                    </button>

                    @if(request('search'))
                        <a href="{{ route('students.promote.select') }}"
                           class="btn btn-outline-secondary">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Roll No</th>
                            <th>Father Name</th>
                            <th>Current Class</th>
                            <th width="220">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                {{ $students instanceof \Illuminate\Pagination\LengthAwarePaginator
                                    ? $loop->iteration + ($students->currentPage() - 1) * $students->perPage()
                                    : $loop->iteration }}
                            </td>
                            <td class="fw-semibold">{{ $student->name }}</td>
                            <td>{{ $student->rollnum ?? 'N/A' }}</td>
                            <td>{{ $student->father_name }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $student->classSubject?->class_name ?? 'Not Assigned' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.promote.form', $student->id) }}"
                                       class="btn btn-success">
                                        <i class="bi bi-arrow-up-right-circle me-1"></i> Promote
                                    </a>

                                    <a href="{{ route('students.promotion.history', $student->id) }}"
                                       class="btn btn-info text-white">
                                        <i class="bi bi-clock-history me-1"></i> History
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-exclamation-circle fs-3 d-block mb-3"></i>
                                No students found{{ request('search') ? ' matching your search' : '' }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer Info --}}
            <div class="text-muted mt-3">
                @if($students instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    Showing <strong>{{ $students->firstItem() }}</strong> to
                    <strong>{{ $students->lastItem() }}</strong> of
                    <strong>{{ $students->total() }}</strong> students
                @else
                    Showing <strong>{{ $students->count() }}</strong> student{{ $students->count() !== 1 ? 's' : '' }}
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
