angular.module('LKLibros.services')
    /**
     * Este servicio va a proporcionar todo
     * lo referente al manejo de Libros.
     * Ej: Lectura, escritura, modificación, baja, etc.
     */
    .service('LibroService', [
        '$http',
        'AuthService',
        'API_SERVER',
        function($http, AuthService, API_SERVER) {
            console.log('Se crea el servicio de Libros.');
            /**
             * Retorna todos los libros
             *
             * @return {Promise}
             */
            this.getAll = function() {
                //return $http.get(API_SERVER + '/traer-libros.php');
                return $http.get(API_SERVER + "/public/libros");
            };

            /**
             * Retorna un Libro por su id.
             *
             * @param {int} id
             * @return {Promise}
             */
            this.getById = function(id) {
                //return $http.get(API_SERVER + '/libro-detalle.php?id=' + id);
                return $http.get(API_SERVER + "/public/libros/" + id);
            };

            /**
             * Crea un libro nuevo.
             * Requiere autenticación, por ende, envía el token en un header.
             *
             * @param {{}} libroData
             * @returns {Promise}
             */
            this.create = function(libroData) {
                //return $http.post(API_SERVER + "/libro-crear.php", libroData, {
                return $http.post(API_SERVER + "/public/libros/nuevo", libroData, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Edita un libro.
             *
             * @param {Number} id
             * @param {{}} libroData
             * @returns {Promise}
             */
            this.update = function(id, libroData) {
                // return $http.put(API_SERVER + "/libro-editar.php?id=" + id, libroData, {
                return $http.put(API_SERVER + "/public/libros/editar/" + id, libroData, {

                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };


            /**
             * Deja un comentario en un libro.
             *
             * @param {Number} id
             * @param {{}} comentario
             * @returns {Promise}
             */

            this.agregarReview = function(comentario, id) {

                //return $http.post(API_SERVER + "/comentario-crear.php?id=" + id, comentario, {
                return $http.post(API_SERVER + "/public/libros/review/" + id, comentario, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Elimina un libro.
             *
             * @param {Number} id
             * @returns {Promise}
             */

            this.delete = function(id) {
                //return $http.delete(API_SERVER + "/libro-eliminar.php?id=" + id);

                return $http.delete(API_SERVER + "/public/libros/eliminar/" + id, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

        }
    ]);