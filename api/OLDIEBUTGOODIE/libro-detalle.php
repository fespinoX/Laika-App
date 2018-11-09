<?php
require 'conexion.php';

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
		WHERE  
		  IDLIBRO = :IDLIBRO";


$stmt = $db->prepare($query);

$stmt->execute([
    'IDLIBRO'           => $_GET['id']
]);

echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));