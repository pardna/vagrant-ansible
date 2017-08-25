angular.module('Pardna')
.controller('GroupAddCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', '$state', '$stateParams', 'jwtHelper', 'localStorageService', 'userService', 'groupService', GroupAddCtrl]);

function GroupAddCtrl($scope, $window, $mdToast, $mdDialog, $state, $stateParams, jwtHelper, localStorageService, userService, groupService) {

  $scope.user = userService.user;
  $scope.ui = {};
  $scope.ui.relationships = [];
  // $scope.group_id = $stateParams.id;
  $scope.data = {
    selectedIndex: 0,
    secondLocked:  true,
    secondLabel:   "Item Two",
    bottom:        false
  };

  console.log("group edit " + $stateParams.id);

if(angular.isDefined($stateParams.id)) {
  loadDetails($stateParams.id);
} else {
  $scope.pardna = {
    name : "",
    amount: 10,
    slots: 6,
    startdate: "",
    frequency: "monthly",
    emails: [{email: ""}, {email: ""}, {email: ""}]
  };

}


  $scope.friends = [];
  $scope.addEmail = addEmail;
  $scope.add = add;

 loadUserRelationships();

  function addEmail() {
    $scope.pardna.emails.push({email: ""});
  }

  function getPardna() {
    var pardna = angular.copy($scope.pardna);

    pardna.emails = [];
    if(angular.isDefined(pardna.emails)) {
      var emails = pardna.emails;
    for(var i = 0; i < emails.length; i++) {
      if(emails[i].email !== "") {
        pardna.emails.push(emails[i].email);
      }
    }
  }
    return pardna;
  }

  function loadUserRelationships() {

    userService.getRelationships({}).then(
      function successCallback(response) {
        $scope.ui.relationships = response.data;
      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Application error')
          .position("top right")
          .hideDelay(3000)
        );
    });

    // userService.getRelationships({}).success(function(data) {
    //   $scope.ui.relationships = data;
    // }).error(function(error) {
    //   $mdToast.show(
    //         $mdToast.simple()
    //           .content('Application error')
    //           .position("top right")
    //           .hideDelay(3000)
    //       );
    // });

  }

 // alert("changed 1");

 function loadDetails(id) {

   groupService.details({id: id}).then(
     function successCallback(response) {
       $scope.pardna = response.data;
       // $scope.group_name = $scope.ui.data.name;
     },
     function errorCallback(response) {
       $mdToast.show(
         $mdToast.simple()
         .content('Cannot load group')
         .position("top right")
         .hideDelay(3000)
       );
   });
 }

  function add() {
    if(angular.isDefined($stateParams.id)) {
      return update();
    }
    var pardna = getPardna();

    groupService.add(pardna).then(
      function successCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Pardna group created')
          .position("top right")
          .hideDelay(3000)
        );

        console.log("added ");
        console.log(response);

        $scope.pages.home.selectedTab = 1;

        $state.go("home", {});
      },
      function errorCallback(response) {
        console.log(response.data);
        var message = "Save failed";
        if(angular.isDefined(response.data.message)) {
          message = response.data.message;
        }
        $mdToast.show(
          $mdToast.simple()
          .content(message)
          .position("top right")
          .hideDelay(3000)
        );
    });

  }

  function update() {
    var pardna = getPardna();

    groupService.update(pardna).then(
      function successCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Pardna group updated')
          .position("top right")
          .hideDelay(3000)
        );

        $state.go("home", {});
      },
      function errorCallback(response) {
        // console.log(response.data);
        var message = "Update failed";
        if(angular.isDefined(response.data.message)) {
          message = response.data.message;
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
