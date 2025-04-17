<?php
$user = get_user();
?>

<div id="userMenuContainer">
    <div id="userMenuButton">
        <img class="avatarImage" src="<?=get_user_avatar_url()?>" width="30" height="30">
        <span><?=$user['username']?></span>
    </div>
    <div id="userMenuPopup">
        <a class="userMenuItem" href="/edit_profile">
            <img src="icons/user_pen.png" width="20" height="20">
            <span>Личный кабинет</span>
        </a>
        <a id="logoutButton" class="userMenuItem">
            <img src="icons/logout.png" width="20" height="20">
            <span>Выйти</span>
            <form action="/logout" method="post">
                <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['csrf_token'])?>">
            </form>
        </a>
    </div>
</div>
