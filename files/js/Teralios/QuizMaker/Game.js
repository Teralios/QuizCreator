define(['Ajax', 'StringUtil', 'Language'], function (Ajax, StringUtil, Language) {
    "use strict";

    // game vars
    var neededKeys = ['quizID', 'type', 'questions', 'questionList', 'goalList'];
    var answers = ['A', 'B', 'C', 'D'];
    var watchClasses = ['stage0', 'stage1', 'stage2'];
    var watchPoints = [10, 5, 1];
    var watchTimeBorder = [5, 15, 0];

    /**
     * @param data
     * @param container
     * @constructor
     */
    function Game(data, containerID)
    {
        this.init(data, containerID);
    }

    Quiz.prototype = {
        /**
         * @param data
         * @param container
         */
        init: function (data, containerID) {
            this._gameContainer = elById(containerID);
            this._data = data;

            if (this._checkData()) {
                this._createBaseHTML();
            }
        },

        startGame: function () {
            elRemove(this._buttonStart);
            this._buildGameHTML();
        },

        nextQuestion: function () {

        },

        answer: function () {

        },

        /**
         * Checks data array
         * @private
         */
        _checkData: function () {
            var error = false;

            var length = neededKeys.length;
            for (var i = 0; i < length; i++) {
                if (!this._data.hasOwnProperty(neededKeys[i])) {
                    error = true;
                    break;
                }
            }

            if (error === true) {
                this._printError(Language.get('wcf.quizMaker.game.missingData'));
            }

            return !error;
        },

        _createBaseHTML: function () {
            // containers for quiz
            this._headerContainer = elCreate('div');
            this._contentContainer = elCreate('div');
            this._footerContainer = elCreate('div');
            this._headerContainer.className = 'gameHeader';
            this._contentContainer.className = 'gameContent';
            this._footerContainer.className = 'gameFooter';

            // build base container
            this._gameContainer.innerHTML = ''; // clears game container.
            this._gameContainer.appendChild(this._headerContainer);
            this._gameContainer.appendChild(this._contentContainer);
            this._gameContainer.appendChild(this._footerContainer);

            // build start button
            this._buttonStart = elCreate('button');
            this._buttonStart.textContent = Language.get('wcf.quizMaker.game.start');
            this._buttonStart.addEventListener(WCF_CLICK_EVENT, this.startGame.bind(this));
            this._contentContainer.appendChild(this._buttonStart);
        },

        _buildGameHTML: function () {
            // game information header
            // question counter
            var questionCounterRawHtml = '<b>' + Language.get('wcf.quizMaker.game.questions') + '</b> ';
            questionCounterRawHtml += '<span class="currentQuestion"> ' + this._questionIndex + '</span> / ' + this._data.questions;

            var questionCounterDiv = elCreate('div');
            questionCounterDiv.className = 'questionCounter';
            questionCounterDiv.innerHTML = questionCounterRawHtml;

            this._questionCounter = elBySel('.currentQuestion', questionCounterDiv);
            this._headerContainer.appendChild(questionCounterDiv);

            // time counter
            var timeCounterRawHtml = '<b>' + Language.get('wcf.quizMaker.game.time') + '</b> ';
            timeCounterRawHtml += '<span class="seconds"></span>';

            var timeCounterDiv = elCreate('div');
            timeCounterDiv.className = 'timeCounter';
            timeCounterDiv.innerHTML = timeCounterRawHtml;

            this._timeCounter = elBySel('.seconds', timeCounterDiv);
            this._headerContainer.appendChild(timeCounterDiv);

            // point value of question
            var pointValueRawHtml = '+ <span class="questionValue"></span> ' + Language.get('wcf.quizMaker.game.points');

            var pointValueDiv = elCreate('div');
            pointValueDiv.className = 'currentQuestionValue';
            pointValueDiv.innerHTML = pointValueRawHtml;

            this._questionValue = elBySel('.questionValue', pointValueDiv);
            this._headerContainer.appendChild(pointValueDiv);

            // game information footer
            var footerRawHtml = '<p><span class="score"></span> ' + Language.get('wcf.quizMaker.score') + '</p>';
            this._footerContainer.innerHTML = footerRawHtml;
            this._playerScore = elBySel('.score', this._footerContainer);

            // build game content
            this._answerList = this._buildQuestionField();
            elHide(this._answerList);

            this._buttonNext = elCreate('button');



        },

        _buildQuestionField: function () {

        },

        _printError: function (errorMessage) {
            this._gameContainer.innerHTML = '<p class="error">' + errorMessage + '</p>';
        }
    }
});
