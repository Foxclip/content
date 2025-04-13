<?php

require_once 'config.php';

function validate_username(string $username): string {
    if (strlen($username) < \Config\min_username_length || strlen($username) > \Config\max_username_length) {
        return 'Логин должен содержать от ' . \Config\min_username_length . ' до ' . \Config\max_username_length . ' символов';
    }
    if (!preg_match('/^\w+$/', $username)) {
        return 'Логин должен состоять только из букв, цифр и нижнего подчеркивания';
    }
    return '';
}

function validate_email(string $email): string {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Некорректный email';
    }
    return '';
}

function validate_password(string $password): string {
    if (strlen($password) < \Config\min_password_length || strlen($password) > \Config\max_password_length) {
        return 'Пароль должен содержать от ' . \Config\min_password_length . ' до ' . \Config\max_password_length . ' символов';
    }
    return '';
}

?>
