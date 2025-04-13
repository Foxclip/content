<?php

require_once "../db.php";
require_once "../utils.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    checkPostVariables(['login', 'email', 'password']);
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (strlen($login) < 4 || strlen($login) > 20) {
        echo "Логин должен содержать от 4 до 20 символов";
        exit();
    }
    if (!preg_match('/^\S+@\S+$/', $email)) {
        echo "Некорректный email";
        exit();
    }
    if (strlen($password) < 6 || strlen($password) > 20) {
        echo "Пароль должен содержать от 6 до 20 символов";
        exit();
    }

} else {

    exit();

}

// проверяем существует ли пользователь с таким логином
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
$stmt->execute(['username' => $login]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    echo 'Пользователь ' . $login . ' уже существует';
    exit();
}

// проверяем существует ли пользователь с таким email
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    echo 'Email ' . $email . ' уже зарегистрирован';
    exit();
}

// добавляем пользователя в базу данных
$stmt = $pdo->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
$stmt->execute([
    'username' => $login,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'email' => $email,
]);
echo 'Пользователь ' . $login . ' успешно зарегистрирован';

?>
