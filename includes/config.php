<?php
return [
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'vgi',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'base_url' => '/don joachim/vgi',
        'uploads_dir' => __DIR__ . '/../uploads/cars',
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
