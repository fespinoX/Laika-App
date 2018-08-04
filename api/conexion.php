<?php
header('Content-Type: application/json; charset=utf-8');

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_base = "LAIKA";
$db_dsn = "mysql:host=$db_host;dbname=$db_base;charset=utf8";

$db = new PDO($db_dsn, $db_user, $db_pass);