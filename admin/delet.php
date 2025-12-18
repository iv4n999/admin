<?php
/**
 * Удаление отгрузки
 */

require_once '../includes/functions.php';
requireAuth();

$id = (int)($_GET['id'] ?? 0);

if ($id && deleteShipment($id)) {
    header('Location: index.php?success=Отгрузка удалена');
} else {
    header('Location: index.php?error=Ошибка при удалении');
}
exit;
