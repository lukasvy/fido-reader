angular.module('search-app',['T','L','common.lvHttp','common.modal'])

.controller('SearchAppCtrl', ['$scope','T','L','returndata','lvHttp', '$route','articlemodal',
	function($scope,T,L,returndata,lvHttp,$route,openModal){
		var offset = 10;
		var page = 0;
		var total = 0;
		var shown = 0;
		var query = $route.current.params.q;
		if ($route.current.params.offset) {
			offset = $route.current.params.offset;
		}
		if ($route.current.params.page) {
			offset = $route.current.params.page;
		}
		$scope.$on('infiniteScroll', function(){
        	    $scope.showMore();
    		});

		$scope.L = L;
		$scope.loadingText = L('loading');
		$scope.loading = false;
		$scope.showArticle = function (id,index) {
	    	openModal(id,$scope.data[index]);
	    }		
	    
		$scope.showMore = function(){
			if ($scope.loading) {
			    return 1;
			}
			$scope.loading = true;
			var search = lvHttp('search',{query:query,page:++page,offset:offset});
			search.then(function(data){
			var articles = data.data.articles;
			total = data.data.total;
				if (articles.length) {
					var leng = articles.length;
					for(var i = 0; i < leng; i++){
						$scope.data.push(articles[i]);
						shown++;
					}
				} else {
					$scope.noMoreData = true;
				}
				$scope.loading = false;
				if (shown >= total) {
					$scope.noMoreData = true;
				}
			})
			.catch(function(){
				
			});
			
		}
		
		if (returndata){
			$scope.data = returndata.data.articles;
			shown = $scope.data.length;
			total = returndata.data.total;
			$scope.error = false;
			$scope.noMoreData = false;
			if ($scope.data.length === 0) {
				$scope.error = true ;
				$scope.noMoreData = true;
			}
			if (shown >= total) {
					$scope.noMoreData = true;
			}
		} else {
			$scope.error = true ;
			$scope.noMoreData = true;
		}
		
	}])