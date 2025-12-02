@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-primary card-outline shadow-lg border-0">

        

        <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                           <i class="bi bi-pencil-square me-2"></i> Edit Teacher
                        </h3>
                        <div class="card-tools">
                             @can('teacher.index')
                            <a href="{{ route('teachers.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-list-ul me-1"></i> All Teachers
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>

        @if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


        <form method="POST" action="{{ route('teachers.update', $teacher->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $teacher->name) }}" class="form-control"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CNIC</label>
                        <input type="text" name="cnic" value="{{ old('cnic', $teacher->cnic) }}" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email', $teacher->email) }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" value="{{ old('address', $teacher->address) }}"
                            class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="form-control @error('phone') is-invalid @enderror">
                        @error('phone') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Description</label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            class="form-control @error('description') is-invalid @enderror">
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
    <label class="form-label fw-semibold">Salary Amount <span class="text-danger">*</span></label>
   <input type="number" name="salary" value="{{ old('salary', $teacher->salary) }}" class="form-control" required>

    @error('salary')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<hr>


                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Profile Picture</label>
                        <input type="file" name="picture" class="form-control">
                        @if($teacher->picture)
                        <img src="{{ asset('storage/'.$teacher->picture) }}" class="img-thumbnail mt-2" height="60"
                            width="80">
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">CNIC Front</label>
                        <input type="file" name="cnic_front_image" class="form-control">
                        @if($teacher->cnic_front_image)
                        <img src="{{ asset('storage/'.$teacher->cnic_front_image) }}" class="img-thumbnail mt-2"
                            height="60" width="80">
                        @endif
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">CNIC Back</label>
                        <input type="file" name="cnic_back_image" class="form-control">
                        @if($teacher->cnic_back_image)
                        <img src="{{ asset('storage/'.$teacher->cnic_back_image) }}" class="img-thumbnail mt-2"
                            height="60" width="80">
                        @endif
                    </div>
                </div>

                <hr class="my-3">
                <h5 class="mb-3">Academic Details</h5>

                <div id="academic-wrapper">

                    @foreach($teacher->academic_details ?? [] as $i => $item)
                    <div class="academic-item border rounded p-3 mb-3">

                        <div class="row g-3 align-items-end">

                            <div class="col-md-3">
                                <label class="form-label">Degree</label>
                                <input type="text" name="academic[{{ $i }}][degree]" value="{{ $item['degree'] }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Institute</label>
                                <input type="text" name="academic[{{ $i }}][institute]" value="{{ $item['institute'] }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Passing Year</label>
                                <input type="text" name="academic[{{ $i }}][passing_year]"
                                    value="{{ $item['passing_year'] }}" class="form-control" required>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">Degree Image</label>
                                <input type="file" name="academic_images[{{ $i }}]" class="form-control">

                                @if(!empty($item['image']))
                                <img src="{{ asset('storage/'.$item['image']) }}" class="img-thumbnail mt-2" height="60"
                                    width="80">

                                <input type="hidden" name="academic[{{ $i }}][old_image]" value="{{ $item['image'] }}">
                                @endif
                            </div>

                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-danger remove-academic">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                    @endforeach

                </div>

                <button type="button" id="add-academic" class="btn btn-outline-primary btn-sm mt-2">
                    + Add Academic
                </button>

            </div>

            <div class="card-footer text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Update Teacher
                </button>
            </div>

        </form>
    </div>
</div>

<script>
// FIX 1: Correct index value
let index = {{ count($teacher->academic_details ?? []) }};

// FIX 2: Add academic field
document.getElementById('add-academic').addEventListener('click', function() {

    let html = `
    <div class="academic-item border rounded p-3 mb-3">
        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <label class="form-label">Degree</label>
                <input type="text" name="academic[${index}][degree]" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Institute</label>
                <input type="text" name="academic[${index}][institute]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Passing Year</label>
                <input type="text" name="academic[${index}][passing_year]" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Degree Image</label>
                <input type="file" name="academic_images[${index}]" class="form-control">
            </div>

            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-danger remove-academic">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

        </div>
    </div>
    `;

    document.getElementById('academic-wrapper').insertAdjacentHTML('beforeend', html);
    index++;
});

// FIX 3: Delete academic field (works for existing + new)
document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-academic')) {
        e.target.closest('.academic-item').remove();
    }
});
</script>



@endsection
