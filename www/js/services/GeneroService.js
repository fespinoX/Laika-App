angular.module('LKLibros.services')
    /**
     * Este servicio va a proporcionar todo
     * lo referente al manejo de Géneros.
     * Ej: Lectura, escritura, modificación, baja, etc.
     */
    .service('GeneroService', [
        '$http',
        'API_SERVER',
        function($http, API_SERVER) {
            console.log('Se crea el servicio de Géneros.');
            /**
             * Retorna todas los géneros
             *
             * @return {Promise}
             */
            this.getAll = function() {
                //return $http.get(API_SERVER + '/traer-generos.php');
                return $http.get(API_SERVER + "/public/generos");
            };
        }
    ]);