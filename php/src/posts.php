<?php

require_once "../pdo.php";

function get_recent_posts() {
    $result = execute_sql_query('get_recent_posts.sql', [
        'userId' => get_user_id()
    ]);
    return $result;
}

function get_user_posts(int|null $id = null): array {
    if (!$id) {
        $id = get_user_id();
    }
    $userId = $id;
    $result = execute_sql_query('get_user_posts.sql', [
        'currentUserId' => get_user_id(),
        'queryUserId' => $userId
    ]);
    return $result;
}

?>
