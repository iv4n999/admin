<?php
/**
 * Установщик базы данных
 * УДАЛИТЕ ЭТОТ ФАЙЛ ПОСЛЕ УСТАНОВКИ!
 */

require_once 'includes/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    // Создаём базу данных если не существует
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // Таблица городов
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            icon VARCHAR(50) DEFAULT '🚚',
            is_special TINYINT(1) DEFAULT 0,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Таблица отгрузок
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS shipments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            city_id INT NOT NULL,
            date_from DATE NOT NULL,
            date_to DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE,
            INDEX idx_dates (date_from, date_to),
            INDEX idx_city (city_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    
    // Проверяем, есть ли уже данные
    $check = $pdo->query("SELECT COUNT(*) FROM cities")->fetchColumn();
    
    if ($check == 0) {
        // Добавляем города
        $cities = [
            ['КАЗАНЬ', '🚚', 0, 1],
            ['КРАСНОДАР', '🚚', 0, 2],
            ['НОВОСЕМЕЙКИНО', '🚚', 0, 3],
            ['ЕКАТЕРИНБУРГ', '🚚', 0, 4],
            ['САРАПУЛ', '🚚', 0, 5],
            ['РЯЗАНЬ', '🚚', 0, 6],
            ['ТУЛА', '🚚', 0, 7],
            ['ЭЛЕКТРОСТАЛЬ И КОЛЕДИНО', '🎁', 1, 8],
        ];
        
        $stmt = $pdo->prepare("INSERT INTO cities (name, icon, is_special, sort_order) VALUES (?, ?, ?, ?)");
        foreach ($cities as $city) {
            $stmt->execute($city);
        }
        
        // Добавляем примеры отгрузок
        $shipments = [
            // Казань
            [1, '2025-12-23', '2025-12-26'],
            [1, '2025-12-25', '2025-12-28'],
            [1, '2025-12-28', '2025-12-31'],
            [1, '2026-01-08', '2026-01-10'],
            // Краснодар
            [2, '2025-12-22', '2025-12-25'],
            [2, '2025-12-23', '2025-12-26'],
            [2, '2025-12-25', '2025-12-29'],
            [2, '2025-12-29', '2026-01-01'],
            [2, '2026-01-08', '2026-01-12'],
            // ... остальные города
        ];
        
        $stmt = $pdo->prepare("INSERT INTO shipments (city_id, date_from, date_to) VALUES (?, ?, ?)");
        foreach ($shipments as $shipment) {
            $stmt->execute($shipment);
        }
    }
    
    echo "
    <!DOCTYPE html>
    <html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <title>Установка завершена</title>
        <style>
            body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
            .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 8px; }
            .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 8px; margin-top: 20px; }
            a { color: #007bff; }
            code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        </style>
    </head>
    <body>
        <div class='success'>
            <h1>✅ Установка завершена!</h1>
            <p>База данных успешно создана и заполнена.</p>
        </div>
        <div class='warning'>
            <h3>⚠️ ВАЖНО!</h3>
            <p><strong>Удалите файл <code>install.php</code> с сервера!</strong></p>
        </div>
        <p style='margin-top: 20px;'>
            <a href='/'>🌐 Открыть сайт</a> | 
            <a href='/admin/'>🔐 Админ-панель</a>
        </p>
    </body>
    </html>
    ";
    
} catch (PDOException $e) {
    die("<h1>❌ Ошибка установки</h1><p>" . $e->getMessage() . "</p>");
}
