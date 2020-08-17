define(['Ajax', 'Language', 'StringUtil'], function (Ajax, Language, StringUtil) {
    "use strict";

    return {
        init: function (playerResult, score, quizData, gameContainer) {
            this._gameContainer = gameContainer;
            this._score = score;
            this._quizData = quizData;
            this._result = playerResult;

            // temp
            gameContainer.innerHTML = '<p>Game finished</p>';
        }
    }
});
