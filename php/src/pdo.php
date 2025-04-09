<?php

const host = 'mysql';
const user = 'root';
const password = 'root';
const dbname = 'content';

function pdo_connect_mysql(): PDO {

    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = "mysql:dbname=".dbname.";host=".host."";
    $pdo = new PDO($dsn, user, password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
    
}

?>
