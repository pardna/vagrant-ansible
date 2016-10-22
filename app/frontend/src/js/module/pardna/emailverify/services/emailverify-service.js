angular.module('Pardna')
.factory('emailVerifyService', ['$http', 'env', EmailVerifyService]);

function EmailVerifyService($http, env) {
  var urlBase = env.apiUrl;
  var dataFactory = {};

  dataFactory.verifyEmail = function(params) {
    return $http.post(urlBase + '/user/verify-email', params);
  };

  return dataFactory;
}
