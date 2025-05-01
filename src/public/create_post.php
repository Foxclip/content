<?php

    require_once('../php/session.php');
    require_once('../php/utils.php');

    if (!is_logged_in()) {
        redirect_to_login_page(true);
        exit();
    }
    
?>

<!DOCTYPE html>
<html>
<?php
    includeFile('../php/ui/head.php', [
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
    <?= includeFile('../php/ui/header.php'); ?>
    <main>
        <div id="createPostContainer">
            <h1 id="createPostTitle">Создать пост</h1>
            <form id="createPostForm" action="/do_create_post" method="post">
                <input id="titleInput" type="text" name="title" placeholder="Заголовок" required />
                <textarea id="contentInput" name="content" placeholder="Текст" required></textarea>
                <?php includeFile('../php/ui/submit_button.php', ['text' => 'Отправить']); ?>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            </form>
        </div>
    </main>
    <?= includeFile('../php/ui/footer.php'); ?>
    <script src="js/create_post.js" type="module"></script>
</body>
</html>
