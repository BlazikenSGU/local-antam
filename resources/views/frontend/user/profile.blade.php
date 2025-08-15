@extends('frontend.layouts.main')

@push('title')
    Thông tin tài khoản
@endpush

@section('content')
    <style>
        .l-o {
            color: orange;
            font-style: italic;
        }

        .l-b {
            color: rgba(19, 87, 182, 0.523);
            font-style: italic;
        }

        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        /* Style cho nút khi loading */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.8;
        }

        @media (max-width: 768px) and (min-width: 375px) {
            .mobile-profile {
                flex-direction: column;
            }
        }

        .select2-container--default .select2-selection--single {
            height: 38px;

            border: 1px solid #ced4da;
            border-radius: 0.375rem;

            padding: 0.375rem 0.75rem;
        }

        .select2-selection__rendered {
            line-height: 1.5;
        }

        .select2-selection__arrow {
            height: 38px;
        }

        .select2-selection__arrow {
            display: none;
        }

        .info-user {
            padding: 1.5rem 0;
        }
    </style>


    <div class="card mt-2">
        <section class="card-body info-user">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="mb-4">Thông tin tài khoản</h2>
                </div>
                <form action="{{ route('user.profile.update') }}" method="POST" id="profileForm">
                    @csrf
                    <div class="col-md-12 d-flex mobile-profile">

                        <div class="col-md-6 p-2">
                            <div class="mb-3">
                                <label class="l-o" for="">Họ tên</label>
                                <input type="text" class="form-control" value="{{ $user->fullname }}" name="fullname"
                                    disabled>
                            </div>

                            <div class="mb-3">
                                <label class="l-o" for="">Số điện thoại</label>
                                <input type="tel" class="form-control" value="{{ $user->phone }}" pattern="[0-9]*"
                                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    minlength="10" maxlength="11" name="phone" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="l-o" for="">Email</label>
                                <input type="text" class="form-control" value="{{ $user->email }}" name="email"
                                    id="emailInput" data-original="{{ $user->email }}">
                            </div>

                            <div class="mb-3" style="display: flex; flex-direction: column;">
                                <label class="l-o" for="">Mật khẩu</label>
                                <span class="mt-2">
                                    <a href="javascript:void(0)" class="l-b" id="changePasswordBtn">(Đổi mật khẩu)</a>
                                </span>
                            </div>

                            <div id="passwordChangeForm" style="display: none;" class="mt-3">
                                <div class="mb-2">
                                    <label>Mật khẩu cũ</label>
                                    <input type="password" class="form-control" name="old_password">
                                </div>
                                <div class="mb-2">
                                    <label>Mật khẩu mới</label>
                                    <input type="password" class="form-control" name="new_password">
                                </div>
                                <div class="mb-2">
                                    <label>Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" name="new_password_confirmation">
                                </div>
                            </div>

                            <div id="otpVerificationForm" style="display: none;" class="mt-3">
                                <div class="mb-2">
                                    <label>Nhập mã OTP</label>
                                    <input type="text" class="form-control" name="otp" maxlength="6">
                                    <small class="text-muted">Mã OTP đã được gửi đến email của bạn</small>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 p-2">
                            <div class="mb-3">
                                <label class="l-o" for="">Ảnh đại diện</label>
                                <input type="text" class="form-control" value="{{ $user->avatar }}" name="avatar">
                            </div>

                            <div class="form-group mb-3">
                                <label class="col-md-12 l-o">Ngân hàng</label>
                                <div class="col-md-12">
                                    <select class="form-control" name="bank_name" id="bank_name"
                                        data-original="{{ $user->bank_name }}">
                                        <option value=""></option>
                                        @foreach (format_name_bank() as $code => $name)
                                            <option value="{{ $code }}"
                                                {{ Auth()->guard('backend')->user()->bank_name == $code ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="l-o" for="">Tên chủ tài khoản</label>
                                <input type="text" class="form-control" value="{{ $user->bank_account }}"
                                    data-original="{{ $user->bank_account }}" name="bank_account" id="bank_account"
                                    oninput="this.value = removeAccents(this.value.toUpperCase())">
                            </div>

                            <div class="mb-3">
                                <label class="l-o" for="">STK ngân hàng</label>
                                <input type="text" class="form-control" value="{{ $user->bank_number }}"
                                    data-original="{{ $user->bank_number }}" name="bank_number" id="bank_number">
                            </div>

                        </div>

                    </div>
                    <div class="" style="text-align:center">
                        <button type="submit" class="btn btn-primary" id="updateBtn">Cập nhật</button>
                        <button type="button" class="btn btn-success" id="verifyOtpBtn" style="display: none;">Xác nhận
                            OTP</button>

                    </div>
                </form>
                <div class="loading-overlay">
                    <div class="loading-spinner"></div>
                </div>
            </div>

        </section>

    </div>
@endsection

@section('script')
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Thông báo!',
                    text: "{{ session('error') }}",
                    icon: 'warning',
                    confirmButtonColor: 'red',
                    confirmButtonText: 'Đã hiểu'
                });
            });
        </script>
    @endif



    <script>
        //hien thi form doi mat khau
        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            const passwordForm = document.getElementById('passwordChangeForm');
            if (passwordForm.style.display === 'none') {
                passwordForm.style.display = 'block';
            } else {
                passwordForm.style.display = 'none';
                // Reset giá trị các input khi ẩn form
                passwordForm.querySelectorAll('input[type="password"]').forEach(input => {
                    input.value = '';
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#bank_name').select2({
                placeholder: "Chọn ngân hàng",
                allowClear: true,
                width: '100%', // đảm bảo full width

            });
        });
    </script>

    <script>
        //chuyen chu nhap vao thanh chu hoa khong dau
        function removeAccents(str) {
            return str.normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '') // xóa dấu
                .replace(/đ/g, 'd').replace(/Đ/g, 'D') // thay thế đ/Đ
                .replace(/[^a-zA-Z\s]/g, ''); // chỉ giữ lại chữ cái và khoảng trắng
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#profileForm');
            const emailInput = document.querySelector('#emailInput');
            const passwordForm = document.querySelector('#passwordChangeForm');
            const otpForm = document.querySelector('#otpVerificationForm');
            const updateBtn = document.querySelector('#updateBtn');
            const verifyOtpBtn = document.querySelector('#verifyOtpBtn');
            const loadingOverlay = document.querySelector('.loading-overlay');
            let needsOTPVerification = false;

            const bankNameInput = document.querySelector('#bank_name');
            const bankAccountInput = document.querySelector('#bank_account');
            const bankNumberInput = document.querySelector('#bank_number');

            // Hàm hiển thị loading
            function showLoading() {
                loadingOverlay.style.display = 'block';
                updateBtn.classList.add('btn-loading');
            }

            // Hàm ẩn loading
            function hideLoading() {
                loadingOverlay.style.display = 'none';
                updateBtn.classList.remove('btn-loading');
            }

            form.addEventListener('submit', async function(e) {
                const emailChanged = emailInput.value !== emailInput.dataset.original;
                const bankNameChanged = bankNameInput.value !== bankNameInput.dataset.original;
                const bankAccountChanged = bankAccountInput.value !== bankAccountInput.dataset.original;
                const bankNumberChanged = bankNumberInput.value !== bankNumberInput.dataset.original;
                const passwordChanged = passwordForm.style.display !== 'none' &&
                    passwordForm.querySelector('input[name="new_password"]').value !== '';

                if ((emailChanged || passwordChanged || bankNameChanged || bankAccountChanged ||
                        bankNumberChanged) && !needsOTPVerification) {
                    e.preventDefault();
                    showLoading();

                    try {
                        const response = await fetch('/user/profile/send-otp', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                email: emailInput.value
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                title: 'Nhập mã OTP',
                                text: data.message,
                                icon: 'warning',
                                confirmButtonText: 'Tiếp tục'
                            });
                            otpForm.style.display = 'block';
                            updateBtn.style.display = 'none';
                            verifyOtpBtn.style.display = 'inline-block';
                            needsOTPVerification = true;
                        } else {
                            throw new Error(data.message || 'Có lỗi xảy ra');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Lỗi!',
                            text: error.message || 'Có lỗi xảy ra khi gửi OTP',
                            icon: 'error',
                            confirmButtonText: 'Đã hiểu'
                        });
                    } finally {
                        hideLoading();
                    }
                }
            });

            // Tương tự cho verifyOtpBtn
            verifyOtpBtn.addEventListener('click', async function() {
                const otp = otpForm.querySelector('input[name="otp"]').value;

                if (!otp) {
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Vui lòng nhập mã OTP',
                        icon: 'error',
                        confirmButtonText: 'Đã hiểu'
                    });
                    return;
                }

                showLoading();

                try {
                    const response = await fetch('/user/profile/verify-otp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            otp: otp
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        needsOTPVerification = false;
                        form.submit();
                    } else {
                        Swal.fire({
                            title: 'Lỗi!',
                            text: 'Mã OTP không chính xác',
                            icon: 'error',
                            confirmButtonText: 'Thử lại'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra khi xác thực OTP',
                        icon: 'error',
                        confirmButtonText: 'Thử lại'
                    });
                } finally {
                    hideLoading();
                }
            });
        });
    </script>
@endsection
