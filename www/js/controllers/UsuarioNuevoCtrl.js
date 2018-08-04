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




