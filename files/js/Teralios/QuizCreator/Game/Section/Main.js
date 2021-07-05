define(["require", "exports", "tslib", "./View/General", "./View/Question", "WoltLabSuite/Core/Dom/Util"], function (require, exports, tslib_1, General_1, Question_1, Util_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Main = exports.setEffectBasics = void 0;
    Util_1 = tslib_1.__importDefault(Util_1);
    let effectDuration = 1000;
    function setEffectBasics(duration) {
        effectDuration = duration * 1000;
    }
    exports.setEffectBasics = setEffectBasics;
    class Main {
        constructor(showQuestionCallback, checkAnswerCallback, nextQuestionCallback, finishGameCallback, startCallback, header, resetClock) {
            this.showQuestionCallback = showQuestionCallback;
            this.checkAnswerCallback = checkAnswerCallback;
            this.nextQuestionCallback = nextQuestionCallback;
            this.finishGameCallback = finishGameCallback;
            this.startGameCallback = startCallback;
            this.resetClock = resetClock;
            this.header = header;
            this._initGeneral();
        }
        nextQuestion(question, notLast) {
            // remove show / fadeout effect
            if (this.container.classList.contains('show')) {
                this.container.classList.remove('show');
            }
            // remove show from header
            this.header.getView().classList.remove('show');
            setTimeout(() => {
                this.intermission();
                if (!notLast) {
                    this.questionView.prepareFor(question, this.finishGameCallback);
                }
                else {
                    this.questionView.prepareFor(question);
                }
            }, effectDuration);
        }
        intermission() {
            Util_1.default.hide(this.currentView);
            this.currentView = this.generalView.getIntermissionView();
            Util_1.default.show(this.currentView);
            this.container.classList.add('show');
            this.resetClock();
            setTimeout(() => {
                this.showNext();
            }, 2000 + effectDuration);
        }
        showNext() {
            this.container.classList.remove('show');
            setTimeout(() => {
                Util_1.default.hide(this.currentView);
                this.currentView = this.questionView.getView();
                Util_1.default.show(this.currentView);
                this.header.getView().classList.add('show');
                this.container.classList.add('show');
                this.finalizeNext();
            }, effectDuration);
        }
        finalizeNext() {
            setTimeout(() => {
                this.container.classList.add('show');
                this.showQuestionCallback();
            }, effectDuration);
        }
        showStartView() {
            this.currentView = this.generalView.getStartView();
            Util_1.default.show(this.currentView);
            this.container.classList.add('show');
        }
        getView() {
            return this.container;
        }
        _initGeneral() {
            this.questionView = new Question_1.QuestionView(this.checkAnswerCallback, this.nextQuestionCallback);
            this.generalView = new General_1.General();
            this.generalView.registerStartCallback(this.startGameCallback);
            // build game field.
            this.container = document.createElement('div');
            this.container.classList.add('main');
            this.container.append(this.generalView.getStartView(), this.generalView.getIntermissionView(), this.questionView.getView());
        }
    }
    exports.Main = Main;
});
