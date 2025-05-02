<?php
    require_once('../config.php');
    require_once('../session.php');
    require_once('../utils.php');
    require_once("../posts.php");

?>

<!DOCTYPE html>
<html>
<?php
    includeFile('../ui/head.php', [
        'title' => 'Главная',
        'styles' => [
            'common.css',
            'header.css',
            'footer.css',
            'index.css',
            'posts.css',
        ]
    ]);
?>
<body>
    <?= includeFile('../ui/header.php', ['includeSearchBar' => true]); ?>
    <main>
        <?php
        if (!isset($_GET['type'])) {
            writePostPage(
                PageType::RecentPosts,
                fn() => get_all_post_count(),
                fn($offset, $count) => get_recent_posts($offset, $count),
                'Все посты'
            );
        } else if (isset($_GET['type']) && $_GET['type'] === 'my_posts') {
            writePostPage(
                PageType::MyPosts,
                fn() => get_user_post_count(get_user_id()),
                fn($offset, $count) => get_user_posts(get_user_id(), $offset, $count),
                'Мои посты'
            );
        }
        ?>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
