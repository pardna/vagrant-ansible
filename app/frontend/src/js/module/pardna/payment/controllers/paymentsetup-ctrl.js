angular.module('Pardna')
    .controller('PaymentSetupCtrl', ['$scope', '$window', '$stateParams', '$state', '$location', '$mdToast', '$mdDialog', 'paymentService', 'userService', PaymentSetupCtrl]);


function PaymentSetupCtrl($scope, $window, $stateParams, $state, $location, $mdToast, $mdDialog, paymentService, userService) {
  //var searchParams = $location.search();
  $scope.group_id = $stateParams.id;
  $scope.showConfirmBankAccount = showConfirmBankAccount;
  $scope.redirectToPaymentsProvider = redirectToPaymentsProvider;
  getUserBankAccounts();

  function showConfirmBankAccount(bankaccount) {
    // Appending dialog to document.body to cover sidenav in docs app
    var confirm = $mdDialog.confirm()
          .title('Would you like to select this account ?')
          .content('By selecting this account, all direct debits will be taken from this account, etc...')
          .ariaLabel('Claim Slot')
          .targetEvent(bankaccount)
          .ok('Ok')
          .cancel('Cancel');
    $mdDialog.show(confirm).then(function(d) {
      var params = {
        bank_account_id: bankaccount.id,
        group_id: $stateParams.id
      };
      paymentService.setupPayment(params);
      $state.go('payment-setup.confirm', {id: $stateParams.id});
    }, function() {
      // $scope.status = 'You decided to keep your debt.';
    });
  };

  function getUserBankAccounts() {
		userService.getUserBankAccounts().success(function(data) {
			$scope.bankaccounts = data.bank_accounts;
		}).error(function(error) {
			$mdToast.show(
					$mdToast.simple()
					.content('Application error getting user bank accounts')
					.position("top right")
					.hideDelay(3000)
					);
		});
	}

  function setupPayment(bankaccount) {
    var params = {};
    params.group_id = $stateParams.id;
    params.bank_account_id = bankaccount.id;
		userService.setupPayment(params).success(function(data) {

		}).error(function(error) {
			$mdToast.show(
					$mdToast.simple()
					.content('Application error setting up payments!')
					.position("top right")
					.hideDelay(3000)
					);
		});
	}

  function redirectToPaymentsProvider(prms) {
    var returnParams = {};
    returnParams.state_id = $state.current.name;
    returnParams.state_name = "Setting_up_Pardna_group_Payments";
    returnParams.params = prms;
    var params = {
			'return_to': returnParams
		};
    console.log(params)
		paymentService.getPaymentUrl(params).success(function(data) {
			$window.location.href = data.payment_url;
			//$scope.ui.groupInvitationList = data;
		}).error(function(error) {
			$mdToast.show(
					$mdToast.simple()
					.content('Application error getting payment url')
					.position("top right")
					.hideDelay(3000)
					);
		});
	}

}
