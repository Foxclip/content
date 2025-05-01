<?php
    require_once('../php/session.php');
    require_once('../php/utils.php');
?>

<!DOCTYPE html>
<html>
<?php
    includeFile('../php/ui/head.php', [
        'title' => 'Регистрация',
        'styles' => [
            'common.css',
            'footer.css',
            'login.css'
        ]
    ]);
?>
<body>
    <div id="formContainer">
        <h1 id="formTitle">Регистрация</h1>
        <form id="form" action="/do_register" method="post">
            <div id="textInputContainer">
                <div class="inputContainer">
                    <input id="loginInput" class="formInput" type="text" name="login" placeholder="Логин" autofocus required />
                    <div class="inputError">Текст ошибки</div>
                </div>
                <div class="inputContainer">
                    <input id="emailInput" class="formInput" type="email" name="email" placeholder="Электронная почта" required />
                    <div class="inputError">Текст ошибки</div>
                </div>
                <div class="inputContainer">
                    <input id="passwordInput1" class="formInput" type="password" name="password" placeholder="Пароль" required />
                    <div class="inputError">Текст ошибки</div>
                </div>
                <div class="inputContainer">
                    <input id="passwordInput2" class="formInput" type="password" placeholder="Повторите пароль" required />
                    <div class="inputError">Текст ошибки</div>
                </div>
            </div>
            <div id="submitButtonContainer">
                <button id="submitButton" type="submit">Зарегистрироваться</button>
                <a id="alternativeButton" href="/login">Войти</a>
            </div>
        </form>
    </div>
    <?php includeFile('../php/ui/footer.php'); ?>
    <script src="js/register.js" type="module"></script>
</body>
</html>
