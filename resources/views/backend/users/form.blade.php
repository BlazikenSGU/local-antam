@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">Cập nhật Quản lý cửa
                hàng</h3>
        </div>
    </div>
    <div class="col-md-12">
        <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
            @include('backend.partials.msg')
            @include('backend.partials.errors')

            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-outline-info">
                        <div class="card-body">
                            <h2 class="font-weight-bold" style="text-transform:uppercase; color: orangered">
                                Thông tin shop
                            </h2>
                            <div class="form-group">
                                <label class="col-md-12 font-weight-bold font-20">Tên shop<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text"
                                           class="form-control form-control-line"
                                           name="name"
                                           value="@if(!empty($data)) {{$data->name}} @endif">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 font-weight-bold font-20">Số điên thoại<span
                                        class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text"
                                           name="phone"
                                           class="form-control form-control-line"
                                           value="@if(!empty($data)) {{$data->phone}} @endif">

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold font-20">Tỉnh thành <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-control-line province"
                                            name="province_id" value="">
                                        <option value=""></option>
                                    </select>
                                    <input type="text" hidden=""
                                           class="form-control form-control-line province_name"
                                           value=" @if(!empty($data)) {{$data->province_name}} @endif"
                                           name="province_name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold font-20">Quận huyện <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control form-control-line district"
                                            name="district_id">
                                        <option
                                            value="@if(!empty($data)) {{$data->district_id}} @endif">@if(!empty($data))
                                                {{$data->district_name}}
                                            @endif</option>
                                    </select>
                                    <input type="text" hidden=""
                                           class="form-control form-control-line district_name"
                                           value="@if(!empty($data)) {{$data->district_name}} @endif"
                                           name="district_name">
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold font-20">Phường xã <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-line ward"
                                            name="ward_id">
                                        <option value="@if(!empty($data)) {{$data->ward_id}} @endif">@if(!empty($data))
                                                {{$data->ward_name}}
                                            @endif</option>
                                    </select>
                                    <input type="text" hidden=""
                                           class="form-control form-control-line ward_name"
                                           value="@if(!empty($data)) {{$data->ward_name}} @endif"
                                           name="ward_name">
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold font-20">Tên đường</label>
                                    <input type="text"
                                           class="form-control form-control-line"
                                           value="@if(!empty($data)) {{$data->street_name}} @endif"
                                           name="street_name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-outline-info">
                        <div class="card-body">
                            <h2 class="font-weight-bold" style="text-transform:uppercase; color: orangered">
                                Cài đặt lên đơn
                            </h2>
                            <div class="form-group">
                                <label class="font-weight-bold font-20">Lưu ý giao hàng</label>
                                <div class="">
                                    <select class="form-control form-control-line required_note" name="required_note">
                                        <option
                                            value="KHONGCHOXEMHANG" {{ !empty($data) && $data->required_note == 'KHONGCHOXEMHANG' ? 'selected' : '' }}>
                                            Không cho xem hàng
                                        </option>
                                        <option
                                            value="CHOXEMHANGKHONGTHU" {{ !empty($data) && $data->required_note == 'CHOXEMHANGKHONGTHU' ? 'selected' : '' }}>
                                            Cho xem hàng - Không cho thử
                                        </option>
                                        <option
                                            value="CHOTHUHANG" {{ !empty($data) && $data->required_note == 'CHOTHUHANG' ? 'selected' : '' }}>
                                            Cho thử hàng
                                        </option>

                                    </select>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold font-20">Tuỳ chọn thanh toán</label>
                                <select class="form-control form-control-line required_note" name="payment_type">
                                    <option
                                        value="1" {{ !empty($data) && $data->payment_type == '1' ? 'selected' : '' }}>
                                        Bên gủi trả phí
                                    </option>
                                    <option
                                        value="2" {{ !empty($data) && $data->payment_type == '2' ? 'selected' : '' }}>
                                        Bên nhận trả phí
                                    </option>

                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold font-20">Đơn vi vận chuyển</label>
                                <select class="form-control form-control-line required_note" name="" readonly="">
                                    <option
                                        value="1">
                                        Gao Hàng Nhanh
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold font-20">Loại hàng</label>
                                <div class="row">
                                    @foreach(\App\Models\Branch::get() as $product_type)
                                        <div class="col-6">
                                            <input type="radio" id="{{$product_type->id}}" name="product_type"
                                                   value="{{$product_type->id}}"
                                                   @if(!empty($data) && $data->product_type ==$product_type->id ) checked
                                                   @elseif($product_type->id == 31)
                                                       checked
                                                @endif>
                                            <label for="{{$product_type->id}}">{{$product_type->name}}</label>
                                        </div>
                                    @endforeach
                                </div>


                            </div>

                            <div class="form-group">
                                <label class=" font-20 font-weight-bold">Ghi chú</label>
                                <div class="">
                                    <textarea class="form-control form-control-sm" name="note" id="description"
                                              rows="5">{!! old('note', !empty($data) ? $data->note : '') !!}</textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card card-outline-info">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                @if( !empty($data) )
                                <button class="btn" type="submit"
                                        style="background-color:#F26522; color: #fff; font-size: 22px ">
                                    Lưu
                                </button>
                                @else
                                    <button class="btn" type="submit"
                                            style="background-color:#4754e8; color: #fff; font-size: 22px ">
                                        Tạo mới
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection


@section('style')
    <link rel="stylesheet" href="{{ asset('/storage/backend/assets/plugins/dropify/dist/css/dropify.min.css')}}">
@stop

@section('script')
    <!-- jQuery file upload -->
    <script src="{{ asset('/storage/backend/assets/plugins/dropify/dist/js/dropify.min.js')}}"></script>
    <script>
        $(document).ready(function () {
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

            drEvent.on('dropify.beforeClear', function (event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function (event, element) {
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function (event, element) {
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function (e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>
    <script>
        $(document).ready(function () {
            var settings = {
                "url": "https://online-gateway.ghn.vn/shiip/public-api/master-data/province",
                "method": "GET",
                "timeout": 0,
                "headers": {
                    "token": "{{ config('constants.ghn_api_token') }}",
                    "Content-Type": "application/json"
                },
            };

            $.ajax(settings).done(function (response) {
                //province
                if (response.code === 200) {
                    let html = `<option value="{{Auth()->guard('backend')->user()->province_id}}">{{Auth()->guard('backend')->user()->province_name}} </option>`;
                    for (const element of response.data) {
                        html += ` <option value="${element.ProvinceID}">${element.ProvinceName}</option>`
                    }
                    $('.province').html(html)
                } else {
                    alert(response.message)
                }
            });

            $(".province").change(function () {
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


                $.ajax(settings).done(function (response) {
                    console.log(response)
                    if (response.code === 200) {
                        let html = ``;
                        for (const element of response.data) {
                            html += ` <option value="${element.DistrictID}">${element.DistrictName}</option>`
                        }
                        $('.district').html(html)


                    } else {
                        alert(response.message)
                    }
                });
            });

            $(".district").change(function () {
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

                $.ajax(settings).done(function (response) {
                    if (response.code === 200) {
                        let html = `<option value="">Chọn </option>`;
                        for (const element of response.data) {
                            html += ` <option value="${element.WardCode}">${element.WardName}</option>`
                        }
                        $('.ward').html(html)
                    } else {
                        alert(response.message)
                    }
                });
            });

            $(".ward").change(function () {
                var text = $(".ward option:selected").text();
                $('.ward_name').val(text)
            });
        });
    </script>
@stop
