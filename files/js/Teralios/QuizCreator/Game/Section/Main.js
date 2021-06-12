define(["require", "exports", "tslib", "./View/Question", "WoltLabSuite/Core/Dom/Util"], function (require, exports, tslib_1, Question_1, Util_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Main = exports.setEffectBasics = void 0;
    Util_1 = tslib_1.__importDefault(Util_1);
    let effectDuration;
    let effectClassIn;
    let effectClassOut;
    function setEffectBasics(duration, inClass, outClass) {
        effectDuration = duration * 1000;
        effectClassIn = inClass;
        effectClassOut = outClass;
    }
    exports.setEffectBasics = setEffectBasics;
    class Main {
        constructor(showQuestionCallback, checkAnswerCallback, nextQuestionCallback, finishGameCallback) {
            this.showQuestionCallback = showQuestionCallback;
            this.checkAnswerCallback = checkAnswerCallback;
            this.nextQuestionCallback = nextQuestionCallback;
            this.finishGameCallback = finishGameCallback;
            this.questionView = new Question_1.QuestionView(this.checkAnswerCallback, this.nextQuestionCallback);
            Util_1.default.hide(this.questionView.getView());
            this._initGeneral();
        }
        nextQuestion(question, isLast) {
            if (isLast) {
                this.questionView.prepareFor(question, this.finishGameCallback);
            }
            else {
                this.questionView.prepareFor(question);
            }
        }
        getView() {
            return this.container;
        }
        _initGeneral() { }
    }
    exports.Main = Main;
});
