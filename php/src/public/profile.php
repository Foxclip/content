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
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/profile.js" type="module"></script>
</body>
</html>
