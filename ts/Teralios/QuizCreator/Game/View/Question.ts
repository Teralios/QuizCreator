import {get as phrase} from 'WoltLabSuite/Core/Language';
import {Question} from "../../Data/Data";

const answerButtons: HTMLButtonElement[] = [];
const questionText = document.createElement('p');
const explanation = document.createElement('p');
const nextButton = document.createElement('button');
const options = ['A', 'B', 'C', 'D'];

export type AnswerCallback = (ev: MouseEvent) => void;
export type NextCallback = () => void;
export type FinishCallback = () => void;

function buildQuestionBlock(callback: AnswerCallback): HTMLElement {
    const optionCount = options.length;
    const ulElement = document.createElement('ul');
    ulElement.classList.add('questionList');

    for (let i = 0; i < optionCount; i++) {
        const button = document.createElement('button');
        button.addEventListener('click', callback);
        answerButtons.push(button);

        const li = document.createElement('li');
        li.appendChild(button);
        ulElement.appendChild(li);
    }

    return ulElement;
}

function buildNextBlock(callback: NextCallback): HTMLElement {
    nextButton.textContent = phrase('wcf.quizCreator.game.button.next');
    nextButton.addEventListener('click', callback);

    const nextContainer = document.createElement('div');
    nextContainer.classList.add('next');
    nextContainer.append(explanation, nextButton);

    return nextContainer;
}

export class QuestionView {
    protected container: HTMLElement;
    protected nextBlock: HTMLElement;
    protected finish: FinishCallback;
    protected next: NextCallback;

    constructor(answer: AnswerCallback, next: NextCallback, finish: FinishCallback) {
        // view template
        this.container = document.createElement('div');
        this.container.classList.add('questionView');
        this.nextBlock = buildNextBlock(next);
        this.container.append(questionText, buildQuestionBlock(answer), this.nextBlock);

        // question class
        questionText.classList.add('question')

        // callback
        this.finish = finish;
        this.next = next;
    }

    public getView(): HTMLElement {
        return this.container;
    }

    public prepareFor(question: Question): void {
        questionText.textContent = question.question;
        explanation.textContent = question.explanation;

        let i = 0;
        options.sort(() => 0.5 - Math.random());
        options.forEach((option) => {
            answerButtons[i].textContent = question.options[option];
            answerButtons[i].dataset.option = option;
            answerButtons[i].classList.remove('correct', 'incorrect');
            answerButtons[i].disabled = false;
            i++;
        })

        nextButton.disabled = true;
        this.nextBlock.classList.remove('show');
    }

    public updateView(userOption: string, correctOption: string, isLast = false): void {
        answerButtons.forEach((button) => {
            if (button.dataset.option == correctOption) {
                button.classList.add('correct');
            } else if (button.dataset.option == userOption) {
                button.classList.add('incorrect');
            }

            button.disabled = true;
        });

        if (isLast) {
            nextButton.textContent = phrase('wcf.quizCreator.game.button.finish');
            nextButton.removeEventListener('click', this.next);
            nextButton.addEventListener('click', this.finish);
        }

        nextButton.disabled = false;
        this.nextBlock.classList.add('show');
    }
}
