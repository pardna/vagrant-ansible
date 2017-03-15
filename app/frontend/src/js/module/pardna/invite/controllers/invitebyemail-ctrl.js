angular.module('Pardna')
.controller('InviteByEmailCtrl', ['$scope', '$window', '$state', '$mdToast', '$mdDialog', 'jwtHelper', 'localStorageService', 'userService', 'inviteService', InviteByEmailCtrl]);

function InviteByEmailCtrl($scope, $window, $state, $mdToast, $mdDialog, jwtHelper, localStorageService, userService, inviteService) {

  $scope.user = userService.user;
  $scope.sendInvitations = sendInvitations;


  function sendInvitations() {
    if ($scope.group){
      $scope.invite.group = $scope.group;
    }
    inviteService.add($scope.invite).then(
      function successCallback(response) {
        if ($scope.modal){
          $scope.inviteserviceresponse.success = true;
          $scope.invite.emails = [];
        } else {
          $mdToast.simple()
          .content('Invitations sent')
          .position("top right")
          .hideDelay(3000);

          // if (){
          //
          // } else {
            $state.go("home", {});
          //}
       }
      },
      function errorCallback(response) {
        if ($scope.modal){
          $scope.inviteserviceresponse.success = false;
        } else {
          $mdToast.show(
            $mdToast.simple()
            .content('Application error getting payment url')
            .position("top right")
            .hideDelay(3000)
          );
        }
    });

  }

}
