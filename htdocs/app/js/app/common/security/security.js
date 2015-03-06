angular.module('common.security',['common.lvHttp','common.registry'])

.factory('security', ['$location','lvHttp','lvRegistry','$timeout',
function($location, lvHttp,lvRegistry,$timeout){
	var currentUser = null;
	var API = {};
	function redirect(url) {
	    url = url || '/';
	    $location.path(url);
    }
    
    API.refreshUser = function () {
    	if (currentUser) {
	    var request = lvHttp('checkuser',{id : currentUser.id});
		    request.then(function(response) {
			    if (response.data.user) {
		        	currentUser = response.data;
		        	currentUser.feeds = response.data.feeds;
		        	currentUser.allUnread = response.data.allUnread;
		        	lvRegistry.set('userRefresh',currentUser);
		        } else {
			    service.logOut("/user/articles/popular");
			}
		    })
		    .catch(function(){
			service.logOut("/user/articles/popular");
			return false;
		    });
		}
    }

    var again = function(){
        $timeout(function(){
                API.refreshUser();
                again();
        },10000);
        }
    again();

    
    API.checkUser = function () {
	    var request = lvHttp('checkuser',{});
		    request.then(function(response) {
			    if (response.data.user) {
		        	currentUser = response.data;
		        	currentUser.feeds = response.data.feeds;
		        	lvRegistry.set('userRefresh',currentUser);
		        	return true;
		        } else {
			    service.logOut("/user/articles/popular");
			}
		    }).catch(function(){
			    service.logOut("/user/articles/popular");
			    return false;
		    });
		    return false;
    }
	var service = {
		getCurrentUser : function() {
			if (currentUser) {
				return currentUser;
			} else {
				return false;
			}
		},
		
		checkUser : function () {
			API.checkUser();
		    return true;
		},
		
		refreshCurrentUser : function() {
			if (service.loggedIn()){
		    	API.refreshUser();
		    }
		    return currentUser;
		},
		
		loggedIn : function() {
			if (currentUser) {
				return currentUser;
			} else {
				return null;
			}
		},
		
		logIn : function (username,password, callback) {
			var request = lvHttp('login',{username : username, password : password});
			return request.then(function(response) {
				if (response.data && response.data.user) {
		        	currentUser = response.data;
		        	currentUser.feeds = response.data.feeds;
		        	currentUser.allUnread = response.data.allUnread;
		        	lvRegistry.set('loggedIn',response.data);
		        	if (callback) {
		        		callback(true);
		        	}
				$location.path( "/user/articles/unread" );	 
		        } else {
		        	lvRegistry.set('loggedOut',false);
			        currentUser = null;
			        if (callback) {
			        	callback(false);
			        }
		        }
		      });
		},
		
		isAdmin : function () {
			if (currentUser && currentUser.user && currentUser.user.role === 'admin') {
				return true;
			} else {
				return false;
			}
		},
		
		logOut : function (redirectTo, callback) {
			var request = lvHttp('logout');
			request.then(function(){
				currentUser = null;
				lvRegistry.set('loggedOut');
				if (callback) {
					callback(true);
				}
				if (redirectTo) {
					redirect(redirectTo);
				}
			});
		}
	};
	return service;
}])