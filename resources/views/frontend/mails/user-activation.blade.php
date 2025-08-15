<!DOCTYPE html>
<html>
    <head>
        <title>Kích hoạt tài khoản </title>
    </head>
    <body>
        <p>
            Chào mừng {{ $user->phone }},<br>
            Bạn hãy click vào đường link sau đây để hoàn tất việc đăng ký thành viên tại shop<br><br>
            <a href="{{ $user->activation_link }}">{{ $user->activation_link }}</a><br><br>
            Lưu ý: Đây là email tự động vui lòng không phản hồi email này.<br>
            Thanks & Best Regards,<br>
            <br>
        </p>
    </body>
</html>
