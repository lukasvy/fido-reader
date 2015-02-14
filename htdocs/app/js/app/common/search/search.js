angular.module('common.search',['T','L'])

.factory('lvSearchSubmit', function(lvHttp) {
	return function (query) {
		var $response;
		return lvHttp({searchQuery : query},'search','POST');
	};
})

.controller('SearchCtrl', function($scope, lvSearchSubmit, lvRegistry, $location) {
    $scope.submit = function(e){
    	$location.search('q',$scope.search.input).path('search');
    	$scope.search.input = '';
    };
})

.directive('lvSearch',function(T,L) {
    return {
        scope : {},
        replace : true,
        restrict : 'AE',
        transclude : true,
        templateUrl : T('common.search'),
        compile : function(tElement, tAttrs){
            return {
                pre : function prelink(scope, iElement, attr){
                    var buttonName = L('common.search');
                    if (attr.buttonName){
                        if (L) {
                            buttonName = L(attr.buttonName);
                        } else {
                            buttonName = attr.buttonName;
                        }
                    }
                    scope.search = {
                        buttonName : buttonName,
                        type : attr.type,
                        input : ''
                    };
                }
            };
        },
        controller :  'SearchCtrl'
    };
    
});
