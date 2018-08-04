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



