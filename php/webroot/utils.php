<?php

function includeFile(string $filePath, array $params = []): void {
    extract($params);
    include($filePath);
}

function checkPostVariables(array $names): void {
    foreach ($names as $name) {
        if (!isset($_POST[$name])) {
            echo "Переменная $name не установлена";
            exit();
        }
        if (empty($_POST[$name])) {
            echo "Переменная $name пустая";
            exit();
        }
    }
}

?>
