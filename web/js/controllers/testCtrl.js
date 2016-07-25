(function () {

    'use strict';

    angular.module('Dictionary').controller('TestCtrl', TestCtrl);

    function TestCtrl($scope, $location, TestService) {

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

            TestService.checkWord(option, set, function(data) {

                if (data.result) {
                    $scope.valid[index] = true;
                    setTimeout(function() {
                        TestService.onCorrectAnswer();
                        getWordSet();
                    }, TestService.btnColorTimeout);
                }
                else {
                    $scope.invalid[index] = true;
                    setTimeout(function() {
                        $scope.invalid[index] = false;
                        TestService.onIncorrectAnswer();
                        if (TestService.isGameOver()) {
                            $location.path('/result');
                        }
                    }, TestService.btnColorTimeout);
                }
            });
        }
    }

})();