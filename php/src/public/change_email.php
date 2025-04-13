<?php

ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');

if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
    exit();
}

require_once('../config.php');
require_once('../session.php');
require_once('../utils.php');
require_once('../validation.php');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'User is not logged in']);
    exit();
}

try {

    $email = file_get_contents('php://input');

    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'email is not set']);
        exit();
    }

    $validationErr = validate_email($email);
    if (!empty($validationErr)) {
        echo json_encode(['success' => false, 'message' => $validationErr]);
        exit();
    }

    execute_sql_query('UPDATE users SET email = :email WHERE id = :id', [
        'email' => $email,
        'id' => get_user_id(),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit();
}

echo json_encode(['success' => true]);

?>
