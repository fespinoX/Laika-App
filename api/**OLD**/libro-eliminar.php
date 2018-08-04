<?php
require 'conexion.php';

$query = "DELETE FROM libros
          WHERE IDLIBRO = ?";

$stmt = $db->prepare($query);

$exito = $stmt->execute([$_GET['id']]);

if($exito) {
    echo json_encode([
        'success' => true,
        'msg' => 'Borraste el libro'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'msg' => 'Hubo un error al intentar eliminar el libro...',
        'errors' => [
            'db' => 'Error de eliminación en la base de datos.'
        ]
    ]);
}