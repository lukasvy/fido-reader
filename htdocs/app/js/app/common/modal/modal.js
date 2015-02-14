angular.module('common.modal',['ui.bootstrap.modal','T','L','restangular','ui.bootstrap.tabs'])

.directive('staticInclude', ['$http', '$templateCache', '$compile',function($http, $templateCache, $compile) {
    return function(scope, element, attrs) {
        var templatePath = attrs.staticInclude;

        $http.get(templatePath, {cache: $templateCache}).success(function(response) {
            var contents = $('<div/>').html(response).contents();
            element.html(contents);
            $compile(contents)(scope);
        });
    };
}])

.factory('newModal',['$modal','Restangular','L',function(modal,Restangular,L){
	return function(id,action,API,templates,lexicon) {
    	var feed = {};
    	if (id) {
	    	feed = Restangular.one(API.url,id).get();
	    }
	    feed.reload_data = API.reload_data;
	    modal.open({templateUrl: templates.main,
					backdrop: true,
		            windowClass: 'modal',
		            //scope : scope,
		            controller: ['$scope','$modalInstance',function ($scope, $modalInstance) {
		            	$scope.modalOptions = {};
		            	$scope.L = L;
		            	$scope.modalOptions.closeButtonText = L('common.modal.close');
			            $scope.modalOptions.actionButtonText = L('common.modal.save');
			            $scope.data = {};
		            	if (action === 'show') {
			            	$scope.modalOptions.headerText = lexicon.headerTextShow;
			            	$scope.modalOptions.show = true;
			            	$scope.modalOptions.edit = false;
			            	$scope.modalOptions.remove = false;
			            	$scope.modalOptions.loading = true;
			            	$scope.modalOptions.add = false;
			            	feed.then(function(data){
			            		$scope.modalOptions.loading = false;
				            	$scope.data = data;
			            	});
		            	} else if (action === 'edit') {
		            		$scope.modalOptions.headerText = lexicon.headerTextEdit;
			            	$scope.modalOptions.show = false;
			            	$scope.modalOptions.edit = true;
			            	$scope.modalOptions.loading = true;
			            	$scope.modalOptions.add = false;
			            	feed.then(function(data){
			            		$scope.modalOptions.loading = false;
				            	$scope.data = data;
			            	});
			            	$scope.modalOptions.ok = function() {
			            		$scope.modalOptions.disableSubmit = true;
			            		API.data.post(
			            		$scope.data)
			            		.then(function(){
				            		feed.reload_data();
				            		close();
			            		})
			            		.catch(function(e,d){
				            		close();
			            		});
			            	}
			            } else if (action === 'remove'){
			            	$scope.modalOptions.headerText = lexicon.headerTextRemove;
			            	$scope.modalOptions.actionButtonText = L('common.modal.remove');
			            	$scope.modalOptions.show = false;
			            	$scope.modalOptions.edit = false;
			            	$scope.modalOptions.remove = true;
			            	$scope.modalOptions.loading = true;
			            	$scope.modalOptions.add = false;
			            	feed.then(function(datas){
			            		$scope.modalOptions.loading = false;
				            	$scope.data = datas;
			            	})
			            	$scope.modalOptions.ok = function() {
			            		$scope.modalOptions.disableSubmit = true;
			            		$scope.data.remove()
			            		.then(function(){
				            		feed.reload_data();
				            		close();
			            		})
			            		.catch(function(e,d){
				            		close();
			            		});
			            		
			            	}
			            } else if (action === 'lock'){
			            	$scope.modalOptions.headerText = lexicon.headerTextLock;
			            	$scope.modalOptions.actionButtonText = L('common.modal.lock');
			            	$scope.modalOptions.show = false;
			            	$scope.modalOptions.edit = false;
			            	$scope.modalOptions.remove = true;
			            	$scope.modalOptions.loading = true;
			            	$scope.modalOptions.add = false;
			            	feed.then(function(datas){
			            		$scope.modalOptions.loading = false;
				            	$scope.data = datas;
			            	})
			            	$scope.modalOptions.ok = function() {
			            		$scope.modalOptions.disableSubmit = true;
			            		$scope.data.remove()
			            		.then(function(){
				            		feed.reload_data();
				            		close();
			            		})
			            		.catch(function(e,d){
				            		close();
			            		});
			            		
			            	}
			            } else if (action === 'unlock'){
			            	$scope.modalOptions.headerText = lexicon.headerTextUnlock;
			            	$scope.modalOptions.actionButtonText = L('common.modal.unlock');
			            	$scope.modalOptions.show = false;
			            	$scope.modalOptions.edit = false;
			            	$scope.modalOptions.remove = true;
			            	$scope.modalOptions.add = false;
			            	$scope.modalOptions.loading = true;
			            	feed.then(function(datas){
			            		$scope.modalOptions.loading = false;
				            	$scope.data = datas;
			            	})
			            	$scope.modalOptions.ok = function() {
			            		$scope.modalOptions.disableSubmit = true;
			            		$scope.data.remove()
			            		.then(function(){
				            		feed.reload_data();
				            		close();
			            		})
			            		.catch(function(e,d){
				            		close();
			            		});
			            		
			            	}
			            } else {
			            	$scope.modalOptions.headerText = lexicon.headerTextNew;
			            	$scope.modalOptions.show = false;
			            	$scope.modalOptions.add = true;
			            	$scope.modalOptions.ok = function() {
			            	$scope.modalOptions.disableSubmit = true;
		            		API.data.post($scope.data)
		            		.then(function(){
			            		feed.reload_data();
			            		close();
		            		})
		            		.catch(function(e,d){
			            		
		            		});
		            	}
		            	}
		            	
		            	$scope.modalOptions.close = function() {
			            	close();
		            	}
		            	var close = function () {
			            	feed = {};
			            	id = {};
			            	action = {};
			            	API = {};
			            	templates = {};
			            	lexicon = {};
			            	$modalInstance.dismiss('cancel');
		            	}
		            	
		            }]
				   });
    }
    
}])

.service('modalService',['$modal','T','L','$compile',function($modal,T,L,$compile){
    var modalDefaults = {
        backdrop : true,
        keyboard : true,
        modalFade: true,
        templateUrl : T('feeds.modal')
    };
    var modalOptions = {
        closeButtonText     : L('common.modal.close'),
        actionButtonText    : L('common.modal.ok'),
        headerText          : L('common.modal.defaultHeader'),
        bodyText            : L('common.modal.defaultBody'),
        bodyTemplateUrl		: false,
        bodyOkCtrl			: false,
        bodyCancelCtrl		: false
    }

    this.showModal = function(customModalDefaults, customModalOptions) {
        if (!customModalDefaults) customModalDefaults = {};
        customModalDefaults.backdrop = 'static';
        return this.show(customModalDefaults, customModalOptions);
    }

    this.show = function(customModalDefaults, customModalOptions) {
        var tempModalDefaults = {};
        var tempModalOptions  = {};

        angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);
        angular.extend(tempModalOptions, modalOptions, customModalOptions);
        
        if (!tempModalDefaults.controller) {
            tempModalDefaults.controller = function($scope, $modalInstance) {
            	$scope.textBody = true;
            	$scope.differentBody = false;
                $scope.modalOptions = tempModalOptions;
                if ($scope.modalOptions.bodyTemplateUrl){
                	$scope.textBody = false;
			        $scope.differentBody = true;
			        $scope.modalOptions.differentBody = $scope.modalOptions.bodyTemplateUrl;
			        
		        }
                $scope.modalOptions.ok = function(result) {
                    $modalInstance.close(result);
                }
                console.log($scope);
                $scope.modalOptions.close = function(result){
                    $modalInstance.dismiss('cancel');
                };
            }
        }
        
        return $modal.open(tempModalDefaults).result;
    };

}])

.directive('pwCheck', [function () {
	return {
		require: 'ngModel',
		link: function (scope, elem, attrs, ctrl) {
			var firstPassword = '#' + attrs.pwCheck;
			elem.add(firstPassword).on('keyup', function () {
				scope.$apply(function () {
					var v = elem.val()===$(firstPassword).val();
					ctrl.$setValidity('pwmatch', v);
				});
			});
		}
	}
}])

.directive('ensureUnique', ['Restangular', function(Restangular) {
  return {
    require: 'ngModel',
    link: function(scope, ele, attrs, c) {
      scope.$watch(attrs.ngModel, function() {
       if (attrs.value === '') {
	       c.$setValidity('unique', true);
       }
       if (attrs.value && (!ele[0].disabled || !ele[0].hidden)) {
       	   c.$valid = false;
       	   c.$setValidity('checking', false);
	       var talk = Restangular.one('unique/check/'+ attrs.name+'?value='+attrs.value+'&table='+attrs.ensureUnique).get();
	       talk.then(function(data){
		       if (data.isValid) {
			       c.$setValidity('unique', true);
			       c.$setValidity('checking', true);
			       c.$valid = true;
		       } else {
			       c.$setValidity('unique', false);
			       c.$setValidity('checking', true);
		       }
	       })
	       .catch(function(){
		       c.$setValidity('unique', false);
		       c.$setValidity('checking', true);
	       });
	       }
      });
    }
  }
}]);