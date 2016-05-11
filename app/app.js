var app = angular.module('myApp', ['ngRoute']);

app.factory("services", ['$http', function($http) {
	var serviceBase = 'services/'
    var obj = {};
    obj.getUsers = function(){
        return $http.get(serviceBase + 'users/view?id=');
    }
    obj.getUser = function(userID){
        return $http.get(serviceBase + 'users/view?id=' + userID);

    }
    obj.insertUser = function (user) {
    return $http.post(serviceBase + 'users/insert', user).then(function (results) {
        return results;
    });
	};
	
	obj.updateUser = function (id,user) {
	    return $http.post(serviceBase + 'users/update', {id:id, user:user}).then(function (status) {
	        return status.data;
	    });
	};
	
	obj.deleteUser = function (id) {
	    return $http.delete(serviceBase + 'users/delete?id=' + id).then(function (status) {
	        return status.data;
	    });
	};
	
    return obj;   
}]);

//déclaration des contrôleurs
app.controller('listCtrl', function ($scope, services) {
    services.getUsers().then(function(data){
        $scope.users = data.data;
    });
});

app.controller('editCtrl', function ($scope, $rootScope, $location, $routeParams, services, user) {
    var userID = ($routeParams.userID) ? parseInt($routeParams.userID) : 0;
    $rootScope.title = (userID > 0) ? 'Edit User' : 'إضافة مستعمل';
    $scope.buttonText = (userID > 0) ? 'Update User' : 'أضف مستعمل';
      var original = user.data;
      original._id = userID;
      $scope.user = angular.copy(original);
      $scope.user._id = userID;

      $scope.isClean = function() {
        return angular.equals(original, $scope.user);
      }

      $scope.deleteUser = function(user) {
        $location.path('/');
        if(confirm("Are you sure to delete user number: "+$scope.user._id)==true)
        services.deleteUser(user.id);
      };

      $scope.saveUser = function(user) {
        $location.path('/');
        if (userID <= 0) {
            services.insertUser(user);
        }
        else {
            services.updateUser(userID, user);
        }
    };
});

app.config(['$routeProvider',
  function($routeProvider) {
    $routeProvider.
      when('/', {
        title: 'Users',
        templateUrl: 'partials/users.html',
        controller: 'listCtrl'
      })
      .when('/edit-user/:userID', {
        title: 'Edit Users',
        templateUrl: 'partials/edit-user.html',
        controller: 'editCtrl',
        resolve: {
          user: function(services, $route){
            var userID = $route.current.params.userID;
            return services.getUser(userID);
          }
        }
      })
      .otherwise({
    	title : 'other',
        redirectTo: '/'
      });
}]);

app.run(['$location', '$rootScope', function($location, $rootScope) {
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
    	/*var props=""
    	for (prop in current.$$route){ props+= prop +  " => " +current[prop] + "\n"; }
    	alert (props)*/
        $rootScope.title = current.$$route.title;
    });
}]);