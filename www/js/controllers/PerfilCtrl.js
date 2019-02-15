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
