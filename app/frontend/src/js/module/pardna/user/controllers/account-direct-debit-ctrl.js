angular.module('Pardna')
		.controller('AccountDirectDebitCtrl', ['$scope', '$window', '$state', '$mdToast', 'userService', 'localStorageService', 'jwtHelper', 'userService', 'groupService', 'paymentService', AccountDirectDebitCtrl]);


function AccountDirectDebitCtrl($scope, $window, $state, $mdToast, userService, localStorageService, jwtHelper, userService, groupService, paymentService) {
	$scope.user = userService.user;
	$scope.setupPayment = setupPayment;
	getUserBankAccounts();
	console.log($scope.user);

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

	function setupPayment() {
		paymentService.getPaymentUrl().success(function(data) {
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
