define(["require", "exports", "./Section/Header", "./Section/Main"], function (require, exports, Header_1, Main_1) {
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
            this.quizSeconds = 0;
            this.score = 0;
            this.value = 0;
            this.quiz = quiz;
            this.container = container;
            this.result = [];
            this.init();
        }
        init() {
            // sections
            this.header = new Header_1.Header(this.quiz.questionsCount);
            this.main = new Main_1.Main(() => { this.startClock(); }, (option) => this.registerAnswer(option), () => { this.setNextQuestion(); }, () => { this.finishGame(); }, () => { this.setNextQuestion(); }, this.header, () => { this.resetClock(); });
            // create game field.
            const field = document.createElement('div');
            field.classList.add('gameField');
            this.container.appendChild(field);
            this.container = field;
            // add sections
            this.container.append(this.header.getView());
            this.container.append(this.main.getView());
            setTimeout(() => {
                this.main.showStartView();
            }, 1000); // css needs some time to render.
        }
        registerAnswer(option) {
            // check answer - may b
            const returnValue = this.currentQuestion.checkAnswer(option);
            if (returnValue) {
                this.score += this.value;
            }
            this.quizSeconds += this.seconds;
            // stop watch and
            this.header.stopAnimation();
            if (this.watchID) {
                clearInterval(this.watchID);
            }
            // update header
            this.header.updateScore(this.score);
            this.header.updateQuestionIndicator(this.currentQuestion.no, returnValue);
            // get current data
            const result = new Map();
            result['time'] = this.seconds;
            result['option'] = option;
            this.result[this.currentQuestionIndex] = result;
            // reset counters
            this.seconds = 0;
            this.value = status1Value;
            return returnValue;
        }
        resetClock() {
            this.value = status1Value; // i know i know.
            this.header.updateValue(this.value);
            this.header.updateTime(this.seconds);
            this.header.updateStatus('s1');
        }
        startClock() {
            this.resetClock();
            this.header.startAnimation();
            this.watchID = setInterval(() => { this.clockTick(); }, 1000);
        }
        setNextQuestion() {
            const question = this.quiz.getQuestion();
            if (question != null) {
                this.main.nextQuestion(question, this.quiz.nextQuestion());
                this.currentQuestion = question;
            }
        }
        finishGame() {
            return;
        }
        clockTick() {
            this.seconds++;
            this.header.updateTime(this.seconds);
            if (this.seconds > status1Seconds) {
                this.value = status2Value;
                this.header.updateStatus('s2');
                this.header.updateValue(this.value);
            }
            if (this.seconds > status2Seconds) {
                this.value = status3Value;
                this.header.updateStatus('s3');
                this.header.updateValue(this.value);
            }
        }
    }
    exports.Game = Game;
});
