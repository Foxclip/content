<?php
require_once('page_elements.php');
?>

<!DOCTYPE html>
<html>
<?php
echoHead('Регистрация', ['common.css', 'login.css']);
?>
<body>
    <div id="formContainer">
        <h1 id="formTitle">Регистрация</h1>
        <form id="form" action="/do_register" method="post">
            <input id="loginInput" class="formInput" type="text" name="login" placeholder="Логин" />
            <input id="emailInput" class="formInput" type="email" name="email" placeholder="Электронная почта" />
            <input id="passwordInput1" class="formInput" type="password" name="password" placeholder="Пароль" />
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
    <script src="js/register.js" type="module"></script>
</body>
</html>
