(function(){
    'use strict';

    function TestService($http, $location) {

        this.user = '';
        this.score = 0;
        this.errors = 0;
        this.apiUrl = '/api/v1';
        var CORRECT_ANSWER_SCORE = 1;

        this.start = function(callback) {
            $http.get(this.apiUrl + '/start', {}).then(function successResult(response) {
                if (typeof callback == 'function') {
                    callback(response.data);
                }
            }, function errorResult() {
                $location.path('/error');
            });
        };

        this.getWordSet = function(callback) {

            $http.get(this.apiUrl + '/word_set', {}).then(function successResult(response) {
                if (typeof callback == 'function') {
                    callback(response.data);
                }
            }, function errorResult() {
                $location.path('/error');
            });
        };

        this.checkWord = function(choice, set, callback) {

            $http.post(this.apiUrl + '/check_word', {
                type: set.type,
                word: set.word,
                choice: choice
            }).then(function successResult(response) {
                if (typeof callback == 'function') {
                    callback(response.data);
                }
            }, function errorResult() {
                $location.path('/error');
            });
        };

        this.onCorrectAnswer = function() {
            this.score += CORRECT_ANSWER_SCORE;
        };

        this.onIncorrectAnswer = function() {
            this.errors++;
        };

        this.isGameOver = function() {
            return this.errors >= 3;
        }
    }

    angular.module('Dictionary').service('TestService', TestService)
})();