@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')
    <div class="login-register-area pt-20 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                    <div class="login-register-wrapper">
{{--                        <div class="login-register-tab-list nav">--}}
{{--                            <a data-toggle="tab" href="#lg2" class="active">--}}
{{--                                <h4> Đăng ký </h4>--}}
{{--                            </a>--}}
{{--                        </div>--}}
                        <div class="tab-content">
                            <div id="lg1" class="tab-pane">
                                <div class="login-form-container">
                                    {{--                                    <div class="login-register-form">--}}
                                    {{--                                        <form action="" method="post">--}}
                                    {{--                                            @csrf--}}
                                    {{--                                            @include('frontend.parts.msg')--}}
                                    {{--                                            @include('frontend.parts.errors')--}}
                                    {{--                                            <label>Tài khoản</label>--}}
                                    {{--                                            <input type="text" name="username">--}}
                                    {{--                                            <label>Tài khoản</label>--}}
                                    {{--                                            <input type="password" name="password" placeholder="Password">--}}
                                    {{--                                            <div class="button-box">--}}
                                    {{--                                                <div class="login-toggle-btn">--}}
                                    {{--                                                    <input type="checkbox">--}}
                                    {{--                                                    <label>Remember me</label>--}}
                                    {{--                                                    <a href="#">Forgot Password?</a>--}}
                                    {{--                                                </div>--}}
                                    {{--                                                <button type="submit">Login</button>--}}
                                    {{--                                            </div>--}}
                                    {{--                                        </form>--}}
                                    {{--                                    </div>--}}
                                </div>
                            </div>
                            <div id="lg2" class="tab-pane active">
                                <div class="login-form-container">
                                    <div class="login-register-form">
                                        <form action="" method="post">
                                            @csrf
                                            @include('frontend.parts.msg')
                                            @include('frontend.parts.errors')
                                            <h5 style="color: red">Vui lòng nhập các thông tin đánh dấu *</h5>
                                            <label>Số điện thoại <sup style="color: red">*</sup></label>
                                            <input type="text" name="phone" value="{{old('phone')}}">
                                            <label>Mật khẩu <sup style="color: red">*</sup></label>
                                            <input type="password" name="password">
                                            <label>Xác Nhận Mật khẩu <sup style="color: red">*</sup></label>
                                            <input type="password" name="password_confirmation">
                                            <label>Họ & Tên <sup style="color: red">*</sup></label>
                                            <input name="fullname" type="text" value="{{old('fullname')}}">
                                            <label>Email <sup style="color: red">*</sup></label>
                                            <input name="email" type="email" value="{{old('email')}}">
{{--                                            <label>SĐT người giới thiệu <sup style="color: red">(Mã giới thiệu)</sup></label>--}}
{{--                                            <input name="phone_referrer" type="text" value="{{isset($_COOKIE['code'])?$_COOKIE['code']:''}}" {{isset($_COOKIE['code'])?'readonly':''}}>--}}
                                            <div class="button-box">
                                                <button type="submit">Đăng ký</button>
                                                <a style="color: red" href="{{url('/user/login')}}"><i class=""></i>Quay
                                                    lại trang đăng nhập</a>
                                            </div>
                                        </form>
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
