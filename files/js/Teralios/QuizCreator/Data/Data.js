define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    exports.Quiz = exports.Goal = exports.Question = void 0;
    /**
     * Default question class.
     */
    class Question {
        constructor(question, optionA, optionB, optionC, optionD, explanation, correctOption, no) {
            this.options = new Map();
            this.question = question;
            this.options['A'] = optionA;
            this.options['B'] = optionB;
            this.options['C'] = optionC;
            this.options['D'] = optionD;
            this.explanation = explanation;
            this.correct = correctOption.toLowerCase();
            this.no = no;
        }
        checkAnswer(givenAnswer) {
            givenAnswer = givenAnswer.toLowerCase();
            return givenAnswer == this.correct;
        }
    }
    exports.Question = Question;
    /**
     * Default goal class.
     */
    class Goal {
        constructor(title, description, icon, minScore) {
            this.title = title;
            this.description = description;
            this.icon = icon;
            this.minScore = minScore;
        }
        reached(score) {
            return (score >= this.minScore);
        }
    }
    exports.Goal = Goal;
    /**
     * Default quiz class.
     */
    class Quiz {
        constructor() {
            this.questions = [];
            this.goals = [];
            this.questionsCount = 0;
            this.questionIndex = 0;
        }
        addQuestion(question) {
            this.questions.push(question);
            ++this.questionsCount;
        }
        addGoal(goal) {
            this.goals.push(goal);
        }
        getQuestion() {
            if (this.questionsCount < this.questionIndex) {
                return null;
            }
            return this.questions[this.questionIndex];
        }
        getQuestionNo() {
            return this.questionIndex + 1;
        }
        nextQuestion() {
            ++this.questionIndex;
            return this.questionIndex <= this.questionsCount;
        }
        getGoal(score) {
            let reachedGoal;
            reachedGoal = null;
            if (this.goals.length > 0) {
                this.goals.sort((a, b) => {
                    return (a >= b) ? 1 : -1;
                });
                this.goals.forEach((goal) => {
                    if (goal.reached(score)) {
                        reachedGoal = goal;
                    }
                });
            }
            return reachedGoal;
        }
    }
    exports.Quiz = Quiz;
});
