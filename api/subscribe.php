<?php
require_once __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['status' => 'error', 'message' => 'Invalid request method'], 405);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$preferredMake = trim($_POST['preferred_make'] ?? '');
$priceRange = trim($_POST['price_range'] ?? '');

if ($name === '' || $email === '') {
    json_response(['status' => 'error', 'message' => 'Name and email are required'], 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['status' => 'error', 'message' => 'Invalid email address'], 422);
}

$subscriberService->upsert($name, $email, $preferredMake, $priceRange);
json_response(['status' => 'success', 'message' => 'Successfully subscribed']);
