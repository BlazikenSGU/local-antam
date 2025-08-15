<!DOCTYPE html>
<html>
    <head>
        <title>Lấy lại mật khẩu </title>
    </head>
    <body>
        <p>
            Xin chào, {{ $user->email }},<br>
            Bạn hãy click vào đường link sau đây để hoàn tất việc lấy lại mật khẩu<br><br>
            <a href="{{ $user->reset_password_link }}">{{ $user->reset_password_link }}</a><br><br>
            Lưu ý: Đây là email tự động vui lòng không phản hồi email này.<br>
            Thanks & Best Regards,<br>
            <br>
        </p>
    </body>
</html>
