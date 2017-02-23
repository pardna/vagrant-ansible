angular.module('Pardna')
.controller('InviteCtrl', ['$scope', '$window', '$state', '$mdToast', '$mdDialog', 'jwtHelper', 'localStorageService', 'userService', 'inviteService', InviteCtrl]);

function InviteCtrl($scope, $window, $state, $mdToast, $mdDialog, jwtHelper, localStorageService, userService, inviteService) {

  $scope.user = userService.user;
  $scope.invite = {
    'message' : 'I would like to invite you to join pardna',
    'emails' : ''
  };

}
