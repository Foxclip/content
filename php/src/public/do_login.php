<?php

require_once "../session.php";
require_once "../db.php";
require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $variableErr = checkVariables($_POST, ['login', 'password']);
    if (!empty($variableErr)) {
        echo $variableErr;
        exit();
    }
    $login = trim($_POST['login']);
    $password = $_POST['password'];
} else {
    exit();
}

// проверяем существует ли пользователь с таким логином
$result = execute_sql_query('SELECT * FROM users WHERE username = :username', [
    'username' => $login,
]);
$user = $result[0];
if (!$user) {
    echo 'Пользователь ' . $login . ' не зарегистрирован';
    exit();
}

// проверяем пароль
if (password_verify($password, $user['password'])) {
    if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        execute_sql_query('UPDATE users SET password = :password WHERE username = :username', [
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
