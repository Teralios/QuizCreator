define(['Ajax', 'Language', 'StringUtil'], function (Ajax, Language, StringUtil) {
    "use strict";

    return {
        /**
         * Init result view.
         * @param result
         * @param score
         * @param timeTotal
         * @param quizData
         * @param gameContainer
         */
        init: function (result, score, timeTotal, quizData, gameContainer) {
            this._result = result;
            this._score = score;
            this._timeTotal = timeTotal;
            this._quizData = quizData;
            this._gameContainer = gameContainer;
        },

        /**
         * Show result for game.
         */
        showResult: function () {
            if (this._quizData.type === 'competition' && this._quizData.isActive === 1) {
                this._loadResult();
            } else {
                this._renderResultOffline();
            }
        },

        /**
         * Load statistic for quiz.
         * @private
         */
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

        /**
         * Render live result.
         * @param data
         * @private
         */
        _renderResultLive: function (data) {
            this._renderBase();
            this._renderScore(data)
        },

        /**
         * Render offline result. (no ajax connection)
         * @private
         */
        _renderResultOffline: function () {
            this._renderBase();
        },

        /**
         * Build base container for result.
         * @private
         */
        _buildBaseContainer: function () {
            this._gameContainer.innerHTML = '<div class="result"></div>';
            this._resultContainer = elBySel('.result', this._gameContainer);
        },

        /**
         * Base rendering for result.
         * @private
         */
        _renderBase: function () {
            this._buildBaseContainer();
            this._renderGoal();
        },

        /**
         * Render goal.
         * @private
         */
        _renderGoal: function () {
            var goalList = this._quizData.goalList;
            var goalHtml = '';
            var goalContainer = elCreate('div');
            goalContainer.classList.add('goal');

            if (goalList.length > 0) {
                var goal = false;

                for (var i = 0; i < goalList.length; i++) {
                    if (Number(goalList[i].points) <= Number(this._score)) {
                        goal = goalList[i];
                    }
                }

                if (goal !== false) {
                    goalHtml = '<span class="icon icon96 ' + goal.icon + '"></span>';
                    goalHtml += '<h3 class="name">' + StringUtil.escapeHTML(goal.title) + '</h3>';

                    if (goal.description !== '') {
                        goalHtml += '<p class="small">' + StringUtil.escapeHTML(goal.description) + '</p>';
                    }
                }
            } else {
                goalHtml = '<h3 class="name">' + Language.get('wcf.quizCreator.game.noGoal') + '</h3>';
                goalHtml += '<p class="small">' + Language.get('wcf.quizCreator.game.noGoal.description') + '</p>';
            }

            goalContainer.innerHTML = goalHtml;
            this._resultContainer.appendChild(goalContainer);
        },

        /**
         * render score.
         * @param data
         * @private
         */
        _renderScore: function (data) {
            var scoreHtml = '<p class="player">' + this._score + ' ' + Language.get('wcf.quizCreator.game.score') + '</p>';

            if (data.players > 0) {
                scoreHtml += '<div class="others">';
                scoreHtml += '<p>⌀ ' + StringUtil.escapeHTML(data.averageScore) + ' ' + Language.get('wcf.quizCreator.game.score') + '</p>';
            }

            if (data.betterAs !== undefined && data.betterAs > 0) {
                scoreHtml += Language.get('wcf.quizCreator.game.otherPlayers', {percent: StringUtil.escapeHTML(data.betterAs)})
            } else {
                scoreHtml += '<p>' + Language.get('wcf.quizCreator.game.lastPosition') + '</p>';
            }

            scoreHtml += '</div>';

            var scoreContainer = elCreate('div');
            scoreContainer.classList.add('score');
            scoreContainer.innerHTML = scoreHtml;

            this._resultContainer.appendChild(scoreContainer);
        },

        /**
         * Ajax setup.
         * @returns {{data: {className: string, actionName: string}}}
         * @private
         */
        _ajaxSetup: function () {
            return {
                data: {
                    actionName: "finishGame",
                    className: 'wcf\\data\\quiz\\QuizAction',
                }
            }
        },

        /**
         * Ajax success.
         * @param data
         * @private
         */
        _ajaxSuccess: function (data) {
            this._renderResultLive(data.returnValues);
        },

        /**
         * Ajax failure.
         * @private
         */
        _ajaxFailure: function () {
            this._renderResultOffline();
        }
    };
});
