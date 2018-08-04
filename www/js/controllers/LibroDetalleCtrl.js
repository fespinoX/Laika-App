angular
    .module('LKLibros.controllers')
    .controller(
        'LibroDetalleCtrl',
        [
            '$scope',
            '$state',
            '$stateParams',
            '$ionicPopup',
            'LibroService',
            function($scope, $state, $stateParams, $ionicPopup, LibroService) {
                LibroService.getById($stateParams.id).then(
                    function(response) {
                        $scope.libro = response.data;
                    },
                    function(errorData) {
                        // Reject block
                        console.log("Uy! Hubo un error!", errorData);
                    }
                );

                $scope.eliminar = function (id) {
                    var popup = $ionicPopup.confirm({
                        title: "Cuidado, astronauta!",
                        template: "Vas a mandar el libro a la estratósfera.. estás segurx?",
                        okText: "OK",
                        cancelText: "NOOOOO"
                    });

                    popup.then(
                        function(confirmar) {
                            if(confirmar) {
                                ejecutarEliminar(id);
                            }
                        }
                    );

                    function ejecutarEliminar(id) {
                        LibroService.delete(id).then(function(rta) {
                            if(rta.data.success) {
                                $ionicPopup.alert({
                                    title: "YAY!",
                                    template: rta.data.msg,
                                    okText: 'OK'
                                }).then(function() {
                                    $state.go('tab.libros');
                                });
                            }
                        });
                    }
                };

            }
        ]
    );