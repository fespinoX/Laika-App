<?php

namespace Laika\Controller;


use Laika\Core\View;
use Laika\Model\Autor;

class AutoresApiController
{
    public function listar()
    {
        $autores = Autor::getAll();

        View::renderJson($autores);
    }
}