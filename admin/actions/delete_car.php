<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
$auth->requireLogin('../login.php');
$auth->requireRole('admin', '../login.php');

$carId = (int) ($_GET['id'] ?? 0);
if ($carId > 0) {
    $carService->deleteImagesByCar($carId);
    $carService->delete($carId);
    $auditLogService->log($auth->id(), 'car_deleted', 'car', (string) $carId, 'Car and images deleted');
}

header('Location: ../cars.php');
exit;
