<header id="header">
    <div id="headerLeftSection">
        <a id="headerLogoLink" href="/"><div id="headerLogo"></div></a>
    </div>
    <div id="headerCenterSection">
        <?php
        if (isset($includeSearchBar) && $includeSearchBar) {
            includeFile('../ui/searchbar.php');
        }
        ?>
    </div>
    <div id="headerRightSection">
        <?php 
        if (is_logged_in()):
            includeFile('../ui/user_button.php');
        else:
        ?>
        <a id="loginButton" href="/login">Войти</a>
        <?php endif; ?>
    </div>
    <script src="js/header.js" type="module"></script>
</header>
