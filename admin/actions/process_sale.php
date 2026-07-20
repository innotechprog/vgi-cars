<?php
require_once __DIR__ . '/../../includes/bootstrap.php';

$auth->requireLogin('../login.php');
$auth->requireRole('admin', '../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../sales.php?msg=Invalid request');
    exit;
}

$customerData = [
    'first_names' => trim((string) ($_POST['first_names'] ?? '')),
    'last_name' => trim((string) ($_POST['last_name'] ?? '')),
    'id_number' => trim((string) ($_POST['id_number'] ?? '')),
    'email' => trim((string) ($_POST['email'] ?? '')),
    'cellphone' => trim((string) ($_POST['cellphone'] ?? '')),
    'address_line1' => trim((string) ($_POST['address_line1'] ?? '')),
    'address_line2' => trim((string) ($_POST['address_line2'] ?? '')),
    'city' => trim((string) ($_POST['city'] ?? '')),
    'state_region' => trim((string) ($_POST['state_region'] ?? '')),
    'postal_code' => trim((string) ($_POST['postal_code'] ?? '')),
    'country' => trim((string) ($_POST['country'] ?? 'South Africa')),
];

if ($customerData['first_names'] === '') {
    header('Location: ../sale-form.php?msg=Customer first names are required');
    exit;
}

$lineCarIds = $_POST['line_car_id'] ?? [];
$lineDescriptions = $_POST['line_description'] ?? [];
$lineRegs = $_POST['line_registration_number'] ?? [];
$lineMakes = $_POST['line_make'] ?? [];
$lineModels = $_POST['line_model'] ?? [];
$lineYears = $_POST['line_year'] ?? [];
$lineVins = $_POST['line_vin'] ?? [];
$lineEngines = $_POST['line_engine_number'] ?? [];
$lineColors = $_POST['line_color'] ?? [];
$linePrices = $_POST['line_unit_price'] ?? [];
$lineQuantities = $_POST['line_quantity'] ?? [];

$rowCount = max(
    count($lineCarIds),
    count($lineDescriptions),
    count($lineRegs),
    count($linePrices)
);

$items = [];
for ($index = 0; $index < $rowCount; $index++) {
    $carId = (int) ($lineCarIds[$index] ?? 0);
    $description = trim((string) ($lineDescriptions[$index] ?? ''));
    $registrationNumber = trim((string) ($lineRegs[$index] ?? ''));
    $make = trim((string) ($lineMakes[$index] ?? ''));
    $model = trim((string) ($lineModels[$index] ?? ''));
    $year = trim((string) ($lineYears[$index] ?? ''));
    $vin = trim((string) ($lineVins[$index] ?? ''));
    $engine = trim((string) ($lineEngines[$index] ?? ''));
    $color = trim((string) ($lineColors[$index] ?? ''));
    $unitPrice = (float) ($linePrices[$index] ?? 0);
    $quantity = max(1, (int) ($lineQuantities[$index] ?? 1));

    if ($carId <= 0 && $description === '' && $unitPrice <= 0) {
        continue;
    }

    if ($carId > 0) {
        $car = $carService->find($carId);
        if ($car) {
            $make = $make !== '' ? $make : (string) ($car['make'] ?? '');
            $model = $model !== '' ? $model : (string) ($car['model'] ?? '');
            $year = $year !== '' ? $year : (string) ($car['year'] ?? '');
            $vin = $vin !== '' ? $vin : (string) ($car['vin'] ?? '');
            $color = $color !== '' ? $color : (string) ($car['color'] ?? '');
            $unitPrice = $unitPrice > 0 ? $unitPrice : (float) ($car['price'] ?? 0);
            if ($description === '') {
                $description = trim(($car['year'] ?? '') . ' ' . ($car['make'] ?? '') . ' ' . ($car['model'] ?? ''));
            }
        }
    }

    $items[] = [
        'car_id' => $carId,
        'registration_number' => $registrationNumber,
        'vehicle_description' => $description,
        'vehicle_make' => $make,
        'vehicle_model' => $model,
        'vehicle_year' => $year,
        'vin_number' => $vin,
        'engine_number' => $engine,
        'color' => $color,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
    ];
}

if (!$items) {
    header('Location: ../sale-form.php?msg=Add at least one purchased vehicle record');
    exit;
}

$saleData = [
    'created_by_user_id' => $auth->id(),
    'sale_date' => trim((string) ($_POST['sale_date'] ?? date('Y-m-d'))),
    'payment_method' => trim((string) ($_POST['payment_method'] ?? 'cash')),
    'deposit_amount' => (float) ($_POST['deposit_amount'] ?? 0),
    'admin_fee_amount' => (float) ($_POST['admin_fee_amount'] ?? 0),
    'outstanding_amount' => (float) ($_POST['outstanding_amount'] ?? 0),
    'total_amount' => (float) ($_POST['total_amount'] ?? 0),
    'notes' => trim((string) ($_POST['notes'] ?? '')),
    'status' => trim((string) ($_POST['status'] ?? 'completed')),
];

try {
    $saleId = $salesService->createSale($customerData, $saleData, $items);
    $auditLogService->log($auth->id(), 'sale_created', 'sale', (string) $saleId, 'Invoice generated and customer stored');
    header('Location: ../invoice.php?sale_id=' . $saleId);
    exit;
} catch (Throwable $e) {
    header('Location: ../sale-form.php?msg=' . urlencode('Failed to generate invoice: ' . $e->getMessage()));
    exit;
}