angular.module('userMenu',['T','L','common.security','common.registry','common.modal','restangular','ui.bootstrap.tooltip'])

.controller('UserMenuCtrl',['$scope','security','lvRegistry','L','newModal','T','Restangular',
function($scope,security,lvRegistry,L,openModal,T,Restangular){
	$scope.show = false;
	$scope.L = L;
	$scope.userFeeds = false;
	$scope.user = false;
	
	lvRegistry.register('newFeed', function(){
	    $scope.newFeed();
	});
	
	lvRegistry.register('loggedIn',function(rec_user){
		if (!rec_user) {
			rec_user = security.getCurrentUser();
		}
		if (!$scope.user) {
			$scope.show = true;
			$scope.allUnread = rec_user.allUnread;
			$scope.userFeeds = rec_user.feeds;
			$scope.user = rec_user.user;
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

    $scope.newFeed = function() {
		openModal(false,'new',API,templates,lexicon);
	}
	
	$scope.removeUserFeed = function(index) {
		user = security.getCurrentUser();
		if (user && user.feeds) {
			openModal(user.feeds[index].id,'remove',API,templates,lexicon, function(done){
				$scope.userFeeds[index].removed = true;
			});
			lvRegistry.set('removeUserFeed',index);
		}
	}
	
	
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
        	}
        }
    }

}])