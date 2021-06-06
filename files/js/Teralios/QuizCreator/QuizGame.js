define(["require", "exports", "./Data/Loader", "WoltLabSuite/Core/Language"], function (require, exports, Loader_1, Language_1) {
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
            // @todo start working on game
        }
        showError() {
            var _a, _b;
            const icon = (_a = this.gameField.getElementsByClassName('icon')[0]) !== null && _a !== void 0 ? _a : null;
            const information = (_b = this.gameField.getElementsByTagName('p')[0]) !== null && _b !== void 0 ? _b : null;
            if (icon !== null) {
                this.gameField.removeChild(icon);
            }
            if (information !== null) {
                information.textContent = Language_1.get('wcf.quizCreator.game.status.error');
                information.classList.add('error');
            }
        }
        findField() {
            const gameField = document.querySelector(this.selector + ' .game');
            if (gameField instanceof HTMLElement) {
                this.gameField = gameField;
            }
            else {
                console.error('Can not found game field element.');
                return;
            }
            // add loading information to game field.
            const spinner = document.createElement('span');
            const information = document.createElement('p');
            spinner.classList.add('icon', 'icon128', 'fa-spinner');
            information.innerText = Language_1.get('wcf.quizCreator.game.status.loading');
            this.gameField.classList.add('statusLoading');
            this.gameField.appendChild(spinner);
            this.gameField.appendChild(information);
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
