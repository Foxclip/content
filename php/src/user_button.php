<?php
$user = get_user();
?>

<div id="userMenuContainer">
    <?php
    includeFile('../icon_button.php', [
        'id' => 'userMenuButton',
        'icon' => 'icons/user.png',
        'text' => $user['username']
    ]);
    ?>
    <div id="userMenuPopup">
        <div id="logoutButton">
            <img src="icons/logout.png" width="20" height="20">
            <a href="/logout">Выйти</a>
        </div>
    </div>
</div>
