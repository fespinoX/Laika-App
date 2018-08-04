angular.module('LKLibros.services')
    /**
     * Este servicio va a proporcionar todo
     * lo referente al manejo de Autores.
     * Ej: Lectura, escritura, modificación, baja, etc.
     */
    .service('AutorService', [
        '$http',
        'API_SERVER',
        function($http, API_SERVER) {
            console.log('Se crea el servicio de Autores.');
            /**
             * Retorna todas los autores
             *
             * @return {Promise}
             */
            this.getAll = function() {
                //return $http.get(API_SERVER + '/traer-autores.php');
                return $http.get(API_SERVER + "/public/autores");
            };
        }
    ]);