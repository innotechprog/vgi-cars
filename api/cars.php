<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$filters = [
    'make' => trim($_GET['make'] ?? ''),
    'model' => trim($_GET['model'] ?? ''),
    'year_min' => trim($_GET['year_min'] ?? ''),
    'year_max' => trim($_GET['year_max'] ?? ''),
    'price_max' => trim($_GET['price_max'] ?? ''),
];

$rows = $carService->list($filters);
$data = array_map(function (array $row): array {
    $image = normalize_image_path($row['image_url'] ?? '') ?: 'images/hero-bg.png';
    if (!preg_match('/^https?:\/\//i', $image)) {
        $abs = __DIR__ . '/../' . ltrim($image, '/');
        if (!is_file($abs)) {
            $image = 'images/hero-bg.png';
        }
    }
    return [
        'id' => (int) $row['car_id'],
        'name' => trim(($row['year'] ?? '') . ' ' . ($row['make'] ?? '') . ' ' . ($row['model'] ?? '')),
        'make' => $row['make'] ?? '',
        'model' => $row['model'] ?? '',
        'year' => (int) ($row['year'] ?? 0),
        'mileage' => (int) ($row['mileage'] ?? 0),
        'transmission' => $row['transmission'] ?? '',
        'fuel' => $row['fuel_type'] ?? '',
        'engine' => $row['variant'] ?: (($row['fuel_type'] ?? '') . ' Engine'),
        'location' => '25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001',
        'price' => (float) ($row['price'] ?? 0),
        'image' => $image,
        'description' => $row['description'] ?? '',
    ];
}, $rows);

json_response(['success' => true, 'data' => $data]);
