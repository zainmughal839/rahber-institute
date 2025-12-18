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

            <form action="{{ route('students.assign', $student->id) }}" method="POST" id="assignForm">
                @csrf
                <div class="row g-3">

                    {{-- Session Program --}}
                    <div class="col-md-6">
                        <label class="form-label">Session Program *</label>
                        <select name="session_program_id" id="session_program_id" class="form-control" required>
                            <option value="">-- Select Session Program --</option>
                            @foreach($sessionPrograms as $sp)
                            <option value="{{ $sp->id }}"
                                {{ (old('session_program_id', $student->session_program_id ?? '') == $sp->id) ? 'selected' : '' }}>
                                {{ $sp->session?->sessions_name ?? 'N/A' }} |
                                ({{ $sp->programs->pluck('name')->join(', ') ?: 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Program (populated after session_program selection) --}}
                    <div class="col-md-6">
                        <label class="form-label">Program *</label>
                        <select id="program_id" name="program_id" class="form-control" required>

                            <option value="">-- Select Program --</option>
                            {{-- options will be inserted by JS --}}
                        </select>
                    </div>

                    {{-- Class --}}
                    <div class="col-md-4">
                        <label>Class *</label>
                        <select id="class_subject_id" name="class_subject_id" class="form-control" required>
                            <option value="">-- Select Class --</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Total Fees *</label>
                        <input type="number" name="total_fees" class="form-control" id="fees"
                            value="{{ $existingTotal->amount ?? '' }}" required>
                    </div>

                    <div class="col-md-4">
                        <label>Advance Fees</label>
                        <input type="number" name="advance_fees" class="form-control" id="advance_fees"
                            value="{{ $existingAdvance->amount ?? '' }}">
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-success">Assign & Save</button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    let sp      = document.getElementById('session_program_id');
    let program = document.getElementById('program_id');
    let cls     = document.getElementById('class_subject_id');
    let feesInp = document.getElementById('fees');

    // 🔹 Edit mode values (Laravel se)
    let editProgramId = "{{ old('program_id', $student->program_id ?? '') }}";
    let editClassId   = "{{ old('class_subject_id', $student->class_subject_id ?? '') }}";
    let hasFees       = feesInp.value !== '';

    /* ===============================
       LOAD PROGRAMS
    =============================== */
    function loadPrograms(sessionProgramId, selectedProgramId = null) {
        if (!sessionProgramId) return;

        program.innerHTML = '<option>Loading...</option>';

        fetch('/ajax/programs/' + sessionProgramId)
            .then(r => r.json())
            .then(data => {
                program.innerHTML = '<option value="">-- Select Program --</option>';

                data.forEach(p => {
                    let selected = (selectedProgramId == p.id) ? 'selected' : '';
                    program.innerHTML += `<option value="${p.id}" ${selected}>${p.name}</option>`;
                });

                if (selectedProgramId) {
                    loadClasses(selectedProgramId, editClassId);
                    if (!hasFees) loadFees(sessionProgramId, selectedProgramId);
                }
            });
    }

    /* ===============================
       LOAD CLASSES
    =============================== */
    function loadClasses(programId, selectedClassId = null) {
        if (!programId) return;

        cls.innerHTML = '<option>Loading...</option>';

        fetch('/ajax/classes/' + programId)
            .then(r => r.json())
            .then(data => {
                cls.innerHTML = '<option value="">-- Select Class --</option>';

                data.forEach(c => {
                    let selected = (selectedClassId == c.id) ? 'selected' : '';
                    cls.innerHTML += `<option value="${c.id}" ${selected}>${c.class_name}</option>`;
                });
            });
    }

    /* ===============================
       LOAD FEES
    =============================== */
    function loadFees(sessionProgramId, programId) {
        fetch(`/ajax/program-fees/${sessionProgramId}/${programId}`)
            .then(r => r.json())
            .then(data => {
                if (data.fees > 0) {
                    feesInp.value = data.fees;
                }
            });
    }

    /* ===============================
       EVENTS
    =============================== */

    // Session Program change
    sp.addEventListener('change', function () {
        program.innerHTML = '<option>Loading...</option>';
        cls.innerHTML = '<option value="">-- Select Class --</option>';
        feesInp.value = '';

        loadPrograms(this.value);
    });

    // Program change
    program.addEventListener('change', function () {
        cls.innerHTML = '<option>Loading...</option>';

        loadClasses(this.value);
        loadFees(sp.value, this.value);
    });

    /* ===============================
       🔥 EDIT MODE AUTO LOAD
    =============================== */
    if (sp.value && editProgramId) {
        loadPrograms(sp.value, editProgramId);
    }
});
</script>


@endsection