angular.module('Pardna')
.controller('GroupAddCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', 'jwtHelper', 'localStorageService', 'userService', GroupAddCtrl]);

function GroupAddCtrl($scope, $window, $mdToast, $mdDialog, jwtHelper, localStorageService, userService) {

  $scope.user = userService.user;


  // Add new pardna
  $scope.data = {
    selectedIndex: 0,
    secondLocked:  true,
    secondLabel:   "Item Two",
    bottom:        false
  };
  $scope.next = function() {
    $scope.data.selectedIndex = Math.min($scope.data.selectedIndex + 1, 2) ;
  };
  $scope.previous = function() {
    $scope.data.selectedIndex = Math.max($scope.data.selectedIndex - 1, 0);
  };

  $scope.color = {
    red: Math.floor(Math.random() * 255),
    green: Math.floor(Math.random() * 255),
    blue: Math.floor(Math.random() * 255)
  };
  $scope.rating1 = 3;
  $scope.rating2 = 2;
  $scope.rating3 = 4;
  $scope.disabled1 = 0;
  $scope.disabled2 = 70;


  $scope.view = {};
  $scope.view.tabs = [
    {
      "icon_class": "fa fa-user",
      "label": "Your Details",
      "template": ""
    },
    {
      "icon_class": "fa fa-gbp",
      "label": "Pardna Details",
      "template": ""
    },
    {
      "icon_class": "fa fa-lock",
      "label": "Direct Debit",
      "template": ""
    },
    {
      "icon_class": "fa fa-users",
      "label": "Invite Friends",
      "template": ""
    },
  ]

}
