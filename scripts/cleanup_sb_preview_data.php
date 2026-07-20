<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$config = require $root . '/includes/config.php';

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $config['db']['host'],
    $config['db']['name'],
    $config['db']['charset']
);

$db = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$selectCars = $db->query("SELECT car_id FROM cars WHERE description LIKE '%[SB_SOURCE_CAR_ID:%' ORDER BY car_id DESC");
$carIds = array_map(static fn(array $r): int => (int) $r['car_id'], $selectCars->fetchAll());

$deletedCars = 0;
$deletedImages = 0;

if ($carIds) {
    $in = implode(',', array_fill(0, count($carIds), '?'));

    $imgStmt = $db->prepare("SELECT image_url FROM images WHERE car_id IN ($in)");
    $imgStmt->execute($carIds);
    $images = $imgStmt->fetchAll();

    foreach ($images as $img) {
        $path = trim((string) ($img['image_url'] ?? ''));
        if ($path === '') {
            continue;
        }
        $abs = $root . '/' . ltrim(str_replace('\\\\', '/', $path), '/');
        if (is_file($abs)) {
            @unlink($abs);
        }
        $deletedImages++;
    }

    $delImg = $db->prepare("DELETE FROM images WHERE car_id IN ($in)");
    $delImg->execute($carIds);

    $delCars = $db->prepare("DELETE FROM cars WHERE car_id IN ($in)");
    $delCars->execute($carIds);
    $deletedCars = count($carIds);
}

$sbPreviewDir = $config['app']['uploads_dir'];
if (is_dir($sbPreviewDir)) {
    foreach (scandir($sbPreviewDir) ?: [] as $name) {
        if ($name === '.' || $name === '..') {
            continue;
        }
        if (strpos($name, 'sb_car_') !== 0) {
            continue;
        }
        $dir = $sbPreviewDir . '/' . $name;
        if (!is_dir($dir)) {
            continue;
        }
        $files = scandir($dir) ?: [];
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            @unlink($dir . '/' . $file);
        }
        @rmdir($dir);
    }
}

echo 'Cleanup complete.' . PHP_EOL;
echo 'Deleted cars: ' . $deletedCars . PHP_EOL;
echo 'Deleted image records (attempted): ' . $deletedImages . PHP_EOL;
