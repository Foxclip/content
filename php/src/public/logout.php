<?php

require_once '../session.php';

if (!check_csrf_token()) {
    echo "CSRF токен недействителен";
    exit();
} else {
    generate_csrf_token();
}

logout();
header('Location: /');

?>
