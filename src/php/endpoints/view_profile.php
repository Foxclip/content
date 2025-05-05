<?php

require_once('../session.php');
require_once('../utils.php');
require_once('../posts.php');

$request = $_SERVER['REQUEST_URI'];
$usernameParts = explode('/', $request);
if (count($usernameParts) >= 3) {
    $username = $usernameParts[2];
    $user = get_user_by_name($username);
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
            'view_profile.css',
            'posts.css'
        ]
    ]);
?>
<body>
    <?= includeFile('../ui/header.php'); ?>
    <main>
        <div id="profileContainer">
            <?php if (!isset($user)): ?>
                <h1>Пользователь не найден</h1>
            <?php else: ?>
            <div id="profileContent">
                <h1 id="profileTitle">Профиль <?= $user['username'] ?></h1>
                <div id="profileCard" class="profileCard card">
                    <img class="avatarImage" src="<?= get_user_avatar_url($user['id']) ?>" width="100" height="100">
                    <div class="profileItem"><?= $user['email'] ?></div>
                </div>
                <div>
                    <?php
                    writePostPage(
                        PageType::UserPosts,
                        fn() => get_user_post_count($user['id']),
                        fn($offset, $count) => get_user_posts($user['id'], $offset, $count),
                        'Посты ' . $user['username']
                    );
                    ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="/js/view_profile.js" type="module"></script>
</body>
</html>
