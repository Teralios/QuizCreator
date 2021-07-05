define(["require", "exports", "tslib", "WoltLabSuite/Core/Dom/Util", "WoltLabSuite/Core/Language"], function (require, exports, tslib_1, Util_1, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.General = void 0;
    Util_1 = tslib_1.__importDefault(Util_1);
    const questionNo = document.createElement('span');
    const startButton = document.createElement('button');
    function buildIntermissionView() {
        const container = document.createElement('div');
        container.classList.add('intermissionView');
        const p = document.createElement('p');
        p.append(Language_1.get('wcf.quizCreator.game.header.question.prefix'), questionNo);
        container.appendChild(p);
        Util_1.default.hide(container);
        return container;
    }
    function buildStartView() {
        const container = document.createElement('div');
        container.classList.add('startView');
        container.appendChild(startButton);
        startButton.textContent = Language_1.get('wcf.quizCreator.game.button.start');
        Util_1.default.hide(container);
        return container;
    }
    class General {
        constructor() {
            this.startView = buildStartView();
            this.intermissionView = buildIntermissionView();
        }
        updateQuestionNo(no) {
            questionNo.textContent = String(no);
        }
        getStartView() {
            return this.startView;
        }
        getIntermissionView() {
            return this.intermissionView;
        }
        registerStartCallback(startGame) {
            startButton.addEventListener('click', startGame);
        }
    }
    exports.General = General;
});
