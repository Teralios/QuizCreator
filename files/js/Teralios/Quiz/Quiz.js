define(['Ajax', 'StringUtil', 'Language'], function (Ajax, StringUtil, Language) {
    "use strict";

    var points = [10, 5, 1];
    var timeLimit = [5, 15, 0];
    var timeClasses = ['stage0', 'stage1', 'stage2'];

    /**
     * @constructor
     */
    function Quiz(container, isCompetition = true)
    {
        this.init(container, isCompetition);
    }

    Quiz.prototype = {
        init: function (quizContainer, isCompetition) {
            // set base vars
            this.quizID = elData(quizContainer, 'id');
            this.currentStage = this.time = 0;
            this.currentQuestionKey = 1;
            this.currentScore = 0;
            this.currentQuestionValue = points[this.currentStage];
            this.isCompetition = isCompetition;

            // select containers for game
            this._container = quizContainer;
            this._gameHeader = elBySel('.quizGameHeader', this._container);
            this._gameContent = elBySel('.quizGameContent', this._container);
            this._gameFooter = elBySel('.quizGameFooter', this._container);

            // set waiting overlay
            this._loadingOverlay = elCreate('div');
            this._loadingOverlay.className = 'quizLoadingOverlay';
            this._loadingOverlay.innerHTML = '<span class="icon icon96 fa-spinner"></span>';
            this._gameContent.classList.add('loading');
            this._gameContent.appendChild(this._loadingOverlay);

            // load data
            Ajax.api(this);
        },

        startGame: function () {
            // remove start button
            elRemove(elBySel('.quizStart', this._gameContent));

            // build header and footer
            var headerHtml = '';
            headerHtml += '<div class="questionCounter"><b>' + Language.get('wcf.quizMaker.play.question') + '</b>';
            headerHtml += '<span class="currentQuestionKey"> ' + this.currentQuestionKey + '</span> / ' + StringUtil.escapeHTML(this.questions) + '</div>';
            headerHtml += '<div class="questionTime"><b>' + Language.get('wcf.quizMaker.play.time') + '</b> <span class="secondsPlayed"></span></div>';
            headerHtml += '<div class="questionPoints"></div>';
            this._gameHeader.innerHTML = headerHtml;

            var footerHtml = '';
            footerHtml += '<p><span class="score">' + this.currentScore + '</span> <b>' + Language.get('wcf.quizMaker.play.points') + '</b></p>';
            this._gameFooter.innerHTML = footerHtml;

            // build content
            var questionDiv = elCreate('div');
            questionDiv.className = 'question';
            this._buttonNext = elCreate('button');
            this._buttonNext.innerHTML = Language.get('wcf.quizMaker.play.next');
            this._buttonNext.addEventListener(WCF_CLICK_EVENT, this.nextQuestion.bind(this));

            this._answerList = elCreate('ul');
            this._answerList.className = 'answerList';

            var buttons = ['A', 'B', 'C', 'D'];
            this._buttons = {};
            for (var i = 0; i < 4; i++) {
                var key = buttons[i];
                var button = elCreate('button');
                button.className = 'answer';
                elData(button, 'value', key);
                button.addEventListener(WCF_CLICK_EVENT, this.answer.bind(this));

                var buttonLi = elCreate('li');
                buttonLi.appendChild(button);
                this._answerList.appendChild(buttonLi);
                this._buttons[key] = button;
            }

            elHide(this._answerList);
            elHide(this._buttonNext);
            this._gameContent.appendChild(questionDiv);
            this._gameContent.appendChild(this._answerList);
            this._gameContent.appendChild(this._buttonNext);

            //add border
            this._gameContent.classList.add('borderTop');
            this._gameFooter.classList.add('borderTop');

            // updates counter;
            this._updatePoints(10);
            this._updateTime();

            this.nextQuestion();
        },

        answer: function (event) {
            this.stopCycle();

            var button = event.target;
            var answer = elData(button, 'value');
            var question = this.currentQuestion;

            if (question['answer'] === answer) {
                button.classList.add('correctAnswer');

                this.currentScore += this.currentQuestionValue;
            } else {
                button.classList.add('wrongAnswer');
            }

            this._buttons['A'].setAttribute('disabled', 'disabled');
            this._buttons['B'].setAttribute('disabled', 'disabled');
            this._buttons['C'].setAttribute('disabled', 'disabled');
            this._buttons['D'].setAttribute('disabled', 'disabled');

            this.currentQuestionKey++;

            if (this.currentQuestionKey > this.questions) {
                this.currentQuestionKey--;
                this.updateGameInformation();

                elRemove(this._answerList);
                elRemove(this._buttonNext);
                this._gameContent.innerHTML = '<p>Game finshed</p>';

            } else {
                elShow(this._buttonNext);
            }
        },

        nextQuestion: function () {
            elHide(this._buttonNext);
            elHide(this._answerList);

            var key = this.currentQuestionKey - 1;
            if (key in this.questionList) {
                this.updateGameInformation();
                var question = this.questionList[key];

                elBySel('.question', this._gameContent).innerHTML = StringUtil.escapeHTML(question['question']);
                this._buttons['A'].innerHTML = StringUtil.escapeHTML(question['optionA']);
                this._buttons['B'].innerHTML = StringUtil.escapeHTML(question['optionB']);
                this._buttons['C'].innerHTML = StringUtil.escapeHTML(question['optionC']);
                this._buttons['D'].innerHTML = StringUtil.escapeHTML(question['optionD']);

                this._buttons['A'].removeAttribute('disabled');
                this._buttons['B'].removeAttribute('disabled');
                this._buttons['C'].removeAttribute('disabled');
                this._buttons['D'].removeAttribute('disabled');

                this._buttons['A'].classList.remove('correctAnswer', 'wrongAnswer');
                this._buttons['B'].classList.remove('correctAnswer', 'wrongAnswer');
                this._buttons['C'].classList.remove('correctAnswer', 'wrongAnswer');
                this._buttons['D'].classList.remove('correctAnswer', 'wrongAnswer');

                this.currentQuestion = question;

                elShow(this._answerList);
                this.startCycle();
            } else {
                this._gameContent.innerHTML = '<p class="error">Question not found.</p>';
            }
        },

        startCycle: function () {
            this._updateTime();
            this._updatePoints(points[this.currentStage]);
            elBySel('.secondsPlayed', this._gameHeader).classList.remove('stage0', 'stage1', 'stage2');
            elBySel('.secondsPlayed', this._gameHeader).classList.add(timeClasses[this.currentStage]);

            this.intervalID = setInterval(this._timeWatch.bind(this), 1000);
        },

        stopCycle: function () {
            clearInterval(this.intervalID);
            this.time = this.currentStage = 0;
        },

        _timeWatch: function () {
            this.time++;
            var timeBorder = timeLimit[this.currentStage];

            if (timeBorder > 0 && this.time >= timeBorder) {
                this.currentStage++;

                var secondsPlayed = elBySel('.secondsPlayed', this._gameHeader);
                secondsPlayed.classList.remove(timeClasses[this.currentStage - 1]);
                secondsPlayed.classList.add(timeClasses[this.currentStage]);
                this._updatePoints(points[this.currentStage])
            }

            this._updateTime();
        },

        updateGameInformation: function () {
            elBySel('.currentQuestionKey', this._gameHeader).innerHTML = this.currentQuestionKey;
            elBySel('.score', this._gameFooter).innerHTML = this.currentScore;
        },

        _updatePoints: function (points) {
            this.currentQuestionValue = points;
            elBySel('.questionPoints', this._gameHeader).innerHTML = '+' + points + ' <b>' + Language.get('wcf.quizMaker.play.points') + '</b>';
        },

        _updateTime: function () {
            var seconds = String(this.time % 60);
            var minutes = Math.floor(this.time / 60);

            if (seconds.length < 2) {
                seconds = "0" + seconds;
            }

            elBySel('.secondsPlayed', this._gameHeader).innerHTML = minutes + ':' + seconds;
        },

        _ajaxSetup: function() {
            return {
                data: {
                    actionName: "loadQuiz",
                    className: "wcf\\data\\quiz\\QuizAction",
                    objectIDs: [this.quizID]
                }
            }
        },

        _ajaxSuccess: function(data) {
            // set data
            this.quiz = data.returnValues;
            this.questions = this.quiz.questions;
            this.questionList = this.quiz.questionList;
            this.goalList = this.quiz.goalList;

            // remove overlay
            this._gameContent.classList.remove('loading');
            elRemove(this._loadingOverlay);

            // create start button
            var startButton = elCreate('button');
            startButton.className = 'quizStart';
            startButton.addEventListener(WCF_CLICK_EVENT, this.startGame.bind(this));
            startButton.innerHTML = Language.get('wcf.quizMaker.play.start');
            this._gameContent.appendChild(startButton);
        },

        _ajaxFailure: function() {
            // remove overlay and add error
            this._gameContent.classList.remove('loading');
            elRemove(this._loadingOverlay);

            this._gameContent.innerHTML = '<p class="error">Could not load game</p>';
        },
    };

    return Quiz;
});
