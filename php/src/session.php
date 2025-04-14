<?php

require_once "config.php";
require_once "db.php";

function login(array $user): void {
    session_regenerate_id(true);
    execute_sql_query('INSERT INTO sessions (token, user_id) VALUES (:token, :user_id)', [
        'token' => session_id(),
        'user_id' => $user['id'],
    ]);
}

function logout(): void {
    if (!is_logged_in()) {
        return;
    }
    execute_sql_query('UPDATE sessions SET logout = TRUE WHERE token = :token', [
        'token' => session_id(),
    ]);
}

function is_logged_in(): bool {

    static $logged_in = null;
    if ($logged_in !== null) {
        return $logged_in;
    }

    function check_logged_in(): bool {

        $sessionInfo = get_session_info();
        if (!$sessionInfo) {
            return false;
        }

        if ($sessionInfo['logout']) {
            return false;
        }

        $last_activity = new DateTime($sessionInfo['last_activity']);
        $now = new DateTime();
        if ($now->getTimestamp() - $last_activity->getTimestamp() > \Config\logout_after) {
            return false;
        }

        return true;

    }

    $logged_in = check_logged_in();
    return $logged_in;

}

function get_session_info(): ?array {

    static $sessionInfo = null;
    if ($sessionInfo !== null) {
        return $sessionInfo;
    }

    $result = execute_sql_query('SELECT * FROM sessions WHERE token = :token AND logout = FALSE', [
        'token' => session_id(),
    ]);
    if (!$result) {
        return null;
    }
    $sessionInfo = $result[0];
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

    $result = execute_sql_query('SELECT * FROM users WHERE id = :id', [
        'id' => $sessionInfo['user_id'],
    ]);
    if (!$result) {
        return null;
    }
    $user = $result[0];
    return $user;

}

function get_user_id(): ?int {
    $user = get_user();
    if (!$user) {
        return 0;
    }
    return $user['id'];
}

function get_user_avatar_url($id = null): string {
    $userId = get_user_id();
    if ($id) {
        $userId = $id;
    }
    if (!file_exists("avatars/$userId.png")) {
        return '/avatars/default.png';
    }
    return "/avatars/$userId.png";
}

function update_last_activity(): void {
    execute_sql_query('UPDATE sessions SET last_activity = :last_activity WHERE token = :token', [
        'last_activity' => date('Y-m-d H:i:s'),
        'token' => session_id(),
    ]);
}

function redirect_to_login_page($redirect_back_after_login = false): void {
    if ($redirect_back_after_login) {
        $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
    }
    header('Location: /login');
}

function set_csrf_token(): void {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function check_csrf_token(): bool {
    return (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']);
}

session_start();

if (is_logged_in()) {
    update_last_activity();
}

if (isset($_SESSION['login_redirect']) && !in_array($_SERVER['REQUEST_URI'], ['/login', '/do_login'])) {
    unset($_SESSION['login_redirect']);
}

?>
