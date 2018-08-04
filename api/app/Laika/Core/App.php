<?php

namespace Laika\Core;

/**
 * Encargada de manejar toda la magia de arranque del sitio.
 *
 * Class App
 * @package Laika\Core
 */
class App
{
    /**
     * @var string La ruta del sitio.
     */
    private static $sitePath;
    /**
     * @var string La ruta a la carpeta app.
     */
    private static $appPath;
    /**
     * @var string La ruta a la carpeta public.
     */
    private static $publicPath;
    /**
     * @var string La ruta a la carpeta views.
     */
    private static $viewsPath;

    /**
     * @var string La url del public.
     */
    private static $url;

    /**
     * @var Route Los datos de la ruta.
     */
    private $route;

    /**
     * App constructor.
     * @param string $sitePath La ruta a la raíz del sitio.
     */
    public function __construct($sitePath)
    {
        self::$sitePath = $sitePath;
        self::$appPath = $sitePath . '/app';
        self::$publicPath = $sitePath . '/public';
        self::$viewsPath = $sitePath . '/views';

        try {
            $this->route = new Route();
            $action = $this->route->getAction();
            self::$url = $this->route->getUrl();
            // Cortamos la parte que nos sirve de la url.
            $posPublic = strpos(self::$url, 'public/') + 6;
            self::$url = substr(self::$url, 0, $posPublic);

            $this->callController($action);
        } catch(\Exception $e) {
            echo "Página 404";
        }
    }

    /**
     * @param string $action  La acción con el formato de "Controller@metodo".
     */
    private function callController($action)
    {
        // Forma más corta y professional.
//        list($controllerName, $method) = explode('@', $action);
        $data = explode('@', $action);
        $controllerName = $data[0];
        $method = $data[1];

        // Agregamos el namespace al controller.
        $controllerName = "\\Laika\\Controller\\" . $controllerName;

        // Instanciamos el controlador.
        $controller = new $controllerName;

        // Llamamos al método del controller.
        $controller->{$method}();
    }

    /**
     * @return string
     */
    public static function getSitePath()
    {
        return self::$sitePath;
    }

    /**
     * @return string
     */
    public static function getAppPath()
    {
        return self::$appPath;
    }

    /**
     * @return string
     */
    public static function getPublicPath()
    {
        return self::$publicPath;
    }

    /**
     * @return string
     */
    public static function getViewsPath()
    {
        return self::$viewsPath;
    }

    /**
     * @return mixed
     */
    public static function getUrl()
    {
        return self::$url;
    }
}