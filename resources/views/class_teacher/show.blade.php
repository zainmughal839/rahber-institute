@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Assignment Details
                </h3>

                <div class="card-tools">
                    @can('class-teacher.index')
                    <a href="{{ route('class-teacher.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left-circle me-1"></i> Back
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="card-body">

            <!-- Teacher Info -->
            <h4 class="fw-bold text-primary mb-3">
                <i class="bi bi-person-fill me-2"></i> Teacher Information
            </h4>
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px;">Teacher Name</th>
                    <td>{{ $record->teacher->name }}</td>
                </tr>
                <tr>
                    <th>CNIC</th>
                    <td>{{ $record->teacher->cnic }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $record->teacher->email }}</td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $record->teacher->phone }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $record->teacher->address }}</td>
                </tr>

            </table>

            <hr>

            <!-- Class & Subject Info -->
            <h4 class="fw-bold text-success mb-3">
                <i class="bi bi-book-half me-2"></i> Class & Subject Information
            </h4>
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px;">Class Name</th>
                    <td>{{ $record->classSubject->class_name ?? '-' }}</td>
                </tr>
                
                <tr>
    <th>Session Program Subjects</th>
    <td>
        @if($record->classSubject?->subjects->count())
            {{ $record->classSubject->subjects->pluck('book_name')->implode(', ') }}
        @else
            -
        @endif
    </td>
</tr>

                <tr>
                    <th>Description</th>
                    <td>{{ $record->classSubject->desc ?? '-' }}</td>
                </tr>

            </table>

            <hr>

            <!-- Session & Program Info -->
            <h4 class="fw-bold text-info mb-3">
                <i class="bi bi-calendar-event me-2"></i> Session & Program Info
            </h4>

            <table class="table table-bordered">
                <tr>
                    <th style="width:200px;">Program</th>
                    <td>
                        @if($record->classSubject?->programs->count())
                        {{ $record->classSubject->programs->pluck('name')->implode(', ') }}
                        @else
                        -
                        @endif
                    </td>
                </tr>



<tr>
    <th>Teacher Assigned Subjects</th>
    <td>
        @if($record->subjects->count())
            {{ $record->subjects->pluck('book_name')->implode(', ') }}
        @else
            -
        @endif
    </td>
</tr>


<tr>
    <th>Assigned Subject Short Names</th>
    <td>
        @if($record->subjects->count())
            {{ $record->subjects->pluck('book_short_name')->implode(', ') }}
        @else
            -
        @endif
    </td>
</tr>


                <tr>
                    <th>Program Code</th>
                    <td>
                        @if($record->classSubject?->programs->count())
                        {{ $record->classSubject->programs->pluck('program_code')->implode(', ') }}
                        @else
                        -
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>Session Start</th>
                    <td>
                        {{ $record->classSubject?->sessionProgram?->session?->start_date ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <th>Session End</th>
                    <td>
                        {{ $record->classSubject?->sessionProgram?->session?->end_date ?? '-' }}
                    </td>
                </tr>

            </table>

            <hr>

            <!-- Assignment Info -->
            <h4 class="fw-bold text-dark mb-3">
                <i class="bi bi-info-circle me-2"></i> Assignment Information
            </h4>

            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px;">Status</th>
                    <td>
                        <span class="badge {{ $record->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($record->status) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $record->desc ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Created At</th>
                    <td>{{ $record->created_at->format('d M, Y ') }}</td>
                </tr>

            </table>

        </div>

    </div>

</div>
@endsection