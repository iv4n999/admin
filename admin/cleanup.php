<?php
require_once '../includes/functions.php';
requireAuth();

cleanupOldShipments();
header('Location: index.php?success=Прошедшие даты удалены');
exit;
