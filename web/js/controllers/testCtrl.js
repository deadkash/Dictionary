(function () {

    'use strict';

    angular.module('Dictionary').controller('TestCtrl', TestCtrl);

    function TestCtrl($scope, $location, TestService) {

        var busy = false;

        if (!TestService.user) {
            $location.path('/');
            return;
        }

        $scope.test = TestService;
        $scope.valid = [];
        $scope.invalid = [];

        var getWordSet = function() {

            $scope.valid = [];
            $scope.invalid = [];

            TestService.getWordSet(function(data) {

                if (data) {
                    $scope.set = data;
                }
                else {
                    $location.path('/result');
                }
            });
        };

        getWordSet();

        $scope.doAnswer = function(option, set, index) {

            if (busy) return;
            busy = true;

            TestService.checkWord(option, set, function(data) {

                if (data.result) {
                    $scope.valid[index] = true;
                    setTimeout(function() {
                        busy = false;
                        TestService.onCorrectAnswer();
                        getWordSet();
                    }, TestService.btnColorTimeout);
                }
                else {

                    $scope.invalid[index] = true;
                    TestService.onIncorrectAnswer();
                    if (TestService.isGameOver()) {
                        $location.path('/result');
                    }

                    setTimeout(function() {
                        busy = false;
                        $scope.invalid[index] = false;
                        $scope.$apply();
                    }, TestService.btnColorTimeout);
                }
            });
        }
    }

})();