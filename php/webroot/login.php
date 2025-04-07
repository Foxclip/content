<?php
require_once('page_elements.php');
?>

<!DOCTYPE html>
<html>
<?php
echoHead('Вход', 'login.css');
?>
<body>
    <div id="formContainer">
        <h1 id="formTitle">Вход</h1>
        <form id="form" action="/login" method="post">
            <input id="loginInput" class="formInput" type="text" placeholder="Логин" />
            <div id="passwordContainer">
                <input id="passwordInput" class="formInput" type="password" placeholder="Пароль" />
                <a id="forgotPasswordButton" href="/forgot-password">Забыли пароль?</a>
            </div>
            <div id="submitButtonContainer">
                <button id="submitButton" type="submit">Войти</button>
                <a id="alternativeButton" href="/register">Зарегистрироваться</a>
            </div>
        </form>
    </div>
    <?php
    echoFooter();
    ?>
    <script src="login.js" type="module"></script>
</body>
</html>
