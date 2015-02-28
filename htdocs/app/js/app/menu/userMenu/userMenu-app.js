angular.module('userMenu',['T','L','common.security','common.registry','common.modal','restangular','ui.bootstrap.tooltip'])

.controller('UserMenuCtrl',['$scope','security','lvRegistry','L','newModal','T','Restangular',
function($scope,security,lvRegistry,L,openModal,T,Restangular){
	$scope.show = false;
	$scope.L = L;
	$scope.userFeeds = false;
	
	
	lvRegistry.register('loggedIn',function(){
		var user = security.getCurrentUser();
		if (!user) {
			$scope.show = true;
			$scope.allUnread = user.allUnread;
			$scope.userFeeds = user.feeds;
		}
	});
	lvRegistry.register('loggedOut',function(){
		$scope.show = false;
		$scope.userFeeds = false;
		$scope.allUnread = 0;
		user = false;
	});
	lvRegistry.register('userRefresh', function(){
	    API.reload_data();
	});

	var API = {};
	API.url = 'user/feed';
	API.data = Restangular.all(API.url);
	API.reload_data = function(){
	    if (security.loggedIn()){
		    var currentUser = security.getCurrentUser();
		    $scope.userFeeds = currentUser.feeds;
		    $scope.allUnread = currentUser.allUnread;
		    $scope.show = true;
		    user = currentUser;
	    }
    };
    
	var templates = {
	    main : T('user.feed.modal'), 
	};
	
	var lexicon = {
	    headerTextShow : L('feed.form.showfeed'),
	    headerTextEdit : L('feed.form.editfeed'),
	    headerTextRemove : L('feed.form.removeFeed'),
	    headerTextNew : L('feed.form.addnewfeed')
    };

    $scope.newFeed = function() {
		openModal(false,'new',API,templates,lexicon);
	};
	
	
}])

.directive('lvUserMenu', ['T','L','security',function(T,L,security){
	return {
        scope : false,
        replace : false,
        restrict : 'AE',
        transclude : true,
        templateUrl : T('userMenu'),
        controller : 'UserMenuCtrl',
        compile : function(tElement, tAttrs, transclude){
        	return {
	        	pre : function(scope, iElement, attr, ctrl){
	        		var that = this;
		        	
	        	}
        	};
        }
    };

}]);