<?php
$user = get_user();
?>

<div id="userMenuContainer">
    <div id="userMenuButton" class="iconButton">
        <img id="iserIcon" src="icons/user.png" width="20" height="20">
        <div id="username"><?= $user['username'] ?></div>
    </div>
    <div id="userMenuPopup">
        <div id="logoutButton">
            <img src="icons/logout.png" width="20" height="20">
            <a href="/logout">Выйти</a>
        </div>
    </div>
</div>
