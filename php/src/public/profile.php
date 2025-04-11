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
        </div>
    </main>
    <?= includeFile('../ui/footer.php'); ?>
    <script src="js/profile.js" type="module"></script>
</body>
</html>
