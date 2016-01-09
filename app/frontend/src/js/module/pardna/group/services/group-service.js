angular.module('Pardna')
.factory('groupService', ['$http', 'env', GroupService]);

function GroupService($http, env) {
  var urlBase = env.apiUrl;
  var dataFactory = {};

  dataFactory.add = function(params) {
    return $http.post(urlBase + '/pardna/group', params);
  };
   
  dataFactory.list = function(params) {
    return $http.get(urlBase + '/pardna/group', params);
  };

  return dataFactory;
}
