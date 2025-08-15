<!DOCTYPE html>
<html>
    <head>
        <title>OTP Email</title>
    </head>
    <body>
        <p>
            Xin chào {{ $user->username }},
            </br>
            {{ $user->email_title }}
            <br>
            <strong>{{ $user->otp_code }}</strong>
        </p>
    </body>
</html>
