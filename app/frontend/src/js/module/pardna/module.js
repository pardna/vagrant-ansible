(function() {

angular.module('Pardna', ['app-parameters', 'ui.router', 'ngMaterial', 'ngMessages', 'LocalStorageModule', 'angular-jwt', 'angular-loading-bar', 'ng-mfb']);

angular.module('Pardna').config(['$mdIconProvider', '$mdThemingProvider', function ($mdIconProvider, $mdThemingProvider, localStorageServiceProvider) {
  // localStorageServiceProvider.setStorageType('sessionStorage');
  $mdIconProvider.icon('md-toggle-arrow', 'img/icons/toggle-arrow.svg', 48);
  $mdIconProvider.icon('md-close', 'img/icons/ic_close_black_24px.svg', 24);
  $mdThemingProvider.theme('dark-grey').backgroundPalette('grey').dark();
  $mdThemingProvider.theme('dark-orange').backgroundPalette('orange').dark();
  $mdThemingProvider.theme('dark-purple').backgroundPalette('deep-purple').dark();
  $mdThemingProvider.theme('dark-blue').backgroundPalette('blue').dark();
}])
.directive('bankaccountCard', function () {
  return {
    restrict: 'E',
    templateUrl: 'module/pardna/payment/templates/bankaccountCard.tmpl.html',
    scope: {
      name: '@',
      theme: '@',
      accountnumberending: '@',
      bankname: '@',
      accountholdername: '@'
    },
    controller: function ($scope) {
      $scope.theme = $scope.theme || 'default';
    }
  }
})
.directive('invitebyemailCard', function () {
  return {
    restrict: 'EA',
    templateUrl: 'module/pardna/invite/templates/invitebyemailCard.tmpl.html',
    scope: {
      group: '=group',
      invite: '=invite',
      inviteserviceresponse: "=inviteserviceresponse",
      modal: '@'
    },
    controller: InviteByEmailCtrl
  }
});

angular.module('Pardna').run(function($rootScope, $state, localStorageService, jwtHelper, userService) {
  $rootScope.$on('$stateChangeStart', function(e, to) {
    if (typeof to.requiresLogin !== "undefined" && to.requiresLogin === true) {
      console.log("requires login");
      console.log(userService.getToken());
      if (!userService.getToken() || jwtHelper.isTokenExpired(userService.getToken())) {
        e.preventDefault();
        $state.go('login');
      }
    }
  });
});

angular.module('Pardna').config(function Config($httpProvider, jwtInterceptorProvider) {
  jwtInterceptorProvider.authHeader = "X-Access-Token";
  jwtInterceptorProvider.authPrefix = "Bearer";
  // Please note we're annotating the function so that the $injector works when the file is minified
  jwtInterceptorProvider.tokenGetter = ['localStorageService', 'userService', function(localStorageService, userService) {
    // myService.doSomething();
    return userService.getToken();
  }];


  $httpProvider.interceptors.push('jwtInterceptor');
});

}.call(this));
