angular
  .module('Pardna')
  .controller('MasterCtrl', function($scope) {

  })
  .config( function($mdThemingProvider){
    // Extend the red theme with a different color and make the contrast color black instead of white.
    // For example: raised button text will be black instead of white.
    var neonRedMap = $mdThemingProvider.extendPalette('purple', {
      '500': '#3a1144',
      'A700': '#009688',
      'A400': '#009688',
      'contrastDefaultColor': 'light'
    });

    // Register the new color palette map with the name <code>neonRed</code>
    $mdThemingProvider.definePalette('neonRed', neonRedMap);

    // Use that theme for the primary intentions
    $mdThemingProvider.theme('default')
      .primaryPalette('neonRed');
  });

//
