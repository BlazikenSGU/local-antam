<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ url('assets/fe') }}" data-template="vertical-menu-template-free">

<head>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="An Tâm E-commerce">
    <link rel="apple-touch-icon" href="/mini_logo.png">

    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0, viewport-fit=cover">

    <title> @stack('title') </title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ url('assets/fe') }}/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    {{-- select2     --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet" />
    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ url('assets/fe') }}/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="{{ url('assets/fe') }}/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ url('assets/fe') }}/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ url('assets/fe') }}/css/demo.css" />


    <link rel="stylesheet" href="{{ url('assets/fe') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="{{ url('assets/fe') }}/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ url('assets/fe') }}/vendor/js/helpers.js"></script>

</head>

<body>

    <style>
        .layout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .layout-menu-expanded .layout-overlay {
            opacity: 1;
            visibility: visible;
        }

        .badge.text-bg-success,
        .badge.text-bg-danger {
            all: revert;
            display: inline-block !important;
            padding: 0.35em 0.65em !important;
            font-size: 0.75em !important;
            font-weight: 700 !important;
            line-height: 1 !important;
            text-align: center !important;
            white-space: nowrap !important;
            vertical-align: baseline !important;
            border-radius: 0.375rem !important;
        }

        .badge.text-bg-success {
            background-color: #198754 !important;
            color: #fff !important;
        }

        .badge.text-bg-danger {
            background-color: #dc3545 !important;
            color: #fff !important;
        }

        .hr-menu-bar {
            margin: .5rem auto;
            width: 90%;
        }

        @media (max-width: 650px) {
            .menu .app-brand.demo {
                padding: 0 1rem;
            }

            .app-brand-text.demo {
                font-size: 20px !important;
                text-transform: none;
            }

            .menu-item {
                font-size: 14px !important;
            }
        }
    </style>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('user.index') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                <defs>
                                    <path
                                        d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                        id="path-1"></path>
                                    <path
                                        d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                                        id="path-3"></path>
                                    <path
                                        d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                                        id="path-4"></path>
                                    <path
                                        d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                                        id="path-5"></path>
                                </defs>
                                <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                        <g id="Icon" transform="translate(27.000000, 15.000000)">
                                            <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                <mask id="mask-2" fill="white">
                                                    <use xlink:href="#path-1"></use>
                                                </mask>
                                                <use fill="#696cff" xlink:href="#path-1"></use>
                                                <g id="Path-3" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-3"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                </g>
                                                <g id="Path-4" mask="url(#mask-2)">
                                                    <use fill="#696cff" xlink:href="#path-4"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4">
                                                    </use>
                                                </g>
                                            </g>
                                            <g id="Triangle"
                                                transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                                <use fill="#696cff" xlink:href="#path-5"></use>
                                                <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2"
                            style="font-size: 18px; text-transform: none;">An Tâm
                            Ecommerce</span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <hr class="hr-menu-bar">

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item">
                        <a href="{{ route('user.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-grid"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-item ">
                        <a href="{{ route('user.order.index') }}" class="menu-link">
                            <i class='menu-icon tf-icons bx bx-receipt'></i>
                            <div data-i18n="Analytics">Đơn hàng</div>
                        </a>
                    </li>

                    @php
                        $check = 0;
                        $shopIds = Auth::check() ? Auth::user()->shopId : [];

                        // Nếu là chuỗi JSON thì decode
                        if (!is_array($shopIds)) {
                            $shopIds = json_decode($shopIds, true) ?: [];
                        }

                        foreach ($shopIds as $shopId) {
                            $branch = \App\Models\Branch::where('shopId', $shopId)->first();
                            if ($branch && $branch->type == 2) {
                                $check = 1;
                                break;
                            }
                        }
                    @endphp

                    <li class="menu-item {{ $check == 1 ? '' : 'd-none' }}">
                        <a href="{{ route('user.channel.index') }}" class="menu-link">
                            <i class='menu-icon tf-icons bx  bx-store'></i>
                            <div data-i18n="Analytics">Kênh bán hàng</div>
                        </a>
                    </li>

                    <li class="menu-item ">
                        <a href="{{ route('user.mystore') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Cửa hàng</div>
                        </a>
                    </li>

                    <li class="menu-item ">
                        <a href="{{ route('user.doisoat') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                            <div data-i18n="Analytics">Đối soát</div>
                        </a>
                    </li>

                    <li class="menu-item ">
                        <a href="javascrip:void(0)" class="menu-link">
                            <i class='menu-icon tf-icons bx bx-pie-chart-alt'></i>
                            <div data-i18n="Analytics">Thống kê</div>
                        </a>
                    </li>


                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" role="button"
                            onclick="toggleMenu(event)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">

                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ url('assets/fe') }}/img/avatars/avatauser.png" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.profile') }}">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ url('assets/fe') }}/img/avatars/avatauser.png"
                                                            alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span
                                                        class="fw-semibold d-block">{{ Auth::user()->fullname }}</span>
                                                    <small class="text-muted">Id: {{ Auth::user()->id }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.profile') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Thông tin</span>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('backend.logout') }}">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Đăng xuất</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 ">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>

            </div>

        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

        function showSuccessandOpenOrder(message) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: message,
                html: '{{ session('success') }}',
                showCancelButton: false,
                confirmButtonColor: 'red',
                //cancelButtonColor: '#6c757d',
                confirmButtonText: 'Tạo thêm đơn',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('user.order.add') }}';
                }

                if (result.dismiss === Swal.DismissReason.backdrop) {
                    // Nếu người dùng click ra ngoài để tắt
                    location.reload();
                }
            });
        }

        @if (session('success'))
            showSuccess("{{ session('success') }}");
        @endif

        @if (session('successandopenOrder'))
            showSuccessandOpenOrder("{{ session('successandopenOrder') }}");
        @endif

        @if (session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                showError("{!! session('error') !!}");
            });
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.querySelector('html');
            const wrapper = document.querySelector('.layout-wrapper');
            const menu = document.querySelector('.layout-menu');
            const menuToggle = document.querySelector('.layout-menu-toggle');
            const menuIcon = menuToggle ? menuToggle.querySelector('.bx') : null;
            let isAnimating = false; // Biến để kiểm soát trạng thái đang xử lý


            function closeMenu() {
                if (isAnimating) return;
                isAnimating = true;

                wrapper.classList.remove('layout-menu-expanded');
                html.classList.remove('layout-menu-expanded');
                document.body.style.overflow = '';

                setTimeout(() => {
                    isAnimating = false;
                }, 300); // 300ms là thời gian để animation hoàn thành
            }

            function openMenu() {
                if (isAnimating) return;
                isAnimating = true;

                wrapper.classList.add('layout-menu-expanded');
                document.body.style.overflow = 'hidden';

                setTimeout(() => {
                    isAnimating = false;
                }, 300);
            }

            function handleMenuToggle(e) {
                if (e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                if (isAnimating) return; // Nếu đang xử lý thì bỏ qua

                if (wrapper.classList.contains('layout-menu-expanded')) {
                    closeMenu();
                } else {
                    openMenu();
                }
            }


            // Xử lý sự kiện cho nút toggle
            if (menuToggle) {
                menuToggle.style.cursor = 'pointer';

                // Chỉ xử lý một sự kiện click
                menuToggle.addEventListener('click', handleMenuToggle, {
                    once: false
                });

                // Xử lý riêng cho icon
                if (menuIcon) {
                    menuIcon.style.cursor = 'pointer';
                    menuIcon.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        handleMenuToggle(e);
                    });
                }
            }

            // Xử lý click bên ngoài để đóng menu
            document.addEventListener('click', function(e) {
                if (!isAnimating && wrapper.classList.contains('layout-menu-expanded')) {
                    const clickedOnToggle = menuToggle && menuToggle.contains(e.target);
                    const clickedOnMenu = menu && menu.contains(e.target);

                    if (!clickedOnToggle && !clickedOnMenu) {
                        closeMenu();
                    }
                }
            });

            // Ngăn chặn click trong menu lan ra ngoài
            if (menu) {
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Gán function cho window
            window.toggleMenu = handleMenuToggle;
            window.closeMenu = closeMenu;
        });
    </script>


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="{{ url('assets/fe') }}/vendor/libs/popper/popper.js"></script>

    <script src="{{ url('assets/fe') }}/vendor/js/bootstrap.js"></script>
    <script src="{{ url('assets/fe') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="{{ url('assets/fe') }}/vendor/js/menu.js"></script>


    <script src="{{ url('assets/fe') }}/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ url('assets/fe') }}/js/main.js"></script>
    <script src="{{ url('assets/fe') }}/js/dashboards-analytics.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    @yield('script')
</body>

</html>
