(function(){

    'use strict';

    angular.module('Dictionary').controller('ResultCtrl', ResultCtrl);

    function ResultCtrl($scope, $location, TestService) {
        $scope.test = TestService;

        if (!TestService.user) {
            $location.path('/');
            return;
        }
    }

})();