<?php

require_once('../session.php');
require_once('../utils.php');
require_once('../posts.php');

enum UserPageType {
    case UserProfile;
    case UserPosts;
}

$baseUri = get_base_uri($_SERVER['REQUEST_URI']);
$usernameParts = explode('/', $baseUri);
if (count($usernameParts) >= 3) {
    $username = $usernameParts[2];
    $user = get_user_by_name($username);
}

$pageType = UserPageType::UserProfile;
$pageTitle = 'Профиль ' . $username;
if (isset($_GET['type']) && $_GET['type'] === 'posts') {
    $pageType = UserPageType::UserPosts;
    $pageTitle = 'Посты ' . $username;
}

?>

<!DOCTYPE html>
<html>
<?php
    includeFile('../ui/head.php', [
        'title' => $pageTitle,
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
                <h1 id="profileTitle"><?= $pageTitle ?></h1>
                <div class="tabButtonList">
                    <?php
                    writeTab('Профиль', $baseUri, $pageType === UserPageType::UserProfile);
                    writeTab('Посты', $baseUri . '?type=posts', $pageType === UserPageType::UserPosts);
                    ?>
                </div>
                <?php if ($pageType === UserPageType::UserProfile): ?>
                <div id="profileCard" class="profileCard card">
                    <img class="avatarImage" src="<?= get_user_avatar_url($user['id']) ?>" width="100" height="100">
                    <div class="profileItem"><?= $user['email'] ?></div>
                </div>
                <?php elseif ($pageType === UserPageType::UserPosts): ?>
                <?php
                writePostPage(
                    fn() => get_user_post_count($user['id']),
                    fn($offset, $count) => get_user_posts($user['id'], $offset, $count),
                    get_base_uri($_SERVER['REQUEST_URI']),
                    'posts'
                );
                ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="/js/view_profile.js" type="module"></script>
</body>
</html>
