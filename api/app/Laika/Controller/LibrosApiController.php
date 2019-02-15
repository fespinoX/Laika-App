<?php

namespace Laika\Controller;


use Laika\Core\View;
use Laika\Model\Libro;
use Laika\Core\Route;

class LibrosApiController
{
    //levanta todos los libros

    public function listar()
    {
        $libros = Libro::getAll();

        View::renderJson($libros);
    }

    //crea un libro nuevo
    public function crear()
    {
        $libros = Libro::create();
    }

    //edita un libro
    public function editar()
    {
        $id = Route::getParams()['id'];
        $libros = Libro::edit($id);
        return $id;
    }

    //borra un libro
    public function eliminar()
    {
        $id = Route::getParams()['id'];
        $libros = Libro::delete($id);
        return $id;
    }

    //trae el detalle de un libro
    public function detallar()
    {
        $id = Route::getParams()['id'];
        $libroDetalle = Libro::getById($id);

        View::renderJson($libroDetalle);
        return $id;
    }

    //agrega un comentario
    public function comentar()
    {
        $id = Route::getParams()['id'];
        $comentario = Libro::agregarReview($id);
    }

    //agrega un comentario
    public function agregarFav()
    {
        $fav = Libro::addFav();
    }

    //borra un libro
    public function eliminarFav()
    {
        $id = Route::getParams()['id'];
        $libros = Libro::deleteFav($id);
        return $id;
    }

}

