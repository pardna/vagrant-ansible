var myApp = angular.module('myApp', []);
myApp.controller('mainCtrl', ['$scope', function( $scope ){
  
  // Set the default value of inputType
  $scope.inputType = 'password';
  
  // Hide & show password function
  $scope.hideShowPassword = function(){
    if ($scope.inputType == 'password')
      $scope.inputType = 'text';
    else
      $scope.inputType = 'password';
  };
  
}]);