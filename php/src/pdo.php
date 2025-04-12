<?php

const host = 'mysql';
const user = 'root';
const password = 'root';
const dbname = 'content';

const sql_dir = __DIR__ . '/sql/';

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

function execute_sql_query(string $filename, array $params = []): array {
    $pdo = pdo_connect_mysql();
    $sql = file_get_contents(sql_dir . $filename);
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

?>
