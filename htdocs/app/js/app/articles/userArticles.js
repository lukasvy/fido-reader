angular.module('userArticles',[])

.controller('ShowArticles',['$scope','returndata','lvHttp','$route','L','articlemodal','T',
function($scope,returndata,lvHttp,$route,L,openModal,T){
	var offset = 10;
	var page = 0;
	var total = 0;
	var shown = 0;
	var search_id = 0;
	if ($route.current.params.offset) {
		offset = $route.current.params.offset;
	}
	if ($route.current.params.page) {
		offset = $route.current.params.page;
	}
	lvRegistry.set('selectorChange',{text:L('All Unread')});
	lvRegistry.register('infiniteScroll',function(scroll){
	   if (scroll){
	   		$scope.showMore();
	   } 
    });
	$scope.L = L;
	$scope.loadingText = L('loading');
	$scope.loading = false;
	$scope.showArticle = function (id,index) {
		if (!$scope.data[index].user_read) {
			$scope.data[index].user_read = true;
		}
	    openModal(id,$scope.data[index]);
	}		
	$scope.showMore = function(){
		$scope.loading = true;
		page++;
		var feed = lvHttp('/user/articles/unread',{page:page,offset:offset,search_id:search_id});
		feed.then(function(data){
			var articles = data.data.articles;
			total = data.data.total;
			search_id = returndata.data.search_id;
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
			$scope.loading = false;
		});	
	}

	if (returndata.data.articles) {
		$scope.data = returndata.data.articles;
		total = returndata.data.total;
		search_id = returndata.data.search_id;
		if (shown >= total) {
			$scope.noMoreData = true;
		}
	}
}])

.controller('UserArticlesCtrl',['$scope','returndata','lvHttp','$route','L','articlemodal','T','lvRegistry','$location',
function($scope,returndata,lvHttp,$route,L,openModal,T,lvRegistry,$location){
	var offset = 10;
	var page = 0;
	var total = 0;
	var shown = 0;
	var search_id = 0;
	if ($route.current.params.offset) {
		offset = $route.current.params.offset;
	}
	if ($route.current.params.page) {
		offset = $route.current.params.page;
	}
	lvRegistry.set('selectorChange',{text:L('Unread Articles')});
    lvRegistry.register('infiniteScroll',function(scroll){
	   if (scroll){
	   		$scope.showMore();
	   } 
    });
	$scope.L = L;
	$scope.loadingText = L('loading');
	$scope.loading = false;
	$scope.showArticle = function (id,index) {
		if (!$scope.data[index].user_read) {
			$scope.data[index].user_read = true;
		}
	    openModal(id,$scope.data[index]);
	}		
	$scope.showMore = function(){
		if ($scope.loading){
		    return 1;
		}
		$scope.loading = true;
		page++;
		var feed = lvHttp('/user/articles/unread',{page:page,offset:offset,search_id:search_id});
		feed.then(function(data){
			var articles = data.data.articles;
			total = data.data.total;
			search_id = returndata.data.search_id;
			if (articles && articles.length) {
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
			$scope.loading = false;
		});	
	}

	if (returndata.data.articles) {
		$scope.data = returndata.data.articles;
		total = returndata.data.total;
		search_id = returndata.data.search_id;
		if (shown >= total) {
			$scope.noMoreData = true;
		}
	} else {
		$location.path( "/user/articles/popular" );
		$scope.noMoreData = true;
	}
}])