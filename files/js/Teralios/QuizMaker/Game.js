define(['Ajax', 'StringUtil', 'Language'], function (Ajax, StringUtil, Language) {
    "use strict";

    var neededKeys = ['quizID', 'type', 'questions', 'questionList', 'goalList']

    function Game(data, container)
    {
        this.init(data, container);
    }

    Quiz.prototype = {
        init: function (data, container) {
            this._gameContainer = container;
            this._data = data;


        },

        _checkData: function() {
            var error = false;

        }
    }
});
