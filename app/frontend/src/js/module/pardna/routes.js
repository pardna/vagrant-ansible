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
            })
            .state('geomodel-weight', {
                url: '/geomodel/weight/:id',
                controller: 'GeoModelWeightCtrl',
                templateUrl: 'module/mrt/geomodel/templates/weight-index.html'
            }).state('geomodel', {
                url: '/geomodel',
                controller: 'GeoModelCtrl',
                templateUrl: 'module/mrt/geomodel/templates/index.html'
            })
            .state('geography', {
                url: '/geography',
                controller: 'GeographyCtrl',
                templateUrl: 'module/mrt/geography/templates/index.html'
            })
            .state('geogroup', {
                url: '/geogroup',
                controller: 'GeoGroupCtrl',
                templateUrl: 'module/mrt/geogroup/templates/index.html'
            })
            .state('geogroup-view', {
                url: '/geogroup-view/:id',
                controller: 'GeoGroupViewCtrl',
                templateUrl: 'module/mrt/geogroup/templates/view.html'
            })
            .state('geoindicatorgroup', {
                url: '/geoindicatorgroup',
                controller: 'GeoIndicatorGroupCtrl',
                templateUrl: 'module/mrt/geoindicatorgroup/templates/index.html'
            })
            .state('geoindicatorgroup-view', {
                url: '/geoindicatorgroup-view/:id',
                controller: 'GeoIndicatorGroupViewCtrl',
                templateUrl: 'module/mrt/geoindicatorgroup/templates/view.html'
            })
            .state('geoindicator', {
                url: '/geoindicator',
                controller: 'GeoIndicatorCtrl',
                templateUrl: 'module/mrt/geoindicator/templates/index.html',

            })
            .state('geoindicator-view', {
                url: '/geoindicator/view/:id',
                controller: 'GeoIndicatorViewCtrl',
                templateUrl: 'module/mrt/geoindicator/templates/view.html'
            })
            .state('geoindicator-add', {
                url: '/geoindicator/add',
                controller: 'GeoIndicatorAddCtrl',
                templateUrl: 'module/mrt/geoindicator/templates/add.html'
            })
            .state('geoindicator-edit', {
                url: '/geoindicator/edit/:id',
                controller: 'GeoIndicatorAddCtrl',
                templateUrl: 'module/mrt/geoindicator/templates/add.html'
            })
            .state('geomodel-cluster', {
                url: '/geomodel/cluster',
                controller: 'GeoModelCtrl',
                templateUrl: 'module/mrt/geomodel/templates/geomodel-cluster-index.html'
            })
            .state('view-fund', {
                url: '/view-fund/:phoneId',
                controller: 'ViewFundCtrl',
                templateUrl: 'tpls/mrt/tpls/tpls/view-fund.html'
            })
            .state('tables', {
                url: '/tables',
                templateUrl: 'tables.html'
            });
    }]);
