define(['Ajax', 'Dom/Util', 'Language'], function (Ajax, Dom, Language) {
    "use strict";

    var points = [10, 5, 1];
    var timeLimit = [5, 15, 0];
    var timeClasses = ['stage0', 'stage1', 'stage2'];
    var currentTimeStage = 0;
    var time = 0;
    var type = 'competition';
    var data = {};
    var currentQuestion = 1;

    return {
        init: function () {
            this.startButton = elById('quizStart');
            this.startButton.addEventListener(WCF_CLICK_EVENT, this.startGame.bind(this));
        },

        startGame: function () {
            // build quiz game header
            var headerHtml = '';
            headerHtml += '<div id="questionCounter"><b>' + Language.get('wcf.quizMaker.play.question') + '</b>';
            headerHtml += '<span id="currentQuestion"> ' + currentQuestion + '</span> / n</div>';
            headerHtml += '<div id="questionTime"><b>' + Language.get('wcf.quizMaker.play.time') + '</b> <span id="secondsPlayed"></span></div>';
            headerHtml += '<div id="questionPoints"></div>';

            // reset vars
            time = currentQuestion = currentTimeStage = 0;

            // remove start button and add game header
            elRemove(elById('quizStart'));

            var quizGameHeader = elById('quizGameHeader');
            quizGameHeader.innerHTML = headerHtml;

            this._gameContent = elById('quizGameContent');
            this._gameContent.classList.add('borderTop');


            // updates counter;
            this._updatePoints(10);
            this._updateTime();
        },

        startCycle: function () {
            this._updateTime();
            elById('secondsPlayed').classList.add(timeClasses[currentTimeStage]);
            elById('questionPoints').innerHTML = '+<b>' + points[currentTimeStage] + '</b> Punkte';
            setInterval(this.timeWatch.bind(this), 1000);
        },

        stopCycle: function () {
            clearInterval(this.timeWatch);
            time = 0;
            currentTimeStage = 0;

            this._updateTime();
            this._updatePoints(points[currentTimeStage])
        },

        timeWatch: function () {
            var timeBorder = timeLimit[currentTimeStage];

            if (timeBorder > 0 && time >= timeBorder) {
                currentTimeStage++;
                elById('secondsPlayed').classList.remove(timeClasses[currentTimeStage - 1]);
                elById('secondsPlayed').classList.add(timeClasses[currentTimeStage]);
                this._updatePoints(points[currentTimeStage])
            }

            this._updateTime();

            time++;
        },

        _updatePoints: function (points) {
            elById('questionPoints').innerHTML = '+' + points + ' <b>' + Language.get('wcf.quizMaker.play.points') + '</b>';
        },

        _updateTime: function () {
            var seconds = String(time % 60);
            var minutes = Math.floor(time / 60)

            if (seconds.length < 2) {
                seconds = "0" + seconds;
            }

            elById('secondsPlayed').innerHTML = minutes + ':' + seconds;
        },
    }
});
