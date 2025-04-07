<?php
require_once('page_elements.php');
?>

<!DOCTYPE html>
<html>
<?php
echoHead('Регистрация', 'login.css');
?>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Регистрация</title>
    <link rel="stylesheet" href="login.css" />
</head>
<body>
    <div id="formContainer">
        <h1 id="formTitle">Регистрация</h1>
        <form id="form" action="/register" method="post">
            <input id="loginInput" class="formInput" type="text" placeholder="Логин" />
            <input id="emailInput" class="formInput" type="email" placeholder="Электронная почта" />
            <input id="passwordInput1" class="formInput" type="password" placeholder="Пароль" />
            <input id="passwordInput2" class="formInput" type="password" placeholder="Повторите пароль" />
            <div id="submitButtonContainer">
                <button id="submitButton" type="submit">Зарегистрироваться</button>
                <a id="alternativeButton" href="/login">Войти</a>
            </div>
        </form>
    </div>
    <?php
    echoFooter();
    ?>
    <script src="register.js" type="module"></script>
</body>
</html>
