@extends('backend.layouts.main')

@section('content')

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-dark" style="border-bottom: 1px solid #000; display: inline-block">{{$subtitle}}</h3>
        </div>
        <div class="col-md-7 align-self-center font-weight-bold">
            {{ Breadcrumbs::render('backend.staff.add') }}
        </div>
    </div>
        <div class="col-md-12">
            <div class="card card-outline-info">
                <div class="card-body">

                    <form class="form-horizontal" action="" method="post">

                        @include('backend.partials.msg')
                        @include('backend.partials.errors')

                        {{ csrf_field() }}

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control form-control-line"
                                           value="{{$form_init->phone}}"
                                           name="phone">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control form-control-line"
                                           value="{{$form_init->fullname}}"
                                           name="fullname">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Email <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control form-control-line"
                                           value="{{$form_init->email}}"
                                           name="email">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Mật Khẩu <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control form-control-line"
                                           value="{{$form_init->password}}"
                                           name="password">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Tỉnh thành <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-line province"
                                            name="province_id"   value="{{$form_init->province_id}}">
                                        <option value="">Chọn</option>
                                    </select>
                                    <input type="text" hidden=""
                                           class="form-control form-control-line province_name"
                                           value=""
                                           name="province_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Quận huyện <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-line district"
                                            name="district_id"  value="{{$form_init->district_id}}">
                                        <option value="">Chọn</option>
                                    </select>
                                    <input type="text" hidden=""
                                           class="form-control form-control-line district_name"
                                           value=""
                                           name="district_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Phường xã <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-line ward"
                                            name="ward_id"  value="{{$form_init->ward_id}}">
                                        <option value="">Chọn</option>
                                    </select>
                                    <input type="text" hidden=""
                                           class="form-control form-control-line ward_name"
                                           value=""
                                           name="ward_name">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">Địa chỉ <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control form-control-line"
                                           value="{{$form_init->address}}"
                                           name="address">
                                </div>
                            </div>

                            @foreach(\App\Models\Branch::get() as $product_type)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Phí gủi - {{$product_type->name}}<span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control form-control-line"
                                               name="fee[]">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" hidden
                                               class="form-control form-control-line"
                                               value="{{$product_type->shopId}}"
                                               name="shop_ids[]">
                                    </div>
                                </div>
                            @endforeach


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="font-weight-bold">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-line"
                                            name="status">
                                        <option value="">Chọn</option>
                                        <option value="{{\App\Models\CoreUsers::STATUS_REGISTERED}}" {!! $form_init->status ==\App\Models\CoreUsers::STATUS_REGISTERED ? 'selected="selected"' : '' !!}>
                                            Đang hoạt động
                                        </option>
                                        <option value="{{\App\Models\CoreUsers::STATUS_BANNED}}" {!! $form_init->status == \App\Models\CoreUsers::STATUS_BANNED ? 'selected="selected"' : '' !!}>
                                            Đã bị cấm
                                        </option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2" hidden>
                                <div class="form-group">
                                    <label>Chức vụ <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-line"
                                            name="account_position">
                                        <option value="1">Admin</option>

                                    </select>

                                </div>
                            </div>


                        </div>
{{--                        <div class="row">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <h4 class="title font-weight-bold" style="color: #00467F" >Quyền trong hệ thống </h4>--}}

{{--                                <div class="form-group">--}}
{{--                                    @foreach($all_permissions as $k=> $v)--}}
{{--                                        <div class="row">--}}
{{--                                            <div class="col-md-2">--}}
{{--                                                <p class="font-weight-bold" style="color: #F26522">{{ucfirst($k)}}:</p>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-8">--}}
{{--                                                @foreach($v as $k2=> $v2)--}}

{{--                                                    <input type="checkbox" id="basic_checkbox_{{$v2->name}}"--}}
{{--                                                           name="grant_permissions[]"--}}
{{--                                                           value="{{$v2->id}}" {{old('grant_permissions')&&in_array($v2->id,old('grant_permissions'))?"checked":''}}/>--}}
{{--                                                    <label for="basic_checkbox_{{$v2->name}}"--}}
{{--                                                           class="filled-in chk-col-red font-weight-bold">{{$v2->title}}</label>--}}
{{--                                                    &nbsp; &nbsp;--}}

{{--                                                @endforeach--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="row">
                            <div class="col-sm-6 text-center">
                                <div class="form-group">
                                    <button class="btn" type="submit" style="background-color: #00467F; color: #fff">Thêm cửa hàng</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
@section('script')
    <script>
        $( document ).ready(function() {
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
                if(response.code === 200) {
                    let html =`<option value="">Chọn </option>`;
                    for (const element of response.data) {
                        html += ` <option value="${element.ProvinceID}">${element.ProvinceName}</option>`
                    }
                    $('.province').html(html)
                } else  {
                    alert(response.message)
                }
            });

            $(".province").change(function(){
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
                    if(response.code === 200) {
                        let html =``;
                        for (const element of response.data) {
                            html += ` <option value="${element.DistrictID}">${element.DistrictName}</option>`
                        }
                        $('.district').html(html)


                    } else  {
                        alert(response.message)
                    }
                });
            });

            $(".district").change(function(){
                var district_id =  parseInt($(this).val());
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
                    if(response.code === 200) {
                        let html =`<option value="">Chọn </option>`;
                        for (const element of response.data) {
                            html += ` <option value="${element.WardCode}">${element.WardName}</option>`
                        }
                        $('.ward').html(html)
                    } else  {
                        alert(response.message)
                    }
                });
            });

            $(".ward").change(function(){
                var text = $(".ward option:selected").text();
                $('.ward_name').val(text)
            });
        });
    </script>
@endsection

