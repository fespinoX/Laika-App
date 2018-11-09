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
$postData = json_decode($inputEntrada, true);

// Acá iría la validación ¯\_(ツ)_/¯

$query = "INSERT INTO libros (FKGENEROS, FKAUTORES, TITULO, ANIO, DESCRIPCION)
          VALUES (:FKGENEROS, :FKAUTORES, :TITULO, :ANIO, :DESCRIPCION)";

$stmt = $db->prepare($query);

$exito = $stmt->execute([
    'FKGENEROS'     => $postData['FKGENEROS'],
    'FKAUTORES'     => $postData['FKAUTORES'],
    'TITULO'        => $postData['TITULO'],
    'ANIO'          => $postData['ANIO'],
    'DESCRIPCION'   => $postData['DESCRIPCION'],
]);

if($exito) {
    echo json_encode([
        'success' => true,
        'msg' => 'Se agregó el libro.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'msg' => 'Hubo un error al intentar agregar el libro',
        'errors' => [
            'db' => 'Error de inserción en la base de datos.'
        ]
    ]);
}