<?php

require_once('../session.php');

if (!is_logged_in()) {
    http_response_code(403);
    exit();
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$pdo = pdo_connect_mysql();
if ($data['action'] == 'like') {
    $stmt = $pdo->prepare('INSERT INTO likes (post_id, user_id)
        VALUES (:post_id, :user_id)
        ON DUPLICATE KEY UPDATE created_at = NOW()'
    );
    $stmt->execute([
        'post_id' => $data['postId'],
        'user_id' => get_user()['id'],
    ]);
} else {
    $stmt = $pdo->prepare('DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id');
    $stmt->execute([
        'post_id' => $data['postId'],
        'user_id' => get_user()['id'],
    ]);
}

$stmt = $pdo->prepare('SELECT COUNT(id) AS like_count FROM likes WHERE post_id = :post_id');
$stmt->execute([
    'post_id' => $data['postId'],
]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
echo json_encode($result);

?>
