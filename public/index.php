<?php

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

if (APPLICATION_ENV == 'production') {
//    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
//        $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//        //header('HTTP/1.1 301 Moved Permanently');
//        header('Location: ' . $redirect);
//        exit();
//
//    }
//    if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {
//        //header('HTTP/1.1 301 Moved Permanently');
//        $url = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
//        header("Location: " . $url);
//        exit;
//    }
//
//    // cờ trạng thái đăng nhập:
//    $LoginSuccessful = false;
//    // Kiểm tra username và password:
//    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
//        $Username = $_SERVER['PHP_AUTH_USER'];
//        $Password = $_SERVER['PHP_AUTH_PW'];
//        if ($Username == 'bdst' && $Password == '1234561') {
//            $LoginSuccessful = true;
//        }
//    }
//    $LoginSuccessful = true;
//    // Kiểm tra trạng thái đăng nhập
//    if (!$LoginSuccessful) {
//        header('WWW-Authenticate: Basic realm="Secret page"');
//        header('HTTP/1.0 401 Unauthorized');
//        exit;
//    }
}

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
