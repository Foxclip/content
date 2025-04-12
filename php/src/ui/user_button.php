<?php
$user = get_user();
?>

<div id="userMenuContainer">
    <div id="userMenuButton">
        <img src="<?=get_user_avatar_url()?>" width="30" height="30">
        <span><?=$user['username']?></span>
    </div>
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
