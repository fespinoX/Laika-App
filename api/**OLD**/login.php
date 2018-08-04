<?php
//para levantar con PHP:
//require 'vendor/autoload.php';

require 'token-functions.php';

require 'conexion.php';

$input = file_get_contents('php://input');
$postData = json_decode($input, true);

// Acá iría la validación ¯\_(ツ)_/¯

$query = "SELECT * FROM usuarios
        WHERE USUARIO = ?";

$stmt = $db->prepare($query);

$stmt->execute([$postData['USUARIO']]);

if($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // TODO: Agregar password_verify
    if(md5($postData['CLAVE']) == $fila['CLAVE']) {
        // Creamos el token.
        $token = generateToken($fila['IDUSUARIO']);

        echo json_encode([
            'success' => true,
            'msg' => 'Autenticación exitosa! :D',
            'data' => [
//                'token' => $token->__toString(),
                'token' => "" . $token,
                'user' => $fila['USUARIO'],
                'nombre' => $fila['NOMBRE'],
            ]
        ]);
        exit;
    }
}

echo json_encode([
    'success' => false,
    'msg' => 'Email y/o password incorrectos...',
    'errors' => [
        'user' => 'Email y/o password incorrectos.',
    ]
]);