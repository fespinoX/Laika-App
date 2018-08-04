angular
    .module('LKLibros.controllers')
    .controller(
        'ComentarioNuevoCtrl',
        [
            '$scope',
            '$state',
            '$ionicPopup',
            '$stateParams',
            'LibroService',
            function($scope, $state, $ionicPopup, $stateParams, LibroService) {


                $scope.$on('$ionicView.enter', function() {
                    $scope.getOne();
                });

                $scope.getOne = function() {
                    LibroService.getById($stateParams.id).then(function(rta) {
                        $scope.libro = rta.data;
                    });
                }


                $scope.agregarReview = function(comentario) {

                    var idLibro = $stateParams.id;

                    LibroService.agregarReview(comentario, idLibro).then(function(rta) {

                        var info = rta.data;

                        if(info.success) {
                            $ionicPopup.alert({
                                template: info.msg,
                                okText: 'Ok'
                            });

                            $state.go('tab.libros');
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