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
    includeFile('../ui/head.php', [
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
    <?= includeFile('../ui/header.php'); ?>
    <main>
        <div id="createPostContainer">
            <h1 id="createPostTitle">Создать пост</h1>
            <form id="createPostForm" action="/do_create_post" method="post">
                <input id="titleInput" type="text" name="title" placeholder="Заголовок" required />
                <textarea id="contentInput" name="content" placeholder="Текст" required></textarea>
                <?php includeFile('../ui/submit_button.php', ['text' => 'Отправить']); ?>
            </form>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/create_post.js" type="module"></script>
</body>
</html>
