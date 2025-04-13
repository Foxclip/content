<?php

require_once('../session.php');
require_once('../utils.php');

if (!is_logged_in()) {
    redirect_to_login_page(true);
    exit();
}

function includeEditButton(): void {
    includeFile('../ui/icon_button.php', [
        'icon' => 'icons/pencil.png',
        'text' => 'Изменить',
        'classes' => ['profileEditButton']
    ]);
}

function includeSaveButton(): void {
    includeFile('../ui/icon_button.php', [
        'icon' => 'icons/send.png',
        'text' => 'Сохранить',
        'classes' => ['profileSaveButton', 'hidden']
    ]);
    includeFile('../ui/icon_button.php', [
        'icon' => 'icons/cross.png',
        'text' => 'Отмена',
        'classes' => ['profileCancelButton', 'hidden']
    ]);
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
                <div id="profileTabList" class="tabButtonList">
                    <div class="tabButton active">Главное</div>
                </div>
                <div id="profileMainTab" class="profileTabBody">
                    <table id="profileTable">
                        <tr id="usernameRow">
                            <td>Логин:</td>
                            <td><?= get_user()['username'] ?></td>
                        </tr>
                        <tr id="emailRow">
                            <td>Почта:</td>
                            <td>
                                <div class="profileErrorContainer">
                                    <span class="profileDisplayText"><?= get_user()['email'] ?></span>
                                    <input class="profileTextInput hidden" type="email" name="email">
                                    <span class="profileErrorText hidden"></span>
                                </div>
                            </td>
                            <td>
                                <div class="profileEditButtonsContainer">
                                    <?php
                                    includeEditButton();
                                    includeSaveButton();
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr id="avatarRow">
                            <td>Аватар:</td>
                            <td>
                                <img class="profileDisplayImage avatarImage" src="<?= get_user_avatar_url() ?>" width="40" height="40">
                            </td>
                            <td>
                                <div class="profileErrorContainer">
                                    <div class="profileEditButtonsContainer">
                                        <?php
                                        includeEditButton();
                                        ?>
                                        <input class="profileHiddenFileInput hidden" type="file" accept="image/jpeg, image/png">
                                    </div>
                                    <span class="profileErrorText hidden"></span>
                                </div>
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
