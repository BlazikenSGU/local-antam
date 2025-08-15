@extends('backend.layouts.main')

@section('title', 'Đăng ký tài khoản')

@section('content')
    <style>
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
            margin-top: 20px;
        }

        .company-info .info-details p {
            margin-bottom: 8px;
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

        #loginform {
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }

        .login-register {
            background-image: url(https://antamecommerce.vn/storage/backend/assets/images/background/login-register.jpg);
            padding: 0;
            display: flex;
            flex-direction: column;
            height: fit-content;
            position: relative !important;
        }

        .card {
            margin-top: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) and (min-width: 375px) {
            .card {
                margin-top: .5rem;
            }
        }

        @media(max-width: 650px) {
            .company-info {
                margin-top: 1rem;
            }

            .login-register {
                height: auto;
            }
        }
    </style>

    <section id="wrapper">
        <div class="login-register">

            <div class="login-box card">
                <div class="card-body">

                    <form class="" id="loginform" action="{{ route('backend.register') }}" method="POST">
                        <h3 class="box-title m-b-20 text-center">Đăng ký tài khoản</h3>
                        @csrf

                        @include('backend.partials.msg')
                        @include('backend.partials.errors')


                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="fullname" required="" placeholder="Họ Tên">
                                <input class="form-control mt-3" name="email" required="" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group ">
                            <div class="col-xs-12">
                                <input class="form-control" name="phone" type="tel" required=""
                                    placeholder="Số điện thoại" pattern="[0-9]{9,}" minlength="9" maxlength="12"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12" style="position: relative;">
                                <input class="form-control" name="password" type="password" id="password" required=""
                                    placeholder="Mật khẩu">
                                <span id="togglePassword" style="position:absolute; top:3%; right:15px; cursor:pointer;">
                                    <i class="fa-solid fa-eye"></i>
                                </span>
                                <ul class="mt-2"id="password-rules"
                                    style="list-style: none; padding-left: 0; font-size: 10px;">
                                    <li id="rule-length" style="color: red;">• Tối thiểu 6 ký tự</li>
                                    <li id="rule-lower" style="color: red;">• Có chữ thường (a-z)</li>
                                    <li id="rule-upper" style="color: red;">• Có chữ hoa (A-Z)</li>
                                    <li id="rule-number" style="color: red;">• Có số (0-9)</li>
                                    <li id="rule-special" style="color: red;">• Có ký tự đặc biệt (!@#$...)</li>
                                </ul>

                                <input class="form-control" name="password_confirmation" type="password" required=""
                                    placeholder="Nhập lại mật khẩu">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="g-recaptcha" data-sitekey="6Lc9kgMrAAAAAFfQTytPoS7sMKokGLRRp0tB1f1E"></div>

                            </div>
                        </div>

                        @if (Session::has('msg'))
                            <div class="alert alert-danger">{{ Session::get('msg') }}</div>
                        @endif

                        <button class="btn btn-warning" type="submit">
                            Đăng ký
                        </button>
                        <span class="mt-2" style="text-align: center;">Nếu đã có tài khoản, đăng nhập <a
                                href="{{ route('backend.login') }}">tại
                                đây</a></span>
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
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.querySelector('input[name="password"]');
            if (!passwordInput) return;

            passwordInput.addEventListener('input', function() {
                const value = passwordInput.value;

                // Kiểm tra từng điều kiện
                document.getElementById('rule-length').style.color = value.length >= 6 ? 'green' : 'red';
                document.getElementById('rule-lower').style.color = /[a-z]/.test(value) ? 'green' : 'red';
                document.getElementById('rule-upper').style.color = /[A-Z]/.test(value) ? 'green' : 'red';
                document.getElementById('rule-number').style.color = /\d/.test(value) ? 'green' : 'red';
                document.getElementById('rule-special').style.color =
                    /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value) ? 'green' : 'red';
            });
        });
    </script>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Đổi biểu tượng Font Awesome
            this.innerHTML = type === 'password' ?
                '<i class="fa-solid fa-eye"></i>' :
                '<i class="fa-solid fa-eye-slash"></i>';
        });
    </script>
@endsection
