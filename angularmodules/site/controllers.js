'use strict';

var controllers = angular.module('controllers', []);
controllers.controller('MainController', ['$scope', '$location', '$window',
    function ($scope, $location, $window) {
        $scope.loggedIn = function() {
            return Boolean($window.sessionStorage.access_token);
        };

        $scope.getUserName = function() {
            return $window.sessionStorage.username;
        };

        $scope.logout = function () {
            delete $window.sessionStorage.access_token;
            delete $window.sessionStorage.username;
            $location.path('/login').replace();
        };
    }
]);

controllers.controller('ContactController', ['$scope', '$http', '$window', 'path',
    function($scope, $http, $window, path) {
        $scope.captchaUrl = path.toWebApi + '/site/captcha';
        $scope.contact = function () {
            $scope.submitted = true;
            $scope.error = {};
            $http.post(path.toWebApi + '/api/contact', $scope.contactModel).success(
                function (data) {
                    $scope.contactModel = {};
                    $scope.flash = data.flash;
                    $window.scrollTo(0,0);
                    $scope.submitted = false;
                    $scope.captchaUrl = 'site/captcha' + '?' + new Date().getTime();
            }).error(
                function (data) {
                    angular.forEach(data, function (error) {
                        $scope.error[error.field] = error.message;
                    });
                }
            );
        };

        $scope.refreshCaptcha = function() {
            $http.get(path.toWebApi + '/site/captcha?refresh=1').success(function(data) {
                $scope.captchaUrl = data.url;
            });
        };
    }]);

controllers.controller('SignupController', ['$scope', '$http', '$location', 'path',
    function ($scope, $http, $location, path) {
        $scope.captchaUrl = path.toWebApi +'/site/captcha';
        $scope.signup = function () {
            $scope.submitted = true;
            $scope.error = {};
            $http.post(path.toWebApi + '/api/signup', $scope.signupModel).success(
                function (data) {
                    $scope.signupModel = {};
                    //$scope.flash = data.flash;
                    $scope.submitted = false;
                    $location.path('/login').replace();
                }).error(
                function (data) {
                    angular.forEach(data, function (error) {
                        $scope.error[error.field] = error.message;
                    });
                }
            );
        };

        $scope.refreshCaptcha = function() {
            $http.get(path.toWebApi + '/site/captcha?refresh=1').success(function(data) {
                $scope.captchaUrl = data.url;
            });
        };
    }
]);

controllers.controller('DashboardController', ['$scope', '$http', 'path',
    function ($scope, $http, path) {
        $http.get(path.toWebApi + '/api/dashboard').success(function (data) {
           $scope.dashboard = data;
        })
    }
]);

controllers.controller('LoginController', ['$scope', '$http', '$window', '$location', 'path',
    function($scope, $http, $window, $location, path) {
        $scope.login = function () {
            $scope.submitted = true;
            $scope.error = {};
            $http.post(path.toWebApi + '/api/login', $scope.userModel).success(
                function (data) {
                    $window.sessionStorage.access_token = data.access_token;
                    $window.sessionStorage.username = data.username;
                    $location.path('/').replace();
                    //$window.history.back();
            }).error(
                function (data) {
                    angular.forEach(data, function (error) {
                        $scope.error[error.field] = error.message;
                    });
                }
            );
        };
    }
]);