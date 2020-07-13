define(['Ajax', 'Dom/Util', 'Language'], function (Ajax, Dom, Language) {
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
            this.currentQuestion = 1;
            this.currentScore = 0;
            this.currentQuestionValue = points[this.currentStage];
            this.isCompetition = isCompetition;

            // select containers for game
            this._container = quizContainer;
            this._gameHeader = elBySel('.quizGameHeader', this._container);
            this._gameContent = elBySel('.quizGameContent', this._container);
            this._gameFooter = elBySel('.quizGameContent', this._container);

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

            // build data
            var headerHtml = '';
            headerHtml += '<div class="questionCounter"><b>' + Language.get('wcf.quizMaker.play.question') + '</b>';
            headerHtml += '<span class="currentQuestion"> ' + this.currentQuestion + '</span> / ' + this.questions + '</div>';
            headerHtml += '<div class="questionTime"><b>' + Language.get('wcf.quizMaker.play.time') + '</b> <span class="secondsPlayed"></span></div>';
            headerHtml += '<div class="questionPoints"></div>';
            this._gameHeader.innerHTML = headerHtml;

            var footerHtml = '';
            footerHtml += '<p><span class="score">' + this.currentScore + '</span> <b>' + Language.get('wcf.quizMaker.play.points') + '</b></p>';
            this._gameFooter.innerHTML = footerHtml;

            //add border
            this._gameContent.classList.add('borderTop');
            this._gameFooter.classList.add('borderTop');

            // updates counter;
            this._updatePoints(10);
            this._updateTime();
        },

        startCycle: function () {
            this._updateTime();
            this._updatePoints(points[this.currentStage]);
            elBySel('.secondsPlayed', this._gameHeader).classList.add(timeClasses[this.currentStage]);
            setInterval(this._timeWatch.bind(this), 1000);
        },

        stopCycle: function () {
            // remove interval and reset data
            clearInterval(this._timeWatch);
            this.time = this.currentStage = 0;

            // update game information
            this._updateTime();
            this._updatePoints(points[this.currentStage])
        },

        _timeWatch: function () {
            var timeBorder = timeLimit[this.currentStage];

            if (timeBorder > 0 && this.time >= timeBorder) {
                this.currentStage++;

                var secondsPlayed = elBySel('.secondsPlayed', this._gameHeader);
                secondsPlayed.classList.remove(timeClasses[this.currentStage - 1]);
                secondsPlayed.classList.add(timeClasses[this.currentStage]);
                this._updatePoints(points[this.currentStage])
            }

            this._updateTime();

            this.time++;
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
            this.questionList = this.questionList;
            this.goalList = this.goalList;

            // remove overlay
            this._gameContent.classList.remove('loading');
            elRemove(this._loadingOverlay);

            // create start button
            var startButton = elCreate('button');
            startButton.className = 'quizStart';
            startButton.addEventListener(/** global: WCF_CLICK_EVENT */WCF_CLICK_EVENT, this.startGame.bind(this));
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
