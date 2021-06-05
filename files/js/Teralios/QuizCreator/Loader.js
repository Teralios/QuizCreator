define(["require", "exports", "WoltLabSuite/Core/Ajax"], function (require, exports, Ajax_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    class Loader {
        constructor(htmlSelector) {
            this.quizSelector = htmlSelector;
            this.quizID = null;
            this.findQuizID();
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
                Ajax_1.api(this);
            }
            else {
                console.error('No quiz id found.');
            }
        }
        buildQuiz() {
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
            this.jsonData = JSON.parse(data.returnValues);
            this.buildQuiz();
        }
    }
});
