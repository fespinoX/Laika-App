<?php

namespace Laika\Controller;


use Laika\Core\View;
use Laika\Model\Usuario;
use Laika\Core\Route;

class AstronautasApiController
{
    //levanta todos los libros

    public function listar()
    {
        $astronautas = Usuario::getAll();

        View::renderJson($astronautas);
    }

    public function amigos()
    {
        $astronautas = Usuario::getFriends();

        View::renderJson($astronautas);
    }

    public function favoritos()
    {
        $libros = Usuario::getFavs();

        View::renderJson($libros);
    }

    //agrega un usuario a mis favoritos
    public function agregar()
    {
        $agregar = Usuario::addFav();
        return;
    }

    //elimina un usuario de mis favoritos
    public function eliminar()
    {
        $id = Route::getParams()['id'];
        $astronautas = Usuario::deleteFav($id);
        return;
    }

    //trae el detalle de un usuario
    public function detalle()
    {
        $id = Route::getParams()['id'];
        //var_dump($id);
        $usuarioDetalle = Usuario::detail($id);
        View::renderJson($usuarioDetalle);
    }

    //trae las notificaciones
    public function getNotificaciones()
    {
        $notificaciones = Usuario::notificationsGet();

        View::renderJson($notificaciones);
    }

    //trae las notificaciones
    public function readNotificaciones()
    {
        if(isset(Route::getParams()['id'])){
            $id = Route::getParams()['id'];
            $notificaciones = Usuario::notificationsRead($id);
        } else {
            $notificaciones = Usuario::notificationsRead();
        }
        return;
    }

}

