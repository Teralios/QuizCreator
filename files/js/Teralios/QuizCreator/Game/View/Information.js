define(["require", "exports", "WoltLabSuite/Core/Language"], function (require, exports, Language_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    // information to question
    const questionNo = document.createElement('b');
    const questionCount = document.createElement('b');
    let questionIndicators;
    const value = document.createElement('span');
    const time = document.createElement('span');
    const score = document.createElement('span');
    // templates
    function buildQuestionView(questions) {
        // update question count
        questionCount.textContent = String(questions);
        // build general container
        const container = document.createElement('div');
        container.classList.add('questionInfo');
        // question (Question 1 from 10)
        const firstParagraph = document.createElement('p');
        firstParagraph.append(Language_1.get('wcf.quizCreator.game.header.question.prefix'), questionNo, Language_1.get('wcf.quizCreator.game.header.question.suffix'), questionCount);
        // Question signs (??????????)
        const secondParagraph = document.createElement('p');
        for (let i = 0; i < questions; i++) {
            const questionSign = document.createElement('span');
            questionSign.classList.add('question', 'fa', 'icon16', 'fa-question-circle');
            questionIndicators.push(questionSign);
            secondParagraph.appendChild(questionSign);
        }
        container.append(firstParagraph, secondParagraph);
        return container;
    }
});
