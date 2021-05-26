define(["require", "exports", "tslib", "./Data/Quiz"], function (require, exports, tslib_1, Quiz_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.loadQuiz = void 0;
    Quiz_1 = tslib_1.__importDefault(Quiz_1);
    function loadQuiz() {
        return new Quiz_1.default();
    }
    exports.loadQuiz = loadQuiz;
});
