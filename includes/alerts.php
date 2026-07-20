<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function ensure_alert_log_table(PDO $db): void
{
    $db->exec('CREATE TABLE IF NOT EXISTS car_alert_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subscriber_id INT NOT NULL,
        car_id INT NOT NULL,
        sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_subscriber_id (subscriber_id),
        INDEX idx_car_id (car_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
}

function send_new_car_alerts(PDO $db, array $config, int $carId): int
{
    if (empty($config['mail']['enabled'])) {
        return 0;
    }

    ensure_alert_log_table($db);

    $carStmt = $db->prepare('SELECT * FROM cars WHERE car_id = :id LIMIT 1');
    $carStmt->execute(['id' => $carId]);
    $car = $carStmt->fetch();
    if (!$car) {
        return 0;
    }

    $imgStmt = $db->prepare('SELECT image_url FROM images WHERE car_id = :id ORDER BY is_primary DESC, image_id ASC LIMIT 1');
    $imgStmt->execute(['id' => $carId]);
    $img = $imgStmt->fetch();

    $sql = 'SELECT * FROM car_alerts_subscribers WHERE is_active = 1';
    $params = [];

    if (!empty($car['make'])) {
        $sql .= ' AND (preferred_make = :make OR preferred_make IS NULL OR preferred_make = "")';
        $params['make'] = $car['make'];
    }

    $subStmt = $db->prepare($sql);
    $subStmt->execute($params);
    $subs = $subStmt->fetchAll();

    if (!$subs) {
        return 0;
    }

    $phpMailerBase = dirname(__DIR__) . '/../PHPMailer';
    if (!is_dir($phpMailerBase)) {
        return 0;
    }

    require_once $phpMailerBase . '/Exception.php';
    require_once $phpMailerBase . '/PHPMailer.php';
    require_once $phpMailerBase . '/SMTP.php';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $config['mail']['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['mail']['username'];
    $mail->Password = $config['mail']['password'];
    $mail->SMTPSecure = $config['mail']['encryption'];
    $mail->Port = (int) $config['mail']['port'];
    $mail->setFrom($config['mail']['from_email'], $config['mail']['from_name']);
    $mail->isHTML(true);

    $sent = 0;
    foreach ($subs as $sub) {
        try {
            $mail->clearAddresses();
            $mail->addAddress($sub['email'], $sub['name']);
            $mail->Subject = 'New Car Alert: ' . ($car['make'] ?? '') . ' ' . ($car['model'] ?? '');

            $carName = trim(($car['year'] ?? '') . ' ' . ($car['make'] ?? '') . ' ' . ($car['model'] ?? ''));
            $price = number_format((float) ($car['price'] ?? 0));
            $imageUrl = !empty($img['image_url']) ? $img['image_url'] : 'images/hero-bg.png';
            $carLink = ($config['app']['base_url'] ?? '') . '/vehicle?id=' . $carId;

            $mail->Body = '<h2>New Car Available</h2>'
                . '<p>Hello ' . htmlspecialchars($sub['name']) . ',</p>'
                . '<p>A new vehicle matching your alert preferences is available:</p>'
                . '<p><strong>' . htmlspecialchars($carName) . '</strong> - R' . $price . '</p>'
                . '<p><img src="' . htmlspecialchars($imageUrl) . '" alt="' . htmlspecialchars($carName) . '" style="max-width:100%;height:auto;"></p>'
                . '<p><a href="' . htmlspecialchars($carLink) . '">View Vehicle</a></p>';

            $mail->send();

            $logStmt = $db->prepare('INSERT INTO car_alert_logs (subscriber_id, car_id) VALUES (:subscriber_id, :car_id)');
            $logStmt->execute([
                'subscriber_id' => (int) $sub['id'],
                'car_id' => $carId,
            ]);
            $sent++;
        } catch (Exception $e) {
            error_log('send_new_car_alerts failed for ' . ($sub['email'] ?? 'unknown') . ': ' . $e->getMessage());
        }
    }

    return $sent;
}
