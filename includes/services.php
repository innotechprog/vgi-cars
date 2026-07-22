<?php

class UserService
{
    public function __construct(private PDO $db)
    {
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE user_id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function verifyCredentials(string $username, string $password): ?array
    {
        $user = $this->findByUsername($username);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }
        return $user;
    }

    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE users SET
                email = :email,
                first_name = :first_name,
                last_name = :last_name,
                phone_number = :phone_number,
                about = :about,
                company = :company,
                job = :job,
                country = :country,
                address = :address,
                twitter = :twitter,
                facebook = :facebook,
                instagram = :instagram,
                linkedin = :linkedin
            WHERE user_id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'email' => $data['email'] ?? '',
            'first_name' => $data['first_name'] ?? '',
            'last_name' => $data['last_name'] ?? '',
            'phone_number' => $data['phone_number'] ?? '',
            'about' => $data['about'] ?? '',
            'company' => $data['company'] ?? '',
            'job' => $data['job'] ?? '',
            'country' => $data['country'] ?? '',
            'address' => $data['address'] ?? '',
            'twitter' => $data['twitter'] ?? '',
            'facebook' => $data['facebook'] ?? '',
            'instagram' => $data['instagram'] ?? '',
            'linkedin' => $data['linkedin'] ?? '',
        ]);
    }

    public function changePassword(int $id, string $newPassword): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET password_hash = :hash WHERE user_id = :id');
        return $stmt->execute([
            'id' => $id,
            'hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);
    }
}

class CarService
{
    public function __construct(private PDO $db)
    {
    }

    public function list(array $filters = []): array
    {
        $where = ['c.visibility = "Yes"'];
        $params = [];

        if (!empty($filters['make'])) {
            $where[] = 'c.make = :make';
            $params['make'] = $filters['make'];
        }
        if (!empty($filters['model'])) {
            $where[] = 'c.model = :model';
            $params['model'] = $filters['model'];
        }
        if (!empty($filters['year_min'])) {
            $where[] = 'c.year >= :year_min';
            $params['year_min'] = (int) $filters['year_min'];
        }
        if (!empty($filters['year_max'])) {
            $where[] = 'c.year <= :year_max';
            $params['year_max'] = (int) $filters['year_max'];
        }
        if (!empty($filters['price_max'])) {
            $where[] = 'c.price <= :price_max';
            $params['price_max'] = (float) $filters['price_max'];
        }

        $sql = 'SELECT c.car_id, c.make, c.model, c.year, c.mileage, c.price, c.transmission, c.fuel_type,
            c.description, c.variant, c.vin, c.color, c.mm_code, c.car_condition, c.condition_type, c.finance_eligible,
            COALESCE(
                (SELECT image_url FROM images i2 WHERE i2.car_id = c.car_id AND i2.is_primary = 1 LIMIT 1),
                (SELECT image_url FROM images i3 WHERE i3.car_id = c.car_id ORDER BY i3.image_id ASC LIMIT 1)
            ) AS image_url
            FROM cars c
            WHERE ' . implode(' AND ', $where) . ' ORDER BY c.car_id DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function count(array $filters = []): int
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['visibility'])) {
            $where[] = 'visibility = :visibility';
            $params['visibility'] = $filters['visibility'];
        }

        if (!empty($filters['q'])) {
            $where[] = '(make LIKE :q OR model LIKE :q OR variant LIKE :q OR CAST(year AS CHAR) LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM cars WHERE ' . implode(' AND ', $where));
        $stmt->execute($params);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function listPaginated(int $page = 1, int $perPage = 10, array $filters = []): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = ['1=1'];
        $params = [];

        if (!empty($filters['visibility'])) {
            $where[] = 'c.visibility = :visibility';
            $params['visibility'] = $filters['visibility'];
        }

        if (!empty($filters['q'])) {
            $where[] = '(c.make LIKE :q OR c.model LIKE :q OR c.variant LIKE :q OR CAST(c.year AS CHAR) LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql = 'SELECT c.car_id, c.make, c.model, c.year, c.mileage, c.price, c.transmission, c.fuel_type,
                c.description, c.variant, c.vin, c.color, c.mm_code, c.car_condition, c.condition_type,
                c.finance_eligible, c.visibility,
                COALESCE(
                    (SELECT image_url FROM images i2 WHERE i2.car_id = c.car_id AND i2.is_primary = 1 LIMIT 1),
                    (SELECT image_url FROM images i3 WHERE i3.car_id = c.car_id ORDER BY i3.image_id ASC LIMIT 1)
                ) AS image_url
            FROM cars c
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY c.car_id DESC
            LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $carId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM cars WHERE car_id = :id LIMIT 1');
        $stmt->execute(['id' => $carId]);
        $car = $stmt->fetch();
        return $car ?: null;
    }

    public function findImages(int $carId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM images WHERE car_id = :id ORDER BY is_primary DESC, image_id ASC');
        $stmt->execute(['id' => $carId]);
        return $stmt->fetchAll();
    }

    public function create(int $sellerId, array $data): int
    {
        $sql = 'INSERT INTO cars
            (seller_id, year, mm_code, make, model, variant, custom_variant, vin, mileage, price, color, transmission,
            fuel_type, description, finance_eligible, condition_type, car_condition, status, visibility)
            VALUES
            (:seller_id, :year, :mm_code, :make, :model, :variant, :custom_variant, :vin, :mileage, :price, :color, :transmission,
            :fuel_type, :description, :finance_eligible, :condition_type, :car_condition, :status, :visibility)';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'seller_id' => $sellerId,
            'year' => (int) ($data['year'] ?? 0),
            'mm_code' => $data['mm_code'] ?? '',
            'make' => $data['make'] ?? '',
            'model' => $data['model'] ?? '',
            'variant' => $data['variant'] ?? '',
            'custom_variant' => $data['custom_variant'] ?? '',
            'vin' => $data['vin'] ?? '',
            'mileage' => (int) ($data['mileage'] ?? 0),
            'price' => (float) ($data['price'] ?? 0),
            'color' => $data['color'] ?? '',
            'transmission' => $data['transmission'] ?? '',
            'fuel_type' => $data['fuel_type'] ?? '',
            'description' => $data['description'] ?? '',
            'finance_eligible' => $data['finance_eligible'] ?? 'Yes',
            'condition_type' => $data['condition_type'] ?? 'Used',
            'car_condition' => $data['condition'] ?? '',
            'status' => $data['status'] ?? 'Available',
            'visibility' => $data['visibility'] ?? 'Yes',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $carId, int $sellerId, array $data): bool
    {
        $sql = 'UPDATE cars SET
            seller_id = :seller_id, year = :year, mm_code = :mm_code, make = :make, model = :model, variant = :variant,
            custom_variant = :custom_variant, vin = :vin, mileage = :mileage, price = :price, color = :color,
            transmission = :transmission, fuel_type = :fuel_type, description = :description,
            finance_eligible = :finance_eligible, condition_type = :condition_type, car_condition = :car_condition,
            status = :status, visibility = :visibility WHERE car_id = :car_id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'car_id' => $carId,
            'seller_id' => $sellerId,
            'year' => (int) ($data['year'] ?? 0),
            'mm_code' => $data['mm_code'] ?? '',
            'make' => $data['make'] ?? '',
            'model' => $data['model'] ?? '',
            'variant' => $data['variant'] ?? '',
            'custom_variant' => $data['custom_variant'] ?? '',
            'vin' => $data['vin'] ?? '',
            'mileage' => (int) ($data['mileage'] ?? 0),
            'price' => (float) ($data['price'] ?? 0),
            'color' => $data['color'] ?? '',
            'transmission' => $data['transmission'] ?? '',
            'fuel_type' => $data['fuel_type'] ?? '',
            'description' => $data['description'] ?? '',
            'finance_eligible' => $data['finance_eligible'] ?? 'Yes',
            'condition_type' => $data['condition_type'] ?? 'Used',
            'car_condition' => $data['condition'] ?? '',
            'status' => $data['status'] ?? 'Available',
            'visibility' => $data['visibility'] ?? 'Yes',
        ]);
    }

    public function delete(int $carId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM cars WHERE car_id = :id');
        return $stmt->execute(['id' => $carId]);
    }

    public function saveImage(int $carId, string $imageUrl, bool $isPrimary): bool
    {
        if ($isPrimary) {
            $reset = $this->db->prepare('UPDATE images SET is_primary = 0 WHERE car_id = :id');
            $reset->execute(['id' => $carId]);
        }

        $stmt = $this->db->prepare('INSERT INTO images (car_id, image_url, is_primary) VALUES (:car_id, :image_url, :is_primary)');
        return $stmt->execute([
            'car_id' => $carId,
            'image_url' => $imageUrl,
            'is_primary' => $isPrimary ? 1 : 0,
        ]);
    }

    public function deleteImage(int $imageId): bool
    {
        $stmt = $this->db->prepare('SELECT image_url FROM images WHERE image_id = :id LIMIT 1');
        $stmt->execute(['id' => $imageId]);
        $img = $stmt->fetch();

        if ($img && !empty($img['image_url'])) {
            $file = __DIR__ . '/../' . ltrim($img['image_url'], '/');
            if (is_file($file)) {
                @unlink($file);
            }
        }

        $delete = $this->db->prepare('DELETE FROM images WHERE image_id = :id');
        return $delete->execute(['id' => $imageId]);
    }

    public function deleteImagesByCar(int $carId): void
    {
        $imgs = $this->findImages($carId);
        foreach ($imgs as $img) {
            $file = __DIR__ . '/../' . ltrim((string) ($img['image_url'] ?? ''), '/');
            if (is_file($file)) {
                @unlink($file);
            }
        }

        $stmt = $this->db->prepare('DELETE FROM images WHERE car_id = :id');
        $stmt->execute(['id' => $carId]);
    }

    public function calculateMonthlyRepayment(float $loanAmount, float $annualInterestRate = 11.72, int $months = 72): float
    {
        $monthlyRate = ($annualInterestRate / 100.0) / 12.0;
        if ($monthlyRate == 0.0) {
            return $loanAmount / max(1, $months);
        }

        $top = $loanAmount * $monthlyRate * pow(1 + $monthlyRate, $months);
        $bottom = pow(1 + $monthlyRate, $months) - 1;
        return (float) ($top / $bottom);
    }
}

class SubscriberService
{
    public function __construct(private PDO $db)
    {
    }

    public function ensureTable(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS car_alerts_subscribers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            preferred_make VARCHAR(100) DEFAULT NULL,
            price_range VARCHAR(50) DEFAULT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            unsubscribed_at DATETIME NULL
        )';
        $this->db->exec($sql);
    }

    public function upsert(string $name, string $email, string $preferredMake, string $priceRange): void
    {
        $exists = $this->db->prepare('SELECT id FROM car_alerts_subscribers WHERE email = :email LIMIT 1');
        $exists->execute(['email' => $email]);

        if ($exists->fetch()) {
            $stmt = $this->db->prepare('UPDATE car_alerts_subscribers
                SET name = :name, preferred_make = :preferred_make,
                price_range = :price_range, is_active = 1, updated_at = NOW()
                WHERE email = :email');
        } else {
            $stmt = $this->db->prepare('INSERT INTO car_alerts_subscribers
                (name, email, preferred_make, price_range, is_active, created_at, updated_at)
                VALUES (:name, :email, :preferred_make, :price_range, 1, NOW(), NOW())');
        }

        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'preferred_make' => $preferredMake,
            'price_range' => $priceRange,
        ]);
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT * FROM car_alerts_subscribers ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public function count(array $filters = []): int
    {
        $where = ['1=1'];
        $params = [];

        if (isset($filters['active']) && $filters['active'] !== '') {
            $where[] = 'is_active = :active';
            $params['active'] = (int) $filters['active'];
        }

        if (!empty($filters['q'])) {
            $where[] = '(name LIKE :q OR email LIKE :q OR preferred_make LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM car_alerts_subscribers WHERE ' . implode(' AND ', $where));
        $stmt->execute($params);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function listPaginated(int $page = 1, int $perPage = 10, array $filters = []): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = ['1=1'];
        $params = [];

        if (isset($filters['active']) && $filters['active'] !== '') {
            $where[] = 'is_active = :active';
            $params['active'] = (int) $filters['active'];
        }

        if (!empty($filters['q'])) {
            $where[] = '(name LIKE :q OR email LIKE :q OR preferred_make LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql = 'SELECT * FROM car_alerts_subscribers
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function toggleStatus(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE car_alerts_subscribers SET is_active = NOT is_active WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM car_alerts_subscribers WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

class SettingsService
{
    public function __construct(private PDO $db)
    {
    }

    public function ensureTable(): void
    {
        $this->db->exec('CREATE TABLE IF NOT EXISTS settings (
            setting_key VARCHAR(120) PRIMARY KEY,
            setting_value TEXT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT setting_key, setting_value FROM settings ORDER BY setting_key');
        return $stmt->fetchAll();
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $stmt = $this->db->prepare('SELECT setting_value FROM settings WHERE setting_key = :k LIMIT 1');
        $stmt->execute(['k' => $key]);
        $row = $stmt->fetch();
        return $row ? (string) $row['setting_value'] : $default;
    }

    public function set(string $key, ?string $value): void
    {
        $stmt = $this->db->prepare('INSERT INTO settings (setting_key, setting_value)
            VALUES (:k, :v)
            ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        $stmt->execute(['k' => $key, 'v' => $value]);
    }
}

class AuditLogService
{
    public function __construct(private PDO $db)
    {
    }

    public function ensureTable(): void
    {
        $this->db->exec('CREATE TABLE IF NOT EXISTS audit_logs (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            action VARCHAR(120) NOT NULL,
            entity VARCHAR(120) NULL,
            entity_id VARCHAR(120) NULL,
            details TEXT NULL,
            ip_address VARCHAR(45) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_action (action),
            INDEX idx_entity (entity),
            INDEX idx_created_at (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }

    public function log(?int $userId, string $action, ?string $entity = null, ?string $entityId = null, ?string $details = null): void
    {
        $stmt = $this->db->prepare('INSERT INTO audit_logs (user_id, action, entity, entity_id, details, ip_address)
            VALUES (:user_id, :action, :entity, :entity_id, :details, :ip)');
        $stmt->execute([
            'user_id' => $userId,
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'details' => $details,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    }

    public function count(array $filters = []): int
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['action'])) {
            $where[] = 'action = :action';
            $params['action'] = $filters['action'];
        }
        if (!empty($filters['entity'])) {
            $where[] = 'entity = :entity';
            $params['entity'] = $filters['entity'];
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM audit_logs WHERE ' . implode(' AND ', $where));
        $stmt->execute($params);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function listPaginated(int $page = 1, int $perPage = 20, array $filters = []): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(200, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = ['1=1'];
        $params = [];
        if (!empty($filters['action'])) {
            $where[] = 'l.action = :action';
            $params['action'] = $filters['action'];
        }
        if (!empty($filters['entity'])) {
            $where[] = 'l.entity = :entity';
            $params['entity'] = $filters['entity'];
        }

        $sql = 'SELECT l.*, u.username
            FROM audit_logs l
            LEFT JOIN users u ON u.user_id = l.user_id
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY l.id DESC
            LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

class SalesService
{
    public function __construct(private PDO $db)
    {
    }

    public function ensureTables(): void
    {
        $this->db->exec('CREATE TABLE IF NOT EXISTS customers (
            customer_id INT AUTO_INCREMENT PRIMARY KEY,
            first_names VARCHAR(150) NOT NULL,
            last_name VARCHAR(150) DEFAULT NULL,
            id_number VARCHAR(50) DEFAULT NULL,
            email VARCHAR(255) DEFAULT NULL,
            cellphone VARCHAR(50) DEFAULT NULL,
            address_line1 VARCHAR(255) DEFAULT NULL,
            address_line2 VARCHAR(255) DEFAULT NULL,
            city VARCHAR(120) DEFAULT NULL,
            state_region VARCHAR(120) DEFAULT NULL,
            postal_code VARCHAR(30) DEFAULT NULL,
            country VARCHAR(120) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_customer_id_number (id_number),
            UNIQUE KEY uniq_customer_email (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        $this->db->exec('CREATE TABLE IF NOT EXISTS sales (
            sale_id INT AUTO_INCREMENT PRIMARY KEY,
            sale_number VARCHAR(50) NOT NULL UNIQUE,
            invoice_number VARCHAR(50) NOT NULL UNIQUE,
            sale_brand VARCHAR(32) NOT NULL DEFAULT "sb_autogroup",
            customer_id INT NOT NULL,
            created_by_user_id INT DEFAULT NULL,
            sale_date DATE NOT NULL,
            payment_method VARCHAR(100) DEFAULT NULL,
            subtotal_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
            deposit_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
            admin_fee_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
            outstanding_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
            total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
            notes TEXT DEFAULT NULL,
            status VARCHAR(50) NOT NULL DEFAULT "completed",
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_sales_customer (customer_id),
            INDEX idx_sales_date (sale_date)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

        try {
            $this->db->exec('ALTER TABLE sales ADD COLUMN sale_brand VARCHAR(32) NOT NULL DEFAULT "sb_autogroup" AFTER invoice_number');
        } catch (Throwable $e) {
            // Ignore duplicate-column errors when the schema is already up to date.
            if (!($e instanceof PDOException) || (int) ($e->errorInfo[1] ?? 0) !== 1060) {
                throw $e;
            }
        }

        $this->db->exec('CREATE TABLE IF NOT EXISTS sale_items (
            sale_item_id INT AUTO_INCREMENT PRIMARY KEY,
            sale_id INT NOT NULL,
            car_id INT DEFAULT NULL,
            registration_number VARCHAR(100) DEFAULT NULL,
            vehicle_description VARCHAR(255) NOT NULL,
            vehicle_make VARCHAR(120) DEFAULT NULL,
            vehicle_model VARCHAR(120) DEFAULT NULL,
            vehicle_year INT DEFAULT NULL,
            vin_number VARCHAR(120) DEFAULT NULL,
            engine_number VARCHAR(120) DEFAULT NULL,
            color VARCHAR(100) DEFAULT NULL,
            quantity INT NOT NULL DEFAULT 1,
            unit_price DECIMAL(12,2) NOT NULL DEFAULT 0,
            line_total DECIMAL(12,2) NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_sale_items_sale (sale_id),
            INDEX idx_sale_items_car (car_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    }

    public function count(array $filters = []): int
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(s.invoice_number LIKE :q OR s.sale_number LIKE :q OR c.first_names LIKE :q OR c.last_name LIKE :q OR c.id_number LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) AS total
            FROM sales s
            INNER JOIN customers c ON c.customer_id = s.customer_id
            WHERE ' . implode(' AND ', $where));
        $stmt->execute($params);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function countCustomers(array $filters = []): int
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(c.first_names LIKE :q OR c.last_name LIKE :q OR c.id_number LIKE :q OR c.email LIKE :q OR c.cellphone LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM customers c WHERE ' . implode(' AND ', $where));
        $stmt->execute($params);
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function listCustomersPaginated(int $page = 1, int $perPage = 15, array $filters = []): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = ['1=1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(c.first_names LIKE :q OR c.last_name LIKE :q OR c.id_number LIKE :q OR c.email LIKE :q OR c.cellphone LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql = 'SELECT c.*,
                COUNT(s.sale_id) AS total_sales,
                COALESCE(SUM(s.total_amount), 0) AS total_spent,
                MAX(s.sale_date) AS last_purchase_date
            FROM customers c
            LEFT JOIN sales s ON s.customer_id = c.customer_id
            WHERE ' . implode(' AND ', $where) . '
            GROUP BY c.customer_id
            ORDER BY c.customer_id DESC
            LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findCustomer(int $customerId): ?array
    {
        $stmt = $this->db->prepare('SELECT c.*,
                COUNT(s.sale_id) AS total_sales,
                COALESCE(SUM(s.total_amount), 0) AS total_spent,
                MAX(s.sale_date) AS last_purchase_date
            FROM customers c
            LEFT JOIN sales s ON s.customer_id = c.customer_id
            WHERE c.customer_id = :customer_id
            GROUP BY c.customer_id
            LIMIT 1');
        $stmt->execute(['customer_id' => $customerId]);
        $customer = $stmt->fetch();
        return $customer ?: null;
    }

    public function listCustomerSales(int $customerId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM sales WHERE customer_id = :customer_id ORDER BY sale_date DESC, sale_id DESC');
        $stmt->execute(['customer_id' => $customerId]);
        return $stmt->fetchAll();
    }

    public function listCustomerOptions(int $limit = 500): array
    {
        $limit = max(1, min(1000, $limit));
        $stmt = $this->db->prepare('SELECT
                customer_id,
                first_names,
                last_name,
                id_number,
                email,
                cellphone,
                address_line1,
                address_line2,
                city,
                state_region,
                postal_code,
                country
            FROM customers
            ORDER BY customer_id DESC
            LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function listPaginated(int $page = 1, int $perPage = 15, array $filters = []): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $where = ['1=1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(s.invoice_number LIKE :q OR s.sale_number LIKE :q OR c.first_names LIKE :q OR c.last_name LIKE :q OR c.id_number LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql = 'SELECT s.*, c.customer_id, c.first_names, c.last_name, c.id_number
            FROM sales s
            INNER JOIN customers c ON c.customer_id = s.customer_id
            WHERE ' . implode(' AND ', $where) . '
            ORDER BY s.sale_id DESC
            LIMIT :limit OFFSET :offset';

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createSale(array $customerData, array $saleData, array $items): int
    {
        if (!$items) {
            throw new InvalidArgumentException('At least one sale item is required.');
        }

        $this->db->beginTransaction();
        try {
            $customerId = $this->upsertCustomer($customerData);
            $invoiceNumber = $this->nextInvoiceNumber();
            $saleNumber = $this->nextSaleNumber();

            $subtotal = 0.0;
            foreach ($items as &$item) {
                $quantity = max(1, (int) ($item['quantity'] ?? 1));
                $unitPrice = (float) ($item['unit_price'] ?? 0);
                $item['quantity'] = $quantity;
                $item['line_total'] = $quantity * $unitPrice;
                $subtotal += $item['line_total'];
            }
            unset($item);

            $deposit = (float) ($saleData['deposit_amount'] ?? 0);
            $adminFee = (float) ($saleData['admin_fee_amount'] ?? 0);
            $outstanding = (float) ($saleData['outstanding_amount'] ?? 0);
            $total = (float) ($saleData['total_amount'] ?? ($subtotal + $adminFee - $deposit));

            $saleStmt = $this->db->prepare('INSERT INTO sales
                (sale_number, invoice_number, sale_brand, customer_id, created_by_user_id, sale_date, payment_method,
                 subtotal_amount, deposit_amount, admin_fee_amount, outstanding_amount, total_amount, notes, status)
                VALUES
                (:sale_number, :invoice_number, :sale_brand, :customer_id, :created_by_user_id, :sale_date, :payment_method,
                 :subtotal_amount, :deposit_amount, :admin_fee_amount, :outstanding_amount, :total_amount, :notes, :status)');
            $saleStmt->execute([
                'sale_number' => $saleNumber,
                'invoice_number' => $invoiceNumber,
                'sale_brand' => in_array((string) ($saleData['sale_brand'] ?? ''), ['sb_autogroup', 'vgi_cars'], true) ? (string) $saleData['sale_brand'] : 'sb_autogroup',
                'customer_id' => $customerId,
                'created_by_user_id' => $saleData['created_by_user_id'] ?? null,
                'sale_date' => $saleData['sale_date'] ?? date('Y-m-d'),
                'payment_method' => $saleData['payment_method'] ?? '',
                'subtotal_amount' => $subtotal,
                'deposit_amount' => $deposit,
                'admin_fee_amount' => $adminFee,
                'outstanding_amount' => $outstanding,
                'total_amount' => $total,
                'notes' => $saleData['notes'] ?? '',
                'status' => $saleData['status'] ?? 'completed',
            ]);

            $saleId = (int) $this->db->lastInsertId();

            $itemStmt = $this->db->prepare('INSERT INTO sale_items
                (sale_id, car_id, registration_number, vehicle_description, vehicle_make, vehicle_model,
                 vehicle_year, vin_number, engine_number, color, quantity, unit_price, line_total)
                VALUES
                (:sale_id, :car_id, :registration_number, :vehicle_description, :vehicle_make, :vehicle_model,
                 :vehicle_year, :vin_number, :engine_number, :color, :quantity, :unit_price, :line_total)');

            foreach ($items as $item) {
                $itemStmt->execute([
                    'sale_id' => $saleId,
                    'car_id' => $item['car_id'] ?: null,
                    'registration_number' => $item['registration_number'] ?? '',
                    'vehicle_description' => $item['vehicle_description'] ?? '',
                    'vehicle_make' => $item['vehicle_make'] ?? '',
                    'vehicle_model' => $item['vehicle_model'] ?? '',
                    'vehicle_year' => $item['vehicle_year'] !== '' ? (int) $item['vehicle_year'] : null,
                    'vin_number' => $item['vin_number'] ?? '',
                    'engine_number' => $item['engine_number'] ?? '',
                    'color' => $item['color'] ?? '',
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'unit_price' => (float) ($item['unit_price'] ?? 0),
                    'line_total' => (float) ($item['line_total'] ?? 0),
                ]);
            }

            $this->db->commit();
            return $saleId;
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function findSale(int $saleId): ?array
    {
        $stmt = $this->db->prepare('SELECT s.*, c.*, u.first_name AS created_by_first_name, u.last_name AS created_by_last_name
            FROM sales s
            INNER JOIN customers c ON c.customer_id = s.customer_id
            LEFT JOIN users u ON u.user_id = s.created_by_user_id
            WHERE s.sale_id = :id LIMIT 1');
        $stmt->execute(['id' => $saleId]);
        $sale = $stmt->fetch();
        return $sale ?: null;
    }

    public function listSaleItems(int $saleId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM sale_items WHERE sale_id = :sale_id ORDER BY sale_item_id ASC');
        $stmt->execute(['sale_id' => $saleId]);
        return $stmt->fetchAll();
    }

    private function upsertCustomer(array $data): int
    {
        $customerId = (int) ($data['customer_id'] ?? 0);
        $idNumber = trim((string) ($data['id_number'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));

        if ($customerId > 0) {
            $stmt = $this->db->prepare('SELECT customer_id FROM customers WHERE customer_id = :customer_id LIMIT 1');
            $stmt->execute(['customer_id' => $customerId]);
            $existing = $stmt->fetch();
            if ($existing) {
                $payload = [
                    'first_names' => trim((string) ($data['first_names'] ?? '')),
                    'last_name' => trim((string) ($data['last_name'] ?? '')),
                    'id_number' => $idNumber !== '' ? $idNumber : null,
                    'email' => $email !== '' ? $email : null,
                    'cellphone' => trim((string) ($data['cellphone'] ?? '')),
                    'address_line1' => trim((string) ($data['address_line1'] ?? '')),
                    'address_line2' => trim((string) ($data['address_line2'] ?? '')),
                    'city' => trim((string) ($data['city'] ?? '')),
                    'state_region' => trim((string) ($data['state_region'] ?? '')),
                    'postal_code' => trim((string) ($data['postal_code'] ?? '')),
                    'country' => trim((string) ($data['country'] ?? '')),
                    'customer_id' => $customerId,
                ];

                $stmt = $this->db->prepare('UPDATE customers SET
                    first_names = :first_names,
                    last_name = :last_name,
                    id_number = :id_number,
                    email = :email,
                    cellphone = :cellphone,
                    address_line1 = :address_line1,
                    address_line2 = :address_line2,
                    city = :city,
                    state_region = :state_region,
                    postal_code = :postal_code,
                    country = :country
                    WHERE customer_id = :customer_id');
                $stmt->execute($payload);
                return $customerId;
            }
        }

        $existing = null;
        if ($idNumber !== '') {
            $stmt = $this->db->prepare('SELECT customer_id FROM customers WHERE id_number = :id_number LIMIT 1');
            $stmt->execute(['id_number' => $idNumber]);
            $existing = $stmt->fetch();
        }
        if (!$existing && $email !== '') {
            $stmt = $this->db->prepare('SELECT customer_id FROM customers WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $existing = $stmt->fetch();
        }

        $payload = [
            'first_names' => trim((string) ($data['first_names'] ?? '')),
            'last_name' => trim((string) ($data['last_name'] ?? '')),
            'id_number' => $idNumber !== '' ? $idNumber : null,
            'email' => $email !== '' ? $email : null,
            'cellphone' => trim((string) ($data['cellphone'] ?? '')),
            'address_line1' => trim((string) ($data['address_line1'] ?? '')),
            'address_line2' => trim((string) ($data['address_line2'] ?? '')),
            'city' => trim((string) ($data['city'] ?? '')),
            'state_region' => trim((string) ($data['state_region'] ?? '')),
            'postal_code' => trim((string) ($data['postal_code'] ?? '')),
            'country' => trim((string) ($data['country'] ?? '')),
        ];

        if ($existing) {
            $stmt = $this->db->prepare('UPDATE customers SET
                first_names = :first_names,
                last_name = :last_name,
                id_number = :id_number,
                email = :email,
                cellphone = :cellphone,
                address_line1 = :address_line1,
                address_line2 = :address_line2,
                city = :city,
                state_region = :state_region,
                postal_code = :postal_code,
                country = :country
                WHERE customer_id = :customer_id');
            $payload['customer_id'] = (int) $existing['customer_id'];
            $stmt->execute($payload);
            return (int) $existing['customer_id'];
        }

        $stmt = $this->db->prepare('INSERT INTO customers
            (first_names, last_name, id_number, email, cellphone, address_line1, address_line2, city, state_region, postal_code, country)
            VALUES
            (:first_names, :last_name, :id_number, :email, :cellphone, :address_line1, :address_line2, :city, :state_region, :postal_code, :country)');
        $stmt->execute($payload);
        return (int) $this->db->lastInsertId();
    }

    private function nextInvoiceNumber(): string
    {
        $stmt = $this->db->query("SELECT MAX(CAST(invoice_number AS UNSIGNED)) AS max_invoice FROM sales WHERE invoice_number REGEXP '^[0-9]+$'");
        $row = $stmt->fetch();
        $next = (int) ($row['max_invoice'] ?? 0);
        if ($next <= 0) {
            $next = 1053730;
        } else {
            $next++;
        }
        return (string) $next;
    }

    private function nextSaleNumber(): string
    {
        $stmt = $this->db->query('SELECT COUNT(*) AS total FROM sales');
        $row = $stmt->fetch();
        $next = ((int) ($row['total'] ?? 0)) + 1;
        return 'SALE-' . date('Ymd') . '-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
