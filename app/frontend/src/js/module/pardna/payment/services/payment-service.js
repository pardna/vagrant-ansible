angular.module('Pardna')
.factory('paymentService', ['$http', 'env', PaymentService]);

function PaymentService($http, env) {
  var urlBase = env.apiUrl;
  var dataFactory = {};

  dataFactory.getPaymentUrl = function(params) {
    return $http.post(urlBase + '/group/payments/getPaymentUrl', params);
  };

  dataFactory.confirmPayment = function(params) {
    return $http.post(urlBase + '/payments/confirm', params);
  };

  dataFactory.setupPayment = function(params) {
    return $http.post(urlBase + '/group/payment/setup', params);
  };

  return dataFactory;
}
