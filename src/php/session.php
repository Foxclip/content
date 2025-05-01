<?php

require_once "config.php";
require_once "db.php";

class CurrentUser {
    public static $user = null;
    public static $logged_in = null;
    public static $sessionInfo = null;
}

function login(array $user): void {
    session_regenerate_id(true);
    execute_sql_query('INSERT INTO sessions (token, user_id) VALUES (:token, :user_id)', [
        'token' => session_id(),
        'user_id' => $user['id'],
    ]);

    CurrentUser::$user = get_user();
    CurrentUser::$sessionInfo = get_session_info();
    CurrentUser::$logged_in = true;

    generate_csrf_token();
}

function logout(): void {
    if (!is_logged_in()) {
        return;
    }
    execute_sql_query('UPDATE sessions SET logout = TRUE WHERE token = :token', [
        'token' => session_id(),
    ]);
}

function check_logged_in(): bool {

    CurrentUser::$sessionInfo = get_session_info();
    if (!CurrentUser::$sessionInfo) {
        return false;
    }

    if (CurrentUser::$sessionInfo['logout']) {
        return false;
    }

    $last_activity = new DateTime(CurrentUser::$sessionInfo['last_activity']);
    $now = new DateTime();
    if ($now->getTimestamp() - $last_activity->getTimestamp() > \Config\logout_after) {
        return false;
    }

    return true;

}

function is_logged_in(): bool {

    if (CurrentUser::$logged_in !== null) {
        return CurrentUser::$logged_in;
    }

    $logged_in = check_logged_in();
    return $logged_in;

}

function get_session_info(): ?array {

    if (CurrentUser::$sessionInfo !== null) {
        return CurrentUser::$sessionInfo;
    }

    $result = execute_sql_query('SELECT * FROM sessions WHERE token = :token AND logout = FALSE', [
        'token' => session_id(),
    ]);
    if (!$result) {
        return null;
    }
    CurrentUser::$sessionInfo = $result[0];
    return CurrentUser::$sessionInfo;
}

function get_user_by_id(int $id): ?array {
    
    static $cache = [];
    if (isset($cache[$id])) {
        return $cache[$id];
    }

    $result = execute_sql_query('SELECT * FROM users WHERE id = :id', [
        'id' => $id,
    ]);
    if (!$result) {
        return null;
    }
    $user = $result[0];
    $cache[$id] = $user;

    return $user;

}

function get_user_by_name(string $name): ?array {

    static $cache = [];
    if (isset($cache[$name])) {
        return $cache[$name];
    }

    $result = execute_sql_query('SELECT * FROM users WHERE username = :username', [
        'username' => $name,
    ]);
    if (!$result) {
        return null;
    }

    $user = $result[0];

    return $user;
}

function get_user(): ?array {

    if (CurrentUser::$user) {
        return CurrentUser::$user;
    }

    if (!is_logged_in()) {
        return null;
    }
    $sessionInfo = get_session_info();
    $id = $sessionInfo['user_id'];

    CurrentUser::$user = get_user_by_id($id);

    return CurrentUser::$user;

}

function get_user_id(): int {
    $user = get_user();
    if (!$user) {
        return 0;
    }
    return $user['id'];
}

function get_user_avatar_url(?int $id = null): string {
    $userId = get_user_id();
    if ($id) {
        $userId = $id;
    }
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . "/avatars/$userId.png")) {
        return "/avatars/default.png";
    }
    return "/avatars/$userId.png";
}

function update_last_activity(): void {
    execute_sql_query('UPDATE sessions SET last_activity = :last_activity WHERE token = :token', [
        'last_activity' => date('Y-m-d H:i:s'),
        'token' => session_id(),
    ]);
}

function redirect_to_login_page(bool $redirect_back_after_login = false): void {
    if ($redirect_back_after_login) {
        $_SESSION['login_redirect'] = $_SERVER['REQUEST_URI'];
    }
    header('Location: /login');
}

function get_csrf_token_from_db(): ?string {
    $result = execute_sql_query(
        'SELECT token
        FROM csrf_tokens
        WHERE user_id = :user_id
        ORDER BY created_at DESC', 
        [
        'user_id' => get_user_id(),
        ]
    );
    if (!$result) {
        return null;
    }
    return $result[0]['token'];
}

function try_restore_csrf_token(): void {
    $token = get_csrf_token_from_db();
    if ($token) {
        $_SESSION['csrf_token'] = $token;
    }
}

function get_csrf_token(): ?string {
    if (isset($_SESSION['csrf_token'])) {
        return $_SESSION['csrf_token'];
    }
    try_restore_csrf_token();
    return $_SESSION['csrf_token'] ?? null;
}

function generate_csrf_token(): void {
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;
    execute_sql_query('INSERT INTO csrf_tokens (token, user_id) VALUES (:token, :user_id)', [
        'token' => $token,
        'user_id' => get_user_id(),
    ]);
}

function check_csrf_token(): bool {
    $csrf_token = get_csrf_token();
    return (
        isset($_POST['csrf_token']) && $_POST['csrf_token'] === $csrf_token
        || isset($_SERVER['HTTP_X_CSRF_TOKEN']) && $_SERVER['HTTP_X_CSRF_TOKEN'] === $csrf_token
    );
}

function check_password(array $user, string $password): bool {
    if (password_verify($password, $user['password'])) {
        if (password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            execute_sql_query('UPDATE users SET password = :password WHERE username = :username', [
                'username' => $user['username'],
                'password' => $newHash,
            ]);
        }
        return true;
    } else {
        return false;
    }
}

session_start();

if (is_logged_in()) {
    update_last_activity();
    if (!isset($_SESSION['csrf_token'])) {
        try_restore_csrf_token();
    }
}

if (isset($_SESSION['login_redirect']) && !in_array($_SERVER['REQUEST_URI'], ['/login', '/do_login'])) {
    unset($_SESSION['login_redirect']);
}

?>
