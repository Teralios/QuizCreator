define(['Ajax', 'StringUtil', 'Language', 'Teralios/QuizCreator/Game'], function (Ajax, StringUtil, Language, Game) {
    "use strict";

    /**
     * Quiz object
     * @param quizContainer
     * @constructor
     */
    function Quiz(quizContainer)
    {
        this.init(quizContainer);
    }

    Quiz.prototype = {
        /**
         * Inits quiz.
         * @param gameContainer
         */
        init: function (gameContainer) {
            this._gameContainer = gameContainer;
            this.quizID = Number(elData(this._gameContainer, 'quiz-id'));
            this._gameFieldContainer = elBySel('.gameField', this._gameContainer);

            if (!this._gameFieldContainer) {
                this._gameFieldContainer = elCreate('div');
                this._gameFieldContainer.className = 'gameField';
                this._gameContainer.appendChild(this._gameFieldContainer);
            } else {
                this._gameContainer.style.height = this._gameContainer.offsetHeight + 'px';
                this._gameFieldContainer.innerHTML = '';
                this._gameFieldContainer.classList.remove('dummy');
            }

            if (!Number.isInteger(this.quizID)) {
                this._printError(Language.get('wcf.quizMaker.quiz.error.notValidID'));
            }

            this._loadData();
        },

        /**
         * Load quiz data.
         * @private
         */
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

        /**
         * Prepare game container.
         * @param data
         */
        prepareGame: function (data) {
            this._data = data.returnValues;
            this._initGame();
        },

        /**
         * Print error.
         * @param errorMessage
         * @private
         */
        _printError: function (errorMessage) {
            this._gameFieldContainer.innerHTML = '<div class="gameContent"><p class="error">' + StringUtil.escapeHTML(errorMessage) + '</p></div>';
        },

        /**
         * Initialize game.
         * @private
         */
        _initGame: function () {
            this._game = new Game(this._data, this._gameFieldContainer);
            this._game.buildGame();
        }
    };

    return Quiz;
});
