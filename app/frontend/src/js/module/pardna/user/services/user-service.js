angular.module('Pardna')
    .factory('userService', ['$http', 'env', 'localStorageService', 'jwtHelper', UserService]);

function UserService($http, env, localStorageService, jwtHelper) {
  var urlBase = env.apiUrl;
          var dataFactory = {};
          dataFactory.user = {};
          dataFactory.token = localStorageService.get("id_token");

          dataFactory.signup = function(params) {
              return $http.post(urlBase + '/signup', params);
          };

          dataFactory.setToken = function(token) {
            if(token) {
              dataFactory.user = jwtHelper.decodeToken(token);
              localStorageService.set("id_token", token);
              dataFactory.token = token;
            }
          }

          dataFactory.getToken = function() {
            return dataFactory.token;
          }

          dataFactory.deleteToken = function() {
            dataFactory.token = null;
            localStorageService.remove("id_token");
          }

          dataFactory.login = function(params) {
              return $http.post(urlBase + '/login', params);
          };

          dataFactory.sendCode = function(params) {
              return $http.post(urlBase + '/user/send-code', params);
          };

          dataFactory.getRelationships = function(params) {
              return $http.get(urlBase + '/relationships', params);
          };


          dataFactory.verify = function(params) {
              return $http.post(urlBase + '/user/verify', params);
          };

          dataFactory.notifications = function(params) {
              return $http.get(urlBase + '/user/notifications');
          };

          dataFactory.setToken(dataFactory.token);

          return dataFactory;

}
