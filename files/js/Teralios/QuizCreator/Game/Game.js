define(["require", "exports", "./Section/Header"], function (require, exports, Header_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Game = void 0;
    const status1Seconds = 5;
    const status2Seconds = 15;
    const status1Value = 10;
    const status2Value = 5;
    const status3Value = 1;
    class Game {
        constructor(quiz, container) {
            this.seconds = 0;
            this.score = 0;
            this.value = 0;
            this.quiz = quiz;
            this.container = container;
            this.init();
        }
        init() {
            // sections
            this.header = new Header_1.Header(this.quiz.questionsCount);
            // create game field.
            const field = document.createElement('div');
            field.classList.add('gameField');
            this.container.appendChild(field);
            // add main section
            // show startView
        }
        registerAnswer(option) {
        }
    }
    exports.Game = Game;
});
