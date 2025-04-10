<?php
    require_once('../session.php');
    require_once('../utils.php');
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
            <!-- <div id="noPostsPlaceholder">Пока нет постов</div> -->
            <div id="recentPostsList">
                <div class="post">
                    <div class="postTopPanel">
                        <span class="postAuthor">Автор поста</span>
                        <span class="postDate">2025.04.02</span>
                    </div>
                    <div class="postTitleContainer">
                        <h2 class="postTitle">Заголовок поста</h2>
                        <div class="postTags">
                            <div class="postTag">Тег 1</div>
                            <div class="postTag">Тег 2</div>
                        </div>
                    </div>
                    <div class="postContent">Текст поста</div>
                    <div class="bottomPanel">
                        <div class="postLikes">
                            <img class="postLikesHeart" src="icons/heart.png">
                            <span class="postLikesCount">0</span>
                        </div>
                    </div>
                </div>
                <div class="post">
                    <div class="postTopPanel">
                        <span class="postAuthor">Автор поста</span>
                        <span class="postDate">2025.04.02</span>
                    </div>
                    <div class="postTitleContainer">
                        <h2 class="postTitle">Заголовок поста</h2>
                        <div class="postTags">
                            <div class="postTag">Тег 1</div>
                            <div class="postTag">Тег 2</div>
                        </div>
                    </div>
                    <div class="postContent">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Phasellus non dui eu quam facilisis tristique eget vulputate ante.
                        Donec quis convallis quam. Vestibulum eget enim lacinia, dignissim sapien ut, efficitur ipsum.
                        Donec faucibus metus eu libero consectetur, eget eleifend orci iaculis.
                        Pellentesque eget nisi augue. Nulla blandit enim at sapien vestibulum, id aliquam est varius.
                        Donec non ipsum molestie, pulvinar ligula lobortis, mattis ligula.
                        Quisque placerat elit vitae nisl posuere hendrerit. Aliquam eget imperdiet neque, in porta arcu.
                        Aliquam id congue ex. Mauris sodales felis quis risus ornare, sed placerat libero ultricies.
                    </div>
                    <div class="bottomPanel">
                        <div class="postLikes">
                            <img class="postLikesHeart" src="icons/heart.png">
                            <span class="postLikesCount">0</span>
                        </div>
                    </div>
                </div>
                <div class="post">
                    <div class="postTopPanel">
                        <span class="postAuthor">Автор поста</span>
                        <span class="postDate">2025.04.02</span>
                    </div>
                    <div class="postTitleContainer">
                        <h2 class="postTitle">Заголовок поста</h2>
                        <div class="postTags">
                            <div class="postTag">Тег 1</div>
                            <div class="postTag">Тег 2</div>
                        </div>
                    </div>
                    <div class="postContent">Текст поста</div>
                    <div class="bottomPanel">
                        <div class="postLikes">
                            <img class="postLikesHeart" src="icons/heart.png">
                            <span class="postLikesCount">0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?= includeFile('../footer.php'); ?>
    <script src="js/index.js" type="module"></script>
</body>
</html>
