'use strict';

/**
 * Route configuration for the Dashboard module.
 *
 */

angular.module('Pardna').config(['$stateProvider', '$urlRouterProvider',
    function ($stateProvider, $urlRouterProvider) {

        // For unmatched routes
        $urlRouterProvider.otherwise('/');

        // Application routes

        $stateProvider
            .state('home', {
                url: '/',

                // templateUrl: 'module/pardna/app/templates/home.html',
                views: {
                  '': {
                    controller: 'HomeCtrl',
                    'templateUrl' : 'module/pardna/app/templates/home.html',
                  },
                  'nav@home': {
                    'templateUrl' : 'module/pardna/app/templates/home-nav.html'
                  },
                  'confirmation@home': {
                    'templateUrl' : 'module/pardna/app/templates/home-confirmation.html'
                  }
                },
                requiresLogin: true
            })
            .state('invite', {
                url: '/invite',

                // templateUrl: 'module/pardna/app/templates/home.html',
                views: {
                  '': {
                    controller: 'InviteCtrl',
                    'templateUrl' : 'module/pardna/invite/templates/invite.html',
                  },
                  'nav@invite': {
                    'templateUrl' : 'module/pardna/app/templates/home-nav.html'
                  }
                },
                requiresLogin: true
            })
            .state('group-details', {
                url: '/group/details/:id',
                views: {
                  '': {
                    controller: 'GroupDetailsCtrl',
                    'templateUrl' : 'module/pardna/group/templates/group-details.html',
                  },
                  'nav@group-details': {
                    'templateUrl' : 'module/pardna/app/templates/home-nav.html'
                  }
                },
                requiresLogin: true
            })
            .state('group-add', {
                data: {
                  'selectedTab': 0
                },
                url: '/group/add',
                views: {
                  '': {
                    data: {
                      'selectedTab': 0
                    },
                    controller: 'GroupAddCtrl',
                    'templateUrl' : 'module/pardna/group/templates/add-group.html',
                  },
                  'nav@group-add': {
                    'templateUrl' : 'module/pardna/app/templates/home-nav.html'
                  },
                  'your-details@group-add': {
                    'templateUrl' : 'module/pardna/group/templates/your-details.html'
                  },
                  'pardna-details@group-add': {
                    'templateUrl' : 'module/pardna/group/templates/pardna-details.html'
                  },
                  'direct-debit@group-add': {
                    'templateUrl' : 'module/pardna/group/templates/direct-debit.html'
                  },
                  'add-users@group-add': {
                    'templateUrl' : 'module/pardna/group/templates/add-users.html'
                  }
                },
                requiresLogin: true
            })
            .state('payment-confirm', {
				        url: '/payment/confirm',
                views: {
                  '': {
                    controller: 'PaymentCtrl',
                    'templateUrl' : 'module/pardna/payment/templates/payment-confirm.html'
                  },
                  'nav@payment-confirm': {
                    'templateUrl' : 'module/pardna/app/templates/home-nav.html'
                  }
                },
                requiresLogin: true
            })
            .state('email-verify', {
				        url: '/account/email/verify',
                views: {
                  '': {
                    controller: 'EmailVerifyCtrl',
                    'templateUrl' : 'module/pardna/emailverify/templates/verify-email.html'
                  }
                },
                requiresLogin: false
            })
    			  .state('account', {
    				    url: '/account',
                views: {
                  '': {
                    controller: 'AccountCtrl',
                    'templateUrl' : 'module/pardna/user/templates/account.html'
                  },
                  'nav@account': {
                    'templateUrl' : 'module/pardna/app/templates/home-nav.html'
                  },
      				  'account-direct-debit@account': {
      					 controller: 'AccountDirectDebitCtrl',
                          'templateUrl' : 'module/pardna/user/templates/account-direct-debit.html'
                        },
      				  'account-payout@account': {
      					 controller: 'AccountPayoutCtrl',
                          'templateUrl' : 'module/pardna/user/templates/account-payout.html'
                        },
      				  'account-user@account': {
      					 controller: 'AccountUserCtrl',
                          'templateUrl' : 'module/pardna/user/templates/account-user.html'
                        }
                },
                requiresLogin: true
            })
            .state('user-add', {
                url: '/user/add',
                controller: 'UserCtrl',
                templateUrl: 'module/pardna/user/templates/add.html'
            })
            .state('signup', {
                url: '/signup',
                controller: 'SignupCtrl',
                templateUrl: 'module/pardna/user/templates/signup.html'
            })
            .state('forgot-password', {
                url: '/forgot-password',
                controller: 'ForgotPasswordCtrl',
                templateUrl: 'module/pardna/user/templates/forgot-password.html'
            })
            .state('login', {
                url: '/login',
                controller: 'LoginCtrl',
                templateUrl: 'module/pardna/user/templates/login.html'
            })
            .state('logout', {
                url: '/logout',
                controller: 'LogoutCtrl',
                resolve: {
                  init: function(userService) {
                    return userService.deleteToken();;
                  }
                }
            });
    }]);
