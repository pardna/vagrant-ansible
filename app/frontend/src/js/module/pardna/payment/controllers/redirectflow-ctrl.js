angular.module('Pardna')
    .controller('RedirectFlowCtrl', ['$scope', '$location', '$mdToast', 'paymentService', RedirectFlowCtrl]);


function RedirectFlowCtrl($scope, $location, $mdToast, paymentService) {
  //var searchParams = $location.search();
  var searchParams = {};
  searchParams.redirect_flow_id = getParameterByName("redirect_flow_id");
  searchParams.membership_number = getParameterByName("membership_number");
  searchParams.group_id = getParameterByName("group_id");
  //searchParams.signature = getParameterByName("signature");
  // console.log(searchParams);
  if (searchParams && searchParams !== "null" && searchParams !== "undefined"){
    confirmPayment(searchParams);
  }

  function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  function confirmPayment(params) {
    paymentService.confirmPayment(params).success(function(data) {
      if (data.id){
        $mdToast.show(
          $mdToast.simple()
            .content('Payment confirmed')
            .position("top right")
            .hideDelay(3000)
        );
      }
    }).error(function(error) {
      $mdToast.show(
            $mdToast.simple()
              .content('Confirm payment error')
              .position("top right")
              .hideDelay(3000)
          );
    });
  }
}
