<header id="header">
    <div id="headerLeftSection">
        <a id="headerLogoLink" href="/"><div id="headerLogo"></div></a>
    </div>
    <div id="headerCenterSection">
        <?php
        if (isset($includeSearchBar) && $includeSearchBar) {
            includeFile('../searchbar.php');
        }
        ?>
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
