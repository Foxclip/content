<?php

ini_set('display_errors', 'Off');
ini_set('display_startup_errors', 'Off');

if (!$_SERVER['REQUEST_METHOD'] === 'POST') {
    exit();
}

require_once('../session.php');
require_once('../utils.php');

header('Content-Type: application/json');

if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'User is not logged in']);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);
if (empty($data)) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit();
}
$variableErr = checkVariables($data, ['action', 'postId']);
if (!empty($variableErr)) {
    echo json_encode(['success' => false, 'message' => $variableErr]);
    exit();
}

if ($data['action'] == 'like') {
    execute_sql_query(
        'INSERT INTO likes (post_id, user_id)
        VALUES (:post_id, :user_id)
        ON DUPLICATE KEY UPDATE created_at = NOW()',
        [
            'post_id' => $data['postId'],
            'user_id' => get_user_id(),
        ]);
} else {
    execute_sql_query(
        'DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id',
        [
            'post_id' => $data['postId'],
            'user_id' => get_user_id(),
        ]
    );
}

$result = execute_sql_query(
    'SELECT COUNT(id) AS like_count FROM likes WHERE post_id = :post_id',
    [
        'post_id' => $data['postId']
    ]
);
$row = $result[0];
echo json_encode(['success' => true, 'like_count' => $row['like_count']]);

?>
