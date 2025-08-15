@extends('frontend.layouts.frontend')

@section('content')
    @include('frontend.parts.breadcrumbs')

    <div class="contact-area section-padding">
        <div class="user-login-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-8 col-md-12 col-12 ml-auto mr-auto">
                        <form action="" method="post">
                            @csrf

                            @include('frontend.parts.msg')
                            @include('frontend.parts.errors')

                            <div class="login-form">

                                <div class="single-login">
                                    <div class="form-group">
                                        <label>Mật khẩu <span>*</span></label>
                                        <input type="password" name="password" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="single-login">
                                    <div class="form-group">
                                        <label>Xác nhận mật khẩu<span>*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" required/>
                                    </div>
                                </div>
                                <div class="single-login single-login-2">
                                    <button type="submit" class="btn btn-black w-100">Đặt mật khẩu</button>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
