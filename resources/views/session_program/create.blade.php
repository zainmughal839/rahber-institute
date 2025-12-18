@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline shadow-lg border-0">

                <!-- Header -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-link-45deg me-2"></i>
                            Assign Programs to Session
                        </h3>
                        <a href="{{ route('session_program.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-list-ul"></i> All Assignments
                        </a>
                    </div>
                </div>

                <!-- Errors -->
                @if ($errors->any())
                <div class="alert alert-danger m-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- Form -->
                <form method="POST"
                      action="{{ isset($item) ? route('session_program.update',$item->id) : route('session_program.store') }}">
                    @csrf
                    @isset($item) @method('PUT') @endisset

                    <div class="card-body">

                        <!-- Session -->
                        <div class="mb-4 col-8">
                            <label class="form-label fw-bold">Select Session <span class="text-danger">*</span></label>
                            <select name="session_id" class="form-control" required>
                                <option value="">-- Select Session --</option>
                                @foreach($sessions as $s)
                                    <option value="{{ $s->id }}"
                                        {{ old('session_id', $item->session_id ?? '') == $s->id ? 'selected' : '' }}>
                                        {{ $s->sessions_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        <!-- Programs Repeater -->
                        <h5 class="fw-bold mb-4">Programs</h5>

                        <div id="program-repeater">

                            @php
                                $rows = old('programs', $itemPrograms ?? [ [] ]);
                            @endphp

                            @foreach($rows as $index => $row)
                            <div class="row g-3 align-items-end program-row border rounded p-3 mb-3">

                                <!-- Program -->
                                <div class="col-md-5">
                                    <label class="form-label">Program <span class="text-danger">*</span></label>
                                    <select name="programs[{{ $index }}][program_id]" class="form-control" required>
                                        <option value="">-- Select Program --</option>
                                        @foreach($programs as $p)
                                            <option value="{{ $p->id }}"
                                                {{ ($row['program_id'] ?? '') == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Seats -->
                                <div class="col-md-3">
                                    <label class="form-label">Seats</label>
                                    <input type="number"
                                           name="programs[{{ $index }}][seats]"
                                           class="form-control"
                                           value="{{ $row['seats'] ?? '' }}"
                                           min="1">
                                </div>

                                <!-- Fees -->
                                <div class="col-md-3">
                                    <label class="form-label">Fees</label>
                                    <input type="number"
                                           name="programs[{{ $index }}][fees]"
                                           class="form-control"
                                           step="0.01"
                                           value="{{ $row['fees'] ?? '' }}">
                                </div>

                                <!-- Remove -->
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-danger remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                            </div>
                            @endforeach
                        </div>

                        <!-- Add Button -->
                        <button type="button" id="add-row" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Add Program
                        </button>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-save"></i> Save
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script>
let index = {{ count($rows) }};

document.getElementById('add-row').addEventListener('click', function () {
    let html = `
    <div class="row g-3 align-items-end program-row border rounded p-3 mb-3">
        <div class="col-md-5">
            <label class="form-label">Program</label>
            <select name="programs[${index}][program_id]" class="form-control" required>
                <option value="">-- Select Program --</option>
                @foreach($programs as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Seats</label>
            <input type="number" name="programs[${index}][seats]" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">Fees</label>
            <input type="number" step="0.01" name="programs[${index}][fees]" class="form-control">
        </div>
        <div class="col-md-1 text-end">
            <button type="button" class="btn btn-danger remove-row">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>`;
    document.getElementById('program-repeater').insertAdjacentHTML('beforeend', html);
    index++;
});

document.addEventListener('click', function (e) {
    if (e.target.closest('.remove-row')) {
        e.target.closest('.program-row').remove();
    }
});
</script>
@endsection
