<?php
    require_once('../config.php');
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

    class PageParameters {
        public static PageType $pageType;
        public static int $postCount;
        public static int $pageCount;
        public static int $page;
        public static array $posts;
        public static string $title;
    };

    function setPageParameters(PageType $pageType, callable $getPostCountFunc, callable $getPostsFunc, string $title) {
        PageParameters::$pageType = $pageType;
        PageParameters::$postCount = $getPostCountFunc();
        PageParameters::$pageCount = ceil(PageParameters::$postCount / \Config\max_posts_per_page);
        if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
            PageParameters::$page = intval($_GET['page']);
        } else {
            PageParameters::$page = ceil(PageParameters::$postCount / \Config\max_posts_per_page);
        }
        $offset = max(0, PageParameters::$postCount - \Config\max_posts_per_page * PageParameters::$page);
        PageParameters::$posts = $getPostsFunc($offset, \Config\max_posts_per_page);
        PageParameters::$title = $title;
    }

    if (!isset($_GET['type'])) {
        setPageParameters(
            PageType::RecentPosts,
            fn() => get_all_post_count(),
            fn($offset, $count) => get_recent_posts($offset, $count),
            'Все посты'
        );
    } else if (isset($_GET['type']) && $_GET['type'] === 'my_posts') {
        setPageParameters(
            PageType::MyPosts,
            fn() => get_user_post_count(get_user_id()),
            fn($offset, $count) => get_user_posts(get_user_id(), $offset, $count),
            'Мои посты'
        );
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
                <h1 id="recentPostsTitle"><?= PageParameters::$title ?></h1>
                <?php
                if (is_logged_in()) {
                    includeFile('../ui/create_post_button.php');
                }
                ?>
            </div>
            <div id="tabContainer">
                <div class="tabButtonList">
                    <?php
                    function writeTab(string $text, string $href, PageType $pageType): void {
                        $active = PageParameters::$pageType === $pageType;
                        $hrefStr = " href=$href";
                        if ($active) {
                            $hrefStr = '';
                        }
                        echo "<a$hrefStr class=\"tabButton " . ($active ? 'active' : '') . '">' . $text . '</a>';
                    }
                    if (is_logged_in()) {
                        writeTab('Все', '/', PageType::RecentPosts);
                        writeTab('Мои', '/?type=my_posts', PageType::MyPosts);
                    }
                    ?>
                </div>
                <div class="tabBodyList">
                    <?php
                    if (PageParameters::$pageType === PageType::RecentPosts):
                    ?>
                    <divb class="postList tabBody active">
                        <?php
                        write_posts(PageParameters::$posts);
                        ?>
                    </div>
                    <?php
                    endif;
                    ?>
                    <?php
                    if (PageParameters::$pageType === PageType::MyPosts):
                    ?>
                    <div class="postList tabBody active">
                        <?php
                        write_posts(PageParameters::$posts);
                        ?>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
            <div class="paginationContainer">
                <?php
                if (PageParameters::$pageCount > 1) {
                    $pageTypeParamStr = PageParameters::$pageType === PageType::RecentPosts ? null : 'my_posts';
                    for ($i = PageParameters::$pageCount; $i >= 1; $i--) {
                        $isActive = PageParameters::$page === $i;
                        $get_parameters = [
                            'type' => $pageTypeParamStr,
                            'page' => $i
                        ];
                        $queryStr = http_build_query($get_parameters);
                        $hrefStr = " href=/?" . $queryStr;
                        if (PageParameters::$page === $i) {
                            $hrefStr = '';
                        }
                        echo '<a' . $hrefStr . ' class="paginationButton ' . ($isActive ? 'active' : '') . '">' . $i . '</a>';
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
