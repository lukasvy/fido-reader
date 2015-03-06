angular.module('admin-app',['common.security','ngTable','restangular','ui.bootstrap.modal','common.modal','ui.bootstrap.tooltip','ui.bootstrap.tabs'])

.controller('AdminCtrl', ['$scope',function($scope){

}])

.controller('statisticsCtrl', ['$scope','Restangular','$timeout',function($scope,Restangular,$timeout){
	var API = {};
	API.url = 'admin/statistics';
	API.data = Restangular.all(API.url);
	$scope.loading = true;
	API.refreshStatistics = function (){
		$scope.loading = true;
		API.data.getList()
		.then(function (data){
			$scope.data = data;
			$scope.tick = data.tick;
			$scope.access = data.access;
			$scope.loading = false;
		})
		.catch(function(){
			
		});
	}
	
	var again = function(){
	$timeout(function(){
		API.refreshStatistics();
		again();
	},10000);
	}
	again();
	
	API.refreshStatistics();
	
}])

.controller('feedsCtrl', ['$scope','ngTableParams','Restangular','T','L','newModal',
function($scope, ngTableParams, Restangular,T,L,openModal) {
	$scope.L = L;
	$scope.loading = true;
	var API = {};
	API.url = 'admin/feeds';
	API.data = Restangular.all(API.url);
    API.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,           // count per page
        sorting: {},
        filter : {}
       }, {
        total: 0, // length of data
        getData: function($defer, params) {
            API.data.getList({page : API.tableParams.page(), 
            				 offset: API.tableParams.count(),
            				 order : API.tableParams.sorting(),
            				 filter: API.tableParams.filter()})
            .then(function(data){
            	$scope.loading = false;
            	params.total(data.total);
	            $defer.resolve(data.data);
            })
            .catch();
        }
    });
    $scope.tableParams = API.tableParams;
    API.reload_data = function(){
	    API.tableParams.reload();
    }
    
    $scope.cancel = function() {
	    if ($scope.searchInput) {
		    $scope.searchInput = '';
	    }
    }
    
    $scope.$on('reload', function(s){API.tableParams.reload()});
    $scope.searchInput;
    $scope.$watch('searchInput',function(){
	    if ($scope.searchInput){
		    API.tableParams.$params.filter = {tag : $scope.searchInput};
		    API.tableParams.$params.page = 1;
		    API.tableParams.$params.count = 10;
		    API.tableParams.reload();
	    } else {
		    API.tableParams.$params.filter = {};
	    }
    })
    
    var templates = {
	    main : T('feeds.modal'), 
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
	$scope.edit = function (id) {
		openModal(id,'edit',API,templates,lexicon);
	}
	$scope.show = function (id) {
		openModal(id,'show',API,templates,lexicon);
	}
	$scope.remove = function (id) {
		openModal(id,'remove',API,templates,lexicon);
	}
	
	$scope.refresh = function () {
		API.reload_data();
	}
    
}])

.controller('usersCtrl', ['$scope','ngTableParams','Restangular','T','L','newModal',
function($scope, ngTableParams, Restangular,T,L,openModal) {
	var API = {};
	$scope.loading = true;
	API.url = 'admin/users';
	API.data = Restangular.all(API.url);
    API.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 10,           // count per page
        sorting: {},
        filter : {}
       }, {
        total: 0, // length of data
        getData: function($defer, params) {
            API.data.getList({page : API.tableParams.page(), 
            				 offset: API.tableParams.count(),
            				 order : API.tableParams.sorting(),
            				 filter: API.tableParams.filter()})
            .then(function(data){
            	$scope.loading = false;
            	params.total(data.total);
	            $defer.resolve(data.data);
            })
            .catch();
        }
    });
    $scope.tableParams = API.tableParams;
    API.reload_data = function(){
	    API.tableParams.reload();
    }
    
    $scope.usersCancel = function() {
	    if ($scope.searchInputUsers) {
		    $scope.searchInputUsers = '';
	    }
    }
    
    $scope.$on('reload', function(s){API.tableParams.reload()});
    $scope.searchInputUsers;
    $scope.$watch('searchInputUsers',function(){
	    if ($scope.searchInputUsers){
		    API.tableParams.$params.filter = {filter : $scope.searchInputUsers};
		    API.tableParams.$params.page = 1;
		    API.tableParams.$params.count = 10;
		    API.tableParams.reload();
	    } else {
		    API.tableParams.$params.filter = {};
	    }
    })

    var templates = {
	    main : T('users.modal'), 
    }
    
    
    var lexicon = {
	    headerTextShow : L('user.form.show'),
	    headerTextEdit : L('user.form.edit'),
	    headerTextLock : L('user.form.lock'),
	    headerTextUnlock : L('user.form.unlock'),
	    headerTextNew : L('user.form.addnew')
    }
    
    $scope.newUser = function() {
		openModal(false,'new',API,templates,lexicon);
	}
	$scope.edit = function (id) {
		openModal(id,'edit',API,templates,lexicon);
	}
	$scope.show = function (id) {
		openModal(id,'show',API,templates,lexicon);
	}
	$scope.lock = function (id) {
		openModal(id,'lock',API,templates,lexicon);
	}
	$scope.unlock = function (id) {
		openModal(id,'unlock',API,templates,lexicon);
	}
    $scope.refresh = function () {
		API.reload_data();
	}
}])

.controller('tagsCtrl', ['$scope','ngTableParams','Restangular','T','L','newModal',
function($scope, ngTableParams, Restangular,T,L,openModal) {
	var API = {};
	$scope.loading = true;
	API.url = 'admin/tags';
	API.data = Restangular.all(API.url);
    API.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 5,           // count per page
        sorting: {},
        filter : {}
       }, {
        total: 0, // length of data
        getData: function($defer, params) {
            API.data.getList({page : API.tableParams.page(), 
            				 offset: API.tableParams.count(),
            				 order : API.tableParams.sorting(),
            				 filter: API.tableParams.filter()})
            .then(function(data){
            	$scope.loading = false;
            	params.total(data.total);
	            $defer.resolve(data.data);
            })
            .catch();
        }
    });
    $scope.tableParams = API.tableParams;
    API.reload_data = function(){
	    API.tableParams.reload();
    }
    
    $scope.tagsCancel = function() {
	    if ($scope.searchInputTags) {
		    $scope.searchInputTags = '';
	    }
    }
    
    $scope.refresh = function () {
	    API.reload_data();
    }
    
    $scope.newTag = function () {
	    openModal(false,'new',API,templates,lexicon);
    }
    
    $scope.$on('reload', function(s){API.tableParams.reload()});
    $scope.searchInputUsers;
    $scope.$watch('searchInputTags',function(){
	    if ($scope.searchInputTags){
		    API.tableParams.$params.filter = {filter : $scope.searchInputTags};
		    API.tableParams.$params.page = 1;
		    API.tableParams.$params.count = 10;
		    API.tableParams.reload();
	    } else {
		    API.tableParams.$params.filter = {};
	    }
    })

    var templates = {
	    main : T('tags.modal'), 
    }
    
    
    var lexicon = {
	    headerTextShow : L('user.form.show'),
	    headerTextEdit : L('user.form.edit'),
	    headerTextLock : L('tag.form.lock'),
	    headerTextUnlock : L('tag.form.unlock'),
	    headerTextNew : L('tag.form.addnew')
    }
    
   	$scope.lock = function (id) {
		openModal(id,'lock',API,templates,lexicon);
	}
	$scope.unlock = function (id) {
		openModal(id,'unlock',API,templates,lexicon);
	}
    
}])
