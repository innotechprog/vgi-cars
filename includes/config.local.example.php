<?php

return [
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'your_live_database_name',
        'user' => 'your_live_database_user',
        'pass' => 'your_live_database_password',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        // Use empty string if the app is served from domain root.
        'base_url' => '',
        'uploads_web' => 'uploads/cars',
    ],
    'mail' => [
        'enabled' => false,
        'host' => '',
        'port' => 587,
        'username' => '',
        'password' => '',
        'encryption' => 'tls',
        'from_email' => '',
        'from_name' => 'VGI Cars',
        'to_email' => '',
    ],
];
