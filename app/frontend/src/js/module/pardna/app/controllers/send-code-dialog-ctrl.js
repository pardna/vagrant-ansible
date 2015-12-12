angular.module('Pardna')
.controller('SendCodeDialogCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', 'jwtHelper', 'localStorageService', 'userService', SendCodeDialogCtrl]);


function SendCodeDialogCtrl($scope, $window, $mdToast, $mdDialog, jwtHelper, localStorageService, userService) {

  $scope.user = userService.user;

  $scope.view = {};
  $scope.page = "send";
  $scope.hide = function() {
    $mdDialog.hide();
  };


  $scope.sendCode = function(form) {
    if(form.$valid) {
      userService.sendCode($scope.user).success(function(data) {
        // $state.go("home");
        // $scope.page = "confirm";
        $mdToast.show(
          $mdToast.simple()
          .content(data.message)
          .position("top right")
          .hideDelay(3000)
          );

          userService.setToken(data.token);

          $mdDialog.hide();


      }).error(function(error) {
        $scope.status = 'Error occure : ';

        if(typeof error.message !== "undefined") {
          $scope.status = $scope.status + error.message;
        }

        $mdToast.show(
          $mdToast.simple()
          .content($scope.status)
          .position("top right")
          .hideDelay(3000)
          );

      });
  }

  };

  $scope.show = function(page) {
    return page == $scope.page;
  }

  $scope.verify = function(form) {
    if(form.$valid) {
      $scope.user.mobile = userService.user.mobile;
    userService.verify($scope.user).success(function(data) {
        userService.setToken(data.token);
      $mdToast.show(
        $mdToast.simple()
        .content(data.message)
        .position("top right")
        .hideDelay(3000)
        );


        $mdDialog.hide();

                    $scope.user = userService.user;
                    console.log($scope.user);

        $scope.user.verified = userService.user.verified;

    }).error(function(error) {
      $scope.status = 'Error occured : ';
      if(typeof error.message !== "undefined") {
        $scope.status = $scope.status + error.message;
      }

      $mdToast.show(
        $mdToast.simple()
        .content($scope.status)
        .position("top right")
        .hideDelay(3000)
        );
    });
    $scope.page = "confirm";
  };
}

}
