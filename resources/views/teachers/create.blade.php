@extends('layout.master')

@section('content')
<div class="container-fluid py-4">
    <div class="card card-primary card-outline shadow-lg border-0">

        

                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title fw-bold mb-0">
                            <i class="bi bi-person-plus me-2"></i> Add Teacher</h3>
                        </h3>
                        @can('teacher.index')
                        <div class="card-tools">
                            <a href="{{ route('teachers.index') }}" class="btn btn-light btn-sm">
                                <i class="bi bi-list-ul me-1"></i> All Teachers
                            </a>
                        </div>
                        @endcan
                    </div>
                </div>

         @if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        @if ($errors->has('db_error'))
            <li>{{ $errors->first('db_error') }}</li>
        @endif
    </ul>
</div>
@endif



        <form method="POST" action="{{ route('teachers.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <!-- Basic info row -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="form-control @error('name') is-invalid @enderror" placeholder="e.g. Muhammad Ali">
                        @error('name') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CNIC</label>
                        <input type="text" name="cnic" value="{{ old('cnic') }}"
                            class="form-control @error('cnic') is-invalid @enderror" placeholder="xxxxx-xxxxxxx-x">
                        @error('cnic') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="email@example.com">
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="form-control @error('address') is-invalid @enderror">
                        @error('address') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
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
    <input type="number" name="salary" value="{{ old('salary') }}" required 
           class="form-control @error('salary') is-invalid @enderror" 
           placeholder="Enter salary amount">
    @error('salary') 
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<hr>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Profile Picture</label>
                        <input type="file" name="picture" class="form-control @error('picture') is-invalid @enderror"
                            accept="image/*">
                        @error('picture') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">CNIC Front</label>
                        <input type="file" name="cnic_front_image"
                            class="form-control @error('cnic_front_image') is-invalid @enderror" accept="image/*">
                        @error('cnic_front_image') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">CNIC Back</label>
                        <input type="file" name="cnic_back_image"
                            class="form-control @error('cnic_back_image') is-invalid @enderror" accept="image/*">
                        @error('cnic_back_image') <div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                   
                </div>

                <hr class="my-3">

                <!-- Academic dynamic repeater -->
                <h5 class="mb-3">Academic Details</h5>
                <div id="academic-wrapper">
                    <template id="academic-template">
                        <div class="academic-item border rounded p-3 mb-3">
                            <div class="row g-3 align-items-end">

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Degree</label>
                                    <input type="text" name="academic[#][degree]" class="form-control" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Institute</label>
                                    <input type="text" name="academic[#][institute]" class="form-control" required>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Passing Year</label>
                                    <input type="text" name="academic[#][passing_year]" class="form-control" required>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Degree Image</label>
                                    <input type="file" name="academic_images[#]" class="form-control" accept="image/*">
                                </div>

                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-danger remove-academic">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </template>

                </div>

                <div class="mb-3">
                    <button type="button" id="add-academic" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i> Add Academic
                    </button>
                </div>

            </div>



            <div class="card-footer bg-light border-top">
               
                        <div class="d-flex justify-content-between align-items-center">
                             @can('teacher.index')
                           <a href="{{ route('teachers.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
                    Back</a>
                    @endcan

                           <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Save Teacher</button>
                        </div>
                    </div>
        </form>
    </div>
</div>


<script>
let index = 0;

document.getElementById('add-academic').addEventListener('click', function() {
    let template = document.getElementById('academic-template').innerHTML;
    template = template.replaceAll('#', index);
    document.getElementById('academic-wrapper').insertAdjacentHTML('beforeend', template);
    index++;
});
</script>


@endsection
