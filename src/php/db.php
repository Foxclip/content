<?php

require_once "config.php";

enum QueryParameterType {
    case String;
    case Integer;
}

function get_pdo_type(QueryParameterType $type): int {
    return match ($type) {
        QueryParameterType::String => PDO::PARAM_STR,
        QueryParameterType::Integer => PDO::PARAM_INT,
    };
}

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

function execute_sql_query(string $query, array $params = [], QueryParameterType|array $types = QueryParameterType::String): array {
    $pdo = pdo_connect_mysql();
    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $value) {
        if (is_array($types)) {
            $type = get_pdo_type($types[$key]);
        } else {
            $type = get_pdo_type($types);
        }
        $stmt->bindValue($key, $value, $type);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function execute_sql_script(string $filename, array $params = [], QueryParameterType|array $types = QueryParameterType::String): array {
    $sql = file_get_contents(\Config\sql_dir . '/' . $filename);
    $result = execute_sql_query($sql, $params, $types);
    return $result;
}

?>
