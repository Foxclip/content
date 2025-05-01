<?php

require_once __DIR__ . "/db.php";

function get_recent_posts(int $offset, int $count) {
    $result = execute_sql_script('get_recent_posts.sql',
        [
            'currentUserId' => get_user_id(),
            'offset' => $offset,
            'count' => $count
        ],
        QueryParameterType::Integer
    );
    return $result;
}

function get_user_posts(int|null $id, int $offset, int $count): array {
    $userId = $id;
    $result = execute_sql_script('get_user_posts.sql',
        [
            'currentUserId' => get_user_id(),
            'queryUserId' => $userId,
            'offset' => $offset,
            'count' => $count
        ],
        QueryParameterType::Integer
    );
    return $result;
}

function get_all_post_count(): int {
    $rows = execute_sql_query('SELECT COUNT(*) AS count FROM posts');
    $result = $rows[0]['count'];
    return $result;
}

function get_user_post_count(int|null $id = null): int {
    if (!$id) {
        $id = get_user_id();
    }
    $userId = $id;
    $rows = execute_sql_query('SELECT COUNT(*) AS count FROM posts WHERE user_id = :userId', [
        'userId' => $userId
    ]);
    $result = $rows[0]['count'];
    return $result;
}

?>
