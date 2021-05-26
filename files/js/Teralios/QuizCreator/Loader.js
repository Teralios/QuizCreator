define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    class Loader {
        constructor(htmlSelector) {
            this.quizSelector = htmlSelector;
            this.quizID = null;
        }
        findQuizID() {
            let quizElement;
            let quizID;
            if (!this.quizSelector.startsWith('#')) {
                console.error('Teralios/QuizCreator/Loader aspects id selector for loading quiz.');
            }
            else {
                quizElement = document.getElementById(this.quizSelector);
                if (quizElement != null) {
                    quizID = quizElement.getAttribute('data-quiz-id');
                    if (quizID != null) {
                        this.quizID = parseInt(quizID);
                    }
                }
            }
            if (this.quizID != null) {
            }
            else {
                console.error('No quiz id found.');
            }
        }
        _ajaxSetup() {
            return {
                data: {
                    actionName: "loadQuiz",
                    className: "wcf\\data\\quiz\\QuizAction",
                    objectIDs: [this.quizID],
                    parameters: {
                        value: 1
                    }
                }
            };
        }
        _ajaxSuccess(data, responseText, xhr, requestData) {
        }
    }
});
