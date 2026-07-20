<?php
require_once __DIR__ . '/../../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php?error=Invalid request');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    $auditLogService->log(null, 'login_failed', 'auth', null, 'Missing username or password');
    header('Location: ../login.php?error=Please fill in all fields');
    exit;
}

$user = $userService->verifyCredentials($username, $password);
if (!$user) {
    $auditLogService->log(null, 'login_failed', 'auth', null, 'Invalid credentials for username: ' . $username);
    header('Location: ../login.php?error=Invalid credentials');
    exit;
}

$auth->login($user);
$auditLogService->log((int) $user['user_id'], 'login_success', 'auth', (string) $user['user_id'], 'User logged in');
header('Location: ../user-profile.php');
exit;
