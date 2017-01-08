(function() {

angular.module('Pardna', ['app-parameters', 'ui.router', 'ngMaterial', 'ngMessages', 'LocalStorageModule', 'angular-jwt', 'angular-loading-bar']);

angular.module('Pardna').config(['$mdIconProvider', '$mdThemingProvider', function ($mdIconProvider, $mdThemingProvider, localStorageServiceProvider) {
  // localStorageServiceProvider.setStorageType('sessionStorage');
  $mdIconProvider.icon('md-toggle-arrow', 'img/icons/toggle-arrow.svg', 48);
  $mdThemingProvider.theme('dark-grey').backgroundPalette('grey').dark();
  $mdThemingProvider.theme('dark-orange').backgroundPalette('orange').dark();
  $mdThemingProvider.theme('dark-purple').backgroundPalette('deep-purple').dark();
  $mdThemingProvider.theme('dark-blue').backgroundPalette('blue').dark();
}]);



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
