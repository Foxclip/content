<?php

require_once __DIR__ . "/db.php";

function get_recent_posts(int $offset, int $count) {
    $result = execute_sql_script('get_recent_posts.sql',
        [
            'currentUserId' => get_user_id(),
            'offset' => $offset,
            'count' => $count
        ],
        QueryParameterType::Integer
    );
    return $result;
}

function get_user_posts(int|null $id, int $offset, int $count): array {
    $userId = $id;
    $result = execute_sql_script('get_user_posts.sql',
        [
            'currentUserId' => get_user_id(),
            'queryUserId' => $userId,
            'offset' => $offset,
            'count' => $count
        ],
        QueryParameterType::Integer
    );
    return $result;
}

function get_all_post_count(): int {
    $rows = execute_sql_query('SELECT COUNT(*) AS count FROM posts');
    $result = $rows[0]['count'];
    return $result;
}

function get_user_post_count(int|null $id = null): int {
    if (!$id) {
        $id = get_user_id();
    }
    $userId = $id;
    $rows = execute_sql_query('SELECT COUNT(*) AS count FROM posts WHERE user_id = :userId', [
        'userId' => $userId
    ]);
    $result = $rows[0]['count'];
    return $result;
}

enum PageType {
    case RecentPosts;
    case MyPosts;
    case UserPosts;
}

function writeTab(string $text, string $href, bool $active): void {
    $hrefStr = " href=$href";
    if ($active) {
        $hrefStr = '';
    }
    echo "<a$hrefStr class=\"tabButton " . ($active ? 'active' : '') . '">' . $text . '</a>';
}

function write_posts($posts) {
    if (empty($posts)) {
        echo '<div class="postsNoPostsPlaceholder">Пока нет постов</div>';
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

function writePostPage(callable $getPostCountFunc, callable $getPostsFunc, string $hrefBase = '/', ?string $typeParamStr = null) {
    $postCount = $getPostCountFunc();
    $pageCount = (int)ceil($postCount / \Config\max_posts_per_page);
    if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
        $page = intval($_GET['page']);
    } else {
        $page = (int)ceil($postCount / \Config\max_posts_per_page);
    }
    $offset = ($pageCount - $page) * \Config\max_posts_per_page;
    $posts = $getPostsFunc($offset, \Config\max_posts_per_page);
    ?>
    <div class="postsPage">
        <div class="postList">
            <?php
            write_posts($posts);
            ?>
        </div>
        <?php if ($pageCount > 1): ?>
        <div class="paginationContainer">
            <?php
            for ($i = $pageCount; $i >= 1; $i--) {
                $isActive = $page === $i;
                $get_parameters = [
                    'type' => $typeParamStr,
                    'page' => $i
                ];
                $queryStr = http_build_query($get_parameters);
                $hrefStr = " href=" . $hrefBase . "?" . $queryStr;
                if ($page === $i) {
                    $hrefStr = '';
                }
                echo '<a' . $hrefStr . ' class="paginationButton ' . ($isActive ? 'active' : '') . '">' . $i . '</a>';
            }
            ?>
        </div>
        <?php endif; ?>
    </div>
<?php
}
?>
