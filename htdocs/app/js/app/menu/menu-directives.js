angular.module('menu-app')

.directive('lvFixed', function($window){
	return {
		scope : false,
        replace : false,
        restrict : 'AE',
        compile : function(tElement, tAttrs, transclude){
        	return {
	        	pre : function(scope, iElement, attr, ctrl){
	        		var windowEl = angular.element($window);
	        		console.log(windowEl);
					windowEl.$on('scroll', function() {
				      scope.$apply(function() {
				        element.css('top',0);
				      });
				    });
	        	}
        	}
        },

	}
		
})

.directive('lvSnapper',function(lvRegistry){
    var snapper = false;
    var drawer_opened = false;
    var side = false;
    return {
        scope : false,
        replace : false,
        restrict : 'AE',
        transclude : true,
        template : '<div ng-transclude></div>',
        compile : function(tElement, tAttrs, transclude){
        	return {
	        	pre : function(scope, iElement, attr, ctrl){
	        		var that = this;
		        	lvRegistry.register('loading',function(da){
		        		snapper.close();
                		loading = da;
	                });
	        	}
        	}
        }
        ,
        controller : function($scope,L){
        		var that = this;
        		var user = false;
        		$scope.logintext = L('login.submit');
        		$scope.menutext = L('menu.text');
        		lvRegistry.register('userRefresh', function(rec_user){
	        		if (!user) {
	        			user = rec_user;
	        			$scope.logintext = user.username;
	        		}
        		});
        		lvRegistry.register('loggedIn', function(rec_user){
	        		that.closeSnapper();
	        		if (!user) {
	        			user = rec_user;
	        			$scope.logintext = user.username;
	        		}
        		});
        		lvRegistry.register('loggedOut', function(){
	        		that.closeSnapper();
	        		user = false;
	        		$scope.logintext = L('login.submit');
        		});
        		
	            this.setSnapper = function(recSnapper){
                if (!snapper) {
                    var that = this;
                    snapper = recSnapper;
                    snapper.on('end', function(e){
                        $scope.$apply(function(){
                            if (snapper.state().state == 'closed') {
                                that.drawer_opened = false;
                            } else {
                                that.drawer_opened = true;
                            }
                        })
                    });
                }
            };
            this.openSnapper = function(pass_side) {
                if (snapper){
                    if (pass_side === 'left') {
                        snapper.open(pass_side);
                    } else {
                        snapper.open('right');
                    }
                }
                side = pass_side;
            };
            this.closeSnapper = function() {
                if (snapper){
                    snapper.close();
                }
            };
            this.toggle = function(side) {
                if (this.drawer_opened) {
                    this.closeSnapper();
                } else {
                    this.openSnapper(side);
                }
            }
        }
    }
})

.directive('lvSnapperMenu', function(snapSettings){
    return {
        require : '?^lvSnapper',
        scope : false,
        transclude : true,
        restrict : 'AE',
        replace : false,
        template : '<div ng-transclude></div>',
        compile : function(tElement, tAttrs, transclude){
            return {
                pre : function(scope, iElement, attr, ctrl){
                    if (snapSettings.style){
                        //iElement.css('width',(snapSettings.maxPosition-20)+'px');
                        if (tAttrs.side == 'right') { 
                            //iElement.addClass('pull-right');
                            iElement.addClass('snap-drawer-'+tAttrs.side);
                        } else {
                            //iElement.addClass('pull-left');
                            iElement.addClass('snap-drawer-'+tAttrs.side);
                        }
                    }
                    if (attr.class) {
                        iElement.addClass(attr.class);
                    }
                }
            }  
        },
    }
})

.directive('lvSnapperContent', function(snapSettings){
    return {
        require : '?^lvSnapper',
        scope : false, 
        transclude : true,
        replace: false,
        restrict : 'AE',
        template : '<div ng-transclude></div>',
        compile : function(tElement, tAttrs, transclude){
            return {
                pre : function prelink(scope, iElement, attr, ctrl){
                    var mySettings = snapSettings;
                    mySettings.element = iElement[0];
                    var snapper = new Snap(mySettings);
                    ctrl.setSnapper(snapper);
                    //iElement.addClass('content');
                    iElement.addClass('drawer-shadow');
                }
            }
        },
        
    }
})

.directive('lvSnapperToggler',function(){
    return {
        scope : false, 
        require : '?^lvSnapper',
        transclude : true,
        restrict : 'AE',
        replace : false,
        template : '<div ng-transclude></div>',
        compile : function(tElement, tAttrs, sup){
            return {
                pre : function prelink(scope, iElement, attr, ctrl){
                    if (!attr.side) { attr.side = 'left' };
                    iElement.addClass('pointer');
                    iElement.bind('click', function(e){ 
                        ctrl.toggle(attr.side); 
                    });
                }
            }
        }
    }
})