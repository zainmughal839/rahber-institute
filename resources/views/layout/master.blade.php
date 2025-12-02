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



    <!-- student create  -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {

        let select = document.getElementById('session_program_id');
        let feesInput = document.getElementById('fees');

        if (!select || !feesInput) return;

        // Check if editing existing value
        let existingFees = "{{ $student->fees_amount ?? '' }}";

        // ✔ If editing and fees already exist → DO NOT auto-load fees
        let allowAutoLoad = (existingFees === "" || existingFees === "0");

        function loadSPInfo(spId) {
            if (!spId || !allowAutoLoad) return; // 🚫 Stop auto-load if editing existing fees

            fetch("{{ url('session-program-info') }}/" + spId)
                .then(res => res.json())
                .then(data => {
                    feesInput.value = data.fees;
                })
                .catch(err => console.error(err));
        }

        // Only auto-load when new assignment (not edit)
        select.addEventListener('change', function() {
            loadSPInfo(this.value);
        });

    });
    </script>

    <!-- teacher create -->


</body>

</html>
