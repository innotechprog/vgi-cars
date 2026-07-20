<?php
require_once __DIR__ . '/../includes/bootstrap.php';
$auditLogService->log($auth->id(), 'logout', 'auth', (string) ($auth->id() ?? ''), 'User logged out');
$auth->logout();
header('Location: login.php');
exit;
