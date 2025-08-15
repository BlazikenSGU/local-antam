<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực OTP</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-top: 50px;
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 2px solid #f8f9fa;
            padding: 20px;
        }

        .form-control {
            height: 50px;
            font-size: 20px;
            text-align: center;
            letter-spacing: 10px;
        }

        .btn-primary {
            padding: 12px 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center mb-0">Xác thực tài khoản</h4>
                    </div>

                    <div class="card-body">
                        @if (session('info'))
                            <div class="alert alert-info" style="text-align: center;">
                                {{ session('info') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="text-center mb-4">
                            <p>Mã OTP đã được gửi đến email: <strong>{{ $email }}</strong></p>

                            <p>Bạn phải xác thực mới có thể đăng nhập vào hệ thống</p>
                        </div>

                        <form method="POST" action="{{ route('backend.verifyOTP') }}">
                            @csrf
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('otp') is-invalid @enderror"
                                        name="otp" placeholder="Nhập mã OTP" maxlength="6" autocomplete="off"
                                        required>
                                </div>
                                @error('otp')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Xác thực
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    setInterval(function() {
        fetch("/admin/check-otp-expire")
            .then(response => response.json())
            .then(data => {
                if (data.status === 'deleted') {
                    alert("Tài khoản đã hết hạn do không xác nhận OTP sau 5 phút.");
                    window.location.href = "/admin/register";
                }
            });
    }, 60000); // 60,000ms = 1 phút
</script>

</html>
