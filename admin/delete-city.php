<?php
require_once '../includes/functions.php';
requireAuth();

$id = (int)($_GET['id'] ?? 0);

if ($id && deleteCity($id)) {
    header('Location: cities.php?success=Город удалён');
} else {
    header('Location: cities.php?error=Ошибка при удалении');
}
exit;
