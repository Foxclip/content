<?php
    require_once('../session.php');
    require_once('../utils.php');
    require_once("../posts.php");

    const max_posts_per_page = 10;

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

    $page = 1;
    if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
        $page = intval($_GET['page']);
    }
    $offset = ($page - 1) * max_posts_per_page;
    if (!isset($_GET['type'])) {
        $pageType = PageType::RecentPosts;
        $posts = get_recent_posts($offset, max_posts_per_page);
        $title = 'Все посты';
        $postCount = get_all_post_count();
    } else if (isset($_GET['type']) && $_GET['type'] === 'my_posts') {
        $pageType = PageType::MyPosts;
        $posts = get_user_posts(get_user_id(), $offset, max_posts_per_page);
        $title = 'Мои посты';
        $postCount = get_user_post_count();
    }
    $pageCount = ceil($postCount / max_posts_per_page);

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
                    <a href="/?type=my_posts"><div class="tabButton <?= $pageType === PageType::MyPosts ? 'active' : '' ?>">Мои</div></a>
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
            <div class="paginationContainer">
                <?php
                if ($pageCount > 1) {
                    $pageTypeParamStr = $pageType === PageType::RecentPosts ? null : 'my_posts';
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $isActive = $page === $i;
                        $get_parameters = [
                            'type' => $pageTypeParamStr,
                            'page' => $i
                        ];
                        $queryStr = http_build_query($get_parameters);
                        echo '<a href="/?' . $queryStr . '"><div class="paginationButton ' . ($isActive ? 'active' : '') . '">' . $i . '</div></a>';
                    }
                }
                ?>
            </div>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
