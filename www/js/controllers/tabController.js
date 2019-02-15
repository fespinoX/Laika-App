angular
    .module('LKLibros.controllers')
    .controller(
        'tabController',
        [
        	'$scope',
        	'$state',
        	'UsuarioService',
	        function($scope,$state,UsuarioService) {

	        	$scope.$on('$ionicView.enter', function() {
                    UsuarioService.getNotificaciones().then(
                    	function(response) {
                            $scope.notificaciones = response.data;
                            console.log(response.data);
                        },
                    );
                });
	            
	        	$scope.goTo = function(args) {
	        	    $state.go(args, {}, {
	        	        reload: true,
	        	        inherit: false,
	        	        notify: true
	        	    });
	        	};

	        }
        ]
    );