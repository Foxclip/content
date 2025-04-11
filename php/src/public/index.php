<?php
    require_once('../session.php');
    require_once('../utils.php');
    require_once("../posts.php");
?>

<!DOCTYPE html>
<html>
<?php
    includeFile('../head.php', [
        'title' => 'Главная',
        'styles' => [
            'common.css',
            'header.css',
            'footer.css',
            'index.css'
        ]
    ]);
?>
<body>
    <?= includeFile('../header.php', ['includeSearchBar' => true]); ?>
    <main>
        <div id="recentPosts">
            <div id="recentPostsTitleContainer">
                <h1 id="recentPostsTitle">Последние посты</h1>
                <?php
                if (is_logged_in()) {
                    includeFile('../create_post_button.php');
                }
                ?>
            </div>
            <div id="recentPostsList">
                <?php
                $posts = get_recent_posts();
                if (empty($posts)) {
                    echo '<div id="noPostsPlaceholder">Пока нет постов</div>';
                }
                foreach ($posts as $post) {
                    includeFile('../post.php', [
                        'postId' => $post['id'],
                        'postAuthor' => $post['username'],
                        'postDate' => $post['created_at'],
                        'postTitle' => $post['title'],
                        'postContent' => $post['content'],
                        'postLikes' => $post['like_count']
                    ]);
                }
                ?>
            </div>
        </div>
    </main>
    <?= includeFile('../footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
