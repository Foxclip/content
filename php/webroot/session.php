<?php

require_once "pdo.php";

const logout_after = 3600;

function login(array $user): void {
    session_regenerate_id(true);
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('INSERT INTO sessions (token, user_id) VALUES (:token, :user_id)');
    $stmt->execute([
        'token' => session_id(),
        'user_id' => $user['id'],
    ]);
}

function is_logged_in(): bool {

    static $logged_in = null;
    if ($logged_in !== null) {
        return $logged_in;
    }

    $sessionInfo = get_session_info();
    if (!$sessionInfo) {
        $logged_in = false;
        return false;
    }

    $last_activity = new DateTime($sessionInfo['last_activity']);
    $now = new DateTime();
    if ($now->getTimestamp() - $last_activity->getTimestamp() > logout_after) {
        $logged_in = false;
        return false;
    }

    $logged_in = true;
    return true;

}

function get_session_info(): ?array {

    static $sessionInfo = null;
    if ($sessionInfo !== null) {
        return $sessionInfo;
    }

    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('SELECT * FROM sessions WHERE token = :token');
    $stmt->execute([
        'token' => session_id(),
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }
    $sessionInfo = $row;
    return $sessionInfo;
}

function get_user(): ?array {

    static $user = null;
    if ($user !== null) {
        return $user;
    }

    $sessionInfo = get_session_info();
    if (!$sessionInfo) {
        return null;
    }

    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
    $stmt->execute([
        'id' => $sessionInfo['user_id'],
    ]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }
    $user = $row;
    return $user;

}

function update_last_activity(): void {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('UPDATE sessions SET last_activity = :last_activity WHERE token = :token');
    $stmt->execute([
        'last_activity' => date('Y-m-d H:i:s'),
        'token' => session_id(),
    ]);
}

session_start();
if (is_logged_in()) {
    update_last_activity();
}

?>
