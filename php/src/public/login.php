<?php
    require_once('../session.php');
    require_once('../utils.php');

    set_csrf_token();
?>

<!DOCTYPE html>
<html>
<?php
    includeFile("../ui/head.php", [
        'title' => 'Вход',
        'styles' => [
            'common.css',
            'footer.css',
            'login.css'
        ]
    ]);
?>
<body>
    <div id="formContainer">
        <h1 id="formTitle">Вход</h1>
        <form id="form" action="/do_login" method="post">
            <div id="textInputContainer">
                <div class="inputContainer">
                    <input id="loginInput" class="formInput" type="text" name="login" placeholder="Логин" autofocus required />
                    <div class="inputError">Текст ошибки</div>
                </div>
                <div id="passwordContainer" class="inputContainer">
                    <input id="passwordInput" class="formInput" type="password" name="password" placeholder="Пароль" required />
                    <div id="belowPasswordContainer">
                        <div class="inputError">Текст ошибки</div>
                        <a id="forgotPasswordButton" href="/forgot-password">Забыли пароль?</a>
                    </div>
                </div>
            </div>
            <div id="submitButtonContainer">
                <button id="submitButton" type="submit">Войти</button>
                <a id="alternativeButton" href="/register">Зарегистрироваться</a>
            </div>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        </form>
    </div>
    <?php includeFile('../ui/footer.php'); ?>
    <script src="js/login.js" type="module"></script>
</body>
</html>
