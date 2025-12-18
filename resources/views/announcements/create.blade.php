@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">

        


        <div class="card-header bg-primary text-white ">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold mb-0">
                     <i class="bi bi-megaphone me-2"></i>
                {{ isset($announcement) ? 'Edit Announcement' : 'Create Announcement' }}
                </h3>

                @can('announcement.create')
                <a href="{{ route('announcements.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-list-ul me-1"></i> All Tasks
                </a>
                @endcan
            </div>
        </div>

        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                action="{{ isset($announcement) ? route('announcements.update', $announcement->id) : route('announcements.store') }}">
                @csrf
                @isset($announcement) @method('PUT') @endisset

                <div class="row g-3">

                    {{-- AUDIENCE --}}
                    <div class="col-md-6">
                        <label class="fw-semibold">Audience</label>
                        <select name="audience[]" id="audience-select" class="form-control my-select" multiple required>
                            <option value="teacher" {{ isset($announcement) && in_array('teacher',$announcement->audience ?? []) ? 'selected' : '' }}>Teacher</option>
                            <option value="student" {{ isset($announcement) && in_array('student',$announcement->audience ?? []) ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    {{-- TEACHERS --}}
                    <div class="teacher-field col-md-6">
                        <label class="fw-semibold">Teachers</label>
                        <select name="teacher_ids[]" class="my-select col-12" multiple style="width: 100%;">
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ isset($announcement) && $announcement->teachers->pluck('id')->contains($t->id) ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Select one or more teachers</small>
                    </div>

                    {{-- TITLE --}}
                    <div class="col-12 teacher-field">
                        <label class="fw-semibold">Announcement Title</label>
                        <input type="text" name="title" class="form-control" 
                               value="{{ old('title', $announcement->title ?? '') }}" required>
                    </div>

                    {{-- TEACHER DESCRIPTION --}}
                    <div class="col-12 teacher-field">
                        <label class="fw-semibold">Description</label>
                        <textarea name="teacher_desc" class="form-control">{{ old('teacher_desc', $announcement->teacher_desc ?? '') }}</textarea>
                    </div>

                    <hr class="student-field">

                    {{-- STUDENT CATEGORY --}}
                    <div class="col-md-6 student-field">
                        <label class="fw-semibold">Student Category</label>
                        <select name="stu_category_ids[]" id="stu-category-select" class="my-select col-12" multiple style="width: 100%;">
                            @foreach($studentCategories as $sc)
                                <option value="{{ $sc->id }}" {{ isset($announcement) && $announcement->studentCategories->pluck('id')->contains($sc->id) ? 'selected' : '' }}>
                                    {{ $sc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- SESSION PROGRAM --}}
                    <div class="col-md-6 student-field">
                        <label class="fw-semibold">Session Program</label>
                        <select name="session_program_id" id="session-program-select" class="form-select">
                            <option value="">-- Select --</option>
                            @foreach($sessionPrograms as $sp)
                                <option value="{{ $sp->id }}" {{ isset($announcement) && $announcement->session_program_id == $sp->id ? 'selected' : '' }}>
                                    {{ $sp->session->sessions_name ?? '' }} | {{ $sp->programs->pluck('name')->join(', ') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PROGRAMS --}}
                    <div class="col-md-6 student-field">
                        <label class="fw-semibold">Program</label>
                        <select name="program_ids[]" id="program-select" class="my-select col-12" multiple style="width: 100%;">
                            {{-- Options loaded via JS --}}
                        </select>
                    </div>

                    {{-- STUDENTS --}}
                    <div class="col-12 student-field">
                        <label class="fw-semibold">Students</label>
                        <div class="border p-2" style="max-height:220px;overflow:auto">
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="select-all-students">
                                <label class="form-check-label fw-bold" for="select-all-students">Select All Students</label>
                            </div>
                            <div id="student-checkbox-list">
                                {{-- Options loaded via JS --}}
                            </div>
                        </div>
                    </div>

                    {{-- ACTIVE --}}
                    <div class="col-12">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" {{ old('is_active', $announcement->is_active ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>

                </div>

                {{-- BUTTONS --}}
                <div class="mt-3 d-flex justify-content-between">
                     @can('announcement.create')
                    <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Back</a>
                    @endcan
                    <button class="btn btn-success">{{ isset($announcement) ? 'Update' : 'Create' }}</button>
                </div>

            </form>
        </div>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function () {

    /* ================= TOGGLE FIELDS ================= */
    function toggleFields() {
        let audience = $('#audience-select').val() || [];
        $('.teacher-field').toggle(audience.includes("teacher"));
        $('.student-field').toggle(audience.includes("student"));
    }

    toggleFields();
    $('#audience-select').on('change', toggleFields);

    /* ================= LOAD PROGRAMS ================= */
    function loadPrograms(spId, selectedPrograms = [], callback = null) {
        $('#program-select').empty();

        if (!spId) {
            if(callback) callback();
            return;
        }

        $.get(`/ajax/get-programs/${spId}`, function (programs) {
            programs.forEach(p => {
                $('#program-select').append(
                    `<option value="${p.id}" ${selectedPrograms.includes(p.id) ? 'selected' : ''}>
                        ${p.name}
                     </option>`
                );
            });

            if(callback) callback();
        });
    }

    /* ================= LOAD STUDENTS ================= */
    function loadStudents(programIds = [], categoryIds = [], selectedStudents = []) {
        $('#student-checkbox-list').html('');
        $('#select-all-students').prop('checked', false);

        if (programIds.length === 0) {
            $('#student-checkbox-list').html('<small class="text-muted">Select program first</small>');
            return;
        }

        $.ajax({
            url: "{{ route('ajax.students.filter') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                program_ids: programIds,
                category_ids: categoryIds
            },
            success: function (students) {
                if (!students.length) {
                    $('#student-checkbox-list').html('<small class="text-muted">No students found</small>');
                    return;
                }

                students.forEach(s => {
                    let checked = selectedStudents.includes(s.id) ? 'checked' : '';
                    $('#student-checkbox-list').append(`
                        <div class="form-check">
                            <input class="form-check-input student-checkbox"
                                   type="checkbox"
                                   name="student_ids[]"
                                   value="${s.id}"
                                   ${checked}>
                            <label class="form-check-label">
                                ${s.name} (${s.rollnum ?? ''})
                            </label>
                        </div>
                    `);
                });

                // Update select-all checkbox
                $('#select-all-students').prop('checked', $('.student-checkbox').length === $('.student-checkbox:checked').length);
            }
        });
    }

   

    /* ================= EDIT MODE FOR ANNOUNCEMENTS ================= */
    @if(isset($announcement))
        let edit = {
            session_program_id: {{ $announcement->session_program_id ?? 'null' }},
            programIds: @json($announcement->programs->pluck('id')),
            studentIds: @json($announcement->students->pluck('id')),
            categoryIds: @json($announcement->studentCategories->pluck('id')),
            teacherIds: @json($announcement->teachers->pluck('id')),
            audience: @json($announcement->audience ?? []),
            is_active: {{ $announcement->is_active ? 'true' : 'false' }}
        };

        // Set audience
        $('#audience-select').val(edit.audience).trigger('change');

        // Set teacher select
        $('select[name="teacher_ids[]"]').val(edit.teacherIds).trigger('change');

        // Set student categories
        $('#stu-category-select').val(edit.categoryIds).trigger('change');

        // Set session program
        $('select[name="session_program_id"]').val(edit.session_program_id).trigger('change');

        // Load programs first, then students
        loadPrograms(edit.session_program_id, edit.programIds, function() {
            loadStudents(edit.programIds, edit.categoryIds, edit.studentIds);
        });

        // Set active checkbox
        $('input[name="is_active"]').prop('checked', edit.is_active);

        // Set title and description
        $('input[name="title"]').val("{{ $announcement->title }}");
        $('textarea[name="teacher_desc"]').val(`{!! $announcement->teacher_desc !!}`);
    @endif

});
</script>

@endsection
