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