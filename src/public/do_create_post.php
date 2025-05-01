<?php

require_once('../session.php');
require_once('../utils.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit();
}

if (!check_csrf_token()) {
    echo "CSRF токен недействителен";
    exit();
}

if (!is_logged_in()) {
    redirect_to_login_page(true);
    exit();
}

$variableErr = checkVariables($_POST, ['title', 'content']);
if (!empty($variableErr)) {
    echo $variableErr;
    exit();
}

$title = trim($_POST['title']);
$content = $_POST['content'];

execute_sql_query('INSERT INTO posts (title, content, user_id) VALUES (:title, :content, :user_id)', [
    'title' => $title,
    'content' => $content,
    'user_id' => get_user()['id'],
]);

header('Location: /');

?>
