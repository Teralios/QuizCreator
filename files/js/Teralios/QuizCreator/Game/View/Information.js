/**
 * Provides helper functions to work with DOM nodes.
 *
 * @author      Karsten (Teralios) Achterrath
 * @copyright   2021 teralios.de
 * @license     GNU General Public License <https://opensource.org/licenses/GPL-3.0>
 * @module      Teralios/QuizCreator/Game/Container/Header
 */
define(["require", "exports", "WoltLabSuite/Core/Language"], function (require, exports, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Information = void 0;
    // information to question
    const currentQuestionNo = document.createElement('b');
    const questionCount = document.createElement('b');
    const questionIndicators = [];
    const value = document.createElement('span');
    const time = document.createElement('p');
    const score = document.createElement('span');
    const spinner = document.createElement('span');
    // templates
    function buildQuestionPart(questions) {
        // update question count
        questionCount.textContent = String(questions);
        // question (Question 1 from 10)
        const firstParagraph = document.createElement('p');
        firstParagraph.append(Language_1.get('wcf.quizCreator.game.header.question.prefix'), currentQuestionNo, Language_1.get('wcf.quizCreator.game.header.question.suffix'), questionCount);
        // Question signs (??????????)
        const secondParagraph = document.createElement('p');
        for (let i = 0; i < questions; i++) {
            const questionSign = document.createElement('span');
            questionSign.classList.add('question', 'fa', 'icon16', 'fa-question-circle');
            questionIndicators.push(questionSign);
            secondParagraph.appendChild(questionSign);
        }
        const container = document.createElement('div');
        container.classList.add('questionInfo');
        container.append(firstParagraph, secondParagraph);
        return container;
    }
    function buildClockPart() {
        const firstParagraph = document.createElement('p');
        spinner.classList.add('fa fa-icon16 fa-circle');
        spinner.classList.add('paused');
        firstParagraph.append(spinner, ' +', value);
        const container = document.createElement('div');
        container.classList.add('clock');
        container.append(firstParagraph, time);
        return container;
    }
    function buildScorePart() {
        const languagePart = document.createElement('b');
        languagePart.textContent = Language_1.get('wcf.quizCreator.game.header.score');
        const container = document.createElement('div');
        container.classList.add('score');
        container.append(score, ' ', languagePart);
        return container;
    }
    class Information {
        constructor(questionCount) {
            this.questionPart = buildQuestionPart(questionCount);
            this.clockPart = buildClockPart();
            this.scorePart = buildScorePart();
        }
        getQuestionPart() {
            return this.questionPart;
        }
        getClockPart() {
            return this.clockPart;
        }
        getScorePart() {
            return this.scorePart;
        }
        switchAnimation() {
            if (spinner.classList.contains('paused')) {
                spinner.classList.remove('paused');
            }
            else {
                spinner.classList.add('paused');
            }
        }
        updateTime(timestamp) {
            const minutes = timestamp / 60;
            const seconds = timestamp % 60;
            time.textContent = ((minutes < 10) ? '0' : '') + String(minutes)
                + ((seconds % 2 == 0) ? ':' : ' ')
                + ((seconds < 10) ? '0' : '') + String(seconds);
        }
        updateClockStatus(newClass) {
            this.clockPart.classList.remove('s1', 's2', 's3');
            this.clockPart.classList.add(newClass);
        }
        updateValue(currentValue) {
            value.textContent = String(currentValue);
        }
        updateScore(currentScore) {
            score.textContent = String(currentScore);
        }
        currentQuestion(questionNo) {
            currentQuestionNo.textContent = String(questionNo);
        }
        updateQuestionIndicator(questionNo, isCorrect) {
            if (questionIndicators[questionNo - 1] !== undefined) {
                questionIndicators[questionNo - 1].classList.add(isCorrect ? 'correct' : 'incorrect');
            }
        }
    }
    exports.Information = Information;
});
