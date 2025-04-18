<?php

require_once "../db.php";
require_once "../session.php";
require_once "../utils.php";
require_once "../validation.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

if (!check_csrf_token()) {
    echo "CSRF токен недействителен";
    exit();
}

$variableErr = checkVariables($_POST, ['login', 'email', 'password']);
if (!empty($variableErr)) {
    echo $variableErr;
    exit();
}

$login = trim($_POST['login']);
$email = trim($_POST['email']);
$password = $_POST['password'];

$validationErr = validate_username($login);
if (!empty($validationErr)) {
    echo $validationErr;
    exit();
}
$validationErr = validate_email($email);
if (!empty($validationErr)) {
    echo $validationErr;
    exit();
}
$validationErr = validate_password($password);
if (!empty($validationErr)) {
    echo $validationErr;
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
