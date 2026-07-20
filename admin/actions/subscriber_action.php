<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
$auth->requireLogin('../login.php');
$auth->requireRole('admin', '../login.php');

$action = $_GET['action'] ?? '';
$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    if ($action === 'toggle') {
        $subscriberService->toggleStatus($id);
        $auditLogService->log($auth->id(), 'subscriber_toggled', 'subscriber', (string) $id, 'Subscriber status toggled');
    } elseif ($action === 'delete') {
        $subscriberService->delete($id);
        $auditLogService->log($auth->id(), 'subscriber_deleted', 'subscriber', (string) $id, 'Subscriber deleted');
    }
}

header('Location: ../subscribers.php?msg=Subscriber updated');
exit;
