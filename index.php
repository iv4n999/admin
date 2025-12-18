<?php
/**
 * Главная страница - График отгрузок для клиентов
 */

require_once 'includes/functions.php';

// Автоматически удаляем прошедшие даты
cleanupOldShipments();

// Получаем все города
$cities = getCities();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(SITE_NAME) ?> • 2025–2026</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <!-- Боке эффект -->
    <div class="bokeh">
        <div class="bokeh-light"></div>
        <div class="bokeh-light"></div>
        <div class="bokeh-light"></div>
        <div class="bokeh-light"></div>
        <div class="bokeh-light"></div>
        <div class="bokeh-light"></div>
    </div>
    
    <!-- Снежинки -->
    <div class="snow-container">
        <?php for ($i = 0; $i < 10; $i++): ?>
            <div class="snowflake"><?= ['❄', '❅', '❆'][$i % 3] ?></div>
        <?php endfor; ?>
    </div>
    
    <!-- Основной контент -->
    <div class="container">
        <div class="header">
            <span class="header-icon">📦</span>
            <h1>График отгрузок • <span class="year">2025–2026</span></h1>
            <p>Актуальное расписание на праздничный период</p>
            <div class="header-stars">✦ ✧ ★ ✧ ✦</div>
        </div>
        
        <div class="schedule-grid">
            <?php foreach ($cities as $city): ?>
                <?php $shipments = getShipments($city['id']); ?>
                <?php if (!empty($shipments)): ?>
                <div class="city-block">
                    <div class="city-name <?= $city['is_special'] ? 'special' : '' ?>">
                        <span class="icon"><?= e($city['icon']) ?></span>
                        <?= e($city['name']) ?>
                    </div>
                    <table class="dates-table">
                        <?php foreach ($shipments as $shipment): ?>
                        <tr>
                            <td>
                                <span class="date-from"><?= formatDate($shipment['date_from']) ?></span>
                                <span class="arrow">→</span>
                                <span class="date-to <?= isNextYear($shipment['date_to']) ? 'date-2026' : '' ?>">
                                    <?= formatDate($shipment['date_to']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        
        <?php if (empty($cities) || array_sum(array_map(function($c) { return count(getShipments($c['id'])); }, $cities)) === 0): ?>
        <div class="no-data">
            <p>📭 На данный момент нет запланированных отгрузок</p>
        </div>
        <?php endif; ?>
        
        <div class="footer">
            <div class="footer-icons">🎄✨🎅✨🎄</div>
            <div class="footer-text">С наступающим Новым 2026 годом!</div>
            <div class="footer-subtext">Желаем успешных поставок и процветания вашему бизнесу ❄️</div>
        </div>
    </div>
    
    <div class="last-update">
        Обновлено: <?= date('d.m.Y H:i') ?>
    </div>
</body>
</html>
