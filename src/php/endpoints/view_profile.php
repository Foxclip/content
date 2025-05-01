<?php

require_once('../session.php');
require_once('../utils.php');

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
            'view_profile.css'
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
            <h1 id="profileTitle">Профиль <?= $user['username'] ?></h1>
            <div id="profileContent">
                <div id="profileCard" class="profileCard card">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="/js/view_profile.js" type="module"></script>
</body>
</html>
