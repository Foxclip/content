<?php
    require_once('../session.php');
    require_once('../utils.php');
    require_once("../posts.php");

    function write_posts($posts) {
        if (empty($posts)) {
            echo '<div id="noPostsPlaceholder">Пока нет постов</div>';
        }
        foreach ($posts as $post) {
            includeFile('../ui/post.php', [
                'postId' => $post['post_id'],
                'postAuthorAvatarUrl' => get_user_avatar_url($post['user_id']),
                'postAuthor' => $post['username'],
                'postDatetime' => $post['created_at'],
                'postTitle' => $post['title'],
                'postContent' => $post['content'],
                'postLikes' => $post['like_count'],
                'postLikedByUser' => $post['liked_by_user']
            ]);
        }
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
            <div id="recentPostsTabListContainer">
                <div class="tabButtonList">
                    <div class="tabButton active">Все</div>
                    <div class="tabButton">Мои</div>
                </div>
                <div class="tabBodyList">
                    <div id="recentPostsList" class="postList tabBody active">
                        <?php
                        $posts = get_recent_posts();
                        write_posts($posts);
                        ?>
                    </div>
                    <div id="myPostsList" class="postList tabBody">
                        <?php
                        $posts = get_user_posts();
                        write_posts($posts);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
