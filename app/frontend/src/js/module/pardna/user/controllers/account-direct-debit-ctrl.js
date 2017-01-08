angular.module('Pardna')
		.controller('AccountDirectDebitCtrl', ['$scope', '$window', '$state', '$mdToast', 'userService', 'localStorageService', 'jwtHelper', 'userService', 'groupService', 'paymentService', AccountDirectDebitCtrl]);


function AccountDirectDebitCtrl($scope, $window, $state, $mdToast, userService, localStorageService, jwtHelper, userService, groupService, paymentService) {
	$scope.user = userService.user;
	$scope.redirectToPaymentsProvider = redirectToPaymentsProvider;
	getUserBankAccounts();
	console.log($scope.user);

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

	function redirectToPaymentsProvider() {
		var returnParams = {};
    returnParams.state_id = $state.current.name;
    returnParams.state_name = "Accounts";
    var params = {
			'return_to': returnParams
		};

		paymentService.getPaymentUrl(params).then(
      function successCallback(response) {
				$window.location.href = response.data.payment_url;
				//$scope.ui.groupInvitationList = data;
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
