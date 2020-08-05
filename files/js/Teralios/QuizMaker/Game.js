define(['Ajax', 'StringUtil', 'Language'], function (Ajax, StringUtil, Language) {
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
            this._showQuestion(true);
        },

        /**
         * Function behind answer click.
         * @param event
         */
        answer: function (event) {
            this._toggleButtons(false);
            this._stopClock();

            if (elData(event.target, 'value') === this._currentQuestion.answer) {
                event.target.classList.add('correct');
                this._score += this._questionScoreValue;
                this._updateScoreContainer();
            } else {
                event.target.classList.add('wrong');
            }

            this._buttonNext.style.visibility = 'visible';
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
            alert(Object.keys(this._data));
            for (var i = 0; i < length; i++) {
                if (this._data.hasOwnProperty(neededKeys[i]) === false) {
                    error = true;
                    break;
                }
            }

            if (error === true) {
                this._printError(Language.get('wcf.quizMaker.game.missingData'));
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
            this._buttonStart.textContent = Language.get('wcf.quizMaker.game.start');
            this._buttonStart.addEventListener(WCF_CLICK_EVENT, this.startGame.bind(this));
            this._contentContainer.appendChild(this._buttonStart);
        },

        /**
         * Creates game html.
         * @private
         */
        _buildGameHTML: function () {
            // game information header
            // question counter
            var questionCounterRawHtml = '<b>' + Language.get('wcf.quizMaker.game.questions') + '</b> ';
            questionCounterRawHtml += '<span class="currentQuestion"> ' + this._questionIndex + '</span> / ' + this._data.questions;

            var questionCounterDiv = elCreate('div');
            questionCounterDiv.className = 'questionCounter';
            questionCounterDiv.innerHTML = questionCounterRawHtml;

            this._questionCounterContainer = elBySel('.currentQuestion', questionCounterDiv);
            this._headerContainer.appendChild(questionCounterDiv);

            // time counter
            var timeCounterDiv = elCreate('div');
            timeCounterDiv.className = 'clock';
            timeCounterDiv.innerHTML = '<b>' + Language.get('wcf.quizMaker.game.time') + '</b> <span class="seconds"></span> ';

            this._timeContainer = elBySel('.seconds', timeCounterDiv);
            this._headerContainer.appendChild(timeCounterDiv);

            // point value of question
            var pointValueDiv = elCreate('div');
            pointValueDiv.className = 'currentQuestionValue';
            pointValueDiv.innerHTML = '+ <span class="questionValue"></span> <b>' + Language.get('wcf.quizMaker.game.points') + '</b>';

            this._questionValueContainer = elBySel('.questionValue', pointValueDiv);
            this._headerContainer.appendChild(pointValueDiv);

            // game information footer
            this._footerContainer.innerHTML = '<p><span class="score"></span> ' + Language.get('wcf.quizMaker.game.score') + '</p>';
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
            this._buttonNext.textContent = Language.get('wcf.quizMaker.game.next');
            this._buttonNext.addEventListener(WCF_CLICK_EVENT, this.next.bind(this));
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
                button.addEventListener(WCF_CLICK_EVENT, this.answer.bind(this));

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

        _clockTick: function () {
            this._time++;

            if (this._data.type === 'competition') {
                var timeBorder = clockBorders[this._clockStatus];

                if (timeBorder > 0 && this._time < timeBorder) {
                    this._timeContainer.classList.remove('status' + this._clockStatus);
                    this._clockStatus++;
                    this._timeContainer.classList.add('status' + this._clockStatus);
                    this._questionScoreValue = clockPoints[this._clockStatus];

                    this._updatePointContainer();
                }
            }

            this._updateClockContainer();
        },

        _updateClockContainer: function () {
            // update clock
            var seconds = String(this._time % 60);
            var minutes = Math.floor(this._time / 60);
            var blinker = (this._time % 2 === 1) ? ' ' : ':';

            if (seconds.length < 2) {
                seconds = "0" + seconds;
            }

            this._timeContainer.textContent = minutes + blinker + seconds;
        },

        _updatePointContainer: function () {
            this._questionValueContainer.textContent = String(this._questionScoreValue);
        },

        _updateScoreContainer: function () {
            this._scoreContainer.textContent = String(this._score);
        },

        _finishGame: function() {
            // @ todo implement final page
        },

        _printError: function (errorMessage) {
            this._gameContainer.innerHTML = '<div class="gameContent"><p class="error">' + errorMessage + '</p></div>';
        }
    };

    return Game;
});
