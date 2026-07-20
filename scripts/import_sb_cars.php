<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$config = require $root . '/includes/config.php';

$vgiDsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $config['db']['host'],
    $config['db']['name'],
    $config['db']['charset']
);

$vgi = new PDO($vgiDsn, $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$sb = new PDO(
    'mysql:host=127.0.0.1;dbname=sbautogroup;charset=utf8mb4',
    'root',
    '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

$sbRoot = dirname($root) . '/sb auto';
$targetBaseDir = $config['app']['uploads_dir'];
$targetBaseWeb = trim($config['app']['uploads_web'], '/');

if (!is_dir($targetBaseDir) && !mkdir($targetBaseDir, 0755, true) && !is_dir($targetBaseDir)) {
    throw new RuntimeException('Unable to create target uploads directory: ' . $targetBaseDir);
}

$adminStmt = $vgi->query("SELECT user_id FROM users WHERE role = 'admin' ORDER BY user_id ASC LIMIT 1");
$admin = $adminStmt->fetch();
if (!$admin) {
    throw new RuntimeException('No admin user found in vgi.users. Run init_db.php first.');
}
$targetSellerId = (int) $admin['user_id'];

$carsStmt = $sb->query('SELECT * FROM cars ORDER BY car_id DESC');
$sourceCars = $carsStmt->fetchAll();

$checkExists = $vgi->prepare('SELECT car_id FROM cars WHERE make = :make AND model = :model AND year = :year AND price = :price AND mileage = :mileage LIMIT 1');
$insertCar = $vgi->prepare(
    'INSERT INTO cars (
        seller_id, year, mm_code, make, model, variant, custom_variant, vin, mileage, price, color,
        transmission, fuel_type, description, finance_eligible, condition_type, car_condition, status, visibility
    ) VALUES (
        :seller_id, :year, :mm_code, :make, :model, :variant, :custom_variant, :vin, :mileage, :price, :color,
        :transmission, :fuel_type, :description, :finance_eligible, :condition_type, :car_condition, :status, :visibility
    )'
);

$sourceImagesStmt = $sb->prepare('SELECT * FROM images WHERE car_id = :car_id ORDER BY is_primary DESC, image_id ASC');
$insertImage = $vgi->prepare('INSERT INTO images (car_id, image_url, is_primary) VALUES (:car_id, :image_url, :is_primary)');
$deleteImagesByCar = $vgi->prepare('DELETE FROM images WHERE car_id = :car_id');

$importedCars = 0;
$skippedCars = 0;
$importedImages = 0;
$syncedExistingCars = 0;

foreach ($sourceCars as $srcCar) {
    $checkExists->execute([
        'make' => (string) ($srcCar['make'] ?? ''),
        'model' => (string) ($srcCar['model'] ?? ''),
        'year' => (int) ($srcCar['year'] ?? 0),
        'price' => (float) ($srcCar['price'] ?? 0),
        'mileage' => (int) ($srcCar['mileage'] ?? 0),
    ]);

    $existing = $checkExists->fetch();
    if ($existing) {
        $newCarId = (int) $existing['car_id'];
        $skippedCars++;
        $syncedExistingCars++;
    } else {
        $description = (string) ($srcCar['description'] ?? '');
        $sourceTag = '[SB_SOURCE_CAR_ID:' . (int) $srcCar['car_id'] . ']';
        if (strpos($description, $sourceTag) === false) {
            $description = trim($description . "\n" . $sourceTag);
        }

        $insertCar->execute([
            'seller_id' => $targetSellerId,
            'year' => (int) ($srcCar['year'] ?? 0),
            'mm_code' => (string) ($srcCar['mm_code'] ?? ''),
            'make' => (string) ($srcCar['make'] ?? ''),
            'model' => (string) ($srcCar['model'] ?? ''),
            'variant' => (string) ($srcCar['variant'] ?? ''),
            'custom_variant' => (string) ($srcCar['custom_variant'] ?? ''),
            'vin' => (string) ($srcCar['vin'] ?? ''),
            'mileage' => (int) ($srcCar['mileage'] ?? 0),
            'price' => (float) ($srcCar['price'] ?? 0),
            'color' => (string) ($srcCar['color'] ?? ''),
            'transmission' => (string) ($srcCar['transmission'] ?? ''),
            'fuel_type' => (string) ($srcCar['fuel_type'] ?? ''),
            'description' => $description,
            'finance_eligible' => (string) ($srcCar['finance_eligible'] ?? 'Yes'),
            'condition_type' => (string) ($srcCar['condition_type'] ?? 'Used'),
            'car_condition' => (string) ($srcCar['car_condition'] ?? ''),
            'status' => (string) ($srcCar['status'] ?? 'Available'),
            'visibility' => (string) ($srcCar['visibility'] ?? 'Yes'),
        ]);

        $newCarId = (int) $vgi->lastInsertId();
        $importedCars++;
    }

    $sourceImagesStmt->execute(['car_id' => (int) $srcCar['car_id']]);
    $sourceImages = $sourceImagesStmt->fetchAll();

    $targetCarDir = $targetBaseDir . '/sb_car_' . (int) $srcCar['car_id'];
    if (!is_dir($targetCarDir) && !mkdir($targetCarDir, 0755, true) && !is_dir($targetCarDir)) {
        continue;
    }

    $deleteImagesByCar->execute(['car_id' => $newCarId]);

    foreach ($sourceImages as $img) {
        $sourcePathRaw = trim(str_replace('\\\\', '/', (string) ($img['image_url'] ?? '')));
        if ($sourcePathRaw === '') {
            continue;
        }

        $candidatePaths = [];
        $candidatePaths[] = $sbRoot . '/' . ltrim($sourcePathRaw, '/');
        $candidatePaths[] = $sbRoot . '/admin/' . ltrim($sourcePathRaw, '/');
        $candidatePaths[] = $sbRoot . '/admin/img/cars/' . basename($sourcePathRaw);

        $sourceAbs = null;
        foreach ($candidatePaths as $candidate) {
            if (is_file($candidate)) {
                $sourceAbs = $candidate;
                break;
            }
        }

        if ($sourceAbs === null) {
            continue;
        }

        $filename = basename($sourceAbs);
        $targetAbs = $targetCarDir . '/' . $filename;

        if (!is_file($targetAbs)) {
            copy($sourceAbs, $targetAbs);
        }

        $targetWeb = $targetBaseWeb . '/sb_car_' . (int) $srcCar['car_id'] . '/' . $filename;
        $insertImage->execute([
            'car_id' => $newCarId,
            'image_url' => $targetWeb,
            'is_primary' => (int) ($img['is_primary'] ?? 0) === 1 ? 1 : 0,
        ]);

        $importedImages++;
    }
}

echo 'SB -> VGI import complete.' . PHP_EOL;
echo 'Imported cars: ' . $importedCars . PHP_EOL;
echo 'Skipped existing cars: ' . $skippedCars . PHP_EOL;
echo 'Existing cars image-synced: ' . $syncedExistingCars . PHP_EOL;
echo 'Imported images: ' . $importedImages . PHP_EOL;
