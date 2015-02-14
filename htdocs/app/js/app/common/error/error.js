angular.module('common.error',['T','L'])

.factory('error',function(){
    return function(){return 1};
})

.directive('lvError',function(T,L){
    return {

        restrict : 'AE',
        replace : true,
        transclude : true,
        templateUrl : T('common.error'),
        controller : function($scope,$element,$attrs,$transclude) {
            
        }
    }
})