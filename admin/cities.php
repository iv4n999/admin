<?php
/**
 * Управление городами
 */

require_once '../includes/functions.php';
requireAuth();

$cities = getCities();
$error = '';
$success = $_GET['success'] ?? '';

// Добавление города
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_city'])) {
    $name = trim($_POST['name'] ?? '');
    $icon = trim($_POST['icon'] ?? '🚚');
    $isSpecial = isset($_POST['is_special']);
    
    if ($name) {
        addCity($name, $icon, $isSpecial, count($cities) + 1);
        header('Location: cities.php?success=Город добавлен');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление городами</title>
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-header">
                <span class="logo">📦</span>
                <h2>Админ-панель</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="index.php" class="nav-item">
                    <span class="nav-icon">📊</span> Отгрузки
                </a>
                <a href="cities.php" class="nav-item active">
                    <span class="nav-icon">🏙️</span> Города
                </a>
                <a href="../" target="_blank" class="nav-item">
                    <span class="nav-icon">🌐</span> Открыть сайт
                </a>
            </nav>
            <div class="sidebar-footer">
                <a href="logout.php" class="nav-item logout">
                    <span class="nav-icon">🚪</span> Выйти
                </a>
            </div>
        </aside>
        
        <main class="main-content">
            <header class="content-header">
                <h1>🏙️ Управление городами</h1>
            </header>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>
            
            <!-- Форма добавления -->
            <div class="card">
                <div class="card-header">
                    <h2>Добавить город</h2>
                </div>
                <form method="POST" class="form form-inline">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Название города" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="icon" placeholder="Иконка" value="🚚" style="width: 80px;">
                    </div>
                    <div class="form-group checkbox">
                        <label>
                            <input type="checkbox" name="is_special">
                            Особый (красный)
                        </label>
                    </div>
                    <button type="submit" name="add_city" class="btn btn-primary">➕ Добавить</button>
                </form>
            </div>
            
            <!-- Список городов -->
            <div class="card">
                <div class="card-header">
                    <h2>Список городов</h2>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Иконка</th>
                                <th>Название</th>
                                <th>Тип</th>
                                <th>Отгрузок</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cities as $city): ?>
                                <?php $shipmentCount = count(getShipments($city['id'], true)); ?>
                                <tr>
                                    <td style="font-size: 24px;"><?= e($city['icon']) ?></td>
                                    <td><strong><?= e($city['name']) ?></strong></td>
                                    <td>
                                        <?php if ($city['is_special']): ?>
                                            <span class="status status-special">Особый</span>
                                        <?php else: ?>
                                            <span class="status status-normal">Обычный</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $shipmentCount ?></td>
                                    <td class="actions">
                                        <a href="delete-city.php?id=<?= $city['id'] ?>" 
                                           class="btn btn-sm btn-delete"
                                           onclick="return confirm('Удалить город и все его отгрузки?')">
                                            🗑️
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
