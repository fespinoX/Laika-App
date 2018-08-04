<?php

namespace Laika\Controller;


use Laika\Core\View;
use Laika\Model\Usuario;
use Laika\Core\Route;
use Laika\Core\App;


class AuthApiController
{

    public function login() {
        $login = Usuario::login();
    }

    public function registro() {
        $registro = Usuario::registro();
    }

}