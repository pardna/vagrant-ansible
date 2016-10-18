angular.module('Pardna')
		.controller('AccountDirectDebitCtrl', ['$scope', '$window', '$state', '$mdToast', 'userService', 'localStorageService', 'jwtHelper', 'userService', 'groupService', 'paymentService', AccountDirectDebitCtrl]);


function AccountDirectDebitCtrl($scope, $window, $state, $mdToast, userService, localStorageService, jwtHelper, userService, groupService, paymentService) {
	$scope.user = userService.user;
	$scope.setupPayment = setupPayment;

	console.log($scope.user);
	
	function setupPayment(params) {
		_setupPayment({id: 49});
	}

	function _setupPayment(params) {
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