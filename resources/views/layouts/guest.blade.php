<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="{{ asset('assets/admin/images/logo/favicon.png') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('assets/admin/images/logo/favicon.png') }}" rel="shortcut icon" type="image/x-icon">

    <title>Sign In | {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap"
          rel="stylesheet">

    <!-- tabler icons-->
    <link href="{{ asset('assets/admin/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">

    <!-- phosphor-icon css-->
    <link href="{{ asset('assets/admin/vendor/phosphor/phosphor-bold.css') }}" rel="stylesheet">

    <!-- Bootstrap css-->
    <link href="{{ asset('assets/admin/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css-->
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" type="text/css">

    <!-- Responsive css-->
    <link href="{{ asset('assets/admin/css/responsive.css') }}" rel="stylesheet" type="text/css">

    <style>
        @media screen and (max-width: 991px) {
            input{
                color: rgba(var(--primary), 1) !important;
            }
        }
        .form-content-box h2 {
            white-space: wrap !important;
        }
    </style>

</head>
<body class="sign-in-bg">
    <div class="app-wrapper d-block">
        <div class="">
            <!-- Body main section starts -->
            <div class="container main-container">
                <div class="row main-content-box">
                    <div class="col-lg-7 image-content-box d-none d-lg-block">
                        <div class="form-container">
                            <div class="signup-content mt-4">
                                <span>
                                <img alt="" class="img-fluid " src="{{ asset('assets/admin/images/logo/1.png') }}">
                                </span>
                            </div>

                            <div class="signup-bg-img">
                                <img alt="" class="img-fluid" src="{{ asset('assets/admin/images/login/01.png') }}">
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-5 form-content-box">
                        <div class="form-container ">
                            {{ $slot }}
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- Body main section ends -->
        </div>
    </div>
    <!-- latest jquery-->
    <script src="{{ asset('assets/admin/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/admin/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</body>

</html>