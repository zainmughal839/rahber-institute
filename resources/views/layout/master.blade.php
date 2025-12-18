<!----
    Project: Rahber Institute
    Developed By: Zain Mughal
    Email: zaindeveloper23@gmail.com
    Phone: +92325-8606798
---->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rahber Dashboard</title>

    <!--  Bootstrap + Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/adminlte.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        crossorigin="anonymous" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />

    <!-- popup -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">

    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="#" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/img/user2-160x160.jpg') }}"
                                class="user-image rounded-circle shadow" alt="User Image" />

                            <span class="d-none d-md-inline">
                                {{ Auth::user()->name ?? 'User' }}
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="./assets/img/user2-160x160.jpg" class="rounded-circle shadow"
                                    alt="User Image" />
                                <p>
                                    {{ Auth::user()->name ?? 'User' }}
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="{{ route('profile.edit') }}" class="btn btn-default btn-flat">Profile</a>

                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-default btn-flat float-end">
                                        Logout
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- SIDEBAR -->
        @include('layout.sidebar')

        <!-- PAGE CONTENT -->

        <main class="app-main">
            <div class="app-content-header">
                @yield('content')
            </div>

        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Anything you want</div>

            <strong>
                Copyright &copy; 2024-2025&nbsp;
                <a href="https://agilesolutionshub.com" class="text-decoration-none">Agile Solutions</a>.
            </strong>
            All rights reserved.
        </footer>

    </div>

    <!-- END WRAPPER -->

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous">
    </script>

    <script src="{{ asset('assets/js/adminlte.js') }}"></script>

    <!-- task & annuoncement js -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            /* ================= TOGGLE FIELDS ================= */
            function toggleFields() {
                let audience = $('#audience-select').val() || [];
                $('.teacher-field').toggle(audience.includes("teacher"));
                $('.student-field').toggle(audience.includes("student"));
            }
            toggleFields();
            $('#audience-select').on('change', toggleFields);
            /* ================= LOAD PROGRAMS ================= */
            function loadPrograms(spId, selectedPrograms = []) {
                $('#program-select').empty();
                if (!spId) return;
                $.get(`/ajax/get-programs/${spId}`, function(programs) {
                    programs.forEach(p => {
                        $('#program-select').append(
                            `<option value="${p.id}" ${selectedPrograms.includes(p.id) ? 'selected' : ''}>
                        ${p.name}
                     </option>`
                        );
                    });
                });
            }
            /* ================= LOAD STUDENTS (NO DUPLICATES) ================= */
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
                    success: function(students) {
                        if (!students.length) {
                            $('#student-checkbox-list').html(
                                '<small class="text-muted">No students found</small>');
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
                    }
                });
            }
            /* ================= EVENTS ================= */
            $('select[name="session_program_id"]').on('change', function() {
                loadPrograms(this.value);
                $('#student-checkbox-list').html(
                    '<small class="text-muted">Please select program</small>');
            });
            $('#program-select, #stu-category-select').on('change', function() {
                loadStudents(
                    $('#program-select').val() || [],
                    $('#stu-category-select').val() || []
                );
            });
            $(document).on('change', '#select-all-students', function() {
                $('.student-checkbox').prop('checked', this.checked);
            });
            /* ================= EDIT MODE ================= */
            if (window.editTask) {
                loadPrograms(
                    editTask.sessionProgramId,
                    editTask.programIds,
                    editTask.studentIds
                );
                loadStudents(
                    editTask.programIds,
                    editTask.categoryIds,
                    editTask.studentIds
                );
            }
        });
    </script>

    <!-- muliple option -->
    <script>
        $(document).ready(function() {
            $('.my-select').select2({
                placeholder: "Select Multiple option",
                allowClear: true,
                closeOnSelect: false
            });
        });

        function getSelected() {
            var selected = $('.my-select').val();
            alert("yes" + selected.join(", "));
        }
    </script>

    <!-- PopUp delete -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This session will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });
    </script>




</body>

</html>