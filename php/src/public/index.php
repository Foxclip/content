<?php
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
            'index.css'
        ]
    ]);
?>
<body>
    <?= includeFile('../ui/header.php', ['includeSearchBar' => true]); ?>
    <main>
        <div id="recentPosts">
            <div id="recentPostsTitleContainer">
                <h1 id="recentPostsTitle">Последние посты</h1>
                <?php
                if (is_logged_in()) {
                    includeFile('../ui/create_post_button.php');
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
                    includeFile('../ui/post.php', [
                        'postId' => $post['id'],
                        'postAuthor' => $post['username'],
                        'postDatetime' => $post['created_at'],
                        'postTitle' => $post['title'],
                        'postContent' => $post['content'],
                        'postLikes' => $post['like_count'],
                        'postLikedByUser' => $post['liked_by_user']
                    ]);
                }
                ?>
            </div>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
