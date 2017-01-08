angular.module('Pardna')
    .controller('PaymentSetupCtrl', ['$scope', '$window', '$stateParams', '$state', '$location', '$mdToast', '$mdDialog', 'paymentService', 'userService', PaymentSetupCtrl]);


function PaymentSetupCtrl($scope, $window, $stateParams, $state, $location, $mdToast, $mdDialog, paymentService, userService) {
  //var searchParams = $location.search();
  $scope.data = {
      group1 : 'Banana',
      group2 : '2',
      group3 : 'avatar-1'
    };
  $scope.avatarData = [{
        id: "avatars:svg-1",
        title: 'avatar 1',
        value: 'avatar-1'
      },{
        id: "avatars:svg-2",
        title: 'avatar 2',
        value: 'avatar-2'
      },{
        id: "avatars:svg-3",
        title: 'avatar 3',
        value: 'avatar-3'
    }];

  $scope.group_id = $stateParams.id;
  $scope.showConfirmBankAccount = showConfirmBankAccount;
  $scope.redirectToPaymentsProvider = redirectToPaymentsProvider;
  getUserBankAccounts();
  $scope.chooseBankAccount = chooseBankAccount;

  $scope.showDirectDebitTerms = function(bankaccount, ev) {
    $scope.chosenBankAccount = bankaccount;
    $mdDialog.show({
      controller: DialogController,
      templateUrl: 'module/pardna/payment/templates/directdebit_terms.tmpl.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true,
      scope: this,
      fullscreen: true // Only for -xs, -sm breakpoints.
    })
    .then(function(answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function() {
      $scope.status = 'You cancelled the dialog.';
    });
  };

  function DialogController($scope, $mdDialog) {
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

  function chooseBankAccount (bankaccount){
    $scope.chosenBankAccount = bankaccount;
    $state.go('paymentsetup-confirm', {id: $stateParams.id});
  }

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
      $state.go('paymentsetup-confirm', {id: $stateParams.id});
    }, function() {
      // $scope.status = 'You decided to keep your debt.';
    });
  };

  function getUserBankAccounts() {

    userService.getUserBankAccounts().then(
      function successCallback(response) {
        $scope.bankaccounts = response.data.bank_accounts;
      },
      function errorCallback(response) {
        $mdToast.show(
  				$mdToast.simple()
  				.content('Application error getting user bank accounts')
  				.position("top right")
  				.hideDelay(3000)
				);
    });

		// userService.getUserBankAccounts().success(function(data) {
		// 	$scope.bankaccounts = data.bank_accounts;
		// }).error(function(error) {
		// 	$mdToast.show(
		// 			$mdToast.simple()
		// 			.content('Application error getting user bank accounts')
		// 			.position("top right")
		// 			.hideDelay(3000)
		// 			);
		// });

	}

  function getBankAccountById(id)
  {
    var bankaccounts = $scope.bankaccounts;
    for (var i=0; i < bankaccounts.length; i++){
      if (bankaccounts[i].id == id){
        return bankaccounts[i];
      }
    }
  }

  function setupPayment(bankaccount) {
    var params = {};
    params.group_id = $stateParams.id;
    params.bank_account_id = bankaccount.id;

    userService.setupPayment(params).then(
      function successCallback(response) {

      },
      function errorCallback(response) {
        $mdToast.show(
  				$mdToast.simple()
  				.content('Application error setting up payments!')
  				.position("top right")
  				.hideDelay(3000)
				);
    });

		// userService.setupPayment(params).success(function(data) {
    //
		// }).error(function(error) {
		// 	$mdToast.show(
		// 			$mdToast.simple()
		// 			.content('Application error setting up payments!')
		// 			.position("top right")
		// 			.hideDelay(3000)
		// 			);
		// });

	}

  function redirectToPaymentsProvider(prms) {
    var returnParams = {};
    returnParams.state_id = $state.current.name;
    returnParams.state_name = "Setting_up_Pardna_group_Payments";
    returnParams.params = prms;
    var params = {
			'return_to': returnParams
		};
    console.log(params);

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
		// 	$window.location.href = data.payment_url;
		// 	//$scope.ui.groupInvitationList = data;
		// }).error(function(error) {
		// 	$mdToast.show(
		// 			$mdToast.simple()
		// 			.content('Application error getting payment url')
		// 			.position("top right")
		// 			.hideDelay(3000)
		// 			);
		// });

	}

}
