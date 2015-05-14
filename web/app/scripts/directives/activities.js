'use strict';

angular.module('pmtoolApp')
  .directive('activities', function (Activity, $location, Project, $routeParams) {
    return {
      templateUrl: 'views/activities.html',
      restrict: 'E',
      link: function(scope, element, attrs) {
        
        Activity.fetch().then(function(response){
          scope.activities = response;
		    }).catch(function(err){
		      scope.error = err.message;
		    });

        scope.path = $location.path(); 
        if(scope.path.indexOf('project')>0){
          Project.fetchProject($routeParams.id).then(function(response){
            scope.project = response;
          }).catch(function(err){
            console.log(err);
            scope.error = err.message;
          }); 
        }
      }
    };
  });
