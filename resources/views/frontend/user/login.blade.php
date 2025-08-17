@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')
    <div class="login-register-area pt-20 pb-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                    <div class="login-register-wrapper">
                        {{--                        <div class="login-register-tab-list nav">--}}
                        {{--                            <h4> Đăng nhập </h4>--}}
                        {{--                        </div>--}}
                        <div class="tab-content">
                            <div id="lg1" class="tab-pane active">
                                <div class="login-form-container">
                                    <div class="login-register-form">
                                        <form action="" method="post">
                                            @csrf
                                            @include('frontend.parts.msg')
                                            @include('frontend.parts.errors')
                                            <input type="tel" name="phone_or_email" value="{{old('phone_or_email')}}"
                                                   placeholder="Số điện thoại" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
                                            <input type="password" name="password" placeholder="Mật khẩu">
                                            <div class="button-box">
                                                <div class="login-toggle-btn">
                                                    <a href="">Quên mật
                                                        khẩu&nbsp|</a>
                                                    <a href="">&nbspĐăng ký </a>
                                                </div>
                                                <button type="submit">Đăng nhập</button>
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
