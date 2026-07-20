<?php
require_once __DIR__ . '/../../includes/bootstrap.php';

$auth->requireLogin('../login.php');
$auth->requireRole('admin', '../login.php');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cars.php');
    exit;
}

$userId = $auth->id() ?? 0;
$carId = (int) ($_POST['car_id'] ?? 0);

if ($carId > 0) {
    $carService->update($carId, $userId, $_POST);
    $auditLogService->log($userId, 'car_updated', 'car', (string) $carId, 'Car updated');
} else {
    $carId = $carService->create($userId, $_POST);
    $auditLogService->log($userId, 'car_created', 'car', (string) $carId, 'Car created');
}

$isNewCar = !isset($_POST['car_id']) || (int) $_POST['car_id'] === 0;

if (!empty($_FILES['images']) && isset($_FILES['images']['tmp_name']) && is_array($_FILES['images']['tmp_name'])) {
    $targetDir = $uploadsDir . '/car_' . $carId;
    ensure_dir($targetDir);

    $acceptedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        if (!$tmpName || !is_uploaded_file($tmpName)) {
            continue;
        }

        $mime = $_FILES['images']['type'][$index] ?? '';
        $size = (int) ($_FILES['images']['size'][$index] ?? 0);
        if (!in_array($mime, $acceptedTypes, true) || $size > 10 * 1024 * 1024) {
            continue;
        }

        $ext = strtolower(pathinfo($_FILES['images']['name'][$index] ?? 'jpg', PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = 'jpg';
        }

        $name = sprintf('%03d.%s', $index + 1, $ext);
        $absPath = $targetDir . '/' . $name;
        if (move_uploaded_file($tmpName, $absPath)) {
            $webPath = $uploadsWeb . '/car_' . $carId . '/' . $name;
            $carService->saveImage($carId, $webPath, $index === 0);
        }
    }
}

if ($isNewCar) {
    $sent = send_new_car_alerts($db, $config, $carId);
    $auditLogService->log($userId, 'car_alerts_sent', 'car', (string) $carId, 'Alert emails sent: ' . (string) $sent);
}

header('Location: ../edit-car.php?id=' . $carId);
exit;
