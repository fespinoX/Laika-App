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