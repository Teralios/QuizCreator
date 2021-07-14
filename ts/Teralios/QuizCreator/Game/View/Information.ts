// imports
import {get as phrase} from 'WoltLabSuite/Core/Language';

// information to question
const questionNo = document.createElement('b');
const questionCount = document.createElement('b');
let questionIndicators: HTMLElement[];
const value = document.createElement('span');
const time = document.createElement('span');
const score = document.createElement('span');

// templates
function buildQuestionView(questions: number): HTMLElement {
    // update question count
    questionCount.textContent = String(questions);

    // build general container
    const container = document.createElement('div');
    container.classList.add('questionInfo');

    // question (Question 1 from 10)
    const firstParagraph = document.createElement('p');
    firstParagraph.append(
        phrase('wcf.quizCreator.game.header.question.prefix'),
        questionNo,
        phrase('wcf.quizCreator.game.header.question.suffix'),
        questionCount
    );

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
