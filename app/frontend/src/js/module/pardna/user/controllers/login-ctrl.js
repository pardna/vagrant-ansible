angular.module('Pardna')
.controller('LoginCtrl', ['$scope', '$window', '$state', '$mdToast', 'userService', 'localStorageService', 'jwtHelper', LoginCtrl]);


function LoginCtrl($scope, $window, $state, $mdToast, userService, localStorageService, jwtHelper) {
  $scope.data = {};
  $scope.data.user = {};

  $scope.login = function() {


    userService.login($scope.data.user).then(
      function successCallback(response) {
        // console.log(data);
        userService.setToken(response.data.token);
        // localStorageService.set("id_token", data.token);
        // var tokenPayload = jwtHelper.decodeToken(data.token);
        // console.log(tokenPayload);
        $state.go("home", {});
      },
      function errorCallback(response) {
        // console.log(error);
        userService.deleteToken();
        $mdToast.show(
          $mdToast.simple()
          .content('Login failed!')
          .position("top right")
          .hideDelay(3000)
        );
        $scope.status = 'Unable to load customer data: ' + response.data.message;
    });

    // userService.login($scope.data.user).success(function(data) {
    //   // console.log(data);
    //   userService.setToken(data.token);
    //   // localStorageService.set("id_token", data.token);
    //   // var tokenPayload = jwtHelper.decodeToken(data.token);
    //   // console.log(tokenPayload);
    //   $state.go("home", {});
    //
    //
    // }).error(function(error) {
    //   // console.log(error);
    //   userService.deleteToken();
    //   $mdToast.show(
    //         $mdToast.simple()
    //           .content('Login failed!')
    //           .position("top right")
    //           .hideDelay(3000)
    //       );
    //   $scope.status = 'Unable to load customer data: ' + error.message;
    // });

  }

}
