@extends('layout.master')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">

                <div class="card-header bg-primary text-white ">
                    <div class="d-flex justify-content-between">
                    <h3 class="card-title fw-bold mb-0">
                        <i class="bi {{ isset($class)?'bi-pencil-square':'bi-plus-circle' }}"></i>
                        {{ isset($class)?'Edit Class':'Add New Class' }}
                    </h3>
                     @can('class-subject.index')
                    <a href="{{ route('class-subjects.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-list-ul"></i> All Classes
                    </a>
                    @endcan
                    </div>
                </div>

                <form method="POST" action="{{ isset($class)? route('class-subjects.update',$class->id) : route('class-subjects.store') }}">
                    @csrf
                    @if(isset($class)) @method('PUT') @endif
                    <div class="card-body row g-4">

                        <!-- Class Name -->
                        <div class="col-md-6">
                            <label>Class Name</label>
                            <input type="text" name="class_name" class="form-control" 
                                   value="{{ old('class_name',$class->class_name??'') }}" required>
                        </div>

                        <!-- Subjects -->
                        <div class="col-md-6">
                            <label>Subjects</label>
                            <select name="subject_id[]" class="form-control select2" multiple required>
                                @foreach($subjects as $s)
                                    <option value="{{ $s->id }}" 
                                        @if(isset($class) && $class->subjects->pluck('id')->contains($s->id)) selected @endif>
                                        {{ $s->book_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Session Program -->
                        <div class="col-md-6">
                            <label>Session & Programs</label>
                            <select name="session_program_id" id="sessionProgramSelect" class="form-control" required>
                                <option value="">Select Session Program</option>
                                @foreach($sessionPrograms as $sp)
                                    <option value="{{ $sp->id }}" 
                                        {{ isset($class) && $class->session_program_id==$sp->id?'selected':'' }}>
                                        session: {{ $sp->session->sessions_name ?? 'N/A' }} — 
                                        Programs: {{ $sp->programs->pluck('name')->join(', ') ?: 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Programs dependent -->
                        <div class="col-md-6">
                            <label>Programs</label>
                            <select name="program_id[]" id="programSelect" class="form-control select2" multiple required>
                                @if(isset($class))
                                    @foreach($class->programs as $p)
                                        <option value="{{ $p->id }}" selected>{{ $p->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active" {{ isset($class) && $class->status=='active'?'selected':'' }}>Active</option>
                                <option value="inactive" {{ isset($class) && $class->status=='inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label>Description</label>
                            <textarea name="desc" class="form-control" rows="4">{{ old('desc',$class->desc??'') }}</textarea>
                        </div>

                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-success">{{ isset($class)?'Update':'Save' }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.select2').select2({ width:'100%' });

    function loadPrograms(spId, selected = []) {
        let programSelect = $('#programSelect');
        programSelect.empty();
        if(!spId) return;
        $.get('/ajax/get-programs/'+spId, function(data){
            data.forEach(p => {
                let selectedAttr = selected.includes(p.id) ? 'selected' : '';
                programSelect.append(`<option value="${p.id}" ${selectedAttr}>${p.name}</option>`);
            });
            programSelect.trigger('change');
        });
    }

    // On change
    $('#sessionProgramSelect').on('change', function(){
        loadPrograms($(this).val());
    });

    // If edit page: load programs of selected session program
    @if(isset($class))
        let selectedPrograms = @json($class->programs->pluck('id'));
        loadPrograms($('#sessionProgramSelect').val(), selectedPrograms);
    @endif
});
</script>
@endsection
