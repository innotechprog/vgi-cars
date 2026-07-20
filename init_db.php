<?php
require_once __DIR__ . '/includes/bootstrap.php';

$db->exec('CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    first_name VARCHAR(100) DEFAULT NULL,
    last_name VARCHAR(100) DEFAULT NULL,
    phone_number VARCHAR(50) DEFAULT NULL,
    role VARCHAR(50) DEFAULT "admin",
    profile_image VARCHAR(255) DEFAULT NULL,
    about TEXT,
    company VARCHAR(255) DEFAULT NULL,
    job VARCHAR(255) DEFAULT NULL,
    country VARCHAR(100) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    twitter VARCHAR(255) DEFAULT NULL,
    facebook VARCHAR(255) DEFAULT NULL,
    instagram VARCHAR(255) DEFAULT NULL,
    linkedin VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$db->exec('CREATE TABLE IF NOT EXISTS cars (
    car_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    year INT NOT NULL,
    mm_code VARCHAR(100) DEFAULT NULL,
    make VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    variant VARCHAR(150) DEFAULT NULL,
    custom_variant VARCHAR(150) DEFAULT NULL,
    vin VARCHAR(100) DEFAULT NULL,
    mileage INT DEFAULT 0,
    price DECIMAL(12,2) DEFAULT 0,
    color VARCHAR(100) DEFAULT NULL,
    transmission VARCHAR(100) DEFAULT NULL,
    fuel_type VARCHAR(100) DEFAULT NULL,
    description TEXT,
    finance_eligible VARCHAR(10) DEFAULT "Yes",
    condition_type VARCHAR(100) DEFAULT "Used",
    car_condition VARCHAR(100) DEFAULT NULL,
    status VARCHAR(100) DEFAULT "Available",
    visibility VARCHAR(10) DEFAULT "Yes",
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_make_model (make, model),
    INDEX idx_price (price),
    INDEX idx_year (year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$db->exec('CREATE TABLE IF NOT EXISTS images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    car_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_primary TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_car_id (car_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

$admin = $userService->findByUsername('admin');
if (!$admin) {
    $stmt = $db->prepare('INSERT INTO users (username, password_hash, email, first_name, last_name, role) VALUES (:username, :password_hash, :email, :first_name, :last_name, :role)');
    $stmt->execute([
        'username' => 'admin',
        'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
        'email' => 'admin@vgi.local',
        'first_name' => 'VGI',
        'last_name' => 'Admin',
        'role' => 'admin',
    ]);
    echo 'Default admin created: admin / admin123' . PHP_EOL;
} else {
    echo 'Admin already exists.' . PHP_EOL;
}

echo 'Database ready.' . PHP_EOL;
