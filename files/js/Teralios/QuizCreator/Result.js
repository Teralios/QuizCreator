define(['Ajax', 'Language', 'StringUtil'], function (Ajax, Language, StringUtil) {
    "use strict";

    return {
        init: function (result, score, timeTotal, quizData, gameContainer) {
            this._gameContainer = gameContainer;
            this._score = score;
            this._timeTotal = timeTotal;
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
                        score: this._score,
                        result: this._result,
                        timeTotal: this._timeTotal
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
            console.log(data);

            this._renderResultLive();
        },

        _ajaxFailure: function () {
            this._renderResultOffline();
        }
    };
});
