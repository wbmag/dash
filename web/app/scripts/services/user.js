'use strict';

angular.module('pmtoolApp')
  .service('UserService', function ($q, $http, $resource, $rootScope) {

	  this.postLogin = function (user) { 

      var deferred = $q.defer();  

	    $http.post('/api/user/login', user)
      .success(function(response){
        deferred.resolve(response);
      })
      .error(function(err) {
        deferred.reject(err);
      });

      return deferred.promise;
    };
	
    //Sends request to API and User Logs out from the APPLICATION
    this.signout = function() {
    
      var deferred = $q.defer();  
      
      $http.get('/api/user/logout')
      .success(function(response){
        deferred.resolve(response);
      })
      .error(function(err) {
        deferred.reject(err);
      });

      return deferred.promise;
    };

    // To update the userprofile who is currently logged in
    this.updateProfile = function (userData) {

      var userId = userData.id;

      var deferred = $q.defer();  
      
      console.log(userData);
      $http.put('/api/user/'+userId, userData)
      .success(function(response){
        deferred.resolve(response);
      })
      .error(function(err) {
        deferred.reject(err);
      });

      return deferred.promise;
    };

    this.forgotPassword = function(){
      console.log('forgot-password');
    };

    //To fetch all the user on the App
    this.fetch = function(){

      var deferred = $q.defer();

      $http.get('api/user')
      .success(function(response){
        deferred.resolve(response);
      }).error(function(err) {
        deferred.reject(response);
      });

      return deferred.promise;
    };

    // When User receive invitation in mail, then For the first time user set the Name and Password
    this.setPassword = function(id,data){

      //accept the name and password as parameter and send as Object to API
      var userData = {name: data.name, password : data.password};
      
      var deferred = $q.defer();
      
      $http.put('api/user/'+id, userData)
      .success(function(response){
        deferred.resolve(response);
      }).error(function(err) {
        deferred.reject(err);
      });

      return deferred.promise;
    };

    //to get the status of the user logged in
    this.isLoggedIn = function(){
      var deferred = $q.defer();
      
      $http.get('api/status')
      .success(function(result){
        $rootScope.user = result;
        deferred.resolve();
      })
      .error(function(err){
        console.log('logged out status', err);
        deferred.reject(err);
      });
      
      return deferred.promise;
    }

    this.uploadAvatar = function(data){
        
        console.log('in', data);      
        var deferred = $q.defer();
        $http.post('api/avatar',data)
        .success(function(response){
          deferred.resolve(response);
          console.log(response);
        })
        .error(function(err) {
          deferred.reject(err);
        });

      return deferred.promise;
    }

  }
);