<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
$auth->requireLogin('../login.php');

$imageId = (int) ($_GET['id'] ?? 0);
$carId = (int) ($_GET['car_id'] ?? 0);
if ($imageId > 0) {
    $carService->deleteImage($imageId);
}

header('Location: ../edit-car.php?id=' . $carId);
exit;
