<?php

namespace Laika\Model;
use Laika\Core\Route;
use Laika\DB\DBConnection;
use Laika\Token\Token;
use PDO;

class Usuario 
{
    /**
     * @var string
     */
    private $token;
	/**
	 * @var number
	 */
	public $idusuario;
	/**
	 * @var string
	 */
	public $nombre;
	/**
    * @var string 
    */
	public $usuario;
		/**
    * @var string 
    */
	public $clave;

	/**
	 * Constructor de Usuario.
	 *
	 * @param string $token      		El token del usuario logueado.
     * @param number $idusuario 		El id del usuario. Autogenerado.
	 * @param string $nombre			El nombre del usuario.
	 * @param string $usuario 			El nombre de usuario.
	 * @param string $clave 			La contraseña del usuario.
	 *
	 */



    /**
     *
     * Setters & Getters de Usuario.
     *
     */

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getIdUsuario()
    {
        return $this->idusuario;
    }

    public function setIdUsuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getUsuario()
    {
        return $this->idusuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function setClave($clave)
    {
        $this->clave = $clave;
    }

    // hace el attempt de loguear al user
	public static function login()
	{

		$db = DBConnection::getConnection();
        $token = new Token();
        $input = file_get_contents('php://input');
        $postData = json_decode($input, true);

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "SELECT * FROM usuarios
        WHERE USUARIO = ?";

        $stmt = $db->prepare($query);

        $stmt->execute([$postData['USUARIO']]);

        if($fila = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            if(md5($postData['CLAVE']) == $fila['CLAVE']) {
                // Creamos el token.
                $token = Token::generateToken($fila['IDUSUARIO']);

                echo json_encode([
                    'success' => true,
                    'msg' => 'Autenticación exitosa! :D',
                    'data' => [
                    //'token' => $token->__toString(),
                        'token' => '' . $token,
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
	}

	public static function registro()
    {
        $db = DBConnection::getConnection();

        $inputEntrada = file_get_contents('php://input');
        $postData = json_decode($inputEntrada, true);

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "INSERT INTO usuarios (NOMBRE, USUARIO, CLAVE)
          VALUES (:NOMBRE, :USUARIO, :CLAVE)";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'NOMBRE'        => $postData['NOMBRE'],
            'USUARIO'       => $postData['USUARIO'],
            'CLAVE'         => md5($postData['CLAVE']),
        ]);

        if($exito) {
            echo json_encode([
                'success' => true,
                'msg' => 'Se creó el usuario.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Hubo un error al intentar crear el usuario',
                'errors' => [
                    'db' => 'Error de inserción en la base de datos.'
                ]
            ]);
        }
    }

    //levanta todos los usuarios
    public static function getAll()
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
        
        $token = $_SERVER['HTTP_X_TOKEN'];
        $idUsuario  = Token::verifyToken($token)['id'];

        $query = "SELECT
            IDUSUARIO,
            NOMBRE
        FROM usuarios WHERE IDUSUARIO != :idUsuario AND IDUSUARIO
            NOT IN ( SELECT FKUSUARIOFAV FROM usuario_has_fav WHERE FKUSUARIO = :idUsuario )";

        $stmt = $db->prepare($query);
        $stmt->execute([
            'idUsuario' => $idUsuario
        ]);
       
        $usuarios = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $usuarios;
    }

    //Agrega un usuario favorito
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

        $query = "INSERT INTO usuario_has_fav (FKUSUARIO, FKUSUARIOFAV) 
          VALUES (:FKUSUARIO, :FKUSUARIOFAV)";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'FKUSUARIO' => $idUsuario,
            'FKUSUARIOFAV'   => $postData['id']
        ]);

        if ($exito) {
            echo json_encode([
                'success' => true,
                'msg' => 'Agregado a tu lista!!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Hubo un error al intentar un astronauta a tu lista',
                'errors' => [
                    'db' => 'Error de inserción en la base de datos.'
                ]
            ]);
        }
    }

    //levanta todos los libros
    public static function deleteFav($id)
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

        $token = $_SERVER['HTTP_X_TOKEN'];
        $idUsuario  = Token::verifyToken($token)['id'];

        // Acá iría la validación ¯\_(ツ)_/¯

        $query = "DELETE FROM usuario_has_fav
          WHERE FKUSUARIO = :FKUSUARIO AND FKUSUARIOFAV = :FKUSUARIOFAV";

        $stmt = $db->prepare($query);

        $exito = $stmt->execute([
            'FKUSUARIO' => $idUsuario,
            'FKUSUARIOFAV'   => $id
        ]);

        if ($exito) {
            echo json_encode([
                'success' => true,
                'msg' => 'Amigo enviado a la estratosfera.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'msg' => 'Hubo un error al eliminar tu amigo...',
                'errors' => [
                    'db' => 'Error de eliminación en la base de datos.'
                ]
            ]);
        }
    }

    // levanta los favoritos del usuario
    public static function getFavs(){

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

        $query = "SELECT * FROM libros WHERE IDLIBRO IN (SELECT FKLIBRO FROM usuario_has_libro WHERE FKUSUARIO = ?)";

        $stmt = $db->prepare($query);

        $stmt->execute([$idUsuario]);

        $usuarioFavs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $usuarioFavs;
    }

    // levanta los friends del usuario
    public static function getFriends(){

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

        $query = "SELECT
        IDUSUARIO,NOMBRE FROM USUARIOS WHERE IDUSUARIO IN (SELECT FKUSUARIOFAV FROM usuario_has_fav
            WHERE FKUSUARIO = ?)";

        $stmt = $db->prepare($query);

        $stmt->execute([$idUsuario]);

        $usuarioFavs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $usuarioFavs;
    }

    //levanta los detalles de un usuario
    public static function detail($id)
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

        $query = "SELECT NOMBRE,USUARIO FROM usuarios WHERE IDUSUARIO = ?";

        $stmt = $db->prepare($query);

        $stmt->execute([$id]);

        $rawDetalle = $stmt->fetch(\PDO::FETCH_ASSOC);

        $usuarioDetalle = [
            'id' => $id,
            'user' => $rawDetalle['USUARIO'],
            'nombre' => $rawDetalle['NOMBRE']
        ];

        $query = "SELECT
        * FROM usuario_has_fav
            WHERE FKUSUARIO = :idUsuario AND FKUSUARIOFAV = :idUsuarioFav";

        $stmt = $db->prepare($query);

        $stmt->execute([
            'idUsuario' => $idUsuario,
            'idUsuarioFav' => $id
        ]);

        $es_amigo = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($es_amigo){
            $usuarioDetalle['is_friend'] = true;
        }

        $query = "SELECT
        * FROM comentarios
            LEFT JOIN libros ON
                libros.IDLIBRO = comentarios.IDCOMENTARIO
            WHERE FKUSUARIOS = ?";

        $stmt = $db->prepare($query);

        $stmt->execute([$id]);

        $usuarioDetalle['COMENTARIOS'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $usuarioDetalle;
    }

    //levanta las notificaciones del usuario logueado
    public static function notificationsGet()
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

        $query = "SELECT
        * FROM notificaciones
            WHERE FKUSUARIO = ? AND LEIDO = 0";

        $stmt = $db->prepare($query);

        $stmt->execute([$idUsuario]);

        $notificaciones = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $notificaciones;
    }

    //marca como leidas las notificaciones del usuario logueado
    public static function notificationsRead($id = -1)
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

        $query = "UPDATE notificaciones SET LEIDO = 1 ";

        if($id != -1){
            $query .= "WHERE IDNOTIFICACION = :IDNOTIFICACION AND FKUSUARIO = :FKUSUARIO";
            $stmt = $db->prepare($query);

            $exito = $stmt->execute([
                'IDNOTIFICACION' => $id,
                'FKUSUARIO' => $idUsuario
            ]);
        } else {
            $query .= "WHERE FKUSUARIO = ?";
            $stmt = $db->prepare($query);
            $exito = $stmt->execute([$idUsuario]);
        }

        if ($exito) {
            echo json_encode([
                'success' => true,
                'msg' => 'Se marco como leido.'
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

 	/**
 	* Levanta la info del user
 	*
 	*/
	public function loadDataFromArray($fila)
	{
		$this->id 		= $fila['IDUSUARIO'];
		$this->user 	= $fila['USER'];
		$this->pass 	= $fila['PASSWORD'];
		$this->nombre 	= $fila['NOMBRE'];
	}
}