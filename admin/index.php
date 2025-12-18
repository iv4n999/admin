<?php
/**
 * Главная страница админ-панели
 */

require_once '../includes/functions.php';
requireAuth();

// Получаем данные
$cities = getCities();
$shipments = getAllShipments();

// Сообщения
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель • <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Сайдбар -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <span class="logo">📦</span>
                <h2>Админ-панель</h2>
            </div>
            
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item active">
                    <span class="nav-icon">📊</span>
                    Отгрузки
                </a>
                <a href="cities.php" class="nav-item">
                    <span class="nav-icon">🏙️</span>
                    Города
                </a>
                <a href="../" target="_blank" class="nav-item">
                    <span class="nav-icon">🌐</span>
                    Открыть сайт
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <a href="logout.php" class="nav-item logout">
                    <span class="nav-icon">🚪</span>
                    Выйти
                </a>
            </div>
        </aside>
        
        <!-- Основной контент -->
        <main class="main-content">
            <header class="content-header">
                <h1>📋 Управление отгрузками</h1>
                <a href="add.php" class="btn btn-primary">
                    <span>➕</span> Добавить отгрузку
                </a>
            </header>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            
            <!-- Статистика -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-icon">🏙️</span>
                    <div class="stat-info">
                        <span class="stat-value"><?= count($cities) ?></span>
                        <span class="stat-label">Городов</span>
                    </div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📦</span>
                    <div class="stat-info">
                        <span class="stat-value"><?= count($shipments) ?></span>
                        <span class="stat-label">Отгрузок</span>
                    </div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📅</span>
                    <div class="stat-info">
                        <span class="stat-value"><?= date('d.m.Y') ?></span>
                        <span class="stat-label">Сегодня</span>
                    </div>
                </div>
            </div>
            
            <!-- Таблица отгрузок -->
            <div class="card">
                <div class="card-header">
                    <h2>Все отгрузки</h2>
                    <div class="card-actions">
                        <a href="cleanup.php" class="btn btn-sm btn-secondary" 
                           onclick="return confirm('Удалить все прошедшие даты?')">
                            🧹 Очистить старые
                        </a>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Город</th>
                                <th>Дата отгрузки</th>
                                <th>Дата доставки</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($shipments)): ?>
                                <tr>
                                    <td colspan="5" class="empty-row">
                                        Нет данных. <a href="add.php">Добавьте первую отгрузку</a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($shipments as $shipment): ?>
                                    <?php 
                                        $isPast = strtotime($shipment['date_to']) < strtotime('today');
                                        $isToday = date('Y-m-d') === $shipment['date_from'];
                                    ?>
                                    <tr class="<?= $isPast ? 'row-past' : '' ?> <?= $isToday ? 'row-today' : '' ?>">
                                        <td>
                                            <span class="city-badge">
                                                <?= e($shipment['city_icon']) ?>
                                                <?= e($shipment['city_name']) ?>
                                            </span>
                                        </td>
                                        <td><?= formatDate($shipment['date_from']) ?></td>
                                        <td class="<?= isNextYear($shipment['date_to']) ? 'text-danger' : '' ?>">
                                            <?= formatDate($shipment['date_to']) ?>
                                        </td>
                                        <td>
                                            <?php if ($isPast): ?>
                                                <span class="status status-past">Завершено</span>
                                            <?php elseif ($isToday): ?>
                                                <span class="status status-today">Сегодня!</span>
                                            <?php else: ?>
                                                <span class="status status-upcoming">Предстоит</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="actions">
                                            <a href="edit.php?id=<?= $shipment['id'] ?>" class="btn btn-sm btn-edit" title="Редактировать">✏️</a>
                                            <a href="delete.php?id=<?= $shipment['id'] ?>" class="btn btn-sm btn-delete" 
                                               onclick="return confirm('Удалить эту отгрузку?')" title="Удалить">🗑️</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
