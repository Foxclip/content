<?php

require_once "../pdo.php";

function get_recent_posts() {
    $pdo = pdo_connect_mysql();
    $sql = file_get_contents(__DIR__ . '/sql/get_recent_posts.sql');
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'userId' => get_user_id()
    ]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

?>
