@extends('layout.master')

@section('content')
<div class="container-fluid py-4">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Assign Session & Collect Fees — Step 2</h4>
        </div>

        <div class="card-body">
            <h5>Student:</h5>
            <p>
                <strong>{{ $student->name }}</strong>
                — Father: {{ $student->father_name }}
                — Roll: {{ $student->rollnum ?? 'Not assigned yet' }}
            </p>

            <form action="{{ route('students.assign', $student->id) }}" method="POST">
                @csrf
                <div class="row g-3">

                    {{-- Session Program --}}
                    <div class="col-md-6">
                        <label class="form-label">Session Program *</label>
                        <select name="session_program_id" id="session_program_id" class="form-control" required>
                            <option value="">-- Select Session Program --</option>
                            @foreach($sessionPrograms as $sp)
                            <option value="{{ $sp->id }}"
                                {{ isset($student) && $student->session_program_id == $sp->id ? 'selected' : '' }}>

                                {{ $sp->session->sessions_name ?? 'N/A' }} |
                                {{ \Carbon\Carbon::parse($sp->session->start_date)->format('d M, Y') }} -
                                {{ \Carbon\Carbon::parse($sp->session->end_date)->format('d M, Y') }} |
                                ({{ $sp->program->name ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-6">
                        <label>Total Fees *</label>
                        <input type="number" name="total_fees" class="form-control"
                            value="{{ $existingTotal->amount ?? '' }}" id="fees" required>
                    </div>

                    <div class="col-md-6">
                        <label>Advance Fees</label>
                        <input type="number" name="advance_fees" class="form-control"
                            value="{{ $existingAdvance->amount ?? '' }}">
                    </div>



                    <div class="col-12 text-end">
                        <button class="btn btn-success">Assign & Save</button>
                    </div>

                </div>
            </form>

            <!-- <hr>

            <h5>Ledger (Payments)</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->ledgers as $l)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $l->amount }}</td>
                        <td>{{ $l->type }}</td>
                        <td>{{ $l->description }}</td>
                        <td>{{ $l->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> -->

        </div>
    </div>
</div>
@endsection