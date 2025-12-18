<?php
/**
 * Добавление новой отгрузки
 */

require_once '../includes/functions.php';
requireAuth();

$cities = getCities();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cityId = (int)$_POST['city_id'];
    $dateFrom = $_POST['date_from'];
    $dateTo = $_POST['date_to'];
    
    if (!$cityId || !$dateFrom || !$dateTo) {
        $error = 'Заполните все поля';
    } elseif (strtotime($dateTo) < strtotime($dateFrom)) {
        $error = 'Дата доставки не может быть раньше даты отгрузки';
    } else {
        if (addShipment($cityId, $dateFrom, $dateTo)) {
            header('Location: index.php?success=Отгрузка добавлена');
            exit;
        } else {
            $error = 'Ошибка при добавлении';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить отгрузку</title>
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
                <a href="index.php" class="nav-item active">
                    <span class="nav-icon">📊</span> Отгрузки
                </a>
                <a href="cities.php" class="nav-item">
                    <span class="nav-icon">🏙️</span> Города
                </a>
            </nav>
        </aside>
        
        <main class="main-content">
            <header class="content-header">
                <h1>➕ Добавить отгрузку</h1>
                <a href="index.php" class="btn btn-secondary">← Назад</a>
            </header>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            
            <div class="card">
                <form method="POST" class="form">
                    <div class="form-group">
                        <label for="city_id">Город *</label>
                        <select name="city_id" id="city_id" required>
                            <option value="">Выберите город</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= $city['id'] ?>">
                                    <?= e($city['icon']) ?> <?= e($city['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_from">Дата отгрузки *</label>
                            <input type="date" name="date_from" id="date_from" required 
                                   value="<?= date('Y-m-d') ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="date_to">Дата доставки *</label>
                            <input type="date" name="date_to" id="date_to" required
                                   value="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            ✅ Добавить отгрузку
                        </button>
                        <a href="index.php" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
