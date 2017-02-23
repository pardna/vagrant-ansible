angular.module('Pardna')
.controller('HomeCtrl', ['$scope', '$window', '$mdToast', '$mdDialog', 'jwtHelper', 'localStorageService', 'userService', 'groupService', 'inviteService', 'paymentService', HomeCtrl]);

function HomeCtrl($scope, $window, $mdToast, $mdDialog, jwtHelper, localStorageService, userService, groupService, inviteService, paymentService) {
  $scope.imagePath = 'img/washedout.png';
  $scope.floatingAddSelectedMode = 'md-fling';
  $scope.settings = [
    { name: '10 January 2017', extraScreen: 'Wi-fi menu', icon: 'img/icons/ic_date_range_black_24px.svg', enabled: true },
    { name: '10 June 2017', extraScreen: 'Bluetooth menu', icon: 'img/icons/ic_date_range_black_24px.svg', enabled: false },
  ];

    $scope.slot = [
        {
          type: 'Payout Date',
          number: '10 January 2017',
          options: {
            icon: 'img/icons/ic_today_black_24px.svg'
          }
        },
        {
          type: 'Next payin date',
          number: '10 June 2017',
          options: {
            icon: 'img/icons/ic_date_range_black_24px.svg'
          }
        }
      ];
    $scope.inviteserviceresponse = {
      'success': false,
      'failure': false
    };

    $scope.selectedFriends = ["mouse", "dog"];
    $scope.friends = ["mouse", "dog", "cat", "bird"];

    $scope.showInvitesModal = function(group, ev) {
      $scope.invite = {
        'message' : 'I would like to invite you to join pardna group ' + group.name + ' on Pardna.com',
        'emails' : ''
      };

      $scope.invitestate = {
        'byfriends' : true,
        'byemail' : false
      }

      $scope.group = group;

      $mdDialog.show({
        controller: HomeCtrl,
        templateUrl: 'module/pardna/app/templates/home-pardna-group-invites.tmpl.html',
        parent: angular.element(document.body),
        targetEvent: ev,
        clickOutsideToClose:true,
        scope: $scope,        // use parent scope in template
        preserveScope: true,
        fullscreen: true // Only for -xs, -sm breakpoints.
      })
      .then(function(bankacc) {

      });
    };

    $scope.showInviteByEmail = function (){
      $scope.invitestate.byfriends = false;
      $scope.invitestate.byemail = true;
    };

    $scope.showInviteFriends = function (){
      $scope.invitestate.byfriends = true;
      $scope.invitestate.byemail = false;
    };

    $scope.returnFromInvite = function (){
      if ($scope.invitebyemail.show){
        showInviteFriends();
      } else{
        cancel();
      }
    };

    $scope.hide = function() {
      $mdDialog.hide();
      $state.reload();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
      $state.reload();
    };

    $scope.selectedcontacts = 0;

    $scope.personStateChanged = function (selected){
      if (selected){
        $scope.selectedcontacts = $scope.selectedcontacts + 1;
      } else{
        $scope.selectedcontacts = $scope.selectedcontacts - 1;
      }
    }

    $scope.allContacts = loadContacts();
    $scope.contacts = [$scope.allContacts[0]];

    function loadContacts() {
    var contacts = [
      'Marina Augustine',
      'Oddr Sarno',
      'Nick Giannopoulos',
      'Narayana Garner',
      'Anita Gros',
      'Megan Smith',
      'Tsvetko Metzger',
      'Hector Simek',
      'Some-guy withalongalastaname'
    ];

    return contacts.map(function (c, index) {
      var cParts = c.split(' ');
      var contact = {
        name: c,
        email: cParts[0][0].toLowerCase() + '.' + cParts[1].toLowerCase() + '@example.com',
        image: 'http://lorempixel.com/50/50/people?' + index
      };
      contact._lowername = contact.name.toLowerCase();
      if (index == 1){
        contact.admin = true;
      }
      return contact;
    });
  }

  $scope.user = userService.user;
  $scope.ui = {};
  $scope.ui.list = [];
  $scope.ui.groupInvitationList = [];
  $scope.ui.userInvitationList = [];
  $scope.ui.relationships = [];
  $scope.acceptUserInvitation = acceptUserInvitation;
  $scope.acceptGroupInvitation = acceptGroupInvitation;
  $scope.ignoreUserInvitation = ignoreUserInvitation;
  var originatorEv;

  loadList();
  loadGroupInvitations();
  loadUserInvitations();
  loadUserRelationships();

  $scope.openMenu = function($mdOpenMenu, ev) {
      originatorEv = ev;
      $mdOpenMenu(ev);
    };

  if(typeof $scope.user.login_count !== "undefined" && $scope.user.login_count === 0) {

  }

  console.log($scope.user);


  // Add new pardna
  $scope.data = {
    selectedIndex: 0,
    secondLocked:  true,
    secondLabel:   "Item Two",
    bottom:        false
  };
  $scope.next = function() {
    $scope.data.selectedIndex = Math.min($scope.data.selectedIndex + 1, 2) ;
  };
  $scope.previous = function() {
    $scope.data.selectedIndex = Math.max($scope.data.selectedIndex - 1, 0);
  };

  $scope.color = {
    red: Math.floor(Math.random() * 255),
    green: Math.floor(Math.random() * 255),
    blue: Math.floor(Math.random() * 255)
  };
  $scope.rating1 = 3;
  $scope.rating2 = 2;
  $scope.rating3 = 4;
  $scope.disabled1 = 0;
  $scope.disabled2 = 70;

  $scope.sendCode = function(ev) {
    $mdDialog.show({
      controller: SendCodeDialogCtrl,
      templateUrl: 'send-code.tmpl.html',
      parent: angular.element(angular.element(document.querySelector('#popupContainer'))),
      targetEvent: ev,
      clickOutsideToClose:true
    })
    .then(function(answer) {
      $scope.status = 'You said the information was "' + answer + '".';
    }, function() {
      $scope.status = 'You cancelled the dialog.';
    });
  };

  $scope.verifyCode = function(ev) {
    $mdDialog.show({
      controller: SendCodeDialogCtrl,
      templateUrl: 'verify-code.tmpl.html',
      parent: angular.element(angular.element(document.querySelector('#popupContainer'))),
      targetEvent: ev,
      clickOutsideToClose:true
    })
    .then(function(answer) {
      $scope.status = 'You said the information was "' + answer + '".';
      $scope.user = userService.user;
    }, function() {
      $scope.status = 'You cancelled the dialog.';
      $scope.user = userService.user;
    });
  };

  function loadList() {

    groupService.list({}).then(function successCallback(response) {
      $scope.ui.list = response.data;
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error')
        .position("top right")
        .hideDelay(3000)
      );
    });

  //   groupService.list({}).success(function(data) {
  //     $scope.ui.list = data;
  //   }).error(function(error) {
  //     $mdToast.show(
  //           $mdToast.simple()
  //             .content('Application error')
  //             .position("top right")
  //             .hideDelay(3000)
  //         );
  //   });
  }

  function loadUserRelationships() {

    userService.getRelationships({}).then(function successCallback(response) {
      $scope.ui.relationships = response.data;
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error')
        .position("top right")
        .hideDelay(3000)
      );
    });

  //   userService.getRelationships({}).success(function(data) {
  //     $scope.ui.relationships = data;
  //   }).error(function(error) {
  //     $mdToast.show(
  //       $mdToast.simple()
  //       .content('Application error')
  //       .position("top right")
  //       .hideDelay(3000)
  //     );
  //   });
  }

  function loadGroupInvitations() {
    inviteService.getGroupInvitations({}).then(function successCallback(response) {
      $scope.ui.groupInvitationList = response.data;
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error getting group invitations')
        .position("top right")
        .hideDelay(3000)
      );
    });

    // inviteService.getGroupInvitations({}).success(function(data) {
    //   $scope.ui.groupInvitationList = data;
    // }).error(function(error) {
    //   $mdToast.show(
    //     $mdToast.simple()
    //     .content('Application error getting group invitations')
    //     .position("top right")
    //     .hideDelay(3000)
    //   );
    // });
  }

  function loadUserInvitations() {
    inviteService.getUserInvitations({}).then(function successCallback(response) {
      $scope.ui.userInvitationList = response.data;
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error getting user invitations')
        .position("top right")
        .hideDelay(3000)
      );
    });

    // inviteService.getUserInvitations({}).success(function(data) {
    //   $scope.ui.userInvitationList = data;
    // }).error(function(error) {
    //   $mdToast.show(
    //         $mdToast.simple()
    //           .content('Application error getting user invitations')
    //           .position("top right")
    //           .hideDelay(3000)
    //       );
    // });
  }

  function acceptUserInvitation(id) {
    inviteService.acceptUserInvitation({id : id}).then(function successCallback(response) {
      loadUserInvitations();
      loadUserRelationships();
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error accepting user invitation')
        .position("top right")
        .hideDelay(3000)
      );
    });
  }

  function acceptGroupInvitation(id) {
    inviteService.acceptGroupInvitation({id : id}).then(function successCallback(response) {
      loadUserInvitations();
      loadUserRelationships();
      loadGroupInvitations();
      loadList();
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error accepting group invitation')
        .position("top right")
        .hideDelay(3000)
      );
    });
  }

  function ignoreUserInvitation(id) {
    inviteService.ignoreUserInvitation({id : id}).then(function successCallback(response) {
      loadUserInvitations();
      loadUserRelationships();
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error accepting user invitation')
        .position("top right")
        .hideDelay(3000)
      );
    });
  }

  function ignoreGroupInvitation(id) {
    inviteService.ignoreGroupInvitation({id : id}).then(function successCallback(response) {
      loadUserInvitations();
      loadUserRelationships();
    }, function errorCallback(response) {
      $mdToast.show(
        $mdToast.simple()
        .content('Application error accepting group invitation')
        .position("top right")
        .hideDelay(3000)
      );
    });
  }

  $scope.firstLogin = function(ev) {
    // Appending dialog to document.body to cover sidenav in docs app

    var confirm = $mdDialog.confirm()
          .title('Welcome to Parda.com!')
          .content('<p>This is you Pardna Dashboard, where you will see an overview of your account</p><p>We sent and activation link to your email address. Please click the link to enable features</p><p>Please alson complete your personal details so you can setup your Pardna group, invite others and begin saving together</p>')
          .ariaLabel('Welcome to Parda.com!')
          .targetEvent(ev)
          .ok('Please do it!')
          .cancel('OK');
    $mdDialog.show(confirm).then(function() {
      $scope.status = 'ok';
    }, function() {
      $scope.status = 'cancel';
    });
  };

  var alert;
    $scope.showAlert = showAlert;
  function showAlert() {
      alert = $mdDialog.alert({
        title: 'Attention',
        content: 'This is an example of how easy dialogs can be!',
        ok: 'Close'
      });
      $mdDialog
        .show( alert )
        .finally(function() {
          alert = undefined;
        });
    }

  // $scope.firstLogin();

  // $scope.showAlert();



  $scope.view = {};
  $scope.view.tabs = [
    {
      "icon_class": "fa fa-user",
      "label": "Your Details",
      "template": ""
    },
    {
      "icon_class": "fa fa-gbp",
      "label": "Pardna Details",
      "template": ""
    },
    {
      "icon_class": "fa fa-lock",
      "label": "Direct Debit",
      "template": ""
    },
    {
      "icon_class": "fa fa-users",
      "label": "Invite Friends",
      "template": ""
    },
  ]

// Format the start date that is returned from the database
function formatDate($scope) {
    $scope.v = {
        startdate: Date.parse()
    }
}




}
