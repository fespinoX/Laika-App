angular.module('LKLibros.services')
    /**
     * Servicio que adminitra la autenticación.
     */
    .service('AuthService', [
        '$http',
        'StorageService',
        'API_SERVER',
        function($http, StorageService, API_SERVER) {
            console.log('Se crea el servicio de Autenticación.');
            var token = null;
            var userData = null;

            if(StorageService.has('token')) {
                token = StorageService.get('token');
                userData = StorageService.get('userData');
            }

            /**
             * Se hace el attempt de logueo.
             *
             * @param {{}} user
             * @returns {string}
             */
            this.login = function(user) {
                return $http.post(API_SERVER + "/public/login", user).then(
                    function(rta) {
                        var info = rta.data;
                        console.log(rta.data);
                        if(info.success) {
                            token = info.data.token;
                            userData = {
                                nombre: info.data.nombre,
                                user: info.data.user
                            };

                            // Se guarda en el local storage para que no se desloguee cuando se refreshee:
                            StorageService.set('token', token);
                            StorageService.set('userData', userData);
                        }
                        return rta;
                    }
                );
            };

            /**
             * Se hace un return en base al resultado del logueo.
             *
             * @returns {boolean}
             */
            this.isLogged = function() {
                return token !== null;
            };

            /**
             * Return del token.
             *
             * @returns {string}
             */
            this.getToken = function() {
                return token;
            };

            this.updateUserData = function(data) {
                StorageService.set('userData', data);
                userData = data;
            }
        }
    ]);