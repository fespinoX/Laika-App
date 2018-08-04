<?php

namespace Laika\Model;
use Laika\DB\DBConnection;

class Autor
{

    /**
     * @var number
     */
    private $idautor;
    /**
     * @var string
     */
    private $autor;


    // Métodos



    /**
     *
     * Setters & Getters de Autor.
     *
     */


    public function setidautorAutor($idautor)
    {
        $this->idautor = $idautor;
    }

    public function getidautorAutor()
    {
        return $this->idautor;
    }

    public function setautorAutor($autor)
    {
        $this->autor = $autor;
    }

    public function getautorAutor()
    {
        return $this->autor;
    }


    /**
     * Constructor de Autor.
     *
     * @param number $id 			El id del autor. Autogenerado.
     * @param string $autor 		El nombre del autor.
     *
     */
    public function loadDataFromArray($fila)
    {
        $this->id 			= $fila['IDAUTOR'];
        $this->autor 		= $fila['AUTOR'];
    }



    /**
     * Retorna los datos de cada autor en un string.
     *
     * @return string
     */


    public static function getAll()
    {
        $db = DBConnection::getConnection();
        $query = "SELECT * FROM autores";

        $stmt = $db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


}