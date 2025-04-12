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

    enum PageType {
        case RecentPosts;
        case MyPosts;
    }

    if ($_SERVER['REQUEST_URI'] === '/') {
        $pageType = PageType::RecentPosts;
        $posts = get_recent_posts();
        $title = 'Все посты';
    } else if (isset($_GET['my_posts'])) {
        $pageType = PageType::MyPosts;
        $posts = get_user_posts();
        $title = 'Мои посты';
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
                <h1 id="recentPostsTitle"><?= $title ?></h1>
                <?php
                if (is_logged_in()) {
                    includeFile('../ui/create_post_button.php');
                }
                ?>
            </div>
            <div id="tabContainer">
                <div class="tabButtonList">
                    <a href="/"><div class="tabButton <?= $pageType === PageType::RecentPosts ? 'active' : '' ?>">Все</div></a>
                    <a href="/?my_posts"><div class="tabButton <?= $pageType === PageType::MyPosts ? 'active' : '' ?>">Мои</div></a>
                </div>
                <div class="tabBodyList">
                    <?php
                    if ($pageType === PageType::RecentPosts):
                    ?>
                    <divb class="postList tabBody active">
                        <?php
                        write_posts($posts);
                        ?>
                    </div>
                    <?php
                    endif;
                    ?>
                    <?php
                    if ($pageType === PageType::MyPosts):
                    ?>
                    <div class="postList tabBody active">
                        <?php
                        write_posts($posts);
                        ?>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
