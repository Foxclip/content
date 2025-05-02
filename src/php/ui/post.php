<div class="post" data-post-id="<?=$postId?>">
<div class="postTopPanel">
    <a class="userNameWithAvatar" href="user/<?=$postAuthor?>">
        <img class="avatarImage" src="<?=$postAuthorAvatarUrl?>" width="30" height="30">
        <span class="postAuthor"><?=$postAuthor?></span>
    </a>
    <span class="postDatetime"><?=$postDatetime?></span>
</div>
<div class="postTitleContainer">
    <h2 class="postTitle"><?=htmlspecialchars($postTitle)?></h2>
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
