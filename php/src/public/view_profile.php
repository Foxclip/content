<?php

require_once('../session.php');
require_once('../utils.php');

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $user = get_user_by_id($id);
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
    <script src="js/edit_profile.js" type="module"></script>
</body>
</html>
