angular.module('common.lvHttp',[])

.provider('lvHttp', [function(){
	this.$get = function($http) {
		return function(url,query, method) {
			if (!method) {
				method = 'POST'
			}
			if (!query) {
				query = {};
			}
			if (query && url && method) {
				if (!_.isObject(query) ) {
					myQuery = { query : query };
				} else {
					myQuery = query;
				}
				return $http({method:method, url:url, data : myQuery})
			} else {
				return false;
			}
		}
}}])