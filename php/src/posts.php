<?php

require_once "../pdo.php";

function get_recent_posts() {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare('SELECT posts.id, users.username, posts.title, posts.content, posts.created_at
        FROM posts
        JOIN users ON posts.user_id = users.id
        ORDER BY created_at DESC
        LIMIT 10
    ');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
