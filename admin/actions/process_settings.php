<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
$auth->requireRole('admin', '../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../settings.php');
    exit;
}

$allowed = [
    'company_name',
    'company_registration_number',
    'company_address',
    'company_phone',
    'site_contact_email',
    'site_phone',
    'smtp_host',
    'smtp_port',
    'smtp_username',
    'smtp_password',
    'smtp_from_email',
    'smtp_from_name',
    'mail_enabled',
];

foreach ($allowed as $key) {
    $settingsService->set($key, trim((string) ($_POST[$key] ?? '')));
}

$auditLogService->log($auth->id(), 'settings_updated', 'settings', null, 'System settings updated');
header('Location: ../settings.php?msg=Settings saved');
exit;
