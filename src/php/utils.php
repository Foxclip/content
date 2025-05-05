<?php

function includeFile(string $filePath, array $params = []): void {
    extract($params);
    include($filePath);
}

function checkVariables(array $array, array $names): string {
    foreach ($names as $name) {
        if (!isset($array[$name])) {
            return "Переменная $name не установлена";
        }
        if (empty(trim($array[$name]))) {
            return "Переменная $name пустая";
        }
    }
    return "";
}

function get_base_uri(): string {
    $questionMarkPos = strpos($_SERVER['REQUEST_URI'], '?');
    if ($questionMarkPos === false) {
        return $_SERVER['REQUEST_URI'];
    }
    return substr($_SERVER['REQUEST_URI'], 0, $questionMarkPos);
}

?>
