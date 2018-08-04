<?php

namespace Laika\Model;
use Laika\DB\DBConnection;

class Genero
{

    /**
     * @var number
     */
    private $idgenero;
    /**
     * @var string
     */
    private $genero;


    // Métodos



    /**
     *
     * Setters & Getters de Genero.
     *
     */


    public function setidgeneroGenero($idgenero)
    {
        $this->idgenero = $idgenero;
    }

    public function getidgeneroGenero()
    {
        return $this->idgenero;
    }

    public function setgeneroGenero($genero)
    {
        $this->genero = $genero;
    }

    public function getgeneroGenero()
    {
        return $this->genero;
    }


    /**
     * Constructor de Genero.
     *
     * @param number $id 			El id del género. Autogenerado.
     * @param string $genero 		El nombre del género.
     *
     */
    public function loadDataFromArray($fila)
    {
        $this->id 			= $fila['IDGENERO'];
        $this->genero 		= $fila['GENERO'];
    }



    /**
     * Retorna los datos de cada género en un string.
     *
     * @return string
     */


    public static function getAll()
    {
        $db = DBConnection::getConnection();
        $query = "SELECT * FROM generos";

        $stmt = $db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}