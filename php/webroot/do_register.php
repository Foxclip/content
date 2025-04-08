<?php

require_once "pdo.php";
require_once "utils.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkPostVariables(['login', 'email', 'password']);
} else {
    header('Location: /register');
    exit();
}

// проверяем существует ли пользователь с таким логином
$pdo = pdo_connect_mysql();
$stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
$stmt->execute(['username' => $_POST['login']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    echo 'Пользователь ' . $_POST['login'] . ' уже существует';
    exit();
}

// проверяем существует ли пользователь с таким email
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
$stmt->execute(['email' => $_POST['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user) {
    echo 'Email ' . $_POST['email'] . ' уже зарегистрирован';
    exit();
}

// добавляем пользователя в базу данных
$stmt = $pdo->prepare('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)');
$stmt->execute([
    'username' => $_POST['login'],
    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    'email' => $_POST['email'],
]);
echo 'Пользователь ' . $_POST['login'] . ' успешно зарегистрирован';

?>
