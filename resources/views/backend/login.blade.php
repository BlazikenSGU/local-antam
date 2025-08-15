@extends('backend.layouts.main')

@section('title', 'Login')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@400;600;700&display=swap');

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        body,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
        }

        .company-info {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            align-items: center;
            margin-top: 3rem;
        }

        .company-info .info-details p {
            margin-bottom: 8px;
        }

        .card {
            margin-top: 2rem;
        }

        .social-links a:hover {
            opacity: 0.8;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .copyright {
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        .login-register {
            background-image: url(https://antamecommerce.vn/storage/backend/assets/images/background/login-register.jpg);
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative !important;
        }

        @media (max-width: 768px) and (min-width: 375px) {
            .card {
                margin-top: 1rem;
                margin-bottom: 20px;
            }

            .login-register {
                height: auto;
            }

            .company-info {
                margin-top: 10px;
            }
        }
    </style>
    <section id="wrapper">
        <div class="login-register">

            <div class="login-box card">

                <div class="card-body">

                    <div class="image_banner d-flex flex-column align-items-center mb-3">
                        <img src="{{ asset('assets/assets/img/icon_logo.jpg') }}" alt=""
                            style="width: 150px; height: 100px; border-radius: 10px;">
                    </div>

                    @if (Session::has('success'))
                        <div class="alert alert-success">{{ Session::get('success') }}</div>
                    @endif

                    @if (Session::has('msg'))
                        <div class="alert alert-danger">{{ Session::get('msg') }}</div>
                    @endif

                    <form class="form-horizontal form-material" id="loginform" action="" method="post">
                        <h3 class="box-title m-b-20 text-center">Đăng nhập</h3>
                        @csrf
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input id="txtPhone" class="form-control" name="email_or_phone" required type='number'
                                    placeholder="Số điện thoại" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" inputmode="decimal">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" name="password" type="password" id="password" required=""
                                    autocomplete="off" placeholder="Mật khẩu">
                                <span id="togglePassword" style="position:absolute; top:50%; right:15px; cursor:pointer;">
                                    <i class="fa-regular fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 font-14">
                                <div class="checkbox checkbox-primary pull-left p-t-0">

                                </div>
                            </div>
                            <div class="col-md-6 font-14 text-right">
                                <a href="{{ route('backend.forgotPassword') }}">Quên mật khẩu</a>
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg" type="submit">
                                    Đăng nhập
                                </button>
                            </div>
                            <div class="col-xs-12 mt-3">
                                <span>Nếu chưa có tài khoản, <a href="{{ route('backend.register') }}">đăng ký ngay</a>
                                </span>

                            </div>
                        </div>
                    </form>

                </div>
            </div>


            <div class="company-info" style=" text-align: center; color: #666; padding: 20px;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <h4 style="color: #333; font-weight: bold; margin-bottom: 15px;">CÔNG TY TNHH AN TÂM E-COMMERCE
                            </h4>
                            <div class="info-details" style="font-size: 14px; line-height: 1.8;">
                                <p> Giấy CNĐKKD: 3604006962 - Ngày cấp 07/03/2025</p>
                                <p>Cơ quan cấp Phòng Đăng ký kinh doanh - Sở kế hoạch đầu tư Tỉnh Đồng Nai</p>
                                <p><i class="fas fa-phone mr-2"></i> Hotline: <a href="tel:0373892132"> 037 389 2132</a></p>
                                <p><i class="fas fa-envelope mr-2"></i> Email: antamecommerce@gmail.com</p>
                                <p><i class="fas fa-globe mr-2"></i> Website: https://antamecommerce.vn</p>
                            </div>
                            <div class="social-links mt-3">
                                <a href="#" style="color: #3b5998; margin: 0 10px;"><i
                                        class="fab fa-facebook-f fa-lg"></i></a>
                                <a href="#" style="color: #00acee; margin: 0 10px;"><i
                                        class="fab fa-twitter fa-lg"></i></a>
                                <a href="#" style="color: #0e76a8; margin: 0 10px;"><i
                                        class="fab fa-linkedin-in fa-lg"></i></a>
                                <a href="#" style="color: #ea4335; margin: 0 10px;"><i
                                        class="fab fa-youtube fa-lg"></i></a>
                            </div>

                            <div class="copyright mt-3" style="font-size: 12px;">
                                <p>© 2025 antamecommerce. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@section('script')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Đổi biểu tượng Font Awesome
            this.innerHTML = type === 'password' ?
                '<i class="fa-regular fa-eye"></i>' :
                '<i class="fa-regular fa-eye-slash"></i>';
        });
    </script>
@endsection
