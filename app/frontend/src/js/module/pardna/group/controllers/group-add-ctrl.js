angular.module('Pardna')
.controller('GroupAddCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', '$state', 'jwtHelper', 'localStorageService', 'userService', 'groupService', GroupAddCtrl]);

function GroupAddCtrl($scope, $window, $mdToast, $mdDialog, $state, jwtHelper, localStorageService, userService, groupService) {

  $scope.user = userService.user;
  $scope.ui = {};
  $scope.ui.relationships = [];


  $scope.pardna = {
    name : "",
    amount: 10,
    slots: 6,
    startdate: "",
    frequency: "monthly",
    emails: [{email: ""}, {email: ""}, {email: ""}]
  };

  $scope.friends = [];
  $scope.addEmail = addEmail;
  $scope.add = add;

 loadUserRelationships();

  function addEmail() {
    $scope.pardna.emails.push({email: ""});
  }

  function getPardna() {
    var pardna = angular.copy($scope.pardna);
    var emails = pardna.emails;
    pardna.emails = [];
    for(var i = 0; i < emails.length; i++) {
      if(emails[i].email !== "") {
        pardna.emails.push(emails[i].email);
      }
    }
    return pardna;
  }

  function loadUserRelationships() {
    userService.getRelationships({}).success(function(data) {
      $scope.ui.relationships = data;
    }).error(function(error) {
      $mdToast.show(
            $mdToast.simple()
              .content('Application error')
              .position("top right")
              .hideDelay(3000)
          );
    });
  }

 // alert("changed 1");

  function add() {
    var pardna = getPardna();
    groupService.add(pardna).success(function(data) {
      $mdToast.simple()
        .content('Pardna group created')
        .position("top right")
        .hideDelay(3000);

      $state.go("home", {});


    }).error(function(error) {

      console.log(error);
      var message = "Save failed";
      if(angular.isDefined(error.message)) {
        message = error.message;
      }
      $mdToast.show(
            $mdToast.simple()
              .content(message)
              .position("top right")
              .hideDelay(3000)
          );
    });
  }

  $scope.next = function() {
    $scope.data.selectedIndex = Math.min($scope.data.selectedIndex + 1, 2) ;
  };
  $scope.previous = function() {
    $scope.data.selectedIndex = Math.max($scope.data.selectedIndex - 1, 0);
  };
}
