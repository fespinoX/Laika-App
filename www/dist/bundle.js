// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('LKLibros', ['ionic', 'LKLibros.controllers', 'LKLibros.services'])

    .run(function($ionicPlatform, $rootScope, $ionicPopup, $state, AuthService) {
        $ionicPlatform.ready(function() {
            // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
            // for form inputs)
            if (window.cordova && window.cordova.plugins && window.cordova.plugins.Keyboard) {
                cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
                cordova.plugins.Keyboard.disableScroll(true);

            }
            if (window.StatusBar) {
                StatusBar.styleDefault();
            }
        });

        $rootScope.$on('$stateChangeStart', function(ev, state) {
            console.log("Cambio de vista");

            //checkeamos si puse que la ruta necesitaba estar logueado
            if(state.data !== undefined && state.data.requireAuth === true) {
                // La vista requiere estar logueado entonces checkeo si el usuario está logueado
                if(!AuthService.isLogged()) {
                    ev.preventDefault();
                    $ionicPopup.alert({
                        title: 'No no no...',
                        template: 'No pusiste las credenciales mágicas...',
                        okText: 'OK'
                    }).then(
                        function () {
                            $state.go('login');
                        }
                    );
                    //TODO: acá iría el redirect al login

                }
            }
        });
    })

    .config(function($stateProvider, $urlRouterProvider) {

        $stateProvider
            .state('tab', {
                url: '/tab',
                abstract: true,
                templateUrl: 'templates/tabs.html'
            })
            .state('tab.home', {
                url: '/home',
                views: {
                    'tab-home': {
                        templateUrl: 'templates/tab-home.html',
                        controller: 'HomeCtrl'
                    }
                }
            })

            .state('tab.libros', {
                url: '/libros',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-libros': {
                        templateUrl: 'templates/tab-libros.html',
                        controller: 'LibrosCtrl'
                    }
                }
            })

            .state('tab.libro-nuevo', {
                url: '/libros/nuevo',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-libros': {
                        templateUrl: 'templates/tab-libros-nuevo.html',
                        controller: 'LibroNuevoCtrl'
                    }
                }
            })

            .state('tab.libro-detalle', {
                url: '/libros/:id',
                views: {
                    'tab-libros': {
                        templateUrl: 'templates/tab-libro-detalle.html',
                        controller: 'LibroDetalleCtrl'
                    }
                }
            })

            .state('tab.libro-editar', {
                url: '/libros/:id/editar',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-libros': {
                        templateUrl: 'templates/tab-libros-editar.html',
                        controller: 'LibroEditarCtrl'
                    }
                }
            })

            .state('tab.comentario-nuevo', {
                url: '/libros/:id/nuevareview',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-libros': {
                        templateUrl: 'templates/tab-comentario-nuevo.html',
                        controller: 'ComentarioNuevoCtrl'
                    }
                }
            })

            .state('tab.perfil', {
                url: '/perfil',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-perfil': {
                        templateUrl: 'templates/tab-perfil.html',
                        controller: 'PerfilCtrl'
                    }
                }
            })

            .state('tab.amigos', {
                url: '/perfil/amigos',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-perfil': {
                        templateUrl: 'templates/tab-amigos.html',
                        controller: 'AmigosCtrl'
                    }
                }
            })

            .state('tab.favoritos', {
                url: '/perfil/favoritos',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-perfil': {
                        templateUrl: 'templates/tab-favoritos.html',
                        controller: 'FavoritosCtrl'
                    }
                }
            })

            .state('tab.perfil-detalle', {
                url: '/perfil/:id',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-perfil': {
                        templateUrl: 'templates/tab-perfil.html',
                        controller: 'PerfilCtrl'
                    }
                }
            })

            .state('tab.orbita', {
                url: '/orbita',
                data: {
                    requireAuth: true
                },
                views: {
                    'tab-perfil': {
                        templateUrl: 'templates/tab-orbita.html',
                        controller: 'OrbitaCtrl'
                    }
                }
            })

            .state('usuario-nuevo', {
                url: '/usuario-nuevo',
                templateUrl: 'templates/tab-usuario-nuevo.html',
                controller: 'UsuarioNuevoCtrl'
            })

            .state('login', {
                url: '/login',
                templateUrl: 'templates/tab-login.html',
                controller: 'LoginCtrl'

            });

        // esta es la tab que se muestra por default:
        $urlRouterProvider.otherwise('/tab/home');

    })
    .constant('API_SERVER', '../api');

angular.module('LKLibros.controllers', [])

.controller('AccountCtrl', function($scope) {
  $scope.settings = {
    enableFriends: true
  };
});

angular.module('LKLibros.services', []);

angular
    .module('LKLibros.controllers')
    .controller(
        'AmigosCtrl',
        [
            '$scope',
            'UsuarioService',
            function($scope, UsuarioService) {
                $scope.$on('$ionicView.enter', function() {
                    cargarAmigos();
                });


                function cargarAmigos() {
                    UsuarioService.getAmigos().then(
                        function(response){
                            $scope.amigos = response.data;
                        }
                    );
                }
            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'ComentarioNuevoCtrl',
        [
            '$scope',
            '$state',
            '$ionicPopup',
            '$stateParams',
            'LibroService',
            function($scope, $state, $ionicPopup, $stateParams, LibroService) {


                $scope.$on('$ionicView.enter', function() {
                    $scope.getOne();
                });

                $scope.getOne = function() {
                    LibroService.getById($stateParams.id).then(function(rta) {
                        $scope.libro = rta.data;
                    });
                }


                $scope.agregarReview = function(comentario) {

                    var idLibro = $stateParams.id;

                    LibroService.agregarReview(comentario, idLibro).then(function(rta) {

                        var info = rta.data;

                        if(info.success) {
                            $ionicPopup.alert({
                                template: info.msg,
                                okText: 'Ok'
                            });

                            $state.go('tab.libros');
                        } else {
                            $ionicPopup.alert({
                                title: "Error",
                                template: info.msg,
                                okText: 'Ok'
                            });
                        }
                    });
                }
            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'FavoritosCtrl',
        [
            '$scope',
            'UsuarioService',
            function($scope, UsuarioService) {
                $scope.$on('$ionicView.enter', function() {
                    cargarLibros();
                });


                function cargarLibros() {
                    UsuarioService.getFavs().then(
                        function(response){
                            $scope.favoritos = response.data;
                        }
                    );
                }
            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'HomeCtrl',
        function($scope) {
            // TODO: algo
        }
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'LibroDetalleCtrl',
        [
            '$scope',
            '$state',
            '$stateParams',
            '$ionicPopup',
            'LibroService',
            function($scope, $state, $stateParams, $ionicPopup, LibroService) {
                LibroService.getById($stateParams.id).then(
                    function(response) {
                        $scope.libro = response.data;
                    },
                    function(errorData) {
                        // Reject block
                        console.log("Uy! Hubo un error!", errorData);
                    }
                );

                $scope.agregarFavs = function() {

                    var idLibro = $stateParams.id;

                    LibroService.agregarFavorito(idLibro).then(function(rta) {

                        var info = rta.data;

                        if(info.success) {
                            $scope.libro.is_fav = true;
                            $ionicPopup.alert({
                                template: info.msg,
                                okText: 'Ok'
                            });
                        } else {
                            $ionicPopup.alert({
                                title: "Error",
                                template: info.msg,
                                okText: 'Ok'
                            });
                        }
                    });
                }

                $scope.eliminarFavs = function() {

                    var idLibro = $stateParams.id;

                    LibroService.eliminarFavorito(idLibro).then(function(rta) {

                        var info = rta.data;

                        if(info.success) {
                            $scope.libro.is_fav = false;
                            $ionicPopup.alert({
                                template: info.msg,
                                okText: 'Ok'
                            });
                        } else {
                            $ionicPopup.alert({
                                title: "Error",
                                template: info.msg,
                                okText: 'Ok'
                            });
                        }
                    });
                }

                $scope.eliminar = function (id) {
                    var popup = $ionicPopup.confirm({
                        title: "Cuidado, astronauta!",
                        template: "Vas a mandar el libro a la estratósfera.. estás segurx?",
                        okText: "OK",
                        cancelText: "NOOOOO"
                    });

                    popup.then(
                        function(confirmar) {
                            if(confirmar) {
                                ejecutarEliminar(id);
                            }
                        }
                    );

                    function ejecutarEliminar(id) {
                        LibroService.delete(id).then(function(rta) {
                            if(rta.data.success) {
                                $ionicPopup.alert({
                                    title: "YAY!",
                                    template: rta.data.msg,
                                    okText: 'OK'
                                }).then(function() {
                                    $state.go('tab.libros');
                                });
                            }
                        });
                    }
                };

            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'LibroEditarCtrl',
        [
            '$scope',
            '$state',
            // $state cambia las rutas
            '$stateParams',
            '$ionicPopup',
            'LibroService',
            'GeneroService',
            'AutorService',
            function($scope, $state, $stateParams, $ionicPopup, LibroService, GeneroService, AutorService) {
                $scope.generos = [];
                $scope.autores = [];
                $scope.libro = {
                    FKGENEROS: '0',
                    FKAUTORES: '0'
                };

                // Levanta los datos del libro
                LibroService.getById($stateParams.id).then(function(rta) {
                    $scope.libro = rta.data;
                });

                // Levanta los géneros.
                GeneroService.getAll().then(function(rta){
                    $scope.generos = rta.data;
                    $scope.generos.unshift({
                        'IDGENERO': '0',
                        'GENERO': 'Elegí un género'
                    });
                    console.log($scope.generos);
                });

                // Levanta los autores.
                AutorService.getAll().then(function(rta){
                    $scope.autores = rta.data;
                    $scope.autores.unshift({
                        'IDAUTOR': '0',
                        'AUTOR': 'Elegí un autor'
                    });
                    console.log($scope.autores);
                });

                // Guarda el libro
                $scope.grabar = function(libroData) {
                    LibroService.update(libroData.IDLIBRO, libroData).then(
                        function(response) {
                            console.log(response.data);
                            $ionicPopup.alert({
                                title: 'Hecho!',
                                okText: 'OK',
                                template: response.data.msg
                            }).then(function() {
                                $state.go('tab.libros');
                            });
                        }
                    );
                }
            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'LibroNuevoCtrl',
        [
            '$scope',
            '$state',
            '$ionicPopup',
            'LibroService',
            'GeneroService',
            'AutorService',
            function($scope, $state, $ionicPopup, LibroService, GeneroService, AutorService) {
                $scope.generos = [];
                $scope.autores = [];
                $scope.libro = {
                    FKGENEROS: '0',
                    FKAUTORES: '0'
                };

                // Levanta los géneros.
                GeneroService.getAll().then(function(rta){
                    $scope.generos = rta.data;
                    $scope.generos.unshift({
                        'IDGENERO': '0',
                        'GENERO': 'Elegí un género'
                    });
                    console.log($scope.generos);
                });

                // Levanta los autores.
                AutorService.getAll().then(function(rta){
                    $scope.autores = rta.data;
                    $scope.autores.unshift({
                        'IDAUTOR': '0',
                        'AUTOR': 'Elegí un autor'
                    });
                    console.log($scope.autores);
                });

                // Guarda el libro
                $scope.grabar = function(libroData) {
                    LibroService.create(libroData).then(
                        function(response) {
                            console.log(response.data);
                            $ionicPopup.alert({
                                title: 'YAY!',
                                okText: 'OK',
                                template: response.data.msg
                            }).then(function() {
                                $state.go('tab.libros');
                            });
                        }
                    );
                }
            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'LibrosCtrl',
        [
            '$scope',
            'LibroService',
            function($scope, LibroService) {
                $scope.$on('$ionicView.enter', function() {
                    cargarLibros();
                });


                function cargarLibros() {
                    LibroService.getAll()
                        .then(function(rta) {
                                // Resolve block
                                console.log(rta);
                                var data = rta.data;

                                $scope.libros = data;
                            },
                            function(rta) {
                                console.warn('ERROR! ', rta);
                            });
                }
            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'LoginCtrl',
        [
            '$scope',
            '$ionicPopup',
            '$state',
            'AuthService',
            function($scope, $ionicPopup, $state, AuthService) {
                $scope.login = function(user) {
                    AuthService.login(user).then(function(rta) {
                        var data = rta.data;

                        // Checkeamos si el usuario se pudo loggear o no:
                        if(data.success) {
                            var popup = $ionicPopup.alert({
                                title: "YAY!",
                                template: "Estás adentro, astronauta",
                                okText: 'OK'
                            });

                            popup.then(function() {
                                $state.go('tab.libros');
                            });
                        } else {
                            $ionicPopup.alert({
                                title: "Oh no..",
                                template: "Credenciales incorrectas",
                                okText: 'OK'
                            });
                        }
                    });
                }
            }
        ]
    );




angular
    .module('LKLibros.controllers')
    .controller(
        'OrbitaCtrl',
        [
            '$scope',
            'UsuarioService',
            function($scope, UsuarioService) {
                $scope.$on('$ionicView.enter', function() {
                    cargarAstronautas();
                });


                function cargarAstronautas() {
                    UsuarioService.getAstronautas().then(
                        function(response){
                            $scope.usuarios = response.data;
                        }
                    );
                }
            }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'PerfilCtrl',
        [
            '$scope',
            '$state',
            'StorageService',
            '$stateParams',
            '$ionicHistory',
            '$window',
            '$ionicPopup',
            'AuthService',
            'UsuarioService',
            function($scope, $state, StorageService, $stateParams, $ionicHistory, $window, $ionicPopup, AuthService, UsuarioService) {

                $scope.$on('$ionicView.enter', function() {
                    if($stateParams.id){
                        UsuarioService.getById($stateParams.id).then(
                            function(response) {
                                $scope.user = response.data;
                            },
                            function(errorData) {
                                // Reject block
                                console.log("Uy! Hubo un error!", errorData);
                            }
                        );
                    } else {
                        $scope.user = StorageService.get('userData');
                        UsuarioService.getNotificaciones().then(
                            function(response) {
                                $scope.notificaciones = response.data;
                            },
                        );

                        UsuarioService.readNotificaciones();
                    }
                });

                /* logout */
                $scope.logout = function() {
                    $window.localStorage.clear();
                    $ionicHistory.clearCache();
                    $ionicHistory.clearHistory();
                    $state.go('login');
                }

                /* editar datos user */
                $scope.grabarUser = function(perfil) {
                    PerfilService.grabarUser(perfil).then(function(rta) {

                        var info = rta.data;

                        if (info.success) {
                            AuthService.updateUserData(info.data);
                            $ionicPopup.alert({
                                okText: 'OK',
                                template: rta.data.msg
                            }).then(function() {
                                $state.go('tab.perfil');
                            });
                        }
                    });
                }

                /* editar datos user */
                $scope.agregarAmigos = function(id) {
                    UsuarioService.agregarAmigos(id).then(function(rta) {
                        var info = rta.data;

                        if(info.success) {
                            $scope.user.is_friend = true;
                            $ionicPopup.alert({
                                template: info.msg,
                                okText: 'Ok'
                            });
                        } else {
                            $ionicPopup.alert({
                                title: "Error",
                                template: info.msg,
                                okText: 'Ok'
                            });
                        }
                    });
                }

                /* editar datos user */
                $scope.quitarAmigos = function(id) {
                    UsuarioService.quitarAmigos(id).then(function(rta) {
                        var info = rta.data;

                        if(info.success) {
                            $scope.user.is_friend = false;
                            $ionicPopup.alert({
                                template: info.msg,
                                okText: 'Ok'
                            });
                        } else {
                            $ionicPopup.alert({
                                title: "Error",
                                template: info.msg,
                                okText: 'Ok'
                            });
                        }
                    });
                }

            }
        ]
    );

angular
    .module('LKLibros.controllers')
    .controller(
        'tabController',
        [
        	'$scope',
        	'$state',
        	'UsuarioService',
	        function($scope,$state,UsuarioService) {

	        	$scope.$on('$ionicView.enter', function() {
                    UsuarioService.getNotificaciones().then(
                    	function(response) {
                            $scope.notificaciones = response.data;
                            console.log(response.data);
                        },
                    );
                });
	            
	        	$scope.goTo = function(args) {
	        	    $state.go(args, {}, {
	        	        reload: true,
	        	        inherit: false,
	        	        notify: true
	        	    });
	        	};

	        }
        ]
    );
angular
    .module('LKLibros.controllers')
    .controller(
        'UsuarioNuevoCtrl',
        [
            '$scope',
            '$state',
            '$ionicPopup',
            'UsuarioService',
            function($scope, $state, $ionicPopup, UsuarioService) {


                $scope.grabar = function(usuarioData) {
                    UsuarioService.create(usuarioData).then(
                        function(response) {
                            console.log(response.data);
                            $ionicPopup.alert({
                                title: 'YAY!',
                                okText: 'OK',
                                template: response.data.msg
                            }).then(function() {
                                $state.go('login');
                            });
                        }
                    );
                }
            }
        ]
    );





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
                return $http.get(API_SERVER + "/public/libros/" + id, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
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
             * Agregar libro a mis favoritos.
             *
             * @param {Number} id
             * @returns {Promise}
             */

            this.agregarFavorito = function(id) {

                data = {
                    'id': id
                }

                return $http.post(API_SERVER + "/public/libros/favoritos", data, {
                    'headers': {
                        'X-Token': AuthService.getToken()
                    }
                });
            };

            /**
             * Eliminar libro de mis favoritos.
             *
             * @param {Number} id
             * @returns {Promise}
             */

            this.eliminarFavorito = function(id) {
                return $http.delete(API_SERVER + "/public/libros/favoritos/" + id, {
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
angular.module('LKLibros.services')
    /**
     * Este servicio va a administrar el almacenamiento de datos de la app.
     * Acciones necesarias:
     * _Leer
     * _Guardar / almacenar
     * _Borrar
     * _Verificar existencia
     */
    .service('StorageService', [
        function() {
            /**
             * Almacena un valor.
             * @param {string} key
             * @param {*} value
             */
            this.set = function(key, value) {
                localStorage.setItem(key, JSON.stringify(value));
            };

            /**
             * Obtiene un valor almacenado.
             * @param {string} key
             * @return {*}
             */
            this.get = function(key) {
                return JSON.parse(localStorage.getItem(key));
            };

            /**
             * Verifica si tiene la key.
             * @param {string} key
             * @returns {boolean}
             */
            this.has = function(key) {
                return localStorage.getItem(key) !== null;
            };

            /**
             * Elimina el valor.
             * @param {string} key
             */
            this.delete = function(key) {
                localStorage.removeItem(key);
            };
        }
    ]);
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

