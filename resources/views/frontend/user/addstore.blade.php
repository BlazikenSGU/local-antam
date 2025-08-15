@extends('frontend.layouts.main')

@section('content')

    <style>
        .l-label {
            color: #f26522;
            font-weight: bold;
        }
    </style>

    <div class="container">

        <div class="page-titles my-4">
            <h2>Thêm cửa hàng mới</h2>
        </div>
        <div class="col-md-12">
            <form class="form-horizontal" action="{{ route('user.mystore.storestore') }}" method="post"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-outline-info">
                            <div class="card-body">

                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Tên shop</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror" id=""
                                        placeholder="">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Số điện thoại</label>
                                    <input type="number" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror" id=""
                                        placeholder="">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Tỉnh thành</label>
                                    <select name="province_id" id="province_id"
                                        class="form-control province @error('province_id') is-invalid @enderror">
                                        <option value=""></option>
                                    </select>
                                    @error('province_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="hidden" class="form-control form-control-line province_name"
                                        value=" @if (!empty($data)) {{ $data->province_name }} @endif"
                                        name="province_name">
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Quận huyện</label>
                                    <select name="district_id" id="district_id"
                                        class="form-control district @error('district_id') is-invalid @enderror">
                                        <option value=""></option>
                                    </select>
                                    @error('district_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="hidden" class="form-control form-control-line district_name"
                                        value=" @if (!empty($data)) {{ $data->district_name }} @endif"
                                        name="district_name">
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Xã phường</label>
                                    <select name="ward_id" id="ward_id"
                                        class="form-control ward @error('ward_id') is-invalid @enderror">
                                        <option value=""></option>
                                    </select>
                                    @error('ward_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <input type="hidden" class="form-control form-control-line ward_name"
                                        value=" @if (!empty($data)) {{ $data->ward_name }} @endif"
                                        name="ward_name">
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Tên đường</label>
                                    <input type="text" name="street_name"
                                        class="form-control @error('street_name') is-invalid @enderror" id=""
                                        placeholder="">
                                    @error('street_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label"><span style="color: red;">Khu vực lấy hàng (*)</span> </label>
                                    <select name="pickup_area" id="pickup_area"
                                        class="form-control @error('pickup_area') is-invalid @enderror">
                                        <option value="" selected>Chọn</option>
                                        <option value="5795344">Siêu nặng 20KG - Miền Nam & Trung</option>
                                        <option value="5795042">Siêu nặng 20KG - Miền Bắc</option>
                                    </select>
                                    @error('pickup_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-outline-info">
                            <div class="card-body">
                                <h2 class="font-weight-bold" style="text-transform:uppercase;">
                                    Cài đặt lên đơn
                                </h2>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Lưu ý giao hàng</label>
                                    <select name="required_note" id="required_note" class="form-control">
                                        <option value="" selected>Chọn</option>
                                        <option value="KHONGCHOXEMHANG"> Không cho xem hàng</option>
                                        <option value="CHOXEMHANGKHONGTHU"> Cho xem hàng - Không cho thử</option>
                                        <option value="CHOXEMCHOTHUHANGHANGKHONGTHU"> Cho thử hàng</option>
                                    </select>
                                    @error('required_note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Bên thanh toán</label>
                                    <select name="payment_type" id="payment_type" class="form-control" disabled>
                                        <option value="">Chọn</option>
                                        <option value="1" selected>Bên gủi trả phí</option>
                                        <option value="2"> Bên nhận trả phí</option>
                                    </select>
                                    @error('payment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Đơn vị vận chuyển</label>

                                    <select name="transport_unit" id="transport_unit" class="form-control">
                                        <option value="" selected>Chọn</option>
                                        <option value="1">Giao Hàng Nhanh</option>
                                    </select>
                                    @error('transport_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Thu tiền khi giao thất bại</label>
                                    <input type="text" class="form-control @error('money_fail') is-invalid @enderror"
                                        name="money_fail" id="" placeholder="" value="0">

                                </div>

                                <div class="mb-3">
                                    <label for="" class="form-label l-label">Ghi chú</label>
                                    <textarea name="note" class="form-control @error('note') is-invalid @enderror" id="" row="5"
                                        placeholder="" style="height: 150px;"></textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">

                    <div class="my-4">
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@section('style')
    <link rel="stylesheet" href="{{ asset('/storage/backend/assets/plugins/dropify/dist/css/dropify.min.css') }}">
@stop

@section('script')
    <!-- jQuery file upload -->
    <script src="{{ asset('/storage/backend/assets/plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
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
        });
    </script>

    {{-- lay thong tin address tu api --}}
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


                if (response.code === 200) {

                    let html = `<option value="">Chọn tỉnh/thành</option>`;
                    response.data.forEach(element => {
                        html +=
                            `<option value="${element.ProvinceID}">${element.ProvinceName}</option>`;
                    });
                    $("#province_id").html(html);
                } else {
                    alert(response.message)
                }
            });


            //province
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


            //district
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
