angular.module('LKLibros.services')
    /**
     * Este servicio va a proporcionar todo
     * lo referente al manejo de Usuarios.
     * Ej: Creación, edición, etc.
     */
    .service('UsuarioService', [
        '$http',
        'API_SERVER',
        function($http, API_SERVER) {
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

