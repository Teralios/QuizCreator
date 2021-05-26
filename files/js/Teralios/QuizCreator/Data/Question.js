define(["require", "exports"], function (require, exports) {
    "use strict";
    class Question {
        constructor(question, optionA, optionB, optionC, optionD, explanation, correctOption) {
            this.question = question;
            this.options['A'] = optionA;
            this.options['B'] = optionB;
            this.options['C'] = optionC;
            this.options['D'] = optionD;
            this.explanation = explanation;
            this.correct = correctOption.toLowerCase();
        }
        checkAnswer(givenAnswer) {
            givenAnswer = givenAnswer.toLowerCase();
            return givenAnswer == this.correct;
        }
    }
    return Question;
});
