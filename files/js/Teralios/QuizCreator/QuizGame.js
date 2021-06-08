define(["require", "exports", "./Data/Loader", "WoltLabSuite/Core/Language", "./Game/Game"], function (require, exports, Loader_1, Language_1, Game_1) {
    "use strict";
    class QuizGame {
        constructor(selector) {
            if (selector.startsWith('#')) {
                this.selector = selector;
                this.findField();
            }
            else {
                console.error('Selector for quiz game must be an html id selector.');
            }
        }
        startGame(quiz) {
            this.quiz = quiz;
            // its not nice, other do not work well so replace
            const oldContainer = this.gameContainer;
            const newContainer = document.createElement('div');
            newContainer.classList.add('game');
            this.gameContainer = newContainer;
            oldContainer.replaceWith(newContainer);
            new Game_1.Game(this.quiz, this.gameContainer);
        }
        showError() {
            var _a, _b;
            const icon = (_a = this.gameContainer.getElementsByClassName('icon')[0]) !== null && _a !== void 0 ? _a : null;
            const information = (_b = this.gameContainer.getElementsByTagName('p')[0]) !== null && _b !== void 0 ? _b : null;
            if (icon !== null) {
                this.gameContainer.removeChild(icon);
            }
            if (information !== null) {
                information.textContent = Language_1.get('wcf.quizCreator.game.status.error');
                information.classList.add('error');
            }
        }
        findField() {
            const gameField = document.querySelector(this.selector + ' .game');
            if (gameField instanceof HTMLElement) {
                this.gameContainer = gameField;
            }
            else {
                console.error('Can not found game field element.');
                return;
            }
            // add loading information to game field.
            const loading = document.createElement('div');
            const spinner = document.createElement('span');
            const information = document.createElement('p');
            spinner.classList.add('icon', 'icon128', 'fa-spinner');
            information.innerText = Language_1.get('wcf.quizCreator.game.status.loading');
            loading.classList.add('statusLoading');
            loading.append(spinner, information);
            this.gameContainer.appendChild(loading);
            // load data
            this.loadData();
        }
        loadData() {
            new Loader_1.LanguageLoader('#js-QuizCreator-Language');
            new Loader_1.QuizLoader(this.selector, (quiz) => { this.startGame(quiz); }, () => { this.showError(); });
        }
    }
    return QuizGame;
});
