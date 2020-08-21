define(['Ajax', 'Language', 'StringUtil', 'User'], function (Ajax, Language, StringUtil, User) {
    "use strict";

    return {
        init: function (result, score, quizData, gameContainer) {
            this._gameContainer = gameContainer;
            this._score = score;
            this._quizData = quizData;
            this._result = result;
        },

        showResult: function () {
            this._getLoadResult();
        },

        _getLoadResult: function () {
            Ajax.api(
                this,
                {
                    objectIDs: [this._quizData.quizID],
                    parameters: {
                        userID: User.getProperty('userId'),
                        score: this.score,
                        result: this._result
                    }
                }
            );
        },

        _renderResultLive: function () {
        },

        _renderResultOffline: function () {
        },

        _ajaxSetup: function () {
            return {
                data: {
                    actionName: "finishGame",
                    className: 'wcf\\data\\quiz\\QuizAction',
                }
            }
        },

        _ajaxSuccess: function (data, responseText, xhr, requestData) {

        },

        _ajaxFailure: function () {

        }
    };
});
