<?php

require_once "pdo.php";

function checkPostVariables(array $names): void {
    foreach ($names as $name) {
        if (!isset($_POST[$name])) {
            echo "Переменная $name не установлена";
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkPostVariables(['login', 'password']);
} else {
    header('Location: /login');
    exit();
}

// проверяем существует ли пользователь с таким логином
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
$stmt->execute(['username' => $_POST['login']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo 'Пользователь ' . $_POST['login'] . ' не зарегистрирован';
    exit();
}

// проверяем пароль
if (password_verify($_POST['password'], $user['password'])) {
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE username = :username');
        $stmt->execute([
            'username' => $_POST['login'],
            'password' => $newHash,
        ]);
        $_SESSION['user_id'] = $user['id'];
    }
    echo 'Пользователь ' . $_POST['login'] . ' успешно авторизован';
} else {
    echo 'Неправильный пароль';
    exit();
}

?>
