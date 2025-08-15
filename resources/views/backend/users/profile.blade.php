@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor">{{ $title }}</h3>
        </div>
        <div class="col-md-7 align-self-center">
            {{ Breadcrumbs::render('Profile') }}
        </div>


    </div>
    <div class="col-md-12">
        <div class="card card-outline-info">
            <div class="card-body">

                <form class="form-horizontal" id="form_update" action="" method="post" enctype="multipart/form-data">

                    <div class="row">
                        <div class="col-md-6" style="margin: auto">
                            @include('backend.partials.msg')
                            @include('backend.partials.errors')

                            {{ csrf_field() }}

                            <div class="form-group">
                                <div class="col-md-3 offset-md-5">
                                    <input type="file" id="input-file-now-custom-1" name="file_avatar" class="dropify"
                                        data-default-file="{{ Auth()->guard('backend')->user()->avatar_file_path }} " />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Họ tên <span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control form-control-line" name="fullname"
                                        value="{{ Auth()->guard('backend')->user()->fullname }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Điện thoại</label>
                                <div class="col-md-12">
                                    <input type="text" disabled="disabled" class="form-control form-control-line"
                                        value="{{ Auth()->guard('backend')->user()->phone }}" name="phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Email</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control form-control-line"
                                        value="{{ Auth()->guard('backend')->user()->email }}" name="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Ngân hàng</label>
                                <div class="col-md-12">
                                    @foreach (format_name_bank() as $code => $name)
                                        <input type="text" class="form-control form-control-line"
                                            value="{{ Auth()->guard('backend')->user()->bank_name  }}" name="bank_name">
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Số tài khoản ngân hàng</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control form-control-line"
                                        value="{{ Auth()->guard('backend')->user()->bank_number }}" name="bank_number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Chủ tài khoản </label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control form-control-line"
                                        value="{{ Auth()->guard('backend')->user()->bank_account }}" name="bank_account">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Mật khẩu cũ</label>
                                <div class="col-md-12">
                                    <input type="password" class="form-control form-control-line" name="oldpassword">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Mật khẩu mới</label>
                                <div class="col-md-12">
                                    <input type="password" class="form-control form-control-line" name="newpassword">
                                </div>
                            </div>

                            <div class="form-group" style="display: none">
                                <label class="col-md-12">Mã OTP</label>
                                <div class="col-md-12">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="otp_code">
                                        <span style="cursor: pointer"
                                            class="input-group-text bg-primary text-white otp_code" id="basic-addon2">Lấy
                                            mã</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6" style="text-align: end">
                                    <div class="form-group">
                                        <a href="{{ route('backend.logout') }}">
                                            <button class="btn btn-danger" type="button">Đăng xuất
                                            </button>
                                        </a>
                                    </div>
                                </div>


                                <div class="col-md-6" style="text-align: start">
                                    <div class="form-group">
                                        <button class="btn btn-info btn_submit" type="submit">Cập nhật</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="container-fluid bg-body-tertiary d-block">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 col-lg-4" style="min-width: 500px;">
                                <div class="card bg-white mb-5 mt-5 border-0"
                                    style="box-shadow: 0 12px 15px rgba(0, 0, 0, 0.02);">
                                    <div class="card-body p-5 text-center">
                                        <h4>Nhập mã OTP</h4>
                                        <p>Vui lòng nhập mã OTP đã được gủi về email của bạn để hoàn tác</p>
                                        <p class="text-danger"></p>

                                        <div class="otp-field mb-4">
                                            <input type="number" />
                                            <input type="number" disabled />
                                            <input type="number" disabled />
                                            <input type="number" disabled />
                                            <input type="number" disabled />
                                            <input type="number" disabled />
                                        </div>

                                        <button class="btn btn-primary mb-3 confirm_otp">
                                            Xác nhận
                                        </button>


                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection


@section('style')
    <link rel="stylesheet" href="{{ asset('/storage/backend/assets/plugins/dropify/dist/css/dropify.min.css') }}">
    <style>
        .otp-field {
            flex-direction: row;
            column-gap: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .otp-field input {
            height: 45px;
            width: 42px;
            border-radius: 6px;
            outline: none;
            font-size: 1.125rem;
            text-align: center;
            border: 1px solid #ddd;
        }

        .otp-field input:focus {
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
        }

        .otp-field input::-webkit-inner-spin-button,
        .otp-field input::-webkit-outer-spin-button {
            display: none;
        }

        .resend {
            font-size: 12px;
        }

        .footer {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: black;
            font-size: 12px;
            text-align: right;
            font-family: monospace;
        }

        .footer a {
            color: black;
            text-decoration: none;
        }
    </style>
@stop

@section('script')
    <!-- jQuery file upload -->
    <script src="{{ asset('/storage/backend/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            //
            const inputs = document.querySelectorAll(".otp-field > input");
            const button = document.querySelector(".btn");

            window.addEventListener("load", () => inputs[0].focus());
            button.setAttribute("disabled", "disabled");

            inputs[0].addEventListener("paste", function(event) {
                event.preventDefault();

                const pastedValue = (event.clipboardData || window.clipboardData).getData(
                    "text"
                );
                const otpLength = inputs.length;

                for (let i = 0; i < otpLength; i++) {
                    if (i < pastedValue.length) {
                        inputs[i].value = pastedValue[i];
                        inputs[i].removeAttribute("disabled");
                        inputs[i].focus;
                    } else {
                        inputs[i].value = ""; // Clear any remaining inputs
                        inputs[i].focus;
                    }
                }
            });

            inputs.forEach((input, index1) => {
                input.addEventListener("keyup", (e) => {
                    const currentInput = input;
                    const nextInput = input.nextElementSibling;
                    const prevInput = input.previousElementSibling;

                    if (currentInput.value.length > 1) {
                        currentInput.value = "";
                        return;
                    }

                    if (
                        nextInput &&
                        nextInput.hasAttribute("disabled") &&
                        currentInput.value !== ""
                    ) {
                        nextInput.removeAttribute("disabled");
                        nextInput.focus();
                    }

                    if (e.key === "Backspace") {
                        inputs.forEach((input, index2) => {
                            if (index1 <= index2 && prevInput) {
                                input.setAttribute("disabled", true);
                                input.value = "";
                                prevInput.focus();
                            }
                        });
                    }

                    button.classList.remove("active");
                    button.setAttribute("disabled", "disabled");

                    const inputsNo = inputs.length;
                    if (!inputs[inputsNo - 1].disabled && inputs[inputsNo - 1].value !== "") {
                        button.classList.add("active");
                        button.removeAttribute("disabled");

                        return;
                    }
                });
            });

            // Basic
            $('.dropify').dropify();

            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });

            // Used events
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
            $('.btn_submit').on('click', function(e) {
                e.preventDefault();
                $('#exampleModal').modal('show');
                let data = {
                    _token: '{{ csrf_token() }}',
                };
                $.ajax({
                    type: "post",
                    url: '{{ route('backend.get_otp') }}',
                    dataType: 'json',
                    data: data,
                    success: function(response) {

                        if (response.code === 200) {
                            $('#exampleModal').modal('show');
                        } else {
                            alert(response.message)
                        }
                    }
                });

            })
            $('.confirm_otp').on('click', function(e) {
                e.preventDefault();
                let arrays = [];

                $('input[type="number"]').each(function() {
                    var val = $(this).val();
                    arrays.push(val); // Thêm giá trị vào mảng arrays
                });

                var combinedString = arrays.join('');
                $('input[name="otp_code"]').val(combinedString);
                //return;

                console.log(combinedString);
                let data = {
                    _token: '{{ csrf_token() }}',
                    otp: combinedString
                }
                $.ajax({
                    type: "POST",
                    url: '{{ route('backend.ajax.check_otp') }}',
                    dataType: 'json',
                    data: data,
                    success: function(data) {
                        console.log(data)
                        if (data.code == 200) {

                            $('#form_update').submit();
                        } else {
                            $('.text-danger').text(data.error)
                        }

                    }
                });

            })
        });
    </script>
    <script>
        $(document).ready(function() {
            var settings = {
                "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/province",
                "method": "GET",
                "timeout": 0,
                "headers": {
                    "token": "{{ config('constants.ghn_api_token') }}",
                    "Content-Type": "application/json"
                },
            };

            $.ajax(settings).done(function(response) {
                //province
                if (response.code === 200) {
                    let html =
                        `<option value="{{ Auth()->guard('backend')->user()->province_id }}">{{ Auth()->guard('backend')->user()->province_name }} </option>`;
                    for (const element of response.data) {
                        html += ` <option value="${element.ProvinceID}">${element.ProvinceName}</option>`
                    }
                    $('.province').html(html)
                } else {
                    alert(response.message)
                }
            });

            $(".otp_code").click(function() {

                let data = {
                    _token: '{{ csrf_token() }}',
                };
                $.ajax({
                    type: "post",
                    url: '{{ route('backend.get_otp') }}',
                    dataType: 'json',
                    data: data,
                    success: function(response) {

                        if (response.code === 200) {
                            Swal.fire({
                                title: "Thông báo.",
                                text: "Vui lòng kiểm tra email để láy mã OTP.",
                                icon: "question"
                            });
                        } else {
                            alert(response.message)
                        }
                    }
                });

            });

            $(".province").change(function() {
                var province_id = parseInt($(this).val());
                var text = $(".province option:selected").text();
                $('.province_name').val(text)


                var settings = {
                    "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/district",
                    "method": "GET",
                    "timeout": 0,
                    "headers": {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    "data": {
                        "province_id": province_id
                    },
                    "dataType": "json"
                };


                $.ajax(settings).done(function(response) {
                    console.log(response)
                    if (response.code === 200) {
                        let html = ``;
                        for (const element of response.data) {
                            html +=
                                ` <option value="${element.DistrictID}">${element.DistrictName}</option>`
                        }
                        $('.district').html(html)


                    } else {
                        alert(response.message)
                    }
                });
            });

            $(".district").change(function() {
                var district_id = parseInt($(this).val());
                var text = $(".district option:selected").text();

                $('.district_name').val(text)
                var settings = {
                    "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/ward",
                    "method": "GET",
                    "timeout": 0,
                    "headers": {
                        "token": "{{ config('constants.ghn_api_token') }}",
                        "Content-Type": "application/json"
                    },
                    "data": {
                        "district_id": district_id
                    },
                    "dataType": "json"

                };

                $.ajax(settings).done(function(response) {
                    if (response.code === 200) {
                        let html = `<option value="">Chọn </option>`;
                        for (const element of response.data) {
                            html +=
                                ` <option value="${element.WardCode}">${element.WardName}</option>`
                        }
                        $('.ward').html(html)
                    } else {
                        alert(response.message)
                    }
                });
            });

            $(".ward").change(function() {
                var text = $(".ward option:selected").text();
                $('.ward_name').val(text)
            });
        });
    </script>
@stop
