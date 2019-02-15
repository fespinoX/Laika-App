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