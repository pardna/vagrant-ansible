angular.module('Pardna')
    .controller('EmailVerifyCtrl', ['$scope', '$location', '$mdToast', 'emailVerifyService', EmailVerifyCtrl]);


function EmailVerifyCtrl($scope, $location, $mdToast, emailVerifyService) {
  function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  var searchParams = {};
  searchParams.selector = getParameterByName("selector");
  searchParams.validator = getParameterByName("validator");
  //searchParams.signature = getParameterByName("signature");
  // console.log(searchParams);
  if (searchParams && searchParams !== "null" && searchParams !== "undefined"){
    verifyEmail(searchParams);
  }

  function verifyEmail(params) {

    emailVerifyService.verifyEmail(params).then(
      function successCallback(response) {
        $scope.email_verified = '1';
        $scope.email_validate_message = response.data.message;
        $mdToast.show(
          $mdToast.simple()
            .content('Email has been successfully validated!!')
            .position("top right")
            .hideDelay(3000)
        );
      },
      function errorCallback(response) {
        $scope.email_verified = '0';
        $scope.email_validate_message = response.error.message;
        $mdToast.show(
          $mdToast.simple()
          .content(response.error.message)
          .position("top right")
          .hideDelay(3000)
        );
    });

    // emailVerifyService.verifyEmail(params).success(function(data) {
    //   $scope.email_verified = '1';
    //   $scope.email_validate_message = data.message;
    //   $mdToast.show(
    //     $mdToast.simple()
    //       .content('Email has been successfully validated!!')
    //       .position("top right")
    //       .hideDelay(3000)
    //   );
    // }).error(function(error) {
    //   $scope.email_verified = '0';
    //   $scope.email_validate_message = error.message;
    //   $mdToast.show(
    //         $mdToast.simple()
    //           .content(error.message)
    //           .position("top right")
    //           .hideDelay(3000)
    //       );
    // });

  }
}
