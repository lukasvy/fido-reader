angular.module('table-app',['common.lvHttp','L','T'])

.controller('TableCtrl',['$scope',function($scope){
	
}])

.directive('lvTable',['lvHttp','L','T',function(lvHttp,L,T){
	return {
		scope : false,
		templateUrl : T('table'),
		restrict : 'AE',
		compile : function(tElement, tAttrs, transclude){
			return {
				pre : function(scope, iElement, attr, ctrl){
					console.log(iElement,attr);
				}
			}
		},
		controller : 'TableCtrl'
	}
}])