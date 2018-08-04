<?php

use Laika\Core\Route;

// Route::addRoute('GET', '/', 'HomeController@index');

Route::addRoute('GET', '/libros', 'LibrosApiController@listar');
Route::addRoute('GET', '/libros/{id}', 'LibrosApiController@detallar');
Route::addRoute('POST', '/libros/nuevo', 'LibrosApiController@crear');
Route::addRoute('PUT', '/libros/editar/{id}', 'LibrosApiController@editar');
Route::addRoute('DELETE', '/libros/eliminar/{id}', 'LibrosApiController@eliminar');
Route::addRoute('POST', '/libros/review/{id}', 'LibrosApiController@comentar');


Route::addRoute('GET', '/autores', 'AutoresApiController@listar');
Route::addRoute('GET', '/generos', 'GenerosApiController@listar');


Route::addRoute('POST', '/login', 'AuthApiController@login');
Route::addRoute('POST', '/registro', 'AuthApiController@registro');