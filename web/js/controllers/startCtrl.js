(function(){

    'use strict';

    angular.module('Dictionary').controller('StartCtrl', StartCtrl);

    function StartCtrl($scope, $location, TestService) {

        $scope.user = '';
        $scope.valid = true;

        $scope.doStart = function() {

            $scope.valid = !!$scope.user;
            if ($scope.valid) {
                TestService.start($scope.user, function(){
                    $location.path('/test/');
                });
            }
        }
    }

})();