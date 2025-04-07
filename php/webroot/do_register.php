<?php

function checkPostVariables(array $names): void {
    foreach ($names as $name) {
        if (!isset($_POST[$name])) {
            echo "Переменная $name не установлена";
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkPostVariables(['login', 'email', 'password']);
} else {
    header('Location: /register');
    exit();
}

const host = 'mysql';
const user = 'root';
const password = 'root';
const dbname = 'content';

$dsn = "mysql:dbname=".dbname.";host=".host."";
try {
    $pdo = new PDO($dsn, user, password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

// проверяем существует ли пользователь с таким логином
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
