(function(){
    'use strict';

    function TestService($http, $location) {

        /**
         * Username
         * @type {string}
         */
        this.user = '';

        /**
         * Current session score
         * @type {number}
         */
        this.score = 0;

        /**
         * Quantity of errors
         * @type {number}
         */
        this.errors = 0;

        /**
         * API URL
         * @type {string}
         */
        this.apiUrl = '/api/v1';

        /**
         * Timeout in seconds for choice buttons
         * @type {number}
         */
        this.btnColorTimeout = 500;

        var CORRECT_ANSWER_SCORE = 1;
        var ERRORS_TO_GAME_OVER = 3;

        /**
         * Start test session
         * @param user
         * @param callback
         */
        this.start = function(user, callback) {

            this.user = user;
            this.score = 0;

            $http.post(this.apiUrl + '/start', {username: user}).then(function successResult(response) {
                if (typeof callback == 'function') {
                    callback(response.data);
                }
            }, function errorResult() {
                $location.path('/error');
            });
        };

        /**
         * Requests random word set from the server
         * @param callback
         */
        this.getWordSet = function(callback) {

            $http.get(this.apiUrl + '/word_set', {}).then(function successResult(response) {
                if (typeof callback == 'function') {
                    callback(response.data);
                }
            }, function errorResult() {
                $location.path('/error');
            });
        };

        /**
         * Check user choice on the server
         * @param choice
         * @param set
         * @param callback
         */
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

        /**
         * Calling if user selected correct choice
         */
        this.onCorrectAnswer = function() {
            this.score += CORRECT_ANSWER_SCORE;
        };

        /**
         * Calling if user selected wrong choice
         */
        this.onIncorrectAnswer = function() {
            this.errors++;
        };

        /**
         * Check if test is over
         * @returns {boolean}
         */
        this.isGameOver = function() {
            return this.errors >= ERRORS_TO_GAME_OVER;
        }
    }

    angular.module('Dictionary').service('TestService', TestService)
})();