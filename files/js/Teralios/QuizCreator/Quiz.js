define(['Ajax', 'StringUtil', 'Language', 'Teralios/QuizCreator/Game'], function (Ajax, StringUtil, Language, Game) {
    "use strict";

    /**
     * Quiz object
     * @param quizContainer
     * @constructor
     */
    function Quiz(quizContainer) {
        this.init(quizContainer);
    }

    Quiz.prototype = {
        /**
         * Inits quiz.
         * @param quizContainer
         */
        init: function (quizContainer) {
            this._quizContainer = quizContainer;
            this.quizID = Number(elData(this._quizContainer, 'id'));

            this._gameContainer = elBySel('.game', this._quizContainer);

            if (!this._gameContainer) {
                this._gameContainer = elCreate('div');
                this._gameContainer.className = 'game';
                this._quizContainer.appendChild(this._gameContainer);
            } else {
                this._gameContainer.style.height = this._gameContainer.offsetHeight + 'px';
                this._gameContainer.innerHTML = '';
                this._gameContainer.classList.remove('dummy');
            }

            if (!Number.isInteger(this.quizID)) {
                this._printError(Language.get('wcf.quizMaker.quiz.error.notValidID'));
            }

            this._loadData();
        },

        _loadData: function () {
            Ajax.apiOnce(
                {
                    data: {
                        actionName: "loadQuiz",
                        className: 'wcf\\data\\quiz\\QuizAction',
                        objectIDs: [this.quizID]
                    },
                    success: this.prepareGame.bind(this),
                    failure: function () {
                        this._printError(Language.get('wcf.quizMaker.quiz.error.loading'));
                    }.bind(this)
                }
            )
        },

        prepareGame: function (data) {
            this._data = data.returnValues;
            this._initGame();
        },

        _printError: function (errorMessage) {
            this._gameContainer.innerHTML = '<div class="gameContent"><p class="error">' + StringUtil.escapeHTML(errorMessage) + '</p></div>';
        },

        _initGame: function () {
            this._game = new Game(this._data, this._gameContainer);
            this._game.buildGame();
        }
    };

    return Quiz;
});
