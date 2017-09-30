angular.module('Pardna')
.controller('GroupDetailsCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', '$filter', '$stateParams', 'jwtHelper', 'localStorageService', 'userService', 'groupService', 'paymentService', GroupDetailsCtrl]);

function GroupDetailsCtrl($scope, $window, $mdToast, $mdDialog, $filter, $stateParams, jwtHelper, localStorageService, userService, groupService, paymentService) {

  $scope.user = userService.user;
  $scope.ui = {data: {}};
  $scope.showConfirm = showConfirm;
  $scope.group_id = $stateParams.id;
  function loadDetails(id) {

    groupService.details({id: id}).then(
      function successCallback(response) {
        $scope.ui.data = response.data;
        $scope.group_name = $scope.ui.data.name;
      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Cannot load group')
          .position("top right")
          .hideDelay(3000)
        );
    });

      // groupService.details({id: id}).success(function(data) {
      //   $scope.ui.data = data;
      //   $scope.group_name = $scope.ui.data.name;
      // }).error(function(error) {
      //   $mdToast.show(
      //         $mdToast.simple()
      //           .content('Cannot load group')
      //           .position("top right")
      //           .hideDelay(3000)
      //       );
      // });

  }

  function showConfirm(slot) {
    // Appending dialog to document.body to cover sidenav in docs app
    var confirm = $mdDialog.confirm()
          .title('Would you like to claim this slot ll ?')
          .content('If you have a previously claimed slot, it will be released.')
          .ariaLabel('Claim Slot')
          .targetEvent(slot)
          .ok('Ok')
          .cancel('Cancel');
    $mdDialog.show(confirm).then(function(d) {
      changeSlot(slot);
  //    console.log(slot);
      // alert("confirmed");
      // console.log(d);
      // setupPayment({id: $scope.ui.data.id});
      //$scope.status = 'You decided to get rid of your debt.';
    }, function() {
      // $scope.status = 'You decided to keep your debt.';
    });
  };

  function changeSlot(slot) {

    groupService.changeSlot(slot).then(
      function successCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content("You have claimed slot " + slot.position)
          .position("top right")
          .hideDelay(3000)
        );
        loadSlots(slot.pardnagroup_id);

      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content(response)
          .position("top right")
          .hideDelay(3000)
        );
    });

  }


  function loadSlots(id) {

    groupService.slots({id: id}).then(
      function successCallback(response) {
        $scope.ui.slots = response.data;
      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Cannot load slots')
          .position("top right")
          .hideDelay(3000)
        );
    });

      // groupService.slots({id: id}).success(function(data) {
      //   $scope.ui.slots = data;
      //
      // }).error(function(error) {
      //   $mdToast.show(
      //         $mdToast.simple()
      //           .content('Cannot load slots')
      //           .position("top right")
      //           .hideDelay(3000)
      //       );
      // });

  }

  function setupPayment(params){

    paymentService.getPaymentUrl(params).then(
      function successCallback(response) {
        $window.location.href = response.data.payment_url;
      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Application error getting payment url')
          .position("top right")
          .hideDelay(3000)
        );
    });

    // paymentService.getPaymentUrl(params).success(function(data) {
    //   $window.location.href = data.payment_url;
    //   //$scope.ui.groupInvitationList = data;
    // }).error(function(error) {
    //   $mdToast.show(
    //         $mdToast.simple()
    //           .content('Application error getting payment url')
    //           .position("top right")
    //           .hideDelay(3000)
    //       );
    // });

  }
  // Format the start date that is returned from the database
  function formatDate($scope) {
      $scope.v = {
          pay_date: Date.parse()
      }
  }



  // console.log($scope.user);
  loadDetails($stateParams.id);
  loadSlots($stateParams.id);

}
