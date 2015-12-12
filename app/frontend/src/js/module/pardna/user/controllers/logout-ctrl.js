angular.module('Pardna')
.controller('LogoutCtrl', ['$scope', '$window', '$state', '$mdToast', 'userService', 'localStorageService', 'jwtHelper', LogoutCtrl]);


function LogoutCtrl($scope, $window, $state, $mdToast, userService, localStorageService, jwtHelper) {
  userService.deleteToken();
  // e.preventDefault();
  $state.go('login');
}
