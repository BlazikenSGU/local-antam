@extends('frontend.layouts.frontend')

@section('content')

    @include('frontend.parts.breadcrumbs')

    <div class="contact-area section-padding pt-20 pb-20">
        <div class="container">
            <form class="js-validate" novalidate="novalidate" action="" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-7">
                        <div class="mb-5">
                            <!-- Title -->
                            <div class="border-bottom border-color-1">
                                <h3 class="section-title mb-0 pb-2 font-size-25"
                                    style="text-transform: uppercase; font-weight: bold">Thông tin đặt hàng</h3>
                            </div>
                            <!-- End Title -->

                            <!-- Billing Form -->
                            <div class="row">

                                <div class="col-md-12">
                                    @include('frontend.parts.msg')
                                    @include('frontend.parts.errors')
                                </div>

                                <div class="col-md-12 pt-3">
                                    @php
                                        $fullname = $email = $phone=null;
                                    @endphp
                                    @if(Auth()->guard('web')->user())

                                        @php
                                            $fullname = Auth()->guard('web')->user()->fullname;
                                            $email = Auth()->guard('web')->user()->email;
                                            $phone = Auth()->guard('web')->user()->phone;
                                            $point = Auth()->guard('web')->user()->point;
                                            $address = \App\Models\Address::where('user_id',\Illuminate\Support\Facades\Auth::guard('web')->user()->id)->get();
                                            $address_default = \App\Models\Address::where('user_id',\Illuminate\Support\Facades\Auth::guard('web')->user()->id)->where('is_default_recipient',1)->first();
                                              if(!empty($address_default)){
                                            $district_default = \App\Models\Location\District::where('province_id', $address_default->province_id)->orderBy('priority', 'ASC')->get();
                                            $ward_default = \App\Models\Location\Ward::where('district_id', $address_default->district_id)->orderBy('priority', 'ASC')->get();
                                            }
                                        @endphp

                                        <div class="logged-in-customer-information">
                                            <div class="logged-in-customer-information-avatar-wrapper">
                                                <div class="logged-in-customer-information-avatar gravatar">
                                                    <i class="fa fa-user icon_user"></i>
                                                    Tài khoản: <strong
                                                        style="color: #007bff;">{{Auth()->guard('web')->user()->phone}}</strong>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="js-form-message mb-3">
                                            <label class="form-label">
                                                Sổ địa chỉ <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control select-address"
                                                    data-style="form-control border-color-1 font-weight-normal"
                                                    name="address">
                                                <option value="0">Tạo mới địa chỉ ...</option>

                                                @foreach($address as $add)
                                                    @if($address_default)
                                                        <option
                                                            value="{{$add->id}}"
                                                            {{$address_default->id == $add->id ? 'selected="checked"':''}}>
                                                            {{$add->name . ' - SĐT: ' . $add->phone . '/' .
                                                            $add->full_address}}
                                                        </option>
                                                    @endif
                                                @endforeach

                                            </select>
                                        </div>
                                    @else
                                        <p>Đã có tài khoản?
                                            <a style="    color: #007bff;"
                                               href="{{route('frontend.user.login')}}?ref={{urlencode('')}}">Đăng
                                                nhập</a></p>
                                    @endif
                                </div>
                            </div>

                            <div class="row ajax-address">
                                <div class="col-md-12">
                                    <!-- Input -->
                                    <div class="js-form-message mb-3">
                                        <label class="form-label">
                                            Họ tên <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="fullname"
                                               value="{{!empty($address_default) ? $address_default->name : old('fullname',$fullname)}}"
                                               placeholder=""
                                               aria-label="" required="" data-msg="Vui lòng nhập họ tên"
                                               data-error-class="u-has-error" data-success-class="u-has-success"
                                               autocomplete="off">
                                    </div>
                                    <!-- End Input -->
                                </div>

                                <div class="col-md-6">
                                    <!-- Input -->
                                    <div class="js-form-message mb-3">
                                        <label class="form-label">
                                            Số điện thoại <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="phone"
                                               placeholder="" aria-label="" required=""
                                               value="{{old('phone',$phone)}}"
                                               data-msg="Vui lòng nhập số điện thoại"
                                               data-error-class="u-has-error" data-success-class="u-has-success"
                                               autocomplete="off">
                                    </div>
                                    <!-- End Input -->
                                </div>

                                <div class="col-md-6">
                                    <!-- Input -->
                                    <div class="js-form-message mb-3">
                                        <label class="form-label">
                                            Email
                                        </label>
                                        <input type="text" class="form-control"
                                               value="{{old('email',$email)}}"
                                               name="email">
                                    </div>
                                    <!-- End Input -->
                                </div>

                                <div class="col-md-12">
                                    <!-- Input -->
                                    <div class="js-form-message mb-3">
                                        <label class="form-label">
                                            Địa chỉ <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="street"
                                               placeholder="" aria-label="" required=""
                                               data-msg="Vui lòng nhập địa chỉ"
                                               data-error-class="u-has-error" data-success-class="u-has-success"
                                               autocomplete="off"
                                               value="{{!empty($address_default)?$address_default->street_name:''}}">
                                    </div>
                                    <!-- End Input -->
                                </div>

                                <div class="w-100"></div>

                                <div class="col-md-4">
                                    <!-- Input -->
                                    <div class="js-form-message mb-3">
                                        <label class="form-label">
                                            Tỉnh/Thành phố
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select_province" required=""
                                                name="province_id"
                                                data-msg="Vui lòng chọn Tỉnh/Thành phố"
                                                data-error-class="u-has-error"
                                                data-success-class="u-has-success"
                                                data-live-search="true"
                                                onchange="get_district($(this))"
                                                data-style="form-control border-color-1 font-weight-normal">
                                            <option value="">Chọn</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->id}}"
                                                @if(!empty($address_default))  {{$address_default->province_id == $province->id ? 'selected="checked"':''}}@endif>
                                                    {{$province->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- End Input -->
                                </div>

                                <div class="col-md-4">
                                    <!-- Input -->
                                    <div class="js-form-message mb-3">
                                        <label class="form-label">
                                            Quận/Huyện
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select_district" required=""
                                                name="district_id"
                                                data-msg="Vui lòng chọn Quận/Huyện" data-error-class="u-has-error"
                                                data-success-class="u-has-success"
                                                data-live-search="true"
                                                onchange="get_ward($(this))"
                                                data-style="form-control border-color-1 font-weight-normal">
                                            <option value="">Chọn</option>
                                            @if(!empty($district_default))
                                                @foreach($district_default as $district)
                                                    <option value="{{$district->id}}"
                                                    @if($address_default)   {{$address_default->district_id == $district->id ? 'selected="checked"':''}}@endif>
                                                        {{$district->name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <!-- End Input -->
                                </div>

                                <div class="col-md-4">
                                    <!-- Input -->
                                    <div class="js-form-message mb-3">
                                        <label class="form-label">
                                            Phường/Xã
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control select_ward" required=""
                                                name="ward_id"
                                                data-msg="Vui lòng chọn Phường/Xã" data-error-class="u-has-error"
                                                data-success-class="u-has-success"
                                                data-live-search="true"
                                                data-style="form-control border-color-1 font-weight-normal">
                                            <option value="">Chọn</option>
                                            @if(!empty($ward_default))
                                                @foreach($ward_default as $ward)
                                                    <option value="{{$ward->id}}"
                                                    @if($address_default)    {{$address_default->ward_id == $ward->id ? 'selected="checked"':''}}@endif>
                                                        {{$ward->name}}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <!-- End Input -->
                                </div>
                            </div>
                            <!-- End Accordion -->
                            <!-- Input -->
                            <div class="js-form-message mb-3">
                                <label class="form-label">
                                    Ghi chú đơn hàng
                                </label>

                                <div class="input-group">
                                    <textarea class="form-control" rows="2" name="note"
                                              placeholder="Nhập ghi chú"></textarea>
                                </div>
                            </div>
                            <!-- Input -->
                            <div class="js-form-message mb-3">
                                <label class="form-label">
                                    Ngày nhận hàng
                                </label>
                                <input type="text" class="form-control" name="date_receiver"
                                       placeholder="" aria-label=""
                                       data-error-class="u-has-error" data-success-class="u-has-success"
                                       autocomplete="off"
                                       value="">
                            </div>
                            <!-- End Input -->
                            <!-- End Input -->
                        </div>
                    </div>

                    <div class="col-lg-5 mb-lg-0 p-b-5">
                        <div class="pl-lg-3 ">
                            <div class="bg-gray-1 rounded-lg">
                                <!-- Order Summary -->
                                <div class="mb-4 checkout-table">
                                    <!-- Title -->
                                    <div class="border-bottom border-color-1">
                                        <h3 class="section-title mb-0 pb-2 font-size-25"
                                            style="text-transform: uppercase; font-weight: bold">Đơn hàng</h3>
                                    </div>
                                    <!-- End Title -->

                                    <!-- Product Content -->
                                    <table class="table checkout_tbl">

                                        <thead>
                                            <tr>
                                                <th class="product-name"></th>
                                                <th class="product-name">Sản phẩm</th>
                                                <th class="product-total">T. tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $total = 0; @endphp
                                            @foreach($arr_products as $product)
                                                @php
                                                    $sum = $product['quantity']*$product['price'];
                                                    $total+=$sum;
                                                @endphp
                                                <tr class="cart_item">
                                                    <td class="product-image">
                                                        <div class="product-thumbnail">
                                                            <div class="product-thumbnail-wrapper">
                                                                <img width="100px" class="product-thumbnail-image"
                                                                     src="{{$product['picture']}}">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="padding-left: 10px">{{$product['title']}}
                                                        <p>Số lượng: x{{$product['quantity']}}</p>
                                                        {!! $product['product_variation_name']?"<br>{$product['product_variation_name']}":'' !!}
                                                        <p>ĐVT: {{$product['specifications']}}</p>
                                                        {{--                                                        @if(!empty($product['is_multilevel']) && $product['is_multilevel'] == 1 && !empty(auth()->guard('web')->user()->id))--}}
                                                        {{--                                                            @if(!empty($user_parent) && empty($user_parent->referrer_id))--}}
                                                        {{--                                                                <p class="text-danger" style="font-size: 12px;">--}}
                                                        {{--                                                                    <strong>Tài khoản {{$user_parent->phone}}--}}
                                                        {{--                                                                        nhận--}}
                                                        {{--                                                                        được--}}
                                                        {{--                                                                        20% hoa hồng đơn hàng</strong>--}}
                                                        {{--                                                                </p>--}}
                                                        {{--                                                            @endif--}}
                                                        {{--                                                            @if(!empty($user_parent_2) && empty($user_parent_2->referrer_id))--}}
                                                        {{--                                                                <p class="text-danger" style="font-size: 12px;">--}}
                                                        {{--                                                                    <strong>Tài khoản {{$user_parent_2->phone}}--}}
                                                        {{--                                                                        nhận--}}
                                                        {{--                                                                        được--}}
                                                        {{--                                                                        10% hoa hồng đơn hàng</strong><br>--}}
                                                        {{--                                                                    <strong>Tài khoản {{$user_parent->phone}}--}}
                                                        {{--                                                                        nhận--}}
                                                        {{--                                                                        được--}}
                                                        {{--                                                                        20% hoa hồng đơn hàng</strong><br>--}}
                                                        {{--                                                                </p>--}}
                                                        {{--                                                            @endif--}}
                                                        {{--                                                            @if(!empty($user_parent_3))--}}
                                                        {{--                                                                <p class="text-danger" style="font-size: 12px;">--}}

                                                        {{--                                                                    <strong>Tài khoản {{$user_parent_3->phone}}--}}
                                                        {{--                                                                        nhận--}}
                                                        {{--                                                                        được--}}
                                                        {{--                                                                        5% hoa hồng đơn hàng</strong><br>--}}
                                                        {{--                                                                    <strong>Tài khoản {{$user_parent_2->phone}}--}}
                                                        {{--                                                                        nhận--}}
                                                        {{--                                                                        được--}}
                                                        {{--                                                                        10% hoa hồng đơn hàng</strong><br>--}}
                                                        {{--                                                                    <strong>Tài khoản {{$user_parent->phone}}--}}
                                                        {{--                                                                        nhận--}}
                                                        {{--                                                                        được--}}
                                                        {{--                                                                        20% hoa hồng đơn hàng</strong>--}}
                                                        {{--                                                                </p>--}}
                                                        {{--                                                            @endif--}}
                                                        {{--                                                        @endif--}}

                                                    </td>
                                                    <td class="text-right">
                                                        {{number_format($sum)}}đ
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="2">Phí vận chuyển</th>
                                                <td class="text-right"><strong>Thỏa thuận</strong></td>
                                            </tr>

                                            <tr>
                                                <th colspan="2">Tổng cộng</th>
                                                <td class="text-right">
                                                    <strong class="product_price"
                                                            data-price="{{$total}}">{{number_format($total)}}đ</strong>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th colspan="2">Giảm giá <sup><i class="text-danger">Mã giảm giá/quà
                                                            tặng</i></sup></th>
                                                <td class="text-right">
                                                    <strong class="total_reduce" data-price="0">0</strong></td>
                                            </tr>
                                            @if(\Illuminate\Support\Facades\Auth::guard('web')->check())
                                                <tr style="display: none">
                                                    <th colspan="2">Giảm giá <sup><i class="text-danger">Điểm tích
                                                                lũy</i></sup></th>
                                                    <td class="text-right">
                                                        <strong class="total_reduce_point" data-price="0">0</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th colspan="2" class="text-uppercase">Tổng thanh toán</th>
                                                <td class="text-right">
                                                    <strong class="total_price"
                                                            data-price="{{$total}}">{{number_format($total)}}đ</strong>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <!-- End Product Content -->

                                    <div class="border-top border-width-3 border-color-1 pt-3 mb-3">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control discount_code" name="discount_code"
                                                   placeholder="Mã giảm giá/Quà tặng">
                                            <div class="input-group-append">
                                                <button class="btn btn-black check_discount" type="button"
                                                        onclick="check_discount()">
                                                    Áp dụng
                                                </button>
                                                <button class="btn btn-black remove_discount d-none" type="button"
                                                        onclick="remove_discount()">
                                                    Xóa
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                    @if(\Illuminate\Support\Facades\Auth::guard('web')->check())--}}
                                    {{--                                        <div class="border-top border-width-3 border-color-1 pt-3 mb-3">--}}
                                    {{--                                            <p class="text-danger"><strong>Bạn đang--}}
                                    {{--                                                    có: {{!empty($point)?floor($point):''}} điểm tích--}}
                                    {{--                                                    lũy.</strong><br>--}}
                                    {{--                                                <i>Tỉ lệ quy đổi điểm: 1 điểm/1.000đ,--}}
                                    {{--                                                    Tối đa không vượt quá 50% giá trị đơn hàng.</i></p>--}}
                                    {{--                                            <div class="input-group mb-3">--}}

                                    {{--                                                <input type="text" class="form-control point"--}}
                                    {{--                                                       name="point"--}}
                                    {{--                                                       placeholder="Điểm tích lũy">--}}
                                    {{--                                                <div class="input-group-append">--}}
                                    {{--                                                    <button class="btn btn-black check_discount_point" type="button"--}}
                                    {{--                                                            onclick="check_discount_point()">--}}
                                    {{--                                                        Áp dụng--}}
                                    {{--                                                    </button>--}}
                                    {{--                                                    <button class="btn btn-black remove_discount_point d-none"--}}
                                    {{--                                                            type="button"--}}
                                    {{--                                                            onclick="remove_discount_point()">--}}
                                    {{--                                                        Xóa--}}
                                    {{--                                                    </button>--}}
                                    {{--                                                </div>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    @endif--}}
                                    <div class="border-top border-width-3 border-color-1 pt-3 mb-3">
                                        <!-- Basics Accordion -->
                                        <div id="basicsAccordion1">
                                            <!-- Card -->
                                            <div class="border-bottom border-color-1 border-dotted-bottom">
                                                <div class="p-3" id="basicsHeadingOne">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class=""
                                                               id="stylishRadio2" name="payment_type" value="1" checked
                                                               style="margin: 5px">
                                                        <label class=" form-label"
                                                               for="stylishRadio2"
                                                               data-toggle="collapse"
                                                               data-target="#basicsCollapseOnee"
                                                               aria-expanded="true"
                                                               aria-controls="basicsCollapseOnee">
                                                            Tiền mặt
                                                        </label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class=""
                                                               id="stylishRadio1" name="payment_type" value="2"
                                                               style="margin: 5px">
                                                        <label class=" form-label"
                                                               for="stylishRadio1"
                                                               data-toggle="collapse"
                                                               data-target="#basicsCollapseOnee"
                                                               aria-expanded="true"
                                                               aria-controls="basicsCollapseOnee">
                                                            Chuyển khoản
                                                        </label>
                                                        <p class="show-stk" style="display: none">
                                                            STK: <b class="text-danger">{{$BANK_ACCOUNT_NUMBER}}</b>
                                                            Tại {{$BANK_NAME}}<br>

                                                            Tên chủ tài khoản: <b
                                                                class="text-danger">{{$BANK_ACCOUNT_NAME}}</b><br>

                                                            Nhấn <b>"Đặt hàng"</b> để tiếp tục thanh toán đơn hàng
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>
                                            <!-- End Card -->

                                        </div>
                                        <!-- End Basics Accordion -->
                                    </div>

                                    <button type="submit"
                                            class="btn btn-danger btn-block btn-pill font-size-20 mb-3 py-3">
                                        Đặt hàng
                                    </button>
                                </div>
                                <!-- End Order Summary -->
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

@stop

@section('script')

    <script src="{{ asset('/storage/frontend')}}/js/vendor/jquery-validation/dist/jquery.validate.min.js"></script>

    <script>
        $.HSCore = {
            components: {}
        };
    </script>

    <script src="{{ asset('/storage/frontend')}}/js/hs.validation.js"></script>

    <script type="text/javascript">
        $('.select-address').change(function () {
            var id = $(this).val();
            if (id == 0) {
                $('input[name=fullname]').removeAttr('value');
                $('input[name=email]').removeAttr('value');
                $('input[name=phone]').removeAttr('value');
                $('input[name=street]').removeAttr('value');
            } else {
                $.ajax({
                    type: 'GET',
                    url: "",
                    data: {id: id},
                    dataType: 'json',
                    success: function (data) {
                        $('.ajax-address').html(data.data.html);
                    }
                });
            }
        });
        $('input[name=payment_type]').change(function () {
            var a = $('input[name=payment_type]:checked').val();
            if (a == 2) {
                $('.show-stk').show();
            } else {
                $('.show-stk').hide()
            }
        });

        function get_district(d) {
            var province_id = d.val();
            $('.select_district').html('<option value="">Chọn</option>');
            $('.select_ward').html('<option value="">Chọn</option>');
            $.ajax({
                type: 'GET',
                url: "{{route('location.district')}}",
                data: {province_id: province_id},
                dataType: 'json',
                success: function (data) {
                    $.each(data.data, function (index, element) {
                        $('.select_district').append('<option value=' + element.id + '>' + element.type + ' ' + element.name + '</option>');
                    });
                }
            });
        }

        function get_ward(d) {
            var district_id = d.val();
            $('.select_ward').html('<option value="">Chọn</option>');
            $.ajax({
                type: 'GET',
                url: "{{route('location.ward')}}",
                data: {district_id: district_id},
                dataType: 'json',
                success: function (data) {
                    $.each(data.data, function (index, element) {
                        $('.select_ward').append('<option value=' + element.id + '>' + element.type + ' ' + element.name + '</option>');
                    });
                }
            });
        }

        setTimeout(function () {
            $('.alert-danger').fadeOut()
        }, 3000)

        // initialization of form validation
        $.HSCore.components.HSValidation.init('.js-validate', {});

        //discount code
        function calculator_total() {

            var total = parseInt($('.product_price').data('price'));
            var total_reduce = parseInt($('.total_reduce').data('price'));
            var total_reduce_point = parseInt($('.total_reduce_point').data('price'));

            total = isNaN(total) ? 0 : total;
            total_reduce = isNaN(total_reduce) ? 0 : total_reduce;
            // $('.total_price').html(numberWithCommas(total - total_reduce) + 'đ');
            $('.total_price').data('price', total - (total_reduce )).html(numberWithCommas(total - (total_reduce )) + 'đ');
        }

        function remove_discount() {
            $('.remove_discount').addClass('d-none');
            $('.check_discount').removeClass('d-none');
            $('.total_reduce').data('price', 0).html(0);
            $('.discount_code').val('').removeAttr('readonly');
            calculator_total();
        }

        function remove_discount_point() {
            $('.remove_discount_point').addClass('d-none');
            $('.check_discount_point').removeClass('d-none');
            $('.total_reduce_point').data('price', 0).html(0);
            $('.point').val('').removeAttr('readonly');
            calculator_total();
        }

        function check_discount() {
            var product_price = parseInt($('.product_price').data('price'));
            var discount_code = $('.discount_code').val();

            if (discount_code == '' || discount_code == null) {
                alert('Vui lòng nhập mã giảm giá!');
                return false;
            }

            $.ajax({
                method: 'POST',
                url: '',
                data: {
                    product_price: product_price,
                    discount_code: discount_code,
                    _token: $('[name="_token"]').val()
                },
                dataType: 'json',
                success: function (r) {
                    if (r.status) {
                        $('.remove_discount').removeClass('d-none');
                        $('.check_discount').addClass('d-none');
                        $('.discount_code').attr('readonly', 'readonly');
                        $('.total_reduce').data('price', parseInt(r.data.reduce)).html('-' + numberWithCommas(r.data.reduce) + 'đ');

                        calculator_total();
                    } else {
                        alert(r.message);
                        $('.discount_code').val('')
                    }
                }
            })
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function check_discount_point() {
            var product_price = parseInt($('.product_price').data('price'));
            var point = $('.point').val();

            if (point == '' || point == null) {
                alert('Vui lòng nhập số điểm tích lũy!');
                return false;
            }

            $.ajax({
                method: 'POST',
                url: '',
                data: {
                    point: point,
                    product_price: product_price,
                    _token: $('[name="_token"]').val()
                },
                dataType: 'json',
                success: function (r) {
                    // console.log(r);
                    if (r.status) {
                        //
                        console.log(r.data)
                        $('.remove_discount_point').removeClass('d-none');
                        $('.check_discount_point').addClass('d-none');
                        $('.point').attr('readonly', 'readonly');
                        $('.txtReducePoint').val(r.data.reduce);
                        $('.total_reduce_point').data('price', r.data.reduce).html('-' + numberWithCommas(r.data.reduce) + 'đ');
                        calculator_total();
                    } else {
                        alert(r.message);
                        $('.discount_code').val('')
                    }
                }
            })
        }
    </script>
@endsection
