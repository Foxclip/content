<?php

require_once "config.php";

function pdo_connect_mysql(): PDO {

    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $dsn = "mysql:dbname=" . \Config\dbname . ";host=". \Config\host . "";
    $pdo = new PDO($dsn, \Config\user, \Config\password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
    
}

function execute_sql_query(string $query, array $params = [], array $types = []): array {
    if (count($params) > 0 && count($types) == 0) {
        foreach ($params as $key => $value) {
            $types[$key] = PDO::PARAM_STR;
        }
    }
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value, $types[$key]);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function execute_sql_script(string $filename, array $params = [], array $types = []): array {
    $sql = file_get_contents(\Config\sql_dir . $filename);
    $result = execute_sql_query($sql, $params, $types);
    return $result;
}

?>
