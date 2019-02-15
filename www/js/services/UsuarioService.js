angular.module('LKLibros.services')
    /**
     * Este servicio va a proporcionar todo
     * lo referente al manejo de Usuarios.
     * Ej: Creación, edición, etc.
     */
    .service('UsuarioService', [
        '$http',
        'API_SERVER',
        'AuthService',
        function($http, API_SERVER,AuthService) {
            console.log('Creando el servicio de Usuarios');

            /**
             * Crear un usuario nuevo.
             *
             * @param {{}} usuarioData
             * @returns {Promise}
             */
            this.create = function(usuarioData) {
                return $http.post(API_SERVER + "/public/registro", usuarioData, {

                });
            };

            /**
             * Traer el detalle del usuario
             *
             * @param string id
             * @returns {Promise}
             */
            this.getById = function(id) {
                return $http.get(API_SERVER + "/public/astronautas/"+id, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Traer las notificaciones
             *
             * @param string id
             * @returns {Promise}
             */
            this.getNotificaciones = function() {
                return $http.get(API_SERVER + "/public/notificaciones", {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Traer las notificaciones
             *
             * @param string id
             * @returns {Promise}
             */
            this.readNotificaciones = function(notificaciones) {
                return $http.put(API_SERVER + "/public/notificaciones/read", {}, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };   

            /**
             * Trae los usuarios
             *
             * @param string id
             * @returns {Promise}
             */
            this.getAstronautas = function() {
                if(AuthService.isLogged()){
                    return $http.get(API_SERVER + "/public/astronautas", {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
                } else {
                    return $http.get(API_SERVER + "/public/astronautas");
                }
            };

            /**
             * Agrega un amigo
             *
             * @param string id
             * @returns {Promise}
             */
            this.agregarAmigos = function(id) {

                var data = {
                    'id': id
                }

                return $http.post(API_SERVER + "/public/astronautas/agregar", data, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Quita un amigo
             *
             * @param string id
             * @returns {Promise}
             */
            this.quitarAmigos = function(id) {

                return $http.delete(API_SERVER + "/public/astronautas/eliminar/" + id, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Trae los amigos
             *
             * @param string id
             * @returns {Promise}
             */
            this.getAmigos = function() {
                return $http.get(API_SERVER + "/public/astronautas/amigos", {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Trae los favs
             *
             * @param string id
             * @returns {Promise}
             */
            this.getFavs = function() {
                return $http.get(API_SERVER + "/public/astronautas/favoritos", {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };



            /**
             * Editar perfil del usuario
             *
             * @param {{}} userData
             * @returns {Promise}
             */
    /*
            this.grabarUsuario = function(userData) {
                return $http.put(API_SERVER + "/perfil-editar.php", perfil, {
                    headers: {
                        'token': AuthService.getToken()
                    }
                });
            };
    */

        }
    ]);

