angular.module('common.modal')

.factory('articlemodal',['$modal','Restangular','L','T',function(modal,Restangular,L,T){
	return function(id,returndata) {
	    modal.open({templateUrl: T('modal.articlemodal'),
					backdrop: true,
		            windowClass: 'articlemodal',
		            //scope : scope,
		            controller: ['$scope','$modalInstance',function ($scope, $modalInstance) {
		            		$scope.data = returndata;
		            		$scope.L = L;
		            		$scope.article_id = id;
		            		$scope.loading = true;
		            }]
				   });
    }
    
}])
