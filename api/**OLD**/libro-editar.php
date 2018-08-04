<?php
require 'conexion.php';
require 'token-functions.php';

$token = $_SERVER['HTTP_X_TOKEN'];

if(!verifyToken($token)) {
    echo json_encode([
        "success" => false,
        "msg" => 'Esta acción requiere autenticación.'
    ]);
    die;
}

$inputEntrada = file_get_contents('php://input');
$putData = json_decode($inputEntrada, true);

// Acá iría la validación ¯\_(ツ)_/¯

$query = "UPDATE libros
           SET    FKGENEROS     = :FKGENEROS,
                  FKAUTORES     = :FKAUTORES,
                  TITULO        = :TITULO,
                  ANIO          = :ANIO,
                  DESCRIPCION   = :DESCRIPCION
           WHERE  IDLIBRO       = :IDLIBRO";

$stmt = $db->prepare($query);

$exito = $stmt->execute([
    'FKGENEROS'         => $putData['FKGENEROS'],
    'FKAUTORES'         => $putData['FKAUTORES'],
    'TITULO'            => $putData['TITULO'],
    'ANIO'              => $putData['ANIO'],
    'DESCRIPCION'       => $putData['DESCRIPCION'],
    'IDLIBRO'           => $_GET['id']
]);

if($exito) {
    echo json_encode([
        'success' => true,
        'msg' => 'Se actualizó el libro.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'msg' => 'Oh noo! No se pudo actualizar el libro...',
        'errors' => [
            'db' => 'Error de actualización en la base de datos.'
        ]
    ]);
}