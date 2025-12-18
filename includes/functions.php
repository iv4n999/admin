<?php
/**
 * Вспомогательные функции
 */

require_once __DIR__ . '/db.php';

/**
 * Проверка авторизации
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Требование авторизации
 */
function requireAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Получить все города
 */
function getCities() {
    $pdo = getDB();
    $stmt = $pdo->query("SELECT * FROM cities ORDER BY sort_order, name");
    return $stmt->fetchAll();
}

/**
 * Получить город по ID
 */
function getCity($id) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM cities WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Получить даты отгрузок для города (только будущие)
 */
function getShipments($cityId, $includePast = false) {
    $pdo = getDB();
    
    if ($includePast) {
        $sql = "SELECT * FROM shipments WHERE city_id = ? ORDER BY date_from";
    } else {
        $sql = "SELECT * FROM shipments WHERE city_id = ? AND date_to >= CURDATE() ORDER BY date_from";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cityId]);
    return $stmt->fetchAll();
}

/**
 * Получить все отгрузки (для админки)
 */
function getAllShipments() {
    $pdo = getDB();
    $stmt = $pdo->query("
        SELECT s.*, c.name as city_name, c.icon as city_icon
        FROM shipments s 
        JOIN cities c ON s.city_id = c.id 
        ORDER BY c.sort_order, s.date_from
    ");
    return $stmt->fetchAll();
}

/**
 * Получить отгрузку по ID
 */
function getShipment($id) {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM shipments WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

/**
 * Добавить город
 */
function addCity($name, $icon = '🚚', $isSpecial = false, $sortOrder = 0) {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO cities (name, icon, is_special, sort_order) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $icon, $isSpecial ? 1 : 0, $sortOrder]);
}

/**
 * Добавить отгрузку
 */
function addShipment($cityId, $dateFrom, $dateTo) {
    $pdo = getDB();
    $stmt = $pdo->prepare("INSERT INTO shipments (city_id, date_from, date_to) VALUES (?, ?, ?)");
    return $stmt->execute([$cityId, $dateFrom, $dateTo]);
}

/**
 * Обновить отгрузку
 */
function updateShipment($id, $cityId, $dateFrom, $dateTo) {
    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE shipments SET city_id = ?, date_from = ?, date_to = ? WHERE id = ?");
    return $stmt->execute([$cityId, $dateFrom, $dateTo, $id]);
}

/**
 * Удалить отгрузку
 */
function deleteShipment($id) {
    $pdo = getDB();
    $stmt = $pdo->prepare("DELETE FROM shipments WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Удалить прошедшие отгрузки
 */
function cleanupOldShipments() {
    $pdo = getDB();
    $stmt = $pdo->prepare("DELETE FROM shipments WHERE date_to < CURDATE()");
    return $stmt->execute();
}

/**
 * Обновить город
 */
function updateCity($id, $name, $icon, $isSpecial, $sortOrder) {
    $pdo = getDB();
    $stmt = $pdo->prepare("UPDATE cities SET name = ?, icon = ?, is_special = ?, sort_order = ? WHERE id = ?");
    return $stmt->execute([$name, $icon, $isSpecial ? 1 : 0, $sortOrder, $id]);
}

/**
 * Удалить город
 */
function deleteCity($id) {
    $pdo = getDB();
    // Сначала удаляем все отгрузки города
    $stmt = $pdo->prepare("DELETE FROM shipments WHERE city_id = ?");
    $stmt->execute([$id]);
    // Потом сам город
    $stmt = $pdo->prepare("DELETE FROM cities WHERE id = ?");
    return $stmt->execute([$id]);
}

/**
 * Форматирование даты
 */
function formatDate($date) {
    return date('d.m.Y', strtotime($date));
}

/**
 * Проверка, является ли дата в следующем году
 */
function isNextYear($date) {
    return date('Y', strtotime($date)) > date('Y');
}

/**
 * Экранирование HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
