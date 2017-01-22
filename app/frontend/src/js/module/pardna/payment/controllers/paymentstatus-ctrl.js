angular.module('Pardna')
    .controller('PaymentStatusCtrl', ['$scope', '$window', '$stateParams', '$state', '$location', '$mdToast', '$mdDialog', 'paymentService', 'userService', PaymentStatusCtrl]);


function PaymentStatusCtrl($scope, $window, $stateParams, $state, $location, $mdToast, $mdDialog, paymentService, userService) {
  $scope.group_id = $stateParams.id;
  getUserPaymentStatus($stateParams.id);

  function getUserPaymentStatus(id){
    paymentService.getUserGroupPaymentStatus({id: id}).then(
      function successCallback(response) {
        handleUserGroupStatusResponse(response.data.payment_status);
      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Application error getting user payment status')
          .position("top right")
          .hideDelay(3000)
        );
    });
  }

  function handleUserGroupStatusResponse(payment_status){
    if (payment_status.setup_completed){
      $scope.payment_status = payment_status.status;
      $scope.selectedbankaccount = payment_status.payment_account;
      $scope.allow_change_account = true;
    } else{
      $state.go('payment-setup', {id: $stateParams.id});
    }
  }

  $scope.showBankAccountDetails = function(bankaccount, ev) {
    $scope.chosenBankAccount = bankaccount;
    $scope.allowSetupPayment = false;
    $mdDialog.show({
      controller: PaymentStatusCtrl,
      templateUrl: 'module/pardna/payment/templates/bankaccount-details.tmpl.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true,
      scope: $scope,        // use parent scope in template
      preserveScope: true,
      fullscreen: true // Only for -xs, -sm breakpoints.
    })
    .then(function(answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function() {
      $scope.status = 'You cancelled the dialog.';
    });
  };

  $scope.hide = function() {
    $mdDialog.hide();
  };

  $scope.cancel = function() {
    $mdDialog.cancel();
  };

  $scope.answer = function(answer) {
    $mdDialog.hide(answer);
  };


}
