<?php
// Agregamos el autoload de Composer.
require 'vendor/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

/**
 * @param $id
 * @return \Lcobucci\JWT\Token
 */
function generateToken($id)
{
    $signer = new Sha256();
    $secret = "cvbhsdifhefuiasghduha";

    $builder = new Builder();

    $builder->setIssuer('http://ohno.com.ar')
        ->setExpiration(time() + 60 * 60 * 24)
        ->set('id', $id)
        ->sign($signer, $secret);

    return $builder->getToken();
}

/**
 * Verifica que el token sea correcto.
 * Retorna un array con los datos de ser válido, false de lo contrario.
 *
 * @param string $token
 * @return array|bool
 */
function verifyToken($token)
{
    if(!is_string($token)) {
        return false;
    }

    $signer = new Sha256();
    $secret = "cvbhsdifhefuiasghduha";

    $parser = new Parser();
    $tokenParseado = $parser->parse($token);

    $data = new ValidationData();
    $data->setIssuer('http://ohno.com.ar');

    // Verificamos si el token es correcto o no.
    if($tokenParseado->validate($data) && $tokenParseado->verify($signer, $secret)) {
//        return true;
        return [
            'id' => $tokenParseado->getClaim('id')
        ];
    } else {
        return false;
    }
}