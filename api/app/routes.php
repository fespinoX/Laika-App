<?php

use Laika\Core\Route;

// Route::addRoute('GET', '/', 'HomeController@index');

Route::addRoute('GET', '/libros', 'LibrosApiController@listar');
Route::addRoute('GET', '/libros/{id}', 'LibrosApiController@detallar');
Route::addRoute('POST','/libros/nuevo', 'LibrosApiController@crear');
Route::addRoute('PUT', '/libros/editar/{id}', 'LibrosApiController@editar');
Route::addRoute('DELETE', '/libros/eliminar/{id}', 'LibrosApiController@eliminar');
Route::addRoute('POST', '/libros/review/{id}', 'LibrosApiController@comentar');

Route::addRoute('POST', '/libros/favoritos', 'LibrosApiController@agregarFav');
Route::addRoute('DELETE', '/libros/favoritos/{id}', 'LibrosApiController@eliminarFav');

Route::addRoute('GET', '/astronautas', 'AstronautasApiController@listar');
Route::addRoute('GET', '/astronautas/amigos', 'AstronautasApiController@amigos');
Route::addRoute('GET', '/astronautas/favoritos', 'AstronautasApiController@favoritos');
Route::addRoute('POST', '/astronautas/agregar', 'AstronautasApiController@agregar');
Route::addRoute('DELETE', '/astronautas/eliminar/{id}', 'AstronautasApiController@eliminar');
Route::addRoute('GET', '/astronautas/{id}', 'AstronautasApiController@detalle');


Route::addRoute('GET', '/autores', 'AutoresApiController@listar');
Route::addRoute('GET', '/generos', 'GenerosApiController@listar');

Route::addRoute('POST', '/login', 'AuthApiController@login');
Route::addRoute('POST', '/registro', 'AuthApiController@registro');

Route::addRoute('GET', '/notificaciones', 'AstronautasApiController@getNotificaciones');
Route::addRoute('PUT', '/notificaciones/read', 'AstronautasApiController@readNotificaciones');
Route::addRoute('PUT', '/notificaciones/read/{id}', 'AstronautasApiController@readNotificaciones');