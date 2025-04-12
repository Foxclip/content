<?php

require_once('../session.php');
require_once('../utils.php');

if (!is_logged_in()) {
    redirect_to_login_page(true);
    exit();
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
            'profile.css'
        ]
    ]);
?>
<body>
    <?= includeFile('../ui/header.php'); ?>
    <main>
        <div id="profileContainer">
            <h1 id="profileTitle">Профиль</h1>
            <div id="profileContent">
                <div id="profileTabList">
                    <div class="profileTab active">Главное</div>
                </div>
                <div id="profileMainTab" class="profileTabBody">
                    <table id="profileTable">
                        <tr>
                            <td>Логин:</td>
                            <td><?= get_user()['username'] ?></td>
                        </tr>
                        <tr>
                            <td>Почта:</td>
                            <td><?= get_user()['email'] ?></td>
                        </tr>
                        <tr>
                            <td>Аватар:</td>
                            <td>
                                <div id="avatarContainer">
                                    <img id="avatarImage" src="<?= get_user_avatar_url() ?>" width="40" height="40">
                                </div>
                            </td>
                            <td>
                                <?php
                                includeFile('../ui/icon_button.php', [
                                    'id' => 'changeAvatarButton',
                                    'icon' => 'icons/pencil.png',
                                    'text' => 'Изменить'
                                ]);
                                ?>
                                <form id="changeAvatarForm" action="/change_avatar" method="post">
                                    <input id="avatarHiddenInput" class="hiddenFileInput" type="file" accept="image/jpeg, image/png">
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/profile.js" type="module"></script>
</body>
</html>
