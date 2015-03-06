angular.module('menu.login',['L','common.error','common.modal','common.security','common.registry'])

.controller ('LoginCtrl',['$scope','L','security','lvRegistry', function($scope,L, security, lvRegistry){
    var errors = [];
    $scope.L = L;
    $scope.errors = errors;
    lvRegistry.register('userRefresh', function(){
            if(security.loggedIn()) {
		set_user();
	    }
    });

    var set_user = function () {
	$scope.errors = [];
        $scope.notloggedin = false;
        $scope.$emit('loggedin',security.getCurrentUser());
        $scope.isadmin = security.isAdmin();
        $scope.loading = false;

    }
    $scope.notloggedin = true;
    $scope.submit = function() {
    	$scope.loading = true;
	    security.logIn($scope.username,$scope.password, function(logg){
		    if (logg) {
			set_user();
			$scope.loading = false;
		    } else {
			   $scope.errors.push(L('login.invalid.upassword'));
			   $scope.loading = false;
		    }
	    })
    };
    $scope.logout = function() {
	    $scope.loading = true;
	    security.logOut('/', function(){
		    $scope.notloggedin = true;
		    //$scope.$emit('loggedout');
	    });
	    $scope.loading = false;
    }
}])

.directive('lvLoginForm', 
['T','L','modalService', 
function(T,L,modalService){
    return {
        scope : false,
        transclude : true,
        restrict : 'EA',
        templateUrl : T('menu.loginForm'),
        replace : true,
        controller : 'LoginCtrl',
        compile : function(tElement, tAttrs, transclude){
            return  {
                pre : function(scope, iElement, attr, ctrl) {
                    //modalService.showModal().then(function(){ console.log('closed')});
                }
            }
        }
    }
}]);