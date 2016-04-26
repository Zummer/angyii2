'use strict';

var app = angular.module('app', [
        'ngRoute',          //$routeProvider
        'ngAnimate',
        'toaster',
        'mgcrea.ngStrap',   //bs-navbar, data-match-route directives
        'controllers',       //Our module frontend/web/js/controllers.js
        'RestModule',
        'PostModule',    // модуль frontend/web/js/angularmodules/post/PostModule.js
    ])
    .constant('path', {
        toWebApi: 'angyii2/frontend/web'
    })
    .config(['$routeProvider', '$httpProvider', '$locationProvider',
        function ($routeProvider, $httpProvider, $locationProvider) {
            var modulesPath = 'angularmodules';
            var apiPath = 'frontend/web';

            $routeProvider
                .when('/', {
                    templateUrl: apiPath + '/partials/index.html'
                })
                .when('/about', {
                    templateUrl: apiPath + '/partials/about.html'
                })
                .when('/posts', {
                    templateUrl: modulesPath + '/post/views/index.html',
                    controller: 'PostIndexCtrl'
                })
                .when('/post/create', {
                    templateUrl: modulesPath + '/post/views/form.html',
                    controller: 'PostCreateCtrl'
                })
                .when('/post/:id/edit', {
                    templateUrl: modulesPath + '/post/views/form.html',
                    controller: 'PostEditCtrl'
                })
                .when('/post/:id/delete', {
                    templateUrl: modulesPath + '/post/views/delete.html',
                    controller: 'PostDeleteCtrl'
                })
                .when('/post/:id', {
                    templateUrl: modulesPath + '/post/views/view.html',
                    controller: 'PostViewCtrl'
                })
                .when('/contact', {
                    templateUrl: apiPath + '/partials/contact.html',
                    controller: 'ContactController'
                })
                .when('/login', {
                    templateUrl: apiPath + '/partials/login.html',
                    controller: 'LoginController'
                })
                .when('/dashboard', {
                    templateUrl: apiPath + '/partials/dashboard.html',
                    controller: 'DashboardController'
                })
                .when('/signup', {
                    templateUrl: apiPath + '/partials/signup.html',
                    controller: 'SignupController'
                })
                .when('/404', {
                    templateUrl: apiPath + '/partials/404.html'
                })
                .otherwise({redirectTo: '/404'});

            $httpProvider.interceptors.push('authInterceptor');
            $locationProvider.html5Mode(true);
        }
    ])
    .factory('authInterceptor', function ($q, $window, $location) {
        return {
            request: function (config) {
                if ($window.sessionStorage.access_token) {
                    //HttpBearerAuth
                    config.headers.Authorization = 'Bearer ' + $window.sessionStorage.access_token;
                }
                return config;
            },
            responseError: function (rejection) {
                if (rejection.status === 401) {
                    $location.path('/login').replace();
                }
                return $q.reject(rejection);
            }
        };
    });