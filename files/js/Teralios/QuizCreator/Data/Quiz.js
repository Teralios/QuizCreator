define(["require", "exports"], function (require, exports) {
    "use strict";
    class Quiz {
        constructor() {
            this.questionsCount = 0;
            this.questionIndex = 0;
        }
        addQuestion(question) {
            this.questions[this.questionsCount] = question;
            ++this.questionsCount;
        }
        getQuestion() {
            if (this.questionsCount < this.questionIndex) {
                return null;
            }
            return this.questions[this.questionIndex];
        }
        nextQuestion() {
            ++this.questionIndex;
            return this.questionIndex <= this.questionsCount;
        }
    }
    return Quiz;
});
