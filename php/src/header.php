<header id="header">
    <div id="headerLeftSection"></div>
    <div id="headerCenterSection">
        <div id="searchBar">
            <img id="searchBarIcon" src="icons/search.png">
            <input id="searchBarInput" type="text" placeholder="Поиск">
        </div>
    </div>
    <div id="headerRightSection">
        <?php
        if (is_logged_in()) {
            includeFile('../user_button.php');
        } else {
            echo '<a id="loginButton" href="/login">Войти</a>';
        }
        ?>
    </div>
    <script src="js/header.js" type="module"></script>
</header>
