define(["require", "exports", "WoltLabSuite/Core/Language", "WoltLabSuite/Core/Ajax", "./Data"], function (require, exports, Language_1, Ajax_1, Data_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.LanguageLoader = exports.QuizLoader = void 0;
    /**
     * Loads a quiz via ajax from server and parse question and goals.
     */
    class QuizLoader {
        /**
         * Default constructor
         * @param idSelector    selector contains id where quiz id is found.
         * @param callbackSuccess      Callback function after quiz is loaded
         */
        constructor(idSelector, callbackSuccess, callbackFailure) {
            this.quizID = null;
            this.quizSelector = '';
            this.quizSelector = (idSelector.startsWith('#')) ? idSelector.substr(1) : idSelector;
            this.callbackSuccess = callbackSuccess;
            this.callbackFailure = callbackFailure;
            this.quizID = null;
            this.findQuizID();
        }
        /**
         * search quiz id.
         * @protected
         */
        findQuizID() {
            let quizElement;
            let quizID;
            quizElement = document.getElementById(this.quizSelector);
            if (quizElement == null) {
                console.error('Quiz with id ' + this.quizSelector + ' not found.');
                return;
            }
            else {
                quizID = quizElement.getAttribute('data-quiz-id');
                if (quizID != null) {
                    this.quizID = parseInt(quizID);
                }
            }
            if (this.quizID != null) {
                Ajax_1.api(this);
            }
            else {
                console.error('No quiz id found.');
            }
        }
        /**
         * build quiz from given json ajax data.
         * @protected
         */
        buildQuiz() {
            let quiz = new Data_1.Quiz();
            if ('questionList' in this.jsonData && Array.isArray(this.jsonData['questionList'])) {
                // add questions
                const questionList = this.jsonData['questionList'];
                if (questionList.length > 0) {
                    questionList.forEach((data) => {
                        var _a, _b, _c, _d, _e, _f;
                        const question = (_a = String(data.question)) !== null && _a !== void 0 ? _a : '';
                        const optionA = (_b = String(data.optionA)) !== null && _b !== void 0 ? _b : '';
                        const optionB = (_c = String(data.optionB)) !== null && _c !== void 0 ? _c : '';
                        const optionC = (_d = String(data.optionC)) !== null && _d !== void 0 ? _d : '';
                        const optionD = (_e = String(data.optionD)) !== null && _e !== void 0 ? _e : '';
                        const correctOption = (_f = String(data.answer)) !== null && _f !== void 0 ? _f : '';
                        const explanation = String(data.explanation);
                        if (question != ''
                            && optionA != ''
                            && optionB != ''
                            && optionC != ''
                            && optionD != ''
                            && correctOption != '') {
                            quiz.addQuestion(new Data_1.Question(question, optionA, optionB, optionC, optionD, explanation, correctOption));
                        }
                    });
                }
                // add goals
                if ('goalList' in this.jsonData && Array.isArray(this.jsonData['goalList'])) {
                    const goalList = this.jsonData['goalList'];
                    if (goalList.length > 0) {
                        goalList.forEach((data) => {
                            var _a, _b, _c, _d;
                            const points = (_a = Number(data.points)) !== null && _a !== void 0 ? _a : 0;
                            const title = (_b = String(data.title)) !== null && _b !== void 0 ? _b : '';
                            const icon = (_c = String(data.icon)) !== null && _c !== void 0 ? _c : '';
                            const description = (_d = String(data.description)) !== null && _d !== void 0 ? _d : '';
                            if (title != '') {
                                quiz.addGoal(new Data_1.Goal(title, icon, description, points));
                            }
                        });
                    }
                }
            }
            this.callbackSuccess(quiz);
        }
        /**
         * ajax setup data.
         */
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
        /**
         * Ajax success data.
         * @param data
         */
        _ajaxSuccess(data) {
            this.jsonData = data.returnValues;
            this.buildQuiz();
        }
        _ajaxFailure() {
            this.callbackFailure();
            return true;
        }
    }
    exports.QuizLoader = QuizLoader;
    /**
     * Load language items from a json script tag.
     */
    class LanguageLoader {
        constructor(contentID) {
            this.contentID = contentID;
            this.loadLanguageItems();
        }
        loadLanguageItems() {
            const jsonField = document.getElementById(this.contentID);
            const languageData = new Map();
            if (jsonField === null) {
                console.log('Can not find language items.');
                return;
            }
            JSON.parse(jsonField.innerHTML, (key, value) => {
                if (typeof (key) == 'string' && typeof (value) == 'string') {
                    languageData.set(key, value);
                }
            });
            if (languageData.size > 0) {
                languageData.forEach((key, value) => {
                    Language_1.add(key, value);
                });
            }
        }
    }
    exports.LanguageLoader = LanguageLoader;
});
