(function () {

    'use strict';

    angular.module('Dictionary').controller('TestCtrl', TestCtrl);

    function TestCtrl($scope, $location, TestService) {

        if (!TestService.user) {
            $location.path('/');
            return;
        }

        $scope.test = TestService;

        var getWordSet = function() {
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

        $scope.doAnswer = function(option, set) {
            TestService.checkWord(option, set, function(data) {

                if (data.result) {
                    TestService.onCorrectAnswer();
                    getWordSet();
                }
                else {
                    TestService.onIncorrectAnswer();
                    if (TestService.isGameOver()) {
                        $location.path('/result');
                    }
                }
            });
        }
    }

})();