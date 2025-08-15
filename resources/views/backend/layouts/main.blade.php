<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="An Tâm E-commerce">
    <link rel="apple-touch-icon" href="/mini_logo.png">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ url('assets/fe') }}/img/favicon/favicon.ico" />

    <title>
        @if ($title != '')
            {{ $title }}
        @endif{{ config('app.name') }}
    </title>

    <meta name="description" content="@isset($description){{ $description }}@endisset">
    <meta name="keywords" content="@isset($keywords){{ $keywords }}@endisset">
    <meta name="author" content="@isset($author){{ $author }}@endisset">

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('/storage/backend/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/storage/backend/assets/plugins/dropzone-master/dist/dropzone.css') }}" rel="stylesheet"
        type="text/css" />

    @yield('style_top')

    <link href="{{ asset('/storage/backend/assets/plugins/bootstrap-select/bootstrap-select.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/storage/backend') }}/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend') }}/assets/plugins/select2/dist/css/select2-bootstrap.css"
        rel="stylesheet">
    <link href="{{ asset('/storage/backend') }}/js/fancybox/jquery.fancybox.min.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend/main/css/style.css') }}?v={{ config('constants.assets_version') }}"
        rel="stylesheet">
    <link href="{{ asset('/storage/backend/main/css/custom.css') }}?v={{ config('constants.assets_version') }}"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- You can change the theme colors from here -->
    <link href="{{ asset('/storage/backend/main/css/colors/blue.css') }}" id="theme" rel="stylesheet">
    <link href="{{ asset('/storage/backend/main/css/sweetalert2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Popup CSS -->
    <link href="{{ asset('/storage/backend') }}/assets/plugins/Magnific-Popup-master/dist/magnific-popup.css"
        rel="stylesheet">

    <link href="{{ asset('/storage/backend') }}/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend') }}/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="{{ asset('/storage/backend') }}/assets/plugins/datepicker/jquery.datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    @yield('style')

    <script>
        var BASE_URL = "{{ config('app.url') }}";

        var STATIC_URL = "{{ asset('/storage') }}";

        var PRODUCT_UNAPPROVED_URL = "{{ route('backend.products.ajax.un_approved') }}";
        var PRODUCT_APPROVED_URL = "{{ route('backend.products.ajax.approved') }}";
        var PRODUCT_DELETE_URL = "{{ route('backend.products.ajax.delete') }}";

        var USER_SEARCH_URL = "{{ route('backend.ajax.searchUser') }}";
    </script>

    @yield('script_top')

    <style>
        @font-face {
            font-family: 'google-sans';
            src: url('/fonts/google-sans/ProductSans-Light.woff') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            color: #0A0A0A;
        }

        #sidebarnav li {
            color: #fff !important;
            font-weight: bold;
        }

        #sidebarnav li i {
            color: #fff !important;
            font-weight: bold;
        }

        #sidebarnav li a {
            border-radius: 10px !important;

        }

        .sidebar-nav ul li a.active {
            background: #f26522 !important;
        }

        .sidebar-nav ul li a:hover {
            /*color: #1976d2;*/
            background: #ff967a !important;
        }

        .hidden {
            display: none;
        }

        .footer {
            position: fixed;
        }

        .actionToolbar_mobile,
        .mobile {
            display: none;
        }

        @media only screen and (max-width: 767px) {

            .topbar,
            .navbar-header,
            .navbar-collapse,
            .left-sidebar,
            .desktop,
            .fillter_desktop {
                display: none !important;
            }

            .actionToolbar_mobile,
            .mobile {
                display: block;
            }

            footer {
                margin-bottom: 55px;

            }

            footer>.draft-btn-create {
                display: none;

            }

            footer>.draft-btn-print {
                display: none;

            }
        }

        .actionToolbar_mobile {
            top: auto !important;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            margin: 0 auto;
            z-index: 999;
            height: 45px;
            width: 100%;
            border-top: 1px solid #e7e7e7;
            background: #fff;
            padding: 5px 10px;
        }

        .actionToolbar_mobile a {
            color: #0A0A0A;
        }

        .actionToolbar_mobile ul.actionToolbar_listing {
            margin: 0;
            padding: 0;
            display: -webkit-flex;
            display: -moz-flex;
            display: flex;
            -webkit-justify-content: space-between;
            justify-content: space-between;
            -webkit-align-items: center;
            align-items: center;
        }

        .actionToolbar_mobile ul.actionToolbar_listing li {
            width: 20%;
            float: left;
        }

        .actionToolbar_mobile ul {
            list-style: none;
        }

        .actionToolbar_listing li i {
            font-size: 22px;
        }
    </style>

</head>

<body>

    @if (Route::currentRouteName() != 'backend.login' &&
            Route::currentRouteName() != 'backend.register' &&
            Route::currentRouteName() != 'backend.forgotPassword' &&
            Route::currentRouteName() != 'backend.changePassword')

        <div id="main-wrapper">
            @include('backend.partials.header')
            <div class="page-wrapper">

                @yield('content')

                <footer class="footer font-weight-bold text-dark text-right card-body">
                    <button class="btn btn-primary  draft-btn-create"
                        @if (request('key') == 1) style="display: none" @endif>
                        <i class="fas fa-file-excel"></i><span>Xuất file Excel</span>
                    </button>
                    <button class="btn btn-warning  draft-btn-cancel"
                        @if (request('key') != 2) style="display: none" @endif>
                        <i class="fas fa-file-excel"></i><span>
                            @if (request('key') == 1)
                                Xóa đơn hàng
                            @else
                                Hủy đơn hàng
                            @endif
                        </span>
                    </button>
                    <button class="btn btn-danger  draft-btn-delete"
                        @if (request('key') != 1) style="display: none" @endif>
                        Xóa đơn hàng
                    </button>


                    <button class="btn btn-secondary  draft-btn-print"
                        @if (request('key') == 1 or request('key') == 9) style="display: none" @endif>
                        <i class="fas fa-print"></i> In vận đơn
                    </button>
                </footer>
            </div>
        </div>


        <div class="actionToolbar_mobile visible-xs  " style="height: 60px;">
            <ul class="actionToolbar_listing">
                <?php $currentPage = Request::url(); ?>
                <li>
                    <a href="{{ Route('backend.ops-live.index') }}" rel="nofollow" aria-label="phone"
                        style="text-align: center; align-items: center; display: block; justify-content: center;
                  <?= $currentPage === Route('backend.ops-live.index') ? 'color: #f26522 !important' : '' ?>">
                        <i class="fa fa-home" aria-hidden="true"></i>
                        <p>Trang chủ</p>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/orders?key=1') }}" rel="nofollow" aria-label="phone"
                        style="text-align: center; align-items: center; display: block; justify-content: center;
                <?= $currentPage === url('admin/orders') ? 'color: #f26522 !important' : '' ?>">
                        <i class="fa fa-tag" aria-hidden="true"></i>
                        <p>Đơn hàng</p>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/doi_soat') }}" rel="nofollow" aria-label="phone"
                        style="text-align: center; align-items: center; display: block; justify-content: center;
                <?= $currentPage === url('admin/doi_soat') ? 'color: #f26522 !important' : '' ?>">
                        <i class="fa fa-calendar" aria-hidden="true"></i>
                        <p>Đối soát</p>
                    </a>
                </li>

                <li>
                    <a href="{{ route('backend.users.index') }}" rel="nofollow" aria-label="phone"
                        style="text-align: center; align-items: center; display: block; justify-content: center;

                <?= $currentPage === route('backend.users.index') ? 'color: #f26522 !important' : '' ?>

                ">
                        <i class="fa fa-cog" aria-hidden="true"></i>
                        <p>Cài đặt</p>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/admin/profile') }}" rel="nofollow" aria-label="phone"
                        style="text-align: center; align-items: center; display: block; justify-content: center;
                 <?= $currentPage === route('backend.users.profile') ? 'color: #f26522 !important' : '' ?>

                ">

                        <i class="fa fa-user" aria-hidden="true"></i>
                        <p>Tài khoản</p>
                    </a>
                </li>
            </ul>
        </div>
    @else
        @yield('content')
    @endif

    <script src="{{ asset('/storage/backend/assets/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('/storage/backend/assets/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('/storage/backend/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('/storage/backend/main/js/jquery.slimscroll.js') }}"></script>

    <!--Wave Effects -->
    <script src="{{ asset('/storage/backend/main/js/waves.js') }}"></script>

    <!--Menu sidebar -->
    <script src="{{ asset('/storage/backend/main/js/sidebarmenu.js') }}"></script>

    <!--stickey kit -->
    <script src="{{ asset('/storage/backend/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('/storage/backend/assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>

    <!--Custom JavaScript -->
    <script src="{{ asset('/storage/backend/main/js/custom.min.js') }}"></script>

    <script src="{{ asset('/storage/backend/main/js/mustache.min.js') }}"></script>

    <!-- Magnific popup JavaScript -->
    <script src="{{ asset('/storage/backend') }}/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup.min.js">
    </script>
    <script src="{{ asset('/storage/backend') }}/assets/plugins/Magnific-Popup-master/dist/jquery.magnific-popup-init.js">
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- chartist chart -->
    <script src="{{ asset('/storage/backend') }}/assets/plugins/chartist-js/dist/chartist.min.js"></script>
    <script
        src="{{ asset('/storage/backend') }}/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js">
    </script>

    <script src="{{ asset('/storage/backend') }}/assets/plugins/datepicker/jquery.datetimepicker.js"></script>

    <script>
        jQuery('.date_time_select').css({
            'cursor': 'pointer'
        }).datetimepicker({
            format: 'Y-m-d',
            step: 15,
            lang: 'vi',

        });
        $('.footer').hide();
        $("#selectAllCheckbox").click(function() {

            $('.footer').toggle();
        });

        $(".check-input").change(function() {
            var numberOfChecked = $(".check-input:checked").length;
            if (numberOfChecked === 0) {
                $('.footer').hide();
            } else {
                $('.footer').show();
            }
        });

        $(".draft-btn-print").click(function() {
            var selectedValues = $(".check-input:checked").map(function() {
                return $(this).val();
            }).get();

            Swal.fire({
                title: "Lưu ý",
                html: '<span>khổ 52 x 70 mm và khổ 80 x 80 mm chỉ dành cho máy in nhiệt, in và dán trực tiếp lên món hàng! </span> <br>' +
                    '<button id="button1" class="btn btn-info m-2">In khổ A5</button>' +
                    '<button id="button2" class="btn btn-info m-2">In Khổ 52 x 70 mm</button>' +
                    '<button id="button3" class="btn btn-info m-2">In Khổ 80 x 80 mm</button>',
                showConfirmButton: false
            });

        });
        $(".draft-btn-create").click(function() {
            var selectedValues = $(".check-input:checked").map(function() {
                return $(this).val();
            }).get();

            if (selectedValues.length > 0) {
                var selectedValuesString = selectedValues.join(',');
                window.location.href = '{{ route('backend.orders.export') }}?selectedValues=' +
                    selectedValuesString;
            } else {
                alert("Không có giá trị nào được chọn.");
            }


        });

        function printLabel(url) {
            var selectedOrderCode = $(".check-input:checked").map(function() {
                return $(this).attr('data-order-code');
            }).get();
            var settings = {
                "url": "https://online-gateway.ghn.vn/shiip/public-api/v2/a5/gen-token",
                "method": "POST",
                "timeout": 0,
                "headers": {
                    "Content-Type": "application/json",
                    "Token": "7bb01781-4af7-11ed-b824-262f869eb1a7"
                },
                "data": JSON.stringify({
                    "order_codes": selectedOrderCode
                }),
            };
            $.ajax(settings).done(function(response) {
                if (response.code === 200) {
                    window.open(url + "?token=" + response.data.token, "_blank");
                } else {
                    Swal.fire(response.message);
                }
            });
        }

        function CancelOrdermobie(id) {
            let data = {
                ids: [id],
                _token: '{{ csrf_token() }}',
            }
            console.log(data)
            Swal.fire({
                title: "Thông báo?",
                text: "Bạn muốn xóa đơn hàng?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            }).then((result) => {
                if (result.value === true) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('backend.orders.cancel.list') }}',
                        dataType: 'json',
                        data: data,
                        success: function(data) {
                            let timerInterval;
                            Swal.fire({
                                title: "Đang Xóa",
                                html: "Vui lòng đợi .",
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                    const timer = Swal.getPopup().querySelector("b");
                                    const timeLeft = Swal.getTimerLeft();
                                    timer.textContent = Math.ceil(timeLeft /
                                        1000); // Chia lấy phần nguyên và làm tròn lên
                                    timerInterval = setInterval(() => {
                                        const timeLeft = Swal.getTimerLeft();
                                        timer.textContent = Math.ceil(timeLeft /
                                            1000); // Cập nhật số giây còn lại
                                    }, 1000);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {
                                window.location.reload();
                            });

                        }
                    });
                }
            });
        }

        $(document).on("click", ".draft-btn-cancel", function() {
            var selectedValues = $(".check-input:checked").map(function() {
                return $(this).val();
            }).get(); //
            let data = {
                ids: selectedValues,
                _token: '{{ csrf_token() }}',
            }
            console.log(data)

            Swal.fire({
                title: "Thông báo?",
                text: "Bạn muốn hủy đơn hàng?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            }).then((result) => {
                if (result.value === true) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('backend.orders.cancel.list') }}',
                        dataType: 'json',
                        data: data,
                        success: function(data) {
                            let timerInterval;
                            Swal.fire({
                                title: "Đang hủy",
                                html: "Vui lòng đợi .",
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                    const timer = Swal.getPopup().querySelector(
                                        "b");
                                    const timeLeft = Swal.getTimerLeft();
                                    timer.textContent = Math.ceil(timeLeft /
                                        1000
                                    ); // Chia lấy phần nguyên và làm tròn lên
                                    timerInterval = setInterval(() => {
                                        const timeLeft = Swal
                                            .getTimerLeft();
                                        timer.textContent = Math.ceil(
                                            timeLeft / 1000
                                        ); // Cập nhật số giây còn lại
                                    }, 1000);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {
                                window.location.reload();
                            });

                        }
                    });
                }
            });


        });
        $(document).on("click", ".draft-btn-delete", function() {
            var selectedValues = $(".check-input:checked").map(function() {
                return $(this).val();
            }).get(); //
            let data = {
                ids: selectedValues,
                _token: '{{ csrf_token() }}',
            }
            console.log(data)

            Swal.fire({
                title: "Thông báo?",
                text: "Bạn muốn xóa đơn hàng?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận"
            }).then((result) => {
                if (result.value === true) {
                    $.ajax({
                        type: "POST",
                        url: '{{ route('backend.orders.cancel.list') }}',
                        dataType: 'json',
                        data: data,
                        success: function(data) {
                            let timerInterval;
                            Swal.fire({
                                title: "Đang xóa",
                                html: "Vui lòng đợi .",
                                timer: 2000,
                                timerProgressBar: true,
                                didOpen: () => {
                                    Swal.showLoading();
                                    const timer = Swal.getPopup().querySelector(
                                        "b");
                                    const timeLeft = Swal.getTimerLeft();
                                    timer.textContent = Math.ceil(timeLeft /
                                        1000
                                    ); // Chia lấy phần nguyên và làm tròn lên
                                    timerInterval = setInterval(() => {
                                        const timeLeft = Swal
                                            .getTimerLeft();
                                        timer.textContent = Math.ceil(
                                            timeLeft / 1000
                                        ); // Cập nhật số giây còn lại
                                    }, 1000);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {
                                window.location.reload();
                            });

                        }
                    });
                }
            });


        });


        $(document).on("click", "#button1", function() {
            printLabel("https://online-gateway.ghn.vn/a5/public-api/printA5");
        });

        $(document).on("click", "#button2", function() {
            printLabel("https://online-gateway.ghn.vn/a5/public-api/print80x80");
        });

        $(document).on("click", "#button3", function() {
            printLabel("https://online-gateway.ghn.vn/a5/public-api/print52x70");
        });
    </script>

    <script src="{{ asset('/storage/backend/assets/plugins/select2/dist/js/select2.full.min.js') }}"
        type="text/javascript"></script>
    <script src="{{ asset('/storage/backend/assets/plugins/dropzone-master/dist/dropzone.js') }}" type="text/javascript">
    </script>

    <script src="{{ asset('/storage/backend/assets/plugins/bootstrap-select/bootstrap-select.min.js') }}"
        type="text/javascript"></script>

    <script src="{{ asset('/storage/backend') }}/js/custom.js?v={{ config('constants.assets_version') }}"></script>
    <script src="{{ asset('/storage/backend') }}/js/app.js?v={{ config('constants.assets_version') }}"></script>
    <script src="{{ asset('/storage/backend') }}/js/sweetalert2.min.js"></script>
    <script src="{{ asset('/storage/backend') }}/js/fancybox/jquery.fancybox.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>

    <script>
        LCK_App.url_get_district = "{{ route('location.district') }}";
        LCK_App.url_get_ward = "{{ route('location.ward') }}";
        LCK_App.google_maps_key = "{{ config('constants.google_maps_key') }}";
        LCK_App.init();
    </script>




    @yield('script')
    @yield('script2')
    @yield('script3')
    @yield('script4')
    <script>
        // Lấy phần tử có class alert
        window.addEventListener('DOMContentLoaded', (event) => {
            var alertElement = document.querySelector('.alert');
            if (alertElement) { // Check if the element exists
                setTimeout(function() {
                    alertElement.classList.add('hidden');
                }, 2500);
            } else {
                console.error("Alert element not found.");
            }
        });

        function logInput(event) {
            if (event.key === 'Enter') {
                var inputValue = event.target.value;
                // Chuyển hướng đến trang với order_code được truyền vào từ giá trị nhập
                window.location.href = `{{ url('/admin/orders?order_code=') }}${inputValue}`;
            }
        }

        $(document).on("click", ".btnsearch_ordercode", function() {
            var inputValue = $('.search_ordercode').val();
            window.location.href = `{{ url('/admin/orders?order_code=') }}${inputValue}`;
        });
    </script>
    <script>
        $(document).ready(function() {
            let table = new DataTable('#table_id', {
                // options
                language: {
                    search: "Tìm kiếm",
                    paginate: {
                        first: "Trang đầu",
                        previous: "Trang trước",
                        next: "Trang sau",
                        last: "Trang cuối"
                    },
                    emptyTable: "Không có dữ liệu",
                    info: "Hiển thị _START_ đến _END_ Tổng cộng _TOTAL_ ",
                    infoEmpty: "Không có dữ liệu, Hiển thị 0 bản ghi ",
                    zeroRecords: "Không có dữ liệu bạn muốn tìm",
                    infoFiltered: "",
                    lengthMenu: "Hiển thị số lượng _MENU_ ",
                    columns: [{
                            width: '20px',
                            targets: 0
                        },

                    ],

                },
                autoWidth: true

            });

        });
    </script>


</body>

</html>
