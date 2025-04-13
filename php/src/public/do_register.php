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

$result = execute_sql_query('SELECT * FROM users WHERE username = :username', [
    'username' => $login,
]);
if ($result) {
    echo 'Пользователь ' . $login . ' уже существует';
    exit();
}

$result = execute_sql_query('SELECT * FROM users WHERE email = :email', [
    'email' => $email,
]);
if ($result) {
    echo 'Email ' . $email . ' уже зарегистрирован';
    exit();
}

execute_sql_query('INSERT INTO users (username, password, email) VALUES (:username, :password, :email)', [
    'username' => $login,
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'email' => $email,
]);
echo 'Пользователь ' . $login . ' успешно зарегистрирован';

?>
