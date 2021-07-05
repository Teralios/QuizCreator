define(["require", "exports", "WoltLabSuite/Core/Language"], function (require, exports, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Header = void 0;
    // general information blogs
    const question = document.createElement('b');
    const questionCounter = document.createElement('b');
    const questionMakers = [];
    const value = document.createElement('span');
    const score = document.createElement('span');
    const clock = document.createElement('p');
    function buildHeaderView(questions) {
        const container = document.createElement('div');
        container.classList.add('header');
        container.append(buildQuestionInfo(questions), buildStopwatch(), buildScore());
        return container;
    }
    function buildQuestionInfo(questions) {
        // main container
        const container = document.createElement('div');
        container.classList.add('questionInfo');
        // text information
        const textInformation = document.createElement('p');
        textInformation.append(Language_1.get('wcf.quizCreator.game.header.question.prefix'), question, Language_1.get('wcf.quizCreator.game.header.question.suffix'), questionCounter);
        questionCounter.textContent = String(questions);
        // markers
        const makerContainer = document.createElement('p');
        for (let i = 0; i < questions; i++) {
            const marker = document.createElement('span');
            marker.classList.add('fa', 'icon16', 'fa-question-circle');
            makerContainer.append(marker);
            questionMakers.push(marker);
        }
        // finalize container
        container.append(textInformation, makerContainer);
        return container;
    }
    function buildStopwatch() {
        const container = document.createElement('div');
        container.classList.add('clock');
        // top line
        const top = document.createElement('p');
        top.classList.add('top', 'paused');
        const dot = document.createElement('span');
        dot.classList.add('fa', 'icon16', 'fa-circle');
        top.append(dot, ' +', value);
        container.append(top, clock);
        return container;
    }
    function buildScore() {
        const container = document.createElement('div');
        container.classList.add('score');
        container.append(score, document.createElement('b'));
        if (container.lastChild != null) {
            container.lastChild.textContent = Language_1.get('wcf.quizCreator.game.header.score');
        }
        return container;
    }
    class Header {
        constructor(questions) {
            this.container = buildHeaderView(questions);
        }
        getView() {
            return this.container;
        }
        updateQuestion(questionIndex) {
            question.textContent = String(questionIndex);
        }
        updateQuestionIndicator(no, isCorrect) {
            no = no - 1;
            questionMakers[no].classList.add((isCorrect) ? 'correct' : 'incorrect');
        }
        updateScore(newScore) {
            score.textContent = String(newScore);
        }
        updateStatus(newClass) {
            var _a;
            const stopWatch = (_a = this.container.getElementsByClassName('clock')[0]) !== null && _a !== void 0 ? _a : null;
            stopWatch.classList.remove('s1', 's2', 's3');
            stopWatch.classList.add(newClass);
        }
        updateValue(newValue) {
            value.textContent = String(newValue);
        }
        updateTime(seconds) {
            const minute = Math.floor(seconds / 60);
            const second = seconds % 60;
            const dot = ((seconds % 2) == 0) ? ':' : ' ';
            clock.textContent = String(minute) + dot + ((second < 10) ? '0' : '') + String(second);
        }
        stopAnimation() {
            const top = this.getStopwatchTop();
            if (top != null) {
                top.classList.add('paused');
            }
        }
        startAnimation() {
            const top = this.getStopwatchTop();
            if (top != null) {
                top.classList.remove('paused');
            }
        }
        getStopwatchTop() {
            var _a;
            return (_a = this.container.getElementsByClassName('top')[0]) !== null && _a !== void 0 ? _a : null;
        }
    }
    exports.Header = Header;
});
