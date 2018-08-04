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