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

?>
