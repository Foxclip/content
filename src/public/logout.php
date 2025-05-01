<?php

require_once '../php/session.php';

if (!check_csrf_token()) {
    echo "CSRF токен недействителен";
    exit();
}

logout();
header('Location: /');

?>
