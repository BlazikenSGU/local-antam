<?php

return [
    'account_kit_app_id'     => '2220797271491128',
    'account_kit_app_secret' => '3d79d25158cc151dfe982f1f5322e68d',
    'account_kit_version'    => 'v1.1',

    'assets_version' => '4.5',

    'upload_dir'      => [
        'root' => storage_path('app/public/uploads'),
        'url'  => config('app.url') . '/storage/uploads',
    ],

    'upload_dir_temp' => [
        'root' => storage_path('app/public/uploads/temp'),
        'url'  => config('app.url') . '/storage/uploads/temp',
    ],

    'firebase_api_key'    => 'AAAAe_Hm86I:APA91bHQ2hz023zX8PYbOFNLaZhfRDIrPfBNhwwRVNcx5_PxuEAbRhjfnJeBpCqJ90rI7dWZjy4dVKzNSmWiMbP5hsoMYpNddtc0_IcJRqGUqA6a5WWcS48IPC9sDDb3Gu-Ti5-VOK11',
    'item_perpage'        => 10,
    'item_per_page_admin' => 30,
    'company_id'          => 6,
    'google_maps_key'     => 'AIzaSyB711stPiEwDrN_Biq6Tcx7KHhtu-QPxm0',
    'limit_records'       => [10, 30, 50, 100, 500, 1000, 5000, 10000],
    'mail_driver'         => 'smtp',
    'mail_host'           => 'smtp.gmail.com',
    'mail_port'           => 587,
    'mail_from_address'   => 'info@food.dev24h.net',
    'mail_from_name'      => 'food.dev24h.net',
    'mail_encryption'     => 'tls',
    'mail_username'       => 'info@thietke24h.net',
    'mail_password'       => 'pccqxzwwtkaqlwwk',

    'ghn_api_token'       => '90efe31e-27ed-11f0-a085-4a8e57ff73ff',
];
