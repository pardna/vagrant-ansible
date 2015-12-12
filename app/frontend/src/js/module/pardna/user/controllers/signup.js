angular.module('Pardna')
    .controller('SignupCtrl', ['$scope', '$window', '$state', 'userService', SignupCtrl]);

function SignupCtrl($scope, $window, $state, userService) {
  $scope.data = {};
  $scope.data.user = {};
  $scope.signup = function() {
    console.log($scope.data);
    userService.signup($scope.data).success(function(data) {
      userService.setToken(data.token);
      $state.go("home", {});

    }).error(function(error) {
      userService.deleteToken();
      $scope.status = 'Unable to load customer data: ' + error.message;
    });
  }
}
