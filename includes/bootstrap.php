<?php

$config = require __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/services.php';
require_once __DIR__ . '/alerts.php';

try {
	try {
		$db = (new \Database($config['db']))->pdo();
	} catch (Throwable $dbError) {
		$legacyDb = [];
		if (function_exists('cfg_parse_legacy_online_db')) {
			$legacyDb = cfg_parse_legacy_online_db(__DIR__ . '/online setup.php');
		}

		if (!empty($legacyDb)) {
			$config['db'] = array_replace($config['db'], $legacyDb);
			$db = (new \Database($config['db']))->pdo();
		} else {
			throw $dbError;
		}
	}

	$auth = new \Auth();
	$userService = new \UserService($db);
	$carService = new \CarService($db);
	$subscriberService = new \SubscriberService($db);
	$settingsService = new \SettingsService($db);
	$auditLogService = new \AuditLogService($db);
	$salesService = new \SalesService($db);
	$subscriberService->ensureTable();
	$settingsService->ensureTable();
	$auditLogService->ensureTable();
	$salesService->ensureTables();

	$config['mail']['enabled'] = ($settingsService->get('mail_enabled', (string) ((int) ($config['mail']['enabled'] ?? 0))) === '1');
	$config['mail']['host'] = $settingsService->get('smtp_host', $config['mail']['host']);
	$config['mail']['port'] = (int) $settingsService->get('smtp_port', (string) $config['mail']['port']);
	$config['mail']['username'] = $settingsService->get('smtp_username', $config['mail']['username']);
	$config['mail']['password'] = $settingsService->get('smtp_password', $config['mail']['password']);
	$config['mail']['from_email'] = $settingsService->get('smtp_from_email', $config['mail']['from_email']);
	$config['mail']['from_name'] = $settingsService->get('smtp_from_name', $config['mail']['from_name']);

	$uploadsDir = $config['app']['uploads_dir'];
	$uploadsWeb = $config['app']['uploads_web'];
	ensure_dir($uploadsDir);
} catch (Throwable $e) {
	error_log('[vgi-bootstrap] ' . $e->getMessage());

	$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '');
	$isApiRequest = stripos($requestUri, '/api/') !== false;
	if ($isApiRequest) {
		json_response([
			'success' => false,
			'message' => 'Server configuration error',
		], 500);
	}

	throw $e;
}
