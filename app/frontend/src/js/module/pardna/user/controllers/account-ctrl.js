angular.module('Pardna')
		.controller('AccountCtrl', ['$scope', '$window', '$state', '$mdToast', 'userService', 'localStorageService', 'jwtHelper', AccountCtrl]);


function AccountCtrl($scope, $window, $state, $mdToast, userService, localStorageService, jwtHelper) {
	var tabs = [
		{title: 'User Details', id: "account-user", content: "Tabs will become paginated if there isn't enough room for them."},
		{title: 'Bank Accounts - Direct Debits', id: "account-direct-debit", content: "You can swipe left and right on a mobile device to change tabs."},
		{title: 'Bank Accounts - Pay Outs', id: "account-payout", content: "You can bind the selected tab via the selected attribute on the md-tabs element."}
	];
	
	var selected = null;
	var previous = null;
	$scope.tabs = tabs;
	$scope.selectedIndex = 2;
	$scope.$watch('selectedIndex', function(current, old) {
		previous = selected;
		selected = tabs[current];
	});

	$scope.addTab = function(title, view) {
		view = view || title + " Content View";
		tabs.push({title: title, content: view, disabled: false});
	};

	$scope.removeTab = function(tab) {
		var index = tabs.indexOf(tab);
		tabs.splice(index, 1);
	};

}
