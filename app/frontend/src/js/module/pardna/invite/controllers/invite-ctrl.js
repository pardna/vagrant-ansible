angular.module('Pardna')
.controller('InviteCtrl', ['$scope', '$window', '$state', '$mdToast', '$mdDialog', 'jwtHelper', 'localStorageService', 'userService', 'inviteService', InviteCtrl]);

function InviteCtrl($scope, $window, $state, $mdToast, $mdDialog, jwtHelper, localStorageService, userService, inviteService) {

  $scope.user = userService.user;
  $scope.sendInvitations = sendInvitations;
  $scope.invite = {
    'message' : 'I would like to invite you to join pardna',
    'emails' : ''
  };

  function sendInvitations() {

    inviteService.add($scope.invite).then(
      function successCallback(response) {
        $mdToast.simple()
        .content('Invitations sent')
        .position("top right")
        .hideDelay(3000);

        $state.go("home", {});
      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Application error getting payment url')
          .position("top right")
          .hideDelay(3000)
        );
    });

    // inviteService.add($scope.invite).success(function(data) {
    //   $mdToast.simple()
    //     .content('Invitations sent')
    //     .position("top right")
    //     .hideDelay(3000);
    //
    //   $state.go("home", {});
    //
    //
    // }).error(function(error) {
    //   $mdToast.show(
    //         $mdToast.simple()
    //           .content('Save failed')
    //           .position("top right")
    //           .hideDelay(3000)
    //       );
    // });

  }

}
