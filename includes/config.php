<?php
/**
 * Конфигурация сайта
 * ВАЖНО: Замените данные на свои от SpaceWeb!
 */

// Настройки базы данных (возьмите из панели SpaceWeb)
define('DB_HOST', 'localhost');
define('DB_NAME', 'ваша_база');      // Например: u1234567_shipping
define('DB_USER', 'ff77mskru'); // Например: u1234567_admin
define('DB_PASS', '5NjSYC@2JWJ8NXMG');

// Настройки админа
define('ADMIN_LOGIN', 'admin');
define('ADMIN_PASSWORD', 'ваш_сложный_пароль_123'); // ОБЯЗАТЕЛЬНО ИЗМЕНИТЕ!

// Настройки сайта
define('SITE_NAME', 'График отгрузок');
define('TIMEZONE', 'Europe/Moscow');

// Установка часового пояса
date_default_timezone_set(TIMEZONE);

// Запуск сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
