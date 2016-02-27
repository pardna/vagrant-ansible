angular.module('Pardna')
.controller('GroupDetailsCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', '$stateParams', 'jwtHelper', 'localStorageService', 'userService', 'groupService', GroupDetailsCtrl]);

function GroupDetailsCtrl($scope, $window, $mdToast, $mdDialog, $stateParams, jwtHelper, localStorageService, userService, groupService) {

  $scope.user = userService.user;
  $scope.ui = {data: {}};

  // $stateParams.id,

  function loadDetails(id) {
      groupService.details({id: id}).success(function(data) {
        $scope.ui.data = data;
      }).error(function(error) {
        $mdToast.show(
              $mdToast.simple()
                .content('Cannot load group')
                .position("top right")
                .hideDelay(3000)
            );
      });
  }

  console.log($stateParams.id);
  loadDetails($stateParams.id);

}
