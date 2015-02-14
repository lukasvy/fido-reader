angular.module('common.registry',[])

.factory('lvRegistry', function(){
	
	var data = {};
	var ch = false;
	var observer = [];

	return {
		set : setData,
		register : register
	};
	
	function register(key,callback) {
		if (observer[key]) {
			observer[key].push(callback);
		} else {
			array = [];
			observer[key] = [];
			observer[key].push(callback);
		}
	}
	
	var setData = function(key,rdata) {
		ch = true;
		data[key] = rdata;
		notify(key);
	}
	
	var getData = function(key) {
		return data[key];
	}
	
	var changed = function() {
		return ch;
	}
	
	var notify = function(key) {
		angular.forEach(observer[key], function(callback){	
	      	callback(getData(key));
	    });
		ch = false;
	};
});
