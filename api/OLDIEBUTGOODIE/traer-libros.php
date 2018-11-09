<?php
require 'conexion.php';

// $query = "SELECT * FROM libros";

$query = "SELECT
			IDLIBRO,
			TITULO,
			ANIO,
			DESCRIPCION,
			autores.AUTOR AS AUTOR,
			generos.GENERO AS GENERO,
			FKAUTORES,
			FKGENEROS

		FROM libros

		LEFT JOIN autores
			ON libros.FKAUTORES = autores.IDAUTOR
		LEFT JOIN generos
			ON libros.FKGENEROS = generos.IDGENERO
		ORDER BY ANIO";



$stmt = $db->prepare($query);

$stmt->execute();
$losLibros = $stmt->fetchAll(PDO::FETCH_ASSOC);



$queryC = "SELECT
			IDCOMENTARIO,
			COMENTARIO,
			DATE_FORMAT(FECHA, '%d-%m-%Y') AS FECHA,
			DESCRIPCION,
			FKUSUARIOS,
			NOMBRE,
			FKLIBROS

		FROM comentarios

		LEFT JOIN usuarios
			ON comentarios.FKUSUARIOS = usuarios.IDUSUARIO
		LEFT JOIN libros
			ON comentarios.FKLIBROS = libros.IDLIBRO";


$stmtC = $db->prepare($queryC);
$stmtC->execute();

while($row = $stmtC->fetch(PDO::FETCH_ASSOC)) {

    foreach($losLibros as $l => $libro) {
        if($libro['IDLIBRO'] == $row['FKLIBROS']) {
            if(!isset($libro['COMENTARIO'])) {
                $losLibros[$l]['COMENTARIO'] = [];
            }
            $losLibros[$l]['COMENTARIO'][] = $row;
            break;
        }
    }
}

echo json_encode($losLibros);