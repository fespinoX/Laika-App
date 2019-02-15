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
