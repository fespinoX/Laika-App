<?php
require 'conexion.php';
require 'token-functions.php';

$token = $_SERVER['HTTP_X_TOKEN'];
$idUsuario  = verifyToken($token)['id'];


if(!verifyToken($token)) {
    echo json_encode([
        "success" => false,
        "msg" => 'Esta acción requiere autenticación.'
    ]);
    die;
}

$inputEntrada  = file_get_contents('php://input');
$postData = json_decode($inputEntrada, true);

// Acá iría la validación ¯\_(ツ)_/¯


$query = "INSERT INTO comentarios (COMENTARIO, FECHA, FKUSUARIOS, FKLIBROS) 
          VALUES (:COMENTARIO, NOW(), :FKUSUARIOS, :FKLIBROS)";

$stmt = $db->prepare($query);

$exito = $stmt->execute([
    'COMENTARIO'    => $postData['COMENTARIO'],
    'FKUSUARIOS'    => $idUsuario,
    'FKLIBROS'      => $_GET['id']
]);

if($exito) {
    echo json_encode([
        'success'   => true,
        'msg'       => 'Dejaste tu review!!'
    ]);
} else {
    echo json_encode([
        'success'   => false,
        'msg'       => 'Hubo un error al intentar agregar la review',
        'errors'    => [
            'db'    => 'Error de inserción en la base de datos.'
        ]
    ]);
}
