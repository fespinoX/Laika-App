<?php

namespace Laika\Controller;


use Laika\Core\View;
use Laika\Model\Genero;

class GenerosApiController
{
    public function listar()
    {
        $generos = Genero::getAll();

        View::renderJson($generos);
    }
}