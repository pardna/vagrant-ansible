angular.module('Pardna')
    .controller('RedirectFlowCtrl', ['$scope', '$location', '$state', '$mdToast', 'paymentService', RedirectFlowCtrl]);


function RedirectFlowCtrl($scope, $location, $state, $mdToast, paymentService) {
  //var searchParams = $location.search();
  var searchParams = {};
  searchParams.redirect_flow_id = getParameterByName("redirect_flow_id");
  searchParams.membership_number = getParameterByName("membership_number");
  $scope.returnStateId = getParameterByName("return_state_id");
  var returnStateName = getParameterByName("return_state_name");
  if (returnStateName){
    $scope.returnStateName = returnStateName.replace(/_/g, ' ');
  }
  $scope.redirectToState = redirectToState;
  var return_params_count = getParameterByName("return_params_count");
  if (! return_params_count){
    $scope.returnStateParams = [];
    if (return_params_count > 1)
    {
      for (var count = 1; count <= return_params_count; count++){
        var return_params_key = getParameterByName("return_params_key");
        var return_params_value = getParameterByName("return_params_value");
        if (return_params_key && return_params_value){
          $scope.returnStateParams [return_params_key] = return_params_value;
        }
      }
    } else {
      var return_params_key = getParameterByName("return_params_key_".concat(count));
      var return_params_value = getParameterByName("return_params_value_".concat(count));
      if (return_params_key && return_params_value){
        $scope.returnStateParams [return_params_key] = return_params_value;
      }
    }
    console.log($scope.returnStateParams);
  }
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

  function redirectToState(stateId)
  {
    var params = $scope.returnStateParams;
    $state.go(stateId, JSON.stringify(params));
  }

  function confirmPayment(params) {

    paymentService.confirmPayment(params).then(
      function successCallback(response) {
        if (response.data.id){
          $mdToast.show(
            $mdToast.simple()
            .content('Payment confirmed')
            .position("top right")
            .hideDelay(3000)
          );
        }
      },
      function errorCallback(response) {
        $mdToast.show(
          $mdToast.simple()
          .content('Confirm payment error')
          .position("top right")
          .hideDelay(3000)
        );
    });

    // paymentService.confirmPayment(params).success(function(data) {
    //   if (data.id){
    //     $mdToast.show(
    //       $mdToast.simple()
    //         .content('Payment confirmed')
    //         .position("top right")
    //         .hideDelay(3000)
    //     );
    //   }
    // }).error(function(error) {
    //   $mdToast.show(
    //         $mdToast.simple()
    //           .content('Confirm payment error')
    //           .position("top right")
    //           .hideDelay(3000)
    //       );
    // });

  }
}
