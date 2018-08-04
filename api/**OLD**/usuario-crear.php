<?php
require 'conexion.php';

$inputEntrada = file_get_contents('php://input');
$postData = json_decode($inputEntrada, true);

// Acá iría la validación ¯\_(ツ)_/¯

$query = "INSERT INTO usuarios (NOMBRE, USUARIO, CLAVE)
          VALUES (:NOMBRE, :USUARIO, :CLAVE)";

$stmt = $db->prepare($query);

$exito = $stmt->execute([
    'NOMBRE'        => $postData['NOMBRE'],
    'USUARIO'       => $postData['USUARIO'],
    'CLAVE'         => md5($postData['CLAVE']),
]);

if($exito) {
    echo json_encode([
        'success' => true,
        'msg' => 'Se creó el usuario.'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'msg' => 'Hubo un error al intentar crear el usuario',
        'errors' => [
            'db' => 'Error de inserción en la base de datos.'
        ]
    ]);
}