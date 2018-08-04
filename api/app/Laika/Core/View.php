<?php

namespace Laika\Core;

/**
 * Maneja la presentación de datos.
 * Class View
 * @package Laika\Core
 */
class View
{
    /**
     * @param string $__vista La vista a imprimir.
     * @param array $__data
     */
    public static function render($__vista, $__data = [])
    {
        // Creamos las variables para la vista
        // en base a los valores provistos en
        // $__data
        foreach ($__data as $__nombre => $__valor) {
            ${$__nombre} = $__valor;
        }

//        require __DIR__ . '/../../../views/' . $__vista . '.php';
        require App::getViewsPath() . '/' . $__vista . '.php';
    }

    /**
     * Renderiza la $data con formato JSON.
     * @param {mixed} $data
     */
    public static function renderJson($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}