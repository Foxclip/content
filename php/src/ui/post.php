<div class="post" data-post-id="<?=$postId?>">
<div class="postTopPanel">
    <span class="postAuthor"><?=$postAuthor?></span>
    <span class="postDatetime"><?=$postDatetime?></span>
</div>
<div class="postTitleContainer">
    <h2 class="postTitle"><?=htmlspecialchars($postTitle)?></h2>
    <!-- <div class="postTags">
        <div class="postTag">Тег 1</div>
        <div class="postTag">Тег 2</div>
    </div> -->
</div>
<div class="postContent"><?=nl2br(htmlspecialchars($postContent))?></div>
<div class="bottomPanel">
    <div class="postLikes <?= $postLikedByUser ? 'active' : '' ?>" data-post-id="<?=$postId?>">
        <div class="postLikesHeart"></div>
        <div class="postLikesHeartFull"></div>
        <span class="postLikesCount"><?=$postLikes?></span>
    </div>
</div>
</div>
