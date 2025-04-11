<?php
$user = get_user();
?>

<div id="userMenuContainer">
    <?php
    includeFile('../ui/icon_button.php', [
        'id' => 'userMenuButton',
        'icon' => 'icons/user.png',
        'text' => $user['username']
    ]);
    ?>
    <div id="userMenuPopup">
        <a class="userMenuItem" href="/profile">
            <img src="icons/user_pen.png" width="20" height="20">
            <span>Личный кабинет</span>
        </a>
        <a class="userMenuItem" href="/logout">
            <img src="icons/logout.png" width="20" height="20">
            <span>Выйти</span>
        </a>
    </div>
</div>
