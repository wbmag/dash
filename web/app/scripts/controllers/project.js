'use strict';

angular.module('pmtoolApp')
  .controller('projectController', function ($scope, Project) {
	
	Project.fetch().then(function(response){
		$scope.projects = response;
	}).catch(function(err){
		$scope.error = err.message;
	});

	$scope.addNewProject = function(data){

		Project.add(data).then(function(response){
			$scope.projects.push(response);
		}).catch(function(err){
			$scope.error = err.message;
		});
	}

	$scope.deleteProject = function(id){
		Project.delete(id).then(function(response){
			//fetch updated project list
			Project.fetch().then(function(response){
				$scope.projects = response;
			}).catch(function(err){
				$scope.error = err.message;
			});
		}).catch(function(err){
			$scope.error = err.message;
		})
	}
});