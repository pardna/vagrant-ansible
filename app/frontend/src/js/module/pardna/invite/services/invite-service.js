angular.module('Pardna')
.factory('inviteService', ['$http', 'env', InviteService]);

function InviteService($http, env) {
  var urlBase = env.apiUrl;
  var dataFactory = {};

  dataFactory.add = function(params) {
    return $http.post(urlBase + '/invite', params);
  };

  dataFactory.getGroupInvitations = function(params) {
    return $http.get(urlBase + '/invite/group', params);
  };


  dataFactory.getUserInvitations = function(params) {
    return $http.get(urlBase + '/invite/user', params);
  };

  dataFactory.acceptUserInvitation = function(params) {
    return $http.post(urlBase + '/invite/accept/user', params);
  };

  dataFactory.acceptGroupInvitation = function(params) {
    return $http.post(urlBase + '/invite/accept/group', params);
  };

  dataFactory.ignoreUserInvitation = function(params) {
    return $http.post(urlBase + '/invite/ignore/user', params);
  };

  dataFactory.ignoreGroupInvitation = function(params) {
    return $http.post(urlBase + '/invite/ignore/group', params);
  };

  return dataFactory;
}
