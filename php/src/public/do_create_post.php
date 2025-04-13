<?php

require_once('../session.php');
require_once('../utils.php');
if (!is_logged_in()) {
    redirect_to_login_page(true);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkPostVariables(['title', 'content']);
    $title = trim($_POST['title']);
    $content = $_POST['content'];
} else {
    exit();
}

execute_sql_query('INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)', [
    'title' => $title,
    'content' => $content,
    'user_id' => get_user()['id'],
]);

header('Location: /');

?>
