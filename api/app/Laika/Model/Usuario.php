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
//                'token' => $token->__toString(),
                        'token' => "" . $token,
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