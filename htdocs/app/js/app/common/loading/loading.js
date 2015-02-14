angular.module('common.loading',['T','L','common.registry'])

.controller ('LoadingCtrl',['$scope','L','lvRegistry',function($scope,L,lvRegistry){
	$scope.loadingtext = L('loading');
	$scope.transcludeShow = false;
	$scope.loadingShow = false;
	$scope.$on('loading-stop', function(){
		$scope.transcludeShow = true;
		$scope.loadingShow = false;
	});
	$scope.$on('loading-start', function(){
		$scope.transcludeShow = false;
		$scope.loadingShow = true;
	});
}])

.directive('lvLoading',['L','T','lvRegistry',function(L,T,lvRegistry){
	return {
		templateUrl : T('loading'),
		transclude : true,
		restrict : 'EA',
		compile : function(tElement, tAttrs, transclude){
            return  {
                pre : function(scope, iElement, attr, ctrl) {
                    scope.loadingEvent = 'loading';
                    scope.loadingShow = true;
                    scope.transcludeShow = false;
                    if(attr.cog) {
	                    scope.no_text = true;
                    }
                    if (attr.event) {
                    	scope.loadingEvent = attr.event;
                    }
                    lvRegistry.register(scope.loadingEvent,function(status){
						if (status) {
							scope.loadingShow = true;
							scope.transcludeShow = false;
						} else {
							scope.loadingShow = false;
							scope.transcludeShow = true;
						}
					});
                }
            }
        },
        controller : 'LoadingCtrl'
	}	
}])