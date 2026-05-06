<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="{{ asset('assets/admin/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/admin/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Animation css -->
    <link href="{{ asset('assets/admin/vendor/animation/animate.min.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
          rel="stylesheet">

    <!--flag Icon css-->
    <link href="{{ asset('assets/admin/vendor/flag-icons-master/flag-icon.css') }}" rel="stylesheet" type="text/css">

    <!-- tabler icons-->
    <link href="{{ asset('assets/admin/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">

    <!-- apexcharts css-->
    {{-- <link href="{{ asset('assets/admin/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css"> --}}

    <!-- glight css -->
    <link href="{{ asset('assets/admin/vendor/glightbox/glightbox.min.css') }}" rel="stylesheet">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets/admin/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- simplebar css-->
    <link href="{{ asset('assets/admin/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets/admin/css/responsive.css') }}" rel="stylesheet" type="text/css">
    <style>
        .container-fluid {
            padding-right: 0;
            padding-left: 0;
        }
    </style>
    @yield('style')
</head>

<body>
    <div class="app-wrapper">

        <div class="loader-wrapper">
            <div class="loader_24"></div>
        </div>

        <!-- Menu Navigation starts -->
        @include('layouts.admin_include.sidebar')
        <!-- Menu Navigation ends -->

        <div class="app-content">
            <div class="">

                <!-- Header Section starts -->
                @include('layouts.admin_include.header')
                <!-- Header Section ends -->

                <!-- Body main section starts -->
                <main>
                    <div class="container-fluid">
                        @yield('content')

                        @isset($slot)
                            {{ $slot }}
                        @endisset
                    </div>
                </main>
            </div>
        </div>
        <!-- Body main section ends -->


        <!-- tap on top -->
        {{-- <div class="go-top">
            <span class="progress-value">
                <i class="ti ti-chevron-up"></i>
            </span>
        </div> --}}

        <!-- Footer Section starts-->
        <footer>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-9 col-12">
                        <p class="footer-text f-w-600 mb-0">Copyright © 2026 {{ config('app.name', 'Laravel') }}. All rights reserved</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Section ends-->
    </div>

    <!-- modal -->

    <!--customizer-->
    {{-- <div id="customizer"></div> --}}

    <!-- latest jquery-->
    <script src="{{ asset('assets/admin/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/admin/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- Simple bar js-->
    <script src="{{ asset('assets/admin/vendor/simplebar/simplebar.js') }}"></script>

    <!-- phosphor js -->
    <script src="{{ asset('assets/admin/vendor/phosphor/phosphor.js') }}"></script>

    <!-- Glight js -->
    <script src="{{ asset('assets/admin/vendor/glightbox/glightbox.min.js') }}"></script>

    <!-- apexcharts-->
    {{-- <script src="{{ asset('assets/admin/vendor/apexcharts/apexcharts.min.js') }}"></script> --}}

    <!-- Customizer js-->
    {{-- <script src="{{ asset('assets/admin/js/customizer.js') }}"></script> --}}

    <!-- Ecommerce js-->
    <script src="{{ asset('assets/admin/js/ecommerce_dashboard.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('assets/admin/js/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @yield('script')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('manage_squad'))

    <script>
        Swal.fire({
            title: 'Manage Squads',
            text: 'Do you want to manage squads for this programme?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/admin/squads/create?programme_id={{ session('manage_squad') }}";
            }
        });
    </script>

    @endif

    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonText: 'OK'
        });
    </script>
    @endif

</body>

</html>