<?php
    require_once('../config.php');
    require_once('../session.php');
    require_once('../utils.php');
    require_once("../posts.php");

    if (!isset($_GET['type'])) {
        $pageType = PageType::RecentPosts;
        $postsTitle = 'Все посты';
    } else if (isset($_GET['type']) && $_GET['type'] === 'my_posts') {
        $pageType = PageType::MyPosts;
        $postsTitle = 'Мои посты';
    }
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
        <div class='mainContainer'>
            <div class="postsTitleContainer">
                <h1 class="postsTitle"><?= $postsTitle ?></h1>
                <?php
                if (is_logged_in()) {
                    includeFile('../ui/icon_button.php', [
                        'icon' => '/icons/plus.png',
                        'text' => 'Написать',
                        'href' => '/create_post'
                    ]);
                }
                ?>
            </div>
            <?php
            if (!isset($_GET['type'])) {
                writePostPage(
                    PageType::RecentPosts,
                    fn() => get_all_post_count(),
                    fn($offset, $count) => get_recent_posts($offset, $count),
                );
            } else if (isset($_GET['type']) && $_GET['type'] === 'my_posts') {
                writePostPage(
                    PageType::MyPosts,
                    fn() => get_user_post_count(get_user_id()),
                    fn($offset, $count) => get_user_posts(get_user_id(), $offset, $count),
                );
            }
            ?>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
