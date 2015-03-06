angular.module('container-app',['T','common.registry'])



.controller('ContainerCtrl', function($scope,$routeParams) {
	$scope.show = {};
    $scope.replaceData = function(data) {
        $scope.show.articles = true;
    }
})


.directive('lvContainer', function(T,lvRegistry){
	
	var data;
	var loading = false;
	
	return {
        scope : {},
        replace : true,
        restrict : 'AE',
        transclude : true,
        templateUrl : T('container'),
        compile : function(tElement, tAttrs){
            return {
                pre : function prelink(scope, iElement, attr){
                	// register watcher on data 
                	lvRegistry.register('loading',function(d){
                		loading = d;
	                });
                    lvRegistry.register('search_result',function(d){
                    	 scope.replaceData(d);
	                     lvRegistry.set('loading',false);
                    });
                    
                }
            }
        },
        controller : 'ContainerCtrl'
    }

});

