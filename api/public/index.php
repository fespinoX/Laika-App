<?php

use Laika\Core\App;

require '../app/autoload.php';

require '../app/routes.php';

// Obtenemos la ruta de nuestro sitio.
// realpath devuelve la ruta final de la que
// pasamos por parámetro.
$ruta = realpath(__DIR__ . '/../');

//echo "La ruta de mi sitio es: " . $ruta;

$app = new App($ruta);

//echo "Homepage! :D";