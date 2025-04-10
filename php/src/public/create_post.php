<?php
    require_once('../session.php');
    require_once('../utils.php');
    if (!is_logged_in()) {
        redirect_to_login_page(true);
        exit();
    }
?>

<!DOCTYPE html>
<html>
<?php
    includeFile('../head.php', [
        'title' => 'Создать пост',
        'styles' => [
            'common.css',
            'header.css',
            'footer.css',
            'create_post.css'
        ]
    ]);
?>
<body>
    <?= includeFile('../header.php'); ?>
    <div id="createPostContainer">
        <h1 id="createPostTitle">Создать пост</h1>
        <form id="createPostForm" action="/do_create_post" method="post">
            <input id="titleInput" class="formInput" type="text" name="title" placeholder="Заголовок" required />
            <input id="contentInput" class="formInput" type="text" name="content" placeholder="Текст" required />
            <button id="createPostButton" type="submit">Отправить</button>
        </form>
    </div>
    <?= includeFile('../footer.php'); ?>
    <script src="js/create_post.js" type="module"></script>
</body>
</html>
