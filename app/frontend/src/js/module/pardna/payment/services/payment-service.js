angular.module('Pardna')
.factory('paymentService', ['$http', 'env', PaymentService]);

function PaymentService($http, env) {
  var urlBase = env.apiUrl;
  var dataFactory = {};

  dataFactory.getPaymentUrl = function(groupDetails) {
    return $http.post(urlBase + '/group/payments/getPaymentUrl/' + groupDetails.id);
  };

  dataFactory.confirmPayment = function(params) {
    return $http.post(urlBase + '/payments/confirm', params);
  };

  return dataFactory;
}
