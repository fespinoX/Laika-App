<?php

require 'conexion.php';
require 'token-functions.php';

$token = $_SERVER['HTTP_TOKEN'];
$id = verifyToken($token)['id'];

$inputEntrada = file_get_contents('php://input');
$putData = json_decode($inputEntrada, true);

// Acá iría la validación... ¯\_(ツ)_/¯

$query = "UPDATE    usuarios 
          SET       NOMBRE = :NOMBRE, 
                    CLAVE = :CLAVE, 
          WHERE     ID_USUARIO = :id";

$stmt = $db->prepare($query);

$exito = $stmt->execute([
    'NOMBRE'        => $dataPost['NOMBRE'],
    'CLAVE'    	    => $dataPost['CLAVE'],
    'id'            => $id
]);

if($exito) {
    echo json_encode([
        'success'   => true,
        'msg'       => 'Tu perfil ha sido actualizado',
        'data'      => [
            'NOMBRE'        => $dataPost['NOMBRE'],
            'CLAVE'         => $dataPost['CLAVE'],
            'id'            => $id
        ]
    ]);
} else {
    echo json_encode([
        'success'   => false,
        'msg' => 'Oh noo! No se pudo actualizar el libro...',
        'errors'    => [
            'db'    => 'Error al intentar actualizar la base de datos'
        ]
    ]);
}