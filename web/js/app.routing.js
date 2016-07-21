(function () {
    'use strict';

    angular.module('Dictionary')
        .config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider){

            $routeProvider
                .when('/', {
                    templateUrl: 'partials/start.html',
                    controller: 'StartCtrl'
                })
                .when('/test', {
                    templateUrl: 'partials/test.html',
                    controller: 'TestCtrl'
                })
                .when('/result', {
                    templateUrl: 'partials/result.html',
                    controller: 'ResultCtrl'
                })
                .when('/error', {
                    templateUrl: 'partials/error.html',
                    controller: 'ErrorCtrl'
                })
                .otherwise({
                    redirectTo: '/'
                });

            $locationProvider.html5Mode(true);
        }]);
})();