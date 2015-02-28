angular.module('myApp',
['ui.bootstrap','restangular',
 'common.lvHttp','T','common.registry','common.loading',
 'ngRoute','menu-app','container-app','myaccount-app','admin-app',
 'search-app','common.security','article-app','feeds-app','userMenu','userArticles'
 ])

.config(['templ','$routeProvider','$locationProvider',
function(templ,$routeProvider, $locationProvider){
	$routeProvider
		.when('/',{
			controller : 'ContainerCtrl',
			templateUrl: templ.container 
		})
		.when('/myaccount',{
			controller : 'MyAccountCtrl',
			templateUrl: templ.myaccount 
		})
		.when('/admin',{
			controller : 'AdminCtrl',
			templateUrl: templ.admin 
		})
		.when('/user/articles/unread',{
			controller : 'UserArticlesCtrl',
			templateUrl: templ.articles,
			resolve :  {
				returndata : function($route,lvHttp) {
					var offset = 10;
					var page = 0;
						return lvHttp('user/articles/unread',{page:page,offset:offset});
					}
			}
		})
		.when('/user/articles/popular',{
			controller : 'PopularArticlesCtrl',
			templateUrl: templ.articles,
			resolve :  {
				returndata : function($route,lvHttp) {
					var offset = 10;
					var page = 0;
						return lvHttp('user/articles/popular',{page:page,offset:offset});
					}
			}
		})
		.when('/article',{
			controller : 'ArticleCtrl',
			templateUrl: templ.article,
			resolve :  {
				returndata : function($route,lvHttp) {
					var query = $route.current.params.id;
						return lvHttp('article',query);				
					}
			}
		})
		.when('/feed',{
			controller : 'FeedCtrl',
			templateUrl: templ.articles,
			resolve :  {
				returndata : function($route,lvHttp) {
					var query = $route.current.params.id;
					var offset = $route.current.params.offset;
					var page = $route.current.params.page;
						return lvHttp('feed',{id:query,page:page,offset:offset});				
					}
			}
		})
		.when('/search',{
			controller : 'SearchAppCtrl',
			templateUrl: templ.searchResult,
			resolve :  {
				returndata : function($route,lvHttp) {
					var query = $route.current.params.q;
						return lvHttp('search',query);				
					}
			}
		})
		.otherwise({ redirectTo: '/' });
		
}])

.run(['$location','Restangular','security',
function($location,Restangular,security){
	Restangular.setBaseUrl(documentUrl);
	security.checkUser();
}])

.controller('AppCtrl', function($scope,$rootScope,$location,lvRegistry,security){
	$scope.loadingEvent = 'loading';
	var authNotRequired = [];
	var filter = function(path) {
		if (path === '/admin' && !security.isAdmin()) {
			$location.path('/');
		}
	};
	$rootScope.$on("$routeChangeStart", function (event, next, current) {
		//filter(next.$$route.originalPath);
		lvRegistry.set($scope.loadingEvent,true);
    });
    $rootScope.$on("$routeChangeSuccess", function (event, current, previous) {
    	lvRegistry.set($scope.loadingEvent,false);
        $scope.newLocation = $location.path();
    });
    $rootScope.$on("$routeChangeError", function (event, current, previous, rejection) {
        alert("ROUTE CHANGE ERROR: Ups something went wrong!" + rejection);
        lvRegistry.set($scope.loadingEvent,false);
        $scope.alertType = "alert-error";
        $scope.alertMessage = "Failed to change routes :(";
        $scope.active = "";
    });
});