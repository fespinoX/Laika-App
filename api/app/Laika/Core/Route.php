<?php

namespace Laika\Core;

class Route
{
    /** @var array Contiene las rutas de la aplicación. */
    private static $routes = [
        /*'peliculas' => 'Pelicula@listar',
        'peliculas/crear' => 'Pelicula@mostrarForm',
        'peliculas/{id}' => 'Pelicula@ver',
        'peliculas/{id}/eliminar' => 'Pelicula@eliminar',
        'peliculas/{id}/editar' => 'Pelicula@editar',
        'peliculas/{id}/editar/{otro}/saraza' => 'Pelicula@saraza',*/
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
    ];

    /**
     * @var string
     */
    private $url;
    /**
     * @var string  La ruta.
     */
    private $route;
    /**
     * @var string  La acción.
     */
    private $action;
    /**
     * @var string  El verbo de la petición (GET, POST, PUT, DELETE).
     */
    private $method;

    /** @var array Los parámetros de la URL, en caso de existir. */
    private static $params = [];

    /**
     * Route constructor.
     * Obtiene los datos de la url, y verifica
     * si la ruta existe.
     */
    public function __construct()
    {
        // http://localhost/santiago/sitio/public/
        // http://localhost/santiago/sitio/public/peliculas
        // Obtenemos la url.
        $protocol   = $_SERVER['REQUEST_SCHEME'];
        $server     = $_SERVER['SERVER_NAME'];
        $url        = $_SERVER['REQUEST_URI'];
        $this->url  = $protocol . "://" . $server . $url;
        $indexPath = $protocol . "://" . $server . $_SERVER['PHP_SELF'];
        $indexPath = substr($indexPath, 0, -10);
        $this->route = str_replace($indexPath, '', $this->url);

        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->action =  $this->parseUrl($this->route);
    }

    /**
     * La acción a ejecutar.
     *
     * @param string $url
     * @return bool|string
     * @throws \Exception
     */
    public function parseUrl($url)
    {
        // Verificamos si la url existe
        // tal cual está.
        if(isset(self::$routes[$this->method][$url])) {
            return self::$routes[$this->method][$url];
        }

        // Buscamos si la url coincide con
        // alguna de las rutas con
        // parámetros.
        $route = $this->getParameterizedRoute($url);

        if($route) {
            return $route;
        }

        throw new \Exception('La url ' . $url . " no existe.");
    }

    public function getParameterizedRoute($url)
    {
        // Explotamos y obtenemos las
        // partes de la url.
        $urlParts = explode('/', $url);

        // Recorremos todas nuestras rutas.
        foreach (self::$routes[$this->method] as $route => $action) {
            // Explotamos la ruta.
            $routeParts = explode('/', $route);

            if(count($urlParts) === count($routeParts)) {
                // Recorremos las partes.
                if($this->checkParts($urlParts, $routeParts)) {
                    return $action;
                }
            } else {
                // La ruta no matcheó.
                //echo "La cantidad de partes NO coincide... :(<br>";
            }
        }

        return false;
    }

    public function checkParts($urlParts, $routeParts)
    {
        // Recorremos las partes.
        foreach ($urlParts as $key => $value) {
            // Definimos una variable temporal para
            // almacenar los parámetros de la url.
            $params = [];

            // Tres posibilidades:
            // 1. Que las partes sean iguales
            //  ej: "peliculas" y "peliculas"
            // 2. Que la parte de la ruta sea
            //  un parámetro. Ej: "1" y
            //  "{id}"
            // 3. Que no coincidan en ningún
            //  aspecto.

            // Verificamos 1.
            if($urlParts[$key] !== $routeParts[$key]) {
                // Verificamos 2.
                $posLlaveApertura = strpos($routeParts[$key], "{");

                // Si no tiene una llave, entonces
                // retornamos false.
                if($posLlaveApertura !== 0) {
                    // Si UNA de las partes
                    // de la ruta no coincide
                    // entonces ya informamos
                    // que esta ruta no es.
                    return false;
                }

                // Si sí tiene una llave, entonces
                // es un parámetro! :D
                // Vamos a obtener el nombre del
                // mismo, y guardar su valor en
                // el array.
                // Quitamos del nombre del
                // parámetro el primer caracter
                // ("{") y el último ("}").
                $nombreParam = substr($routeParts[$key], 1, -1);

                // Guardamos su valor con el
                // nombre.
                $params[$nombreParam] = $urlParts[$key];
            }
        }

        // Si la ruta coincidióm guardamos los
        // parámetros en la propiedad de la clase,
        // y retornamos true.
        self::$params = $params;

        return true;
    }

    /**
     * Registra una ruta en el sitio.
     *
     * @param string $verb      Es el método/verbo de la petición: 'GET', 'POST', 'PUT', 'DELETE'.
     * @param string $url       La ruta a registrar.
     * @param string $action    El método del controlador que se tiene que ejecutar. Sintaxis: Controller@metodo
     */
    public static function addRoute($verb, $url, $action)
    {
        $verb = strtoupper($verb);
        self::$routes[$verb][$url] = $action;
    }

    /**
     * @return array
     */
    public static function getParams()
    {
        return self::$params;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}