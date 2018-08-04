angular
    .module('LKLibros.controllers')
    .controller(
        'PerfilCtrl',
        [
            '$scope',
            '$state',
            'StorageService',
            '$ionicHistory',
            '$window',
            '$ionicPopup',
            'AuthService',
            'UsuarioService',
            function($scope, $state, StorageService, $ionicHistory, $window, $ionicPopup, AuthService, UsuarioService) {


                $scope.$on('$ionicView.enter', function() {
                    $scope.user = StorageService.get('userData');
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

            }
        ]
    );
