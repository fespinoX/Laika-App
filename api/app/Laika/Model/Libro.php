<?php

namespace Laika\Model;
use Laika\Core\Route;
use Laika\DB\DBConnection;
use Laika\Model\Usuario;
use Laika\Token\Token;

class Libro
{

    /**
     * @var number
     */
    private $id;
    /**
     * @var string
     */
    private $titulo;
    /**
     * @var number
     */
    private $anio;
    /**
     * @var string
     */
    private $descripcion;
    /**
     * @var string
     */
    private $autor;
    /**
     * @var string
     */
    private $genero;
    /**
     * @var number
     */
    private $idc;
    /**
     * @var string
     */
    private $comentario;
    /**
     * @var string
     */
    private $fechacomentario;

    public function JSONserialize()
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'anio' => $this->anio,
            'descripcion' => $this->descripcion,
            'autor' => $this->autor,
            'genero' => $this->genero,
            'idc' => $this->idc,
            'comentario' => $this->comentario,
            'fechacomentario' => $this->fechacomentario,
        ];
    }

    /**
     *
     * Setters & Getters de Libro.
     *
     */


    public function setidLibro($id)
    {
        $this->id = $id;
    }

    public function getidLibro()
    {
        return $this->id;
    }

    public function settitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function gettitulo()
    {
        return $this->titulo;
    }

    public function setanio($anio)
    {
        $this->anio = $anio;
    }

    public function getanio()
    {
        return $this->anio;
    }

    public function setdescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getdescripcion()
    {
        return $this->descripcion;
    }

    public function setautorLibro($autor)
    {
        $this->autor = $autor;
    }

    public function getautorLibro()
    {
        return $this->autor;
    }

    public function setgeneroLibro($genero)
    {
        $this->genero = $genero;
    }

    public function getgeneroLibro()
    {
        return $this->genero;
    }

    public function setidComentario($idc)
    {
        $this->idc = $idc;
    }

    public function getidComentario()
    {
        return $this->idc;
    }

    public function setcomentario($comentario)
    {
        $this->comentario = $comentario;
    }

    public function getcomentario()
    {
        return $this->comentario;
    }

    public function setfechacomentario($fechacomentario)
    {
        $this->fechacomentario = $fechacomentario;
    }

    public function getfechacomentario()
    {
        return $this->fechacomentario;
    }


    /**
     * Constructor de Libro.
     *
     * @param number $id El id del libro. Autogenerado.
     * @param string $titulo El título del libro.
     * @param number $anio El año de lanzamiento del libro.
     * @param string $descripcion Descripción corta del libro.
     * @param string $autor El nombre del autor. Tabla autores.
     * @param string $genero El género del libro. Tabla géneros.
     * @param number $idc El id del comentario. Autogenerado.
     * @param string $comentario El comentario del libro.
     * @param string $fechacomentario La fecha del comentario.
     *
     */


    public function loadDataFromArray($fila)
    {
        $this->id = $fila['IDLIBRO'];
        $this->titulo = $fila['TITULO'];
        $this->anio = $fila['ANIO'];
        $this->descripcion = $fila['DESCRIPCION'];
        $this->autor = $fila['AUTOR'];
        $this->genero = $fila['GENERO'];
        $this->idc = $fila['IDCOMENTARIO'];
        $this->comentario = $fila['COMENTARIO'];
        $this->fechacomentario = $fila['FECHA'];
    }


    //levanta todos los libros
    public static function getAll()
    {
        $db = DBConnection::getConnection();

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
        $losLibros = $stmt->fetchAll(\PDO::FETCH_ASSOC);


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

        while ($row = $stmtC->fetch(\PDO::FETCH_ASSOC)) {

            foreach ($losLibros as $l => $libro) {
                if ($libro['IDLIBRO'] == $row['FKLIBROS']) {
                    if (!isset($libro['COMENTARIO'])) {
                        $losLibros[$l]['COMENTARIO'] = [];
                    }
                    $losLibros[$l]['COMENTARIO'][] = $row;
                    break;
                }
            }
        }

        //echo json_encode($losLibros);
        return $losLibros;
    }

    //crea un libro nuevo
    public static function create()
    {
        $db = DBConnection::getConnection();


        $token = $_SERVER['HTTP_X_TOKEN'];

        if (!Token::verifyToken($token)) {
            echo json_encode([
                "success" => false,
                "msg" => 'Esta acción requiere autenticación.'
            ]);
            die;
        }


        $inputEntrada = file_get_contents('php://input');
        $postData = json_decode($inputEntrada, true);

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "INSERT INTO libros (FKGENEROS, FKAUTORES, TITULO, ANIO, DESCRIPCION)
          VALUES (:FKGENEROS, :FKAUTORES, :TITULO, :ANIO, :DESCRIPCION)";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'FKGENEROS'     => $postData['FKGENEROS'],
            'FKAUTORES'     => $postData['FKAUTORES'],
            'TITULO'        => $postData['TITULO'],
            'ANIO'          => $postData['ANIO'],
            'DESCRIPCION'   => $postData['DESCRIPCION'],
        ]);

        if ($exito) {
            echo json_encode([
                'success' => true,
                'msg' => 'Se agregó el libro.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Hubo un error al intentar agregar el libro',
                'errors' => [
                    'db' => 'Error de inserción en la base de datos.'
                ]
            ]);
        }
    }

    //edita un libro
    public static function edit($id)
    {

        $db = DBConnection::getConnection();

        $token = $_SERVER['HTTP_X_TOKEN'];

        if (!Token::verifyToken($token)) {
            echo json_encode([
                "success" => false,
                "msg" => 'Esta acción requiere autenticación.'
            ]);
            die;
        }

        $inputEntrada = file_get_contents('php://input');
        $putData = json_decode($inputEntrada, true);

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "UPDATE libros
           SET    FKGENEROS     = :FKGENEROS,
                  FKAUTORES     = :FKAUTORES,
                  TITULO        = :TITULO,
                  ANIO          = :ANIO,
                  DESCRIPCION   = :DESCRIPCION
           WHERE  IDLIBRO       = :IDLIBRO";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'FKGENEROS'     => $putData['FKGENEROS'],
            'FKAUTORES'     => $putData['FKAUTORES'],
            'TITULO'        => $putData['TITULO'],
            'ANIO'          => $putData['ANIO'],
            'DESCRIPCION'   => $putData['DESCRIPCION'],
            'IDLIBRO'       => $id
        ]);

        if ($exito) {
            echo json_encode([
                'success' => true,
                'msg' => 'Se actualizó el libro.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Oh noo! No se pudo actualizar el libro...',
                'errors' => [
                    'db' => 'Error de actualización en la base de datos.'
                ]
            ]);
        }
    }

    //borra un libro
    public static function delete($id)
    {

        $db = DBConnection::getConnection();

        $token = $_SERVER['HTTP_X_TOKEN'];

        if (!Token::verifyToken($token)) {
            echo json_encode([
                "success" => false,
                "msg" => 'Esta acción requiere autenticación.'
            ]);
            die;
        }

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "DELETE FROM libros
          WHERE IDLIBRO = ?";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([$id]);

        if ($exito) {
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
    }

    //levanta la info de un solo libro
    public static function getById($id)
    {
        $db = DBConnection::getConnection();

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
		  IDLIBRO = ?";


        $stmt = $db->prepare($query);

        $stmt->execute([$id]);

        // echo json_encode($stmt->fetch(\PDO::FETCH_ASSOC));
        $libroDetalle = $stmt->fetch(\PDO::FETCH_ASSOC);

        if(isset($_SERVER['HTTP_X_TOKEN'])){

            $token = $_SERVER['HTTP_X_TOKEN'];
            $idUsuario  = Token::verifyToken($token)['id'];

            if (!Token::verifyToken($token)) {
                echo json_encode([
                    "success" => false,
                    "msg" => 'Esta acción requiere autenticación.'
                ]);
                die;
            }

            // Chequeamos si el usuario lo tiene como favorito.
            $query = "SELECT
                FKLIBRO 
            FROM usuario_has_libro
            WHERE  
              FKLIBRO = :idLibro AND FKUSUARIO = :idUsuario";


            $stmt = $db->prepare($query);

            $stmt->execute([
                'idLibro' => $id,
                'idUsuario' => $idUsuario
            ]);

            $existe = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($existe){
                $libroDetalle['is_fav'] = true;
            }

        }

        return $libroDetalle;
    }

    //crea un comentario
    public static function agregarReview($id)
    {

        $db = DBConnection::getConnection();

        $token = $_SERVER['HTTP_X_TOKEN'];
        $idUsuario  = Token::verifyToken($token)['id'];

        if (!Token::verifyToken($token)) {
            echo json_encode([
                "success" => false,
                "msg" => 'Esta acción requiere autenticación.'
            ]);
            die;
        }

        $inputEntrada = file_get_contents('php://input');
        $postData = json_decode($inputEntrada, true);

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "INSERT INTO comentarios (COMENTARIO, FECHA, FKUSUARIOS, FKLIBROS) 
          VALUES (:COMENTARIO, NOW(), :FKUSUARIOS, :FKLIBROS)";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'COMENTARIO' => $postData['COMENTARIO'],
            'FKUSUARIOS' => $idUsuario,
            'FKLIBROS'   => $id
        ]);

        if ($exito) {

            $query = "SELECT usuario_has_libro.FKUSUARIO, libros.titulo
                FROM usuario_has_fav,usuario_has_libro
                LEFT JOIN libros ON :id_libro = libros.IDLIBRO
                WHERE FKLIBRO = :id_libro OR usuario_has_fav.FKUSUARIOFAV = :id_user";

            $stmt = $db->prepare($query);
            $stmt->execute([
                'id_libro' => $id,
                'id_user' => $idUsuario
            ]);
            $favs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($favs as $notificacion) {  
                $query = "INSERT INTO notificaciones (FKUSUARIO, FKLIBRO, NOTIFICACION)
                  VALUES (:FKUSUARIO, :FKLIBRO,:NOTIFICACION)";

                $stmt = $db->prepare($query);

                $exito = $stmt->execute([
                    'FKUSUARIO' => $idUsuario,
                    'FKLIBRO'   => $id,
                    'NOTIFICACION' => Usuario::detail($idUsuario)['nombre'].' ha dejado una review del libro "'.$notificacion['titulo'].'"'
                ]);

                if(!$exito){
                    var_dump($stmt->errorInfo());
                }
            }
        }

        if($exito){
            echo json_encode([
                'success' => true,
                'msg' => 'Dejaste tu review!!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Hubo un error al intentar agregar la review',
                'errors' => [
                    'db' => 'Error de inserción en la base de datos.'
                ]
            ]);
        }
    }

    //crea un favorito
    public static function addFav()
    {
        $db = DBConnection::getConnection();

        $token = $_SERVER['HTTP_X_TOKEN'];
        $idUsuario  = Token::verifyToken($token)['id'];

        if (!Token::verifyToken($token)) {
            echo json_encode([
                "success" => false,
                "msg" => 'Esta acción requiere autenticación.'
            ]);
            die;
        }

        $inputEntrada = file_get_contents('php://input');
        $postData = json_decode($inputEntrada, true);

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "INSERT INTO usuario_has_libro (FKUSUARIO, FKLIBRO) 
          VALUES (:FKUSUARIO, :FKLIBRO)";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'FKUSUARIO' => $idUsuario,
            'FKLIBRO'   => $postData['id']
        ]);

        if ($exito) {
            echo json_encode([
                'success' => true,
                'msg' => 'Agregado a tu lista!!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Hubo un error al intentar agregar un libro favorito',
                'errors' => [
                    'db' => 'Error de inserción en la base de datos.'
                ]
            ]);
        }
    }

    //elimina un favorito
    public static function deleteFav($id)
    {
        $db = DBConnection::getConnection();

        $token = $_SERVER['HTTP_X_TOKEN'];
        $idUsuario  = Token::verifyToken($token)['id'];

        if (!Token::verifyToken($token)) {
            echo json_encode([
                "success" => false,
                "msg" => 'Esta acción requiere autenticación.'
            ]);
            die;
        }
        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "DELETE FROM usuario_has_libro
          WHERE FKLIBRO = :idLibro AND FKUSUARIO = :idUsuario";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'idLibro'   => $id,
            'idUsuario' => $idUsuario
        ]);

        if ($stmt->rowCount()) {
            echo json_encode([
                'success' => true,
                'msg' => 'Se quito el libro de tus favoritos'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Hubo un error al intentar eliminar un libro favorito',
                'errors' => [
                    'db' => 'Error de inserción en la base de datos.'
                ]
            ]);
        }
    }

}