<?php
require 'conexion.php';

$query = "SELECT * FROM generos";

$stmt = $db->prepare($query);

$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));