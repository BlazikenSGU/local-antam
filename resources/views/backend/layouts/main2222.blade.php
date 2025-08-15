<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="@isset($description){{ $description }}@endisset">
    <meta name="keywords" content="@isset($keywords){{ $keywords }}@endisset">
    <meta name="author" content="@isset($author){{ $author }}@endisset">
    <title>
        @yield('title', config('app.name'))
    </title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ url('assets') }}/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">



    @yield('style_top')

    @yield('style')



    @yield('script_top')
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <style>
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">

        <a class="navbar-brand ps-3" href="{{ route('backend.ops-live.index') }}">AN TAM ECOMMERCE</a>

        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>

        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>

        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                    data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">{{ Auth::user()->fullname }},
                            {{ Auth::user()->id }}</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="{{ route('backend.logout') }}">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Admin</div>

                        <a class="nav-link" href="{{ route('backend.orders.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-file-invoice"></i></div>
                            Đơn hàng
                        </a>
                        <a class="nav-link" href="{{ route('backend.doi_soat.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                            Đối soát
                        </a>
                        <a class="nav-link" href="{{ route('backend.staff.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-users-gear"></i></div>
                            Tài khoản
                        </a>
                        <a class="nav-link" href="{{ route('backend.brands.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-shop-lock"></i></div>
                            ShopId
                        </a>

                        <a class="nav-link" href="{{ route('backend.notification.index') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-font-awesome"></i></div>
                            View Log
                        </a>

                        <a class="nav-link" href="{{ route('backend.brands.selectpicker') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-shop-lock"></i></div>
                            Select picker
                        </a>

                    </div>
                </div>

            </nav>
        </div>
        <div style="background-color: #c5c5c5;" id="layoutSidenav_content"
            class="{{ Route::currentRouteName() != 'backend.login' ? 'fix-header fix-sidebar card-no-border logo-center' : '' }} ">

            @if (Route::currentRouteName() != 'backend.login' &&
                    Route::currentRouteName() != 'backend.register' &&
                    Route::currentRouteName() != 'backend.forgotPassword' &&
                    Route::currentRouteName() != 'backend.changePassword')
                <main style="background-color: #c5c5c5; margin-bottom: 1rem;">
                    @yield('content')
                </main>
                <footer class="py-4 bg-light ">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; 2025</div>
                        </div>
                    </div>
                </footer>
            @else
                @yield('content')
            @endif
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


        <script>
            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: message,
                    confirmButtonText: 'Đóng'
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.backdrop) {
                        // Nếu người dùng click ra ngoài để tắt
                        location.reload();
                    }
                });
            }

            function showSuccess(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: message,
                    confirmButtonText: 'Đóng'
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.backdrop) {
                        // Nếu người dùng click ra ngoài để tắt
                        location.reload();
                    }
                });
            }

            @if (session('success'))
                showSuccess("{{ session('success') }}");
            @endif


            @if (session('error'))
                document.addEventListener('DOMContentLoaded', function() {
                    showError("{!! session('error') !!}");
                });
            @endif
        </script>

        <script src="{{ url('assets') }}/js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
            crossorigin="anonymous"></script>
        <script src="{{ url('assets') }}/js/datatables-simple-demo.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

        @yield('script')
</body>

</html>
