(function(){
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
		
		function setData(key,rdata) {
			ch = true;
			data[key] = rdata;
			notify(key);
		}
		
		function getData (key) {
			return data[key];
		}
		
		function changed() {
			return ch;
		}
		
		function notify(key) {
			angular.forEach(observer[key], function(callback){	
		      	callback(getData(key));
		    });
			ch = false;
		}
	});
})();