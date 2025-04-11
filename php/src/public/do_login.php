<?php

require_once "../session.php";
require_once "../pdo.php";
require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkPostVariables(['login', 'password']);
    $login = trim($_POST['login']);
    $password = $_POST['password'];
} else {
    exit();
}

// проверяем существует ли пользователь с таким логином
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
$stmt->execute(['username' => $login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo 'Пользователь ' . $login . ' не зарегистрирован';
    exit();
}

// проверяем пароль
if (password_verify($password, $user['password'])) {
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE username = :username');
        $stmt->execute([
            'username' => $login,
            'password' => $newHash,
        ]);
    }
    login($user);
    if (isset($_SESSION['login_redirect'])) {
        header('Location: ' . $_SESSION['login_redirect']);
        unset($_SESSION['login_redirect']);
    } else {
        header('Location: /');
    }
    echo 'Пользователь ' . $login . ' успешно авторизован';
} else {
    echo 'Неправильный пароль';
    exit();
}

?>
