angular.module('Pardna')
.controller('GroupDetailsCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', '$filter', '$stateParams', 'jwtHelper', 'localStorageService', 'userService', 'groupService', GroupDetailsCtrl]);

function GroupDetailsCtrl($scope, $window, $mdToast, $mdDialog, $filter, $stateParams, jwtHelper, localStorageService, userService, groupService) {

  $scope.user = userService.user;
  $scope.ui = {data: {}};
  $scope.showConfirm = showConfirm;

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

  function showConfirm(ev) {
    // Appending dialog to document.body to cover sidenav in docs app
    var confirm = $mdDialog.confirm()
          .title('Would you like to claim this slot ?')
          .content('If you have a previously claimed slot, it will be <span class="debt-be-gone">released</span>.')
          .ariaLabel('Claim Slot')
          .targetEvent(ev)
          .ok('Ok')
          .cancel('Cancel');
    $mdDialog.show(confirm).then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  };
  
    
  function loadSlots(id) {
      groupService.slots({id: id}).success(function(data) {
        $scope.ui.slots = data;  
          
      }).error(function(error) {
        $mdToast.show(
              $mdToast.simple()
                .content('Cannot load slots')
                .position("top right")
                .hideDelay(3000)
            );
      });
  }    


// Format the start date that is returned from the database    
function formatDate($scope) {
    $scope.v = {
        pay_date: Date.parse()
    }
}
    
    
    
  console.log($scope.user);
  loadDetails($stateParams.id);
  loadSlots($stateParams.id);

}
