<?php

ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');

if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
    exit();
}

require_once('../php/config.php');
require_once('../php/session.php');
require_once('../php/utils.php');
require_once('../php/validation.php');

header('Content-Type: application/json');

if (!check_csrf_token()) {
    echo json_encode(['success' => false, 'message' => 'CSRF токен недействителен']);
    exit();
}

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'User is not logged in']);
    exit();
}

try {

    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    if (empty($data)) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
        exit();
    }
    $variableErr = checkVariables($data, ['old_password', 'new_password']);
    if (!empty($variableErr)) {
        echo json_encode(['success' => false, 'message' => $variableErr]);
        exit();
    }
    $old_password = $data['old_password'];
    $new_password = $data['new_password'];

    if (!check_password(get_user(), $old_password)) {
        echo json_encode(['success' => false, 'message' => 'Старый пароль неверен']);
        exit();
    }

    if (check_password(get_user(), $new_password)) {
        echo json_encode(['success' => false, 'message' => 'Новый пароль совпадает со старым']);
        exit();
    }

    $validationErr = validate_password($new_password);
    if (!empty($validationErr)) {
        echo json_encode(['success' => false, 'message' => $validationErr]);
        exit();
    }

    execute_sql_query('UPDATE users SET password = :password WHERE id = :id', [
        'password' => password_hash($new_password, PASSWORD_DEFAULT),
        'id' => get_user_id(),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit();
}

echo json_encode(['success' => true]);

?>
