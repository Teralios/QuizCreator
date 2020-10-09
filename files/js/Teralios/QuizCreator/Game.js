define(['StringUtil', 'Language', 'Teralios/QuizCreator/Result'], function (StringUtil, Language, Result) {
    "use strict";

    // game vars
    var neededKeys = ['quizID', 'type', 'questions', 'questionList', 'goalList'];
    var answers = ['A', 'B', 'C', 'D'];
    var clockClasses = 3;
    var clockPoints = [10, 5, 1];
    var clockBorders = [5, 15, 0];

    /**
     * @param data
     * @param gameContainer
     * @constructor
     */
    function Game(data, gameContainer)
    {
        this.init(data, gameContainer);
    }

    Game.prototype = {
        /**
         * @param data
         * @param gameContainer
         */
        init: function (data, gameContainer) {
            this._gameContainer = gameContainer;
            this._data = data;

            if (this._checkData()) {
                this._gameCanStart = true;
            }
        },

        buildGame: function () {
            if (this._gameCanStart === true) {
                this._createBaseHTML();
            }
        },

        /**
         * Start game
         */
        startGame: function () {
            elRemove(this._buttonStart);
            this._buildGameHTML();

            this._questionIndex = 0;
            this._score = 0;
            this._gameResult = [];
            this._timeTotal = 0;
            this._showQuestion(true);
        },

        /**
         * Function behind answer click.
         * @param event
         */
        answer: function (event) {
            this._toggleButtons(false);
            this._stopClock();

            var answer = elData(event.target, 'value');
            if (answer === this._currentQuestion.answer) {
                event.target.classList.add('correct');
                this._score += this._questionScoreValue;
                this._updateScoreContainer();
            } else {
                event.target.classList.add('wrong');
            }

            this._gameResult[this._questionIndex] = {
                'answer' : answer,
                'time' : this._time
            }

            this._timeTotal += this._time;

            if ((this._questionIndex + 1) >= this._data.questions) {
                this._buttonNext.textContent = Language.get('wcf.quizCreator.game.finish');
            }

            this._buttonNext.style.visibility = 'visible';
            this._updateClockContainer(false)
        },

        /**
         * Function behind next button.
         */
        next: function () {
            this._buttonNext.style.visibility = 'hidden';

            this._questionIndex++;

            if (this._questionIndex >= this._data.questions) {
                this._finishGame();
            } else {
                this._showQuestion(false);
            }
        },

        /**
         * Checks data array.
         * @private
         */
        _checkData: function () {
            var error = false;
            var length = neededKeys.length;

            for (var i = 0; i < length; i++) {
                if (this._data.hasOwnProperty(neededKeys[i]) === false) {
                    error = true;
                    break;
                }
            }

            if (this._data.questionList.length === 0) {
                error = true;
            }

            if (error === true) {
                this._printError(Language.get('wcf.quizCreator.game.missingData'));
            }

            return !error;
        },

        /**
         * Creates base html.
         * @private
         */
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
            this._buttonStart.textContent = Language.get('wcf.quizCreator.game.start');
            /** global: WCF_CLICK_EVENT */
            this._buttonStart.addEventListener(/** global: WCF_CLICK_EVENT */WCF_CLICK_EVENT, this.startGame.bind(this));
            this._contentContainer.appendChild(this._buttonStart);
        },

        /**
         * Creates game html.
         * @private
         */
        _buildGameHTML: function () {
            // game information header
            // question counter
            var questionCounterRawHtml = '<b>' + Language.get('wcf.quizCreator.game.questions') + '</b> ';
            questionCounterRawHtml += '<span class="currentQuestion"> ' + this._questionIndex + '</span> / ' + this._data.questions;

            var questionCounterDiv = elCreate('div');
            questionCounterDiv.className = 'questionCounter';
            questionCounterDiv.innerHTML = questionCounterRawHtml;

            this._questionCounterContainer = elBySel('.currentQuestion', questionCounterDiv);
            this._headerContainer.appendChild(questionCounterDiv);

            // time counter
            var timeCounterDiv = elCreate('div');
            timeCounterDiv.className = 'clock';
            timeCounterDiv.innerHTML = '<b>' + Language.get('wcf.quizCreator.game.time') + '</b> <span class="seconds"></span> ';

            this._timeContainer = elBySel('.seconds', timeCounterDiv);
            this._headerContainer.appendChild(timeCounterDiv);

            // point value of question
            var pointValueDiv = elCreate('div');
            pointValueDiv.className = 'currentQuestionValue';
            pointValueDiv.innerHTML = '+ <span class="questionValue"></span> <b>' + Language.get('wcf.quizCreator.game.points') + '</b>';

            this._questionValueContainer = elBySel('.questionValue', pointValueDiv);
            this._headerContainer.appendChild(pointValueDiv);

            // game information footer
            this._footerContainer.innerHTML = '<p><span class="score"></span> ' + Language.get('wcf.quizCreator.game.score') + '</p>';
            this._scoreContainer = elBySel('.score', this._footerContainer);

            // build game content
            this._questionText = elCreate('p');
            this._questionText.className = 'question';
            elHide(this._questionText);
            this._contentContainer.appendChild(this._questionText);

            this._answerList = this._buildQuestionField();
            elHide(this._answerList);
            this._contentContainer.appendChild(this._answerList);

            this._buttonNext = elCreate('button');
            this._buttonNext.textContent = Language.get('wcf.quizCreator.game.next');
            /** global: WCF_CLICK_EVENT */
            this._buttonNext.addEventListener(/** global: WCF_CLICK_EVENT */WCF_CLICK_EVENT, this.next.bind(this));
            this._buttonNext.style.visibility = 'hidden';
            this._contentContainer.appendChild(this._buttonNext)
        },

        /**
         * Build question and answer field.
         * @returns {Element}
         * @private
         */
        _buildQuestionField: function () {
            // build container;
            var list = elCreate('ul');
            list.className = 'answerList';

            // build buttons
            shuffle(answers);
            this._buttons = [];
            for (var i = 0; i < 4; i++) {
                var key = answers[i];
                var button = elCreate('button');

                button.className = 'answer';
                elData(button, 'value', key);
                /** global: WCF_CLICK_EVENT */
                button.addEventListener(/** global: WCF_CLICK_EVENT */WCF_CLICK_EVENT, this.answer.bind(this));

                var liElement = elCreate('li');
                liElement.appendChild(button);
                list.appendChild(liElement);

                this._buttons[i] = button;
            }
            return list;
        },

        /**
         * Toggle answer buttons.
         * @param enable
         * @private
         */
        _toggleButtons: function (enable) {
            for (var i = 0; i < 4; i++) {
                if (enable === true) {
                    this._buttons[i].removeAttribute('disabled');
                    this._buttons[i].classList.remove('wrong', 'correct');
                } else {
                    this._buttons[i].setAttribute('disabled', 'disabled');
                }
            }
        },

        /**
         * Prepare answer fields for new questions.
         * @param startGame
         * @private
         */
        _showQuestion: function (startGame) {
            if (startGame === true) {
                elShow(this._questionText);
                elShow(this._answerList);
                this._contentContainer.classList.add('borderTop');
                this._footerContainer.classList.add('borderTop');
                this._updateScoreContainer();
            }

            this._currentQuestion = this._data.questionList[this._questionIndex];
            this._questionText.textContent = StringUtil.escapeHTML(this._currentQuestion.question);

            // update buttons.
            for (var i = 0; i < 4; i++) {
                var optionString = 'option' + elData(this._buttons[i], 'value');

                this._buttons[i].textContent = StringUtil.escapeHTML(this._currentQuestion[optionString]);
            }

            this._toggleButtons(true);
            this._questionCounterContainer.textContent = String(this._questionIndex + 1);
            this._startClock();
        },

        /**
         * Starts clock.
         * @private
         */
        _startClock: function () {
            if (this._data.type === 'competition') {
                // remove previous status classes
                for (var i = 1; i <= clockClasses; i++) {
                    this._timeContainer.classList.remove('status' + i);
                }

                // set status for game
                this._clockStatus = 0;
                this._timeContainer.classList.add('status' + this._clockStatus);
                this._questionScoreValue = clockPoints[this._clockStatus];
            } else {
                this._questionScoreValue = 1;
            }

            this._time = 0;
            this._updateClockContainer();
            this._updatePointContainer();

            this._clockID = setInterval(this._clockTick.bind(this), 1000);
        },

        /**
         * Stops clock.
         * @private
         */
        _stopClock: function () {
            clearInterval(this._clockID);
        },

        /**
         * A clock tick every second.
         * @private
         */
        _clockTick: function () {
            this._time++;

            if (this._data.type === 'competition') {
                var timeBorder = clockBorders[this._clockStatus];

                if (timeBorder > 0 && this._time > timeBorder) {
                    this._timeContainer.classList.remove('status' + this._clockStatus);
                    this._clockStatus++;
                    this._timeContainer.classList.add('status' + this._clockStatus);
                    this._questionScoreValue = clockPoints[this._clockStatus];

                    this._updatePointContainer();
                }
            }

            this._updateClockContainer(true);
        },

        /**
         * Updates clock view.
         * @param blinking
         * @private
         */
        _updateClockContainer: function (blinking) {
            // update clock
            var seconds = String(this._time % 60);
            var minutes = Math.floor(this._time / 60);

            var blinker = ':'
            if (blinking === true) {
                blinker = (this._time % 2 === 1) ? ' ' : ':';
            }

            if (seconds.length < 2) {
                seconds = "0" + seconds;
            }

            this._timeContainer.textContent = minutes + blinker + seconds;
        },

        /**
         * Update question value view.
         * @private
         */
        _updatePointContainer: function () {
            this._questionValueContainer.textContent = String(this._questionScoreValue);
        },

        /**
         * Update score view.
         * @private
         */
        _updateScoreContainer: function () {
            this._scoreContainer.textContent = String(this._score);
        },

        /**
         * Finish game.
         * @private
         */
        _finishGame: function () {
            Result.init(this._gameResult, this._score, this._timeTotal, this._data, this._gameContainer);
            Result.showResult();
        },

        /**
         * Prints error.
         * @param errorMessage
         * @private
         */
        _printError: function (errorMessage) {
            this._gameContainer.innerHTML = '<div class="gameContent"><p class="error">' + errorMessage + '</p></div>';
        }
    };

    return Game;
});
