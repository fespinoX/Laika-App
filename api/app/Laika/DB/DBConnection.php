<?php
namespace Laika\DB;
use PDO;

/**
 * Clase de conexión con la base en modo Singleton.
 */
class DBConnection
{
    /** @var PDO 	La conexión a la base */
    private static $db;

    /**
     * Método constructor.
     * Lo hacemos privado para poder garantizar el
     * Singleton.
     */
    private function __construct()
    {}

    /**
     * Retorna una instancia de PDO manteniéndola
     * en modo Singleton.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        // Si no tenemos todavía una conexión,
        // entonces la instanciamos.
        if(DBConnection::$db === null) {
            $db_host = "localhost";
            $db_user = "root";
            $db_pass = "";
            $db_base = "LAIKA";
            $db_dsn = "mysql:host=$db_host;dbname=$db_base;charset=utf8";
            DBConnection::$db = new PDO($db_dsn, $db_user, $db_pass);
        }

        // Retornamos la conexión.
        return DBConnection::$db;
    }
}