@extends('backend.layouts.main')

@section('title', 'Login')

@section('content')
    <section id="wrapper">
        <div class="login-register" style="background-image:url({{ asset('/storage/backend/assets/images/background/login-register.jpg')}});">
            <div class="login-box card">
                <div class="card-body">
                    <form class="form-horizontal form-material" id="loginform" action="" method="post">
                        <h3 class="box-title m-b-20 text-center">Quên mật khẩu</h3>
                        @csrf
                        @include('backend.partials.msg')
                        @include('backend.partials.errors')
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="email_or_phone" type="tel" required="" placeholder="Số điện thoại/Email">
                            </div>
                        </div>


                        @if(Session::has('msg'))
                            <div class="alert alert-danger">{{ Session::get('msg')}}</div>
                        @endif
                        <div class="form-group row">

                            <div class="col-md-6 font-14 text-left">
                                <a href="javascript:history.back()">Quay lại</a>
                            </div>
                        </div>
                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">
                                    Gửi OTP
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

@endsection
