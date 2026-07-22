<?php
require_once __DIR__ . '/../includes/bootstrap.php';

$carId = (int) ($_GET['id'] ?? 0);
if ($carId <= 0) {
    json_response(['success' => false, 'message' => 'Invalid car id'], 400);
}

$car = $carService->find($carId);
if (!$car) {
    json_response(['success' => false, 'message' => 'Car not found'], 404);
}

$images = $carService->findImages($carId);
$gallery = [];
foreach ($images as $img) {
    $path = normalize_image_path($img['image_url'] ?? '');
    if (!$path) {
        continue;
    }

    if (!preg_match('/^https?:\/\//i', $path)) {
        $abs = __DIR__ . '/../' . ltrim($path, '/');
        if (!is_file($abs)) {
            continue;
        }
    }

    $gallery[] = $path;
}
$gallery = array_values(array_filter($gallery));
if (!$gallery) {
    $gallery = ['images/hero-bg.png'];
}

$rawFeatures = preg_split('/[\n,]/', (string) ($car['description'] ?? ''));
$features = array_values(array_filter(array_map('trim', $rawFeatures)));
if (!$features) {
    $features = ['Quality checked', 'Dealer serviced', 'Roadworthy'];
}

$item = [
    'id' => (int) $car['car_id'],
    'name' => trim(($car['year'] ?? '') . ' ' . ($car['make'] ?? '') . ' ' . ($car['model'] ?? '')),
    'make' => $car['make'] ?? '',
    'model' => $car['model'] ?? '',
    'year' => (int) ($car['year'] ?? 0),
    'mileage' => (int) ($car['mileage'] ?? 0),
    'transmission' => $car['transmission'] ?? '',
    'fuel' => $car['fuel_type'] ?? '',
    'engine' => $car['variant'] ?: (($car['fuel_type'] ?? '') . ' Engine'),
    'location' => '25/ 27 Heidelberg Rd, Village Main, Johannesburg, 2001',
    'price' => (float) ($car['price'] ?? 0),
    'image' => $gallery[0],
    'gallery' => $gallery,
    'description' => $car['description'] ?? '',
    'features' => array_slice($features, 0, 8),
    'specs' => [
        'Make' => $car['make'] ?? '',
        'Model' => $car['model'] ?? '',
        'Year' => (string) ($car['year'] ?? ''),
        'Mileage' => number_format((int) ($car['mileage'] ?? 0)) . ' km',
        'VIN' => $car['vin'] ?? '',
        'Fuel' => $car['fuel_type'] ?? '',
        'Transmission' => $car['transmission'] ?? '',
        'Condition' => $car['car_condition'] ?? '',
        'Color' => $car['color'] ?? '',
        'MM Code' => $car['mm_code'] ?? '',
    ],
];

json_response(['success' => true, 'data' => $item]);
