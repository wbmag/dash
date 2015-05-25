'use strict';

angular.module('pmtoolApp')
  .service('Activity', function Activity($q, $http, $resource) {
    // AngularJS will instantiate a singleton by calling "new" on this function
  
  	this.fetch = function(id){
		var deferred = $q.defer();
		var url = '/api/activity/';
		if(id)
			url = url + id;
		
		$http.get(url)
		.success(function(data){
			deferred.resolve(data);
		})
		.error(function(err){
			deferred.reject(err);
		});
		
		return deferred.promise;
  	}

  	this.addActivity = function(activityData){
  		var deferred = $q.defer();
		
		$http.post('/api/activity', activityData)
		.success(function(activity){
			deferred.resolve(activity);
		})
		.error(function(err){
			deferred.reject(err);
		});

		return deferred.promise;
	}
  });
