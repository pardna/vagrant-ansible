angular.module('Pardna')
.factory('groupService', ['$http', 'env', GroupService]);

function GroupService($http, env) {
  var urlBase = env.apiUrl;
  var dataFactory = {};

  dataFactory.add = function(params) {
    return $http.post(urlBase + '/pardna/group', params);
  };

  dataFactory.changeSlot = function(params) {
    return $http.post(urlBase + '/pardna/group/slot/change', params);
  };

  dataFactory.update = function(params) {
    return $http.post(urlBase + '/pardna/group/edit/' + params.id, params);
  };

  dataFactory.confirmPardna = function(params) {
    return $http.get(urlBase + '/pardna/group/confirm/' + params.id);
  };

  dataFactory.list = function(params) {
    return $http.get(urlBase + '/pardna/group', params);
  };

  dataFactory.details = function(params) {
    // console.log(params);
    return $http.get(urlBase + '/pardna/group/details/' + params.id);
  };

  dataFactory.slots = function(params) {
    // console.log(params);
    return $http.get(urlBase + '/pardna/group/slots/' + params.id);
  };

  return dataFactory;
}
