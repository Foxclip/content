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

function get_initial_data(): array {
    $user = get_user();
    return [
        'username' => $user['username'],
        'email' => $user['email']
    ];
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
            'edit_profile.css'
        ]
    ]);
?>
<body>
    <?= includeFile('../ui/header.php'); ?>
    <script id="initial-data" type="application/json">
        <?php echo json_encode(get_initial_data(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
    </script>
    <main id="root">
        <div id="profileContainer">
            <h1 id="profileTitle">Профиль</h1>
            <div id="profileContent">
                <div id="profileTabList" class="tabButtonList">
                    <div class="tabButton active">Главное</div>
                </div>
                <div id="profileMainTab" class="profileTabBody card">
                    <table id="profileTable">
                        <tr id="usernameRow">
                            <td><span class="profileLabelText">Логин:</span></td>
                            <td><span class="profileDisplayText"><?= get_user()['username'] ?></span></td>
                        </tr>
                        <tr id="emailRow">
                            <td><span class="profileLabelText">Email:</span></td>
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
                        <tr id="passwordRow">
                            <td><span class="profileLabelText">Пароль:</span></td>
                            <td>
                                <div class="profileErrorContainer">
                                    <span class="profileDisplayText">******</span>
                                    <input class="profileTextInput hidden" type="password" name="old_password" placeholder="Старый пароль">
                                    <input class="profileTextInput hidden" type="password" name="new_password" placeholder="Новый пароль">
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
                            <td><span class="profileLabelText">Аватар:</span></td>
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
    <script src="js/edit_profile.js" type="module"></script>
</body>
</html>
