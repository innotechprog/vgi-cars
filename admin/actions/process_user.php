<?php
require_once __DIR__ . '/../../includes/bootstrap.php';
$auth->requireLogin('../login.php');
$auth->requireRole('admin', '../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../user-profile.php');
    exit;
}

$userId = $auth->id() ?? 0;
$action = $_POST['action'] ?? 'profile';

if ($action === 'profile') {
    $userService->updateProfile($userId, $_POST);
    $auditLogService->log($userId, 'profile_updated', 'user', (string) $userId, 'Profile updated');
    header('Location: ../user-profile.php?msg=Profile updated');
    exit;
}

if ($action === 'password') {
    $user = $userService->findById($userId);
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$user || !password_verify($current, $user['password_hash'] ?? '')) {
        header('Location: ../user-profile.php?msg=Current password is incorrect');
        exit;
    }

    if ($new === '' || $new !== $confirm) {
        header('Location: ../user-profile.php?msg=New passwords do not match');
        exit;
    }

    $userService->changePassword($userId, $new);
    $auditLogService->log($userId, 'password_updated', 'user', (string) $userId, 'Password changed');
    header('Location: ../user-profile.php?msg=Password updated');
    exit;
}

header('Location: ../user-profile.php');
exit;
