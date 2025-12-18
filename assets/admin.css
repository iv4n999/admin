<?php
/**
 * Страница входа в админку
 */

require_once '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($login === ADMIN_LOGIN && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}

// Если уже авторизован
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель</title>
    <link rel="stylesheet" href="../assets/admin.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-header">
            <span class="login-icon">🔐</span>
            <h1>Админ-панель</h1>
            <p>График отгрузок</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="login">Логин</label>
                <input type="text" id="login" name="login" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                Войти
            </button>
        </form>
        
        <div class="login-footer">
            <a href="../">← Вернуться на сайт</a>
        </div>
    </div>
</body>
</html>
