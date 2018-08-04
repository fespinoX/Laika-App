<?php

spl_autoload_register(function($className) {
    $filepath = __DIR__ . '/' . $className . ".php";

    // Reemplazamos las \ con /.
    $filepath = str_replace('\\', '/', $filepath);

    // Verificamos que el archivo exista.
    if(file_exists($filepath)) {
        require $filepath;
    }
});