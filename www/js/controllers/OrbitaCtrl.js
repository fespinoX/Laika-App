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