define(['Ajax', 'Language', 'StringUtil'], function (Ajax, Language, StringUtil) {
    "use strict";

    return {
        init: function (result, score, timeTotal, quizData, gameContainer) {
            this._result = result;
            this._score = score;
            this._timeTotal = timeTotal;
            this._quizData = quizData;
            this._gameContainer = gameContainer;
        },

        showResult: function () {
            if (this._quizData.type === 'competition') {
                this._loadResult();
            } else {
                this._renderResultOffline();
            }
        },

        _loadResult: function () {
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

        _renderResultLive: function (data) {
            this._renderBase();
            this._renderScore(data)
        },

        _renderResultOffline: function () {
            this._renderBase();
        },

        _buildBaseContainer: function () {
            this._gameContainer.innerHTML = '<div class="result"></div>';
            this._resultContainer = elBySel('.result', this._gameContainer);
        },

        _renderBase: function () {
            this._buildBaseContainer();
            this._renderGoal();
        },

        _renderGoal: function () {
            var goalList = this._quizData.goalList;

            if (goalList.length > 0) {
                var goal = false;

                for (var i = 0; i < goalList.length; i++) {
                    if (Number(goalList[i].points) <= Number(this._score)) {
                        goal = goalList[i];
                    }
                }

                if (goal !== false) {
                    var goalHtml = '<span class="icon icon96 ' + goal.icon + '"></span>';
                    goalHtml += '<h3 class="name">' + StringUtil.escapeHTML(goal.title) + '</h3>';

                    if (goal.description !== '') {
                        goalHtml += '<p class="small">' + StringUtil.escapeHTML(goal.description) + '</p>';
                    }

                    var goalContainer = elCreate('div');
                    goalContainer.classList.add('goal');
                    goalContainer.innerHTML = goalHtml;

                    this._resultContainer.appendChild(goalContainer);
                }
            }
        },

        _renderScore: function (data) {
            console.log(data);

            var scoreHtml = '<p class="player">' + this._score + ' ' + Language.get('wcf.quizCreator.game.score') + '</p>';

            if (data.players > 0) {
                scoreHtml += '<div class="average"><p>⌀</p>';
                scoreHtml += '<p> ' + StringUtil.formatNumeric((data.scoreSum / data.players)) + ' ' + Language.get('wcf.quizCreator.game.score') + '</p>';
            }

            if (data.playerWorse === 0) {
                scoreHtml += '<p>' + Language.get('wcf.quizCreator.game.lastPosition') + '</p>';
            } else {
                var playerWorse = StringUtil.formatNumeric((data.playerWorse * 100), 2);
                scoreHtml += Language.get('wcf.quizCreator.game.otherPlayers', {percent: playerWorse})
            }

            var scoreContainer = elCreate('div');
            scoreContainer.classList.add('score');
            scoreContainer.innerHTML = scoreHtml;

            this._resultContainer.appendChild(scoreContainer);
        },

        _ajaxSetup: function () {
            return {
                data: {
                    actionName: "finishGame",
                    className: 'wcf\\data\\quiz\\QuizAction',
                }
            }
        },

        _ajaxSuccess: function (data) {
            this._renderResultLive(data.returnValues);
        },

        _ajaxFailure: function () {
            this._renderResultOffline();
        }
    };
});
