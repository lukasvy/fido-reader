angular.module('article-app',['T','L'])

.controller('ArticleCtrl',['$scope','returndata','$window','common.registry',
function($scope,returndata,$window){
	$scope.close = function() {
		$window.history.back();
	}
	
	$scope.data = returndata.data;
}])

.controller('PopularArticlesCtrl', ['$scope','returndata','lvHttp','$route','L','articlemodal','T','lvRegistry','newModal','Restangular','security',
function($scope,returndata,lvHttp,$route,L,articlemodal,T,lvRegistry,openModal,Restangular,security){
	var offset = 10;
	var page = 0;
	var total = 0;
	var shown = 0;
	var search_id = 0;
	lvRegistry.set('selectorChange',{text:L('Popular Articles')});
	$scope.$on('infiniteScroll', function(){
            $scope.showMore();
        });
	if ($route.current.params.offset) {
		offset = $route.current.params.offset;
	}
	if ($route.current.params.page) {
		offset = $route.current.params.page;
	}
	lvRegistry.register('infiniteScroll',function(scroll){
	   if (scroll){
	   		$scope.showMore();
	   } 
    });
	$scope.L = L;
	$scope.loadingText = L('loading');
	$scope.loading = false;
	$scope.addSource = function() {
		
	}
	$scope.showArticle = function (id,index) {
		if (!$scope.data[index].user_read) {
			$scope.data[index].user_read = true;
		}
	    articlemodal(id,$scope.data[index]);
	}		
	$scope.showMore = function(){
		if ($scope.loading){
		    return 1;
		}
		$scope.loading = true;
		page++;
		var feed = lvHttp('/user/articles/popular', {page:page,offset:offset,search_id:search_id});
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
	
	var API = {};
	API.url = 'user/feed';
	API.data = Restangular.all(API.url);
	API.reload_data = function(){
	    if (security.loggedIn()){
		    var currentUser = security.getCurrentUser();
		    $scope.userFeeds = currentUser.feeds;
		    $scope.allUnread = currentUser.allUnread;
		    $scope.show = true;
		    $scope.user = currentUser.user;
	    }
    }
    
	var templates = {
	    main : T('user.feed.modal'), 
	}
	
	var lexicon = {
	    headerTextShow : L('feed.form.showfeed'),
	    headerTextEdit : L('feed.form.editfeed'),
	    headerTextRemove : L('feed.form.removeFeed'),
	    headerTextNew : L('feed.form.addnewfeed')
    }
    
    $scope.addSource = function(url) {
    	if (security.loggedIn()) {
			openModal({url:url},'new',API,templates,lexicon);
		}
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

.directive('lvArticles', function(T,L){
	var articlesData = [];
	return {
		scope : {},
		restrict : 'AE',
		replace : true,
		templateUrl : T('articles'),
		transclude : true,
		compile : function(tElement, tAttrs, transclude){
			return {
				pre : function(scope, iElement, attr, ctrl){
					
				}
			}
		}
	}
})

.directive('lvArticle', function(T,L){
	return {
		scope : {},
		restrict : 'AE',
		replace : true,
		templateUrl : T('article'),
		
	}
})


/* ng-infinite-scroll - v1.0.0 - 2013-02-23 */
var mod;mod=angular.module("infinite-scroll",[]),mod.directive("infiniteScroll",["$rootScope","$window","$timeout",function(i,n,e){return{link:function(t,l,o){var r,c,f,a;return n=angular.element(n),f=0,null!=o.infiniteScrollDistance&&t.$watch(o.infiniteScrollDistance,function(i){return f=parseInt(i,10)}),a=!0,r=!1,null!=o.infiniteScrollDisabled&&t.$watch(o.infiniteScrollDisabled,function(i){return a=!i,a&&r?(r=!1,c()):void 0}),c=function(){var e,c,u,d;return d=n.height()+n.scrollTop(),e=l.offset().top+l.height(),c=e-d,u=n.height()*f>=c,u&&a?i.$$phase?t.$eval(o.infiniteScroll):t.$apply(o.infiniteScroll):u?r=!0:void 0},n.on("scroll",c),t.$on("$destroy",function(){return n.off("scroll",c)}),e(function(){return o.infiniteScrollImmediateCheck?t.$eval(o.infiniteScrollImmediateCheck)?c():void 0:c()},0)}}}]);